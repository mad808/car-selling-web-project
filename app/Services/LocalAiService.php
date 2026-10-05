<?php

namespace App\Services;

use App\Models\Car;
use App\Models\Brand;
use Illuminate\Support\Facades\Http;
use Throwable;

class LocalAiService
{
    protected string $ollamaUrl;
    protected string $model;

    public function __construct()
    {
        $this->ollamaUrl = env('OLLAMA_URL', 'http://127.0.0.1:11434');
        $this->model = env('OLLAMA_MODEL', 'llama3.2');
    }

    /**
     * Ulanyjy bilen akylly söhbetdeşlik
     */
    public function chat(string $userMessage): string
    {
        $lang = $this->detectLanguage($userMessage);

        // 1. Eger Ollama işläp duran bolsa, ilki LLM-e iberýäris
        try {
            $langNames = [
                'tk' => 'Turkmen (Türkmen dili)',
                'ru' => 'Russian (Русский язык)',
                'en' => 'English'
            ];
            $targetLanguage = $langNames[$lang] ?? 'Turkmen';

            $systemPrompt = "You are 'Ulagym AI', an expert automotive consultant in Turkmenistan. " .
                            "RULES: 1. You MUST answer ONLY in {$targetLanguage}. " .
                            "2. Answer the user's specific question directly (e.g. if they ask why Camry is popular, explain why; if greeting, greet them; if comparing hybrid vs petrol, compare them). " .
                            "3. Be concise (2-4 sentences), factual, and friendly.";

            $response = Http::timeout(4)->post("{$this->ollamaUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => "System: {$systemPrompt}\nUser: {$userMessage}\nAssistant:",
                'stream' => false,
            ]);

            if ($response->successful() && !empty($response->json('response'))) {
                return trim($response->json('response'));
            }
        } catch (Throwable $e) {
            // Ollama ýapyk bolsa, aşakdaky arassa akylly algoritm işleýär
        }

        // 2. Akylly 3 Dilli Awtonom Motor (Her soraga aýratyn jogap)
        return $this->smartMultilingualFallback($userMessage, $lang);
    }

    /**
     * Dili takyk anyklaýjy
     */
    protected function detectLanguage(string $text): string
    {
        if (preg_match('/[а-яА-ЯёЁ]/u', $text)) {
            return 'ru';
        }

        if (preg_match('/[äöüçşžýňÄÖÜÇŞŽÝŇ]/u', $text) || 
            preg_match('/\b(salam|nähili|nahili|baha|bahasy|ulag|masyn|maşyn|aljak|näme|name|kemi|näçe|nace|haýsy|haysy|maslahat|ýangyç|yangyc|gibrid)\b/ui', $text)) {
            return 'tk';
        }

        return 'en';
    }

    /**
     * Soragyň içinden arassa býujeti we markany çykarmak
     */
    protected function extractCriteria(string $query): array
    {
        // 15,000 ýa-da 15 000 ýaly belgileri 15000 görnüşe getirmek
        $clean = preg_replace('/(?<=\d)[,\s.](?=\d{3})/', '', $query);

        $budget = null;
        if (preg_match('/(\d{1,3})[kK]\b/', $clean, $m)) {
            $budget = (float) $m[1] * 1000;
        } elseif (preg_match('/(\d{4,6})/', $clean, $m)) {
            $budget = (float) $m[1];
        }

        $brands = Brand::pluck('name', 'id')->toArray();
        $matchedBrandId = null;
        $matchedBrandName = null;
        foreach ($brands as $id => $name) {
            if (stripos($clean, $name) !== false) {
                $matchedBrandId = $id;
                $matchedBrandName = $name;
                break;
            }
        }

        return [$budget, $matchedBrandId, $matchedBrandName];
    }

    /**
     * 3 Dilde Soragyň Manysyna Görä Jogap Bermek
     */
    protected function smartMultilingualFallback(string $rawMsg, string $lang): string
    {
        $q = mb_strtolower(trim($rawMsg));
        [$budget, $brandId, $brandName] = $this->extractCriteria($rawMsg);

        // ==========================================
        // 🇹🇲 1. TÜRKMEN DILI
        // ==========================================
        if ($lang === 'tk') {
            // Salamlaşyk
            if (preg_match('/\b(salam|salow|ertiriňiz|agşamyňyz|privet|hi|hello)\b/ui', $q)) {
                return "Salam! Men Ulagym emeli aň maslahatçysy. Size awtoulag saýlamakda, bahalary deňeşdirmekde we maslahat bermekde kömek edip bilerin. Nähili ulag gözleýärsiňiz?";
            }

            // Toyota Camry näme üçin meşhur?
            if (str_contains($q, 'camry') || (str_contains($q, 'toyota') && str_contains($q, 'meşhur'))) {
                return "Toyota Camry-nyň Türkmenistanda aşa meşhur bolmagynyň esasy sebäpleri: yssy howa çydamly kuwwatly sowadyjysy (kondisioner), ýollarymyza amatly ýumşak podweskasy, ätiýaçlyk şaýlarynyň iňňän elýeterliligi we bazarda bahasyny hemişe gymmat saklamagydyr.";
            }

            // Gibrid vs Benzin
            if (str_contains($q, 'gibrid') || str_contains($q, 'hybrid') || (str_contains($q, 'benzin') && str_contains($q, 'tygşyt'))) {
                return "Şäher içinde (swetoforlarda we dyknyşyklarda) Gibrid ulaglar benzinden 40-50% çenli köp ýangyç tygşytlaýar. Ýöne uzak ýolda (trassada) ikisiniň arasynda uly tapawut ýokdur. Gibrid alanyňyzda esasy zat batareýasynyň sowadyş ulgamyny barlamalysyňyz.";
            }

            // Türkmenistanda iň ygtybarly ulaglar
            if (str_contains($q, 'ygtybarly') || str_contains($q, 'gowusy') || str_contains($q, 'çydamly')) {
                return "Türkmenistanyň howa we ýol şertlerinde iň ygtybarly ulaglar: Toyota (Camry, Corolla, RAV4), Lexus (ES, RX) we Hyundai (Elantra, Sonata). Bu ulaglaryň ussasy hem, şaýlary hem ähli ýerde tapylýar.";
            }

            // Baha ýa-da Býujet boýunça gözleg
            if ($budget || $brandId) {
                return $this->renderCarRecommendations('tk', $budget, $brandId, $brandName);
            }

            return "Awtoulaglar boýunça soragyňyzy anyklaşdyryň. Mysal üçin: '15,000$ çenli ulaglar', 'Gibrid maşynlaryň kemçiligi näme?' ýa-da 'Lexus ES nähili?'.";
        }

        // ==========================================
        // 🇷🇺 2. РУССКИЙ ЯЗЫК
        // ==========================================
        if ($lang === 'ru') {
            // Приветствие
            if (preg_match('/\b(привет|здравствуй|салам|добрый|хай|hello|hi)\b/ui', $q)) {
                return "Здравствуйте! Я ИИ-консультант Ulagym. Помогу вам выбрать автомобиль под ваш бюджет, сравнить модели или узнать о надежности в условиях Туркменистана. Какой авто вас интересует?";
            }

            // Гибрид или бензин
            if (str_contains($q, 'гибрид') || str_contains($q, 'бензин') || str_contains($q, 'экономич')) {
                return "В городе однозначно экономичнее гибрид — он экономит до 40-50% топлива в пробках за счет электродвигателя. На трассе разница с обычным бензином минимальна. В нашем жарком климате при покупке гибрида главное — проверить состояние батареи и системы охлаждения.";
            }

            // Почему популярна Камри
            if (str_contains($q, 'камри') || str_contains($q, 'camry') || str_contains($q, 'тойот')) {
                return "Toyota Camry в Туркменистане считается легендой благодаря трем вещам: исключительная надежность кондиционера в жару, дешевизна запчастей и моментальная ликвидность на вторичном рынке — ее всегда можно быстро продать по хорошей цене.";
            }

            // Самые надежные машины
            if (str_contains($q, 'надежн') || str_contains($q, 'лучш') || str_contains($q, 'крепк')) {
                return "Для Туркменистана топ по надежности и неприхотливости занимают Toyota Corolla и Camry, Lexus RX/ES, а также Hyundai Elantra. На них легко найти мастера и детали в любом велаяте.";
            }

            // Поиск по бюджету / марке
            if ($budget || $brandId) {
                return $this->renderCarRecommendations('ru', $budget, $brandId, $brandName);
            }

            return "Спросите меня о конкретной модели, сравнении моторов или укажите бюджет (например: 'Машина до 20000$', 'Плюсы и минусы Corolla').";
        }

        // ==========================================
        // 🇬🇧 3. ENGLISH
        // ==========================================
        // Greetings
        if (preg_match('/\b(hello|hi|hey|greetings|test)\b/ui', $q)) {
            return "Hello! I am the Ulagym AI car advisor. I can help you choose the best car for your budget, compare models, and provide maintenance insights in Turkmenistan. What car are you looking for?";
        }

        // Hybrid vs Petrol
        if (str_contains($q, 'hybrid') || str_contains($q, 'petrol') || str_contains($q, 'fuel') || str_contains($q, 'economy')) {
            return "Hybrids are significantly more fuel-efficient in city stop-and-go traffic (saving up to 40-50% fuel). On open highways, standard petrol engines perform similarly. When buying a hybrid in Turkmenistan's hot climate, always inspect the traction battery cooling system.";
        }

        // Why is Camry popular
        if (str_contains($q, 'camry') || (str_contains($q, 'toyota') && str_contains($q, 'popular'))) {
            return "Toyota Camry is the top choice in Turkmenistan due to its bulletproof AC system, durable suspension on local roads, cheap spare parts, and unmatched resale value.";
        }

        // Most reliable cars
        if (str_contains($q, 'reliable') || str_contains($q, 'best car') || str_contains($q, 'durab')) {
            return "The most reliable cars in Turkmenistan are Toyota (Corolla, Camry), Lexus (ES, RX), and Hyundai (Elantra, Sonata). Parts and repair specialists for these brands are widely available everywhere.";
        }

        // Search by budget / brand
        if ($budget || $brandId) {
            return $this->renderCarRecommendations('en', $budget, $brandId, $brandName);
        }

        return "Feel free to ask me anything about cars! For example: 'Best car under $15,000', 'Toyota vs Hyundai', or 'Is Lexus expensive to maintain?'.";
    }

    /**
     * Býujet ýa-da marka soralanda hakyky bazadan dogry bahaly ulaglary çykarmak
     */
    protected function renderCarRecommendations(string $lang, ?float $budget, ?int $brandId, ?string $brandName): string
    {
        $query = Car::active()->with('brand');

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($budget) {
            // Eger 15000 diýen bolsa, 15000-dan kiçi ýa-da deň ulaglary gözleýäris!
            $query->where('price', '<=', $budget);
        }

        $cars = $query->orderBy('price', 'desc')->take(3)->get();

        if ($lang === 'tk') {
            if ($cars->isNotEmpty()) {
                $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} ýyl) — \${$c->price}")->implode("\n");
                $target = $budget ? "$" . number_format($budget) . " çenli" : $brandName;
                return "Siziň {$target} gözlegiňiz boýunça häzirki wagtda bazamyzdaky iň amatly ulaglar:\n{$list}\nUlaglaryň doly suratyny baş sahypamyzdan görüp bilersiňiz.";
            }
            return "Häzirki wagtda " . ($budget ? "$" . number_format($budget) . " çenli" : $brandName) . " tassyklanan ulag tapylmady. Ýöne baş sahypadaky süzgüçden baha çägini giňeldip gözläp bilersiňiz.";
        }

        if ($lang === 'ru') {
            if ($cars->isNotEmpty()) {
                $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} г.) — \${$c->price}")->implode("\n");
                $target = $budget ? "до $" . number_format($budget) : $brandName;
                return "По вашему запросу {$target} в наличии есть следующие варианты:\n{$list}\nПодробности можно посмотреть в каталоге на главной странице.";
            }
            return "К сожалению, в данный момент вариантов " . ($budget ? "до $" . number_format($budget) : $brandName) . " нет в наличии. Попробуйте немного изменить параметры поиска.";
        }

        // English
        if ($cars->isNotEmpty()) {
            $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year}) — \${$c->price}")->implode("\n");
            $target = $budget ? "under $" . number_format($budget) : $brandName;
            return "Here are the top matches {$target} currently in our inventory:\n{$list}\nYou can browse full listings on our home page.";
        }
        return "Currently, no vehicles were found " . ($budget ? "under $" . number_format($budget) : "for " . $brandName) . ". Try slightly adjusting your budget filters.";
    }

    /**
     * Moderasiýa üçin awtoulag barlagy
     */
    public function analyzeCar(Car $car): array
    {
        $currentYear = (int) date('Y');
        $age = max(1, $currentYear - $car->year);
        $avgKm = $car->mileage / $age;

        $verdict = 'FAIR PRICE';
        if ($car->price < 8000 && $car->year >= 2018) {
            $verdict = 'GREAT DEAL';
        } elseif ($car->price > 40000 && $car->year < 2014) {
            $verdict = 'OVERPRICED';
        }

        return [
            'review' => "Lokal AI: {$car->brand->name} {$car->model} ({$car->year}). Ortaça ýyllyk probeg: " . round($avgKm) . " km/ýyl. Baha ýagdaýy: {$verdict}.",
            'verdict' => $verdict,
            'source' => 'Local AI Engine'
        ];
    }
}
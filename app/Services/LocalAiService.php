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
     * Ulanyjy bilen 3 dilde akylly söhbetdeşlik
     */
    public function chat(string $userMessage): string
    {
        // 1. Soragyň dilini anyklamak (TK, RU, EN)
        $lang = $this->detectLanguage($userMessage);

        // 2. Ulanyjynyň soragyndan marka we býujeti anyklap, bazadan gabat gelýän ulaglary tapmak
        $relevantCars = $this->findRelevantCarsFromDatabase($userMessage);

        // 3. Ollama (Lokal LLM) barlap görmek
        try {
            $langNames = [
                'tk' => 'Turkmen (Türkmen dili)',
                'ru' => 'Russian (Русский язык)',
                'en' => 'English'
            ];
            $targetLanguage = $langNames[$lang] ?? 'Turkmen';

            $carContext = $relevantCars->isNotEmpty() 
                ? "Cars currently available in our marketplace database:\n" . $relevantCars->map(fn($c) => "- {$c->brand->name} {$c->model} ({$c->year}), Price: \${$c->price}, Mileage: {$c->mileage} km")->implode("\n")
                : "No exact match found in current inventory, but give general automotive advice.";

            $systemPrompt = "You are 'Ulagym AI', an expert automotive consultant for the car marketplace in Turkmenistan.\n" .
                            "RULES:\n" .
                            "1. You MUST respond ONLY in {$targetLanguage}.\n" .
                            "2. Keep the answer helpful, friendly, and concise (2-4 sentences).\n" .
                            "3. If relevant cars from our database are provided, mention them to the user.\n" .
                            "4. Provide honest pros, cons, or price verdict based on Turkmenistan automotive conditions.\n\n" .
                            "Database Context:\n{$carContext}";

            $response = Http::timeout(8)->post("{$this->ollamaUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => "System: {$systemPrompt}\n\nUser: {$userMessage}\nAssistant:",
                'stream' => false,
            ]);

            if ($response->successful() && !empty($response->json('response'))) {
                return trim($response->json('response'));
            }
        } catch (Throwable $e) {
            // Ollama ýapyk bolsa, aşakdaky 3 dilli awtonom algoritm işleýär
        }

        // 4. Offline Akylly 3 Dilli Awtonom Engine
        return $this->smartMultilingualFallback($userMessage, $lang, $relevantCars);
    }

    /**
     * Dili anyklamak (Türkmen, Rus, Iňlis)
     */
    protected function detectLanguage(string $text): string
    {
        // 1. Kirill harplary bar bolsa -> Rus dili
        if (preg_match('/[а-яА-ЯёЁ]/u', $text)) {
            return 'ru';
        }

        // 2. Türkmen diline mahsus harplar ýa-da köp duş gelýän sözler bar bolsa -> Türkmen dili
        if (preg_match('/[äöüçşžýňÄÖÜÇŞŽÝŇ]/u', $text) || 
            preg_match('/\b(nähili|nahili|baha|bahasy|ulag|masyn|maşyn|aljak|ber|gowusy|amatly|satlyk|bar|yagdayy|ýagdaýy|näme|name|maslahat|kemi|nace|näçe)\b/ui', $text)) {
            return 'tk';
        }

        // 3. Iňlis dili (Default)
        return 'en';
    }

    /**
     * Soragyň içindäki marka ýa-da býujeti tapyp, hakyky bazadan ulaglary getirmek
     */
    protected function findRelevantCarsFromDatabase(string $query)
    {
        $q = mb_strtolower($query);

        // Býujet sanyny tapmak (mysal: 15000, 20000, 15k)
        $budget = null;
        if (preg_match('/(\d{1,3})[kK]\b/', $q, $matches)) {
            $budget = (float) $matches[1] * 1000;
        } elseif (preg_match('/(\d{4,6})/', $q, $matches)) {
            $budget = (float) $matches[1];
        }

        // Markany tapmak
        $brands = Brand::pluck('name', 'id')->toArray();
        $matchedBrandId = null;
        foreach ($brands as $id => $name) {
            if (str_contains($q, mb_strtolower($name))) {
                $matchedBrandId = $id;
                break;
            }
        }

        return Car::active()
            ->with('brand')
            ->when($matchedBrandId, fn($query) => $query->where('brand_id', $matchedBrandId))
            ->when($budget, fn($query) => $query->where('price', '<=', $budget * 1.15))
            ->latest()
            ->take(3)
            ->get();
    }

    /**
     * 3 Dilde Awtonom Akylly Jogap
     */
    protected function smartMultilingualFallback(string $msg, string $lang, $cars): string
    {
        $text = mb_strtolower($msg);

        // --- 1. TÜRKMENÇE JOGAPLAR ---
        if ($lang === 'tk') {
            if ($cars->isNotEmpty()) {
                $carList = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} ýyl) — \${$c->price}")->implode("\n");
                return "Siziň gözlegiňiz boýunça häzirki wagtda sahypamyzda şu amatly ulaglar bar:\n{$carList}\nHas giňişleýin görmek üçin baş sahypadaky süzgüçden peýdalanyp bilersiňiz.";
            }

            if (str_contains($text, 'toyota') || str_contains($text, 'camry') || str_contains($text, 'corolla')) {
                return "Toyota (aýratyn-da Camry we Corolla) Türkmenistanyň şertlerinde iň ygtybarly we ätiýaçlyk şaýlary aňsat tapylýan ulagdyr. Bahasyny hemişe gowy saklaýar.";
            }

            if (str_contains($text, 'gibrid') || str_contains($text, 'hybrid') || str_contains($text, 'tok') || str_contains($text, 'elektro')) {
                return "Gibrid ulaglar şäher içinde ýangyjy ep-esli tygşytlaýar. Almazdan öň batareýasynyň ýagdaýyny we inwertoryny barlatmagy maslahat berýäris.";
            }

            if (str_contains($text, 'baha') || str_contains($text, 'arzan') || str_contains($text, 'maslahat')) {
                return "Amatly we çykdajysyz ulag gözleýän bolsaňyz, 2012–2018-nji ýyllaryň Hyundai Elantra, Kia Forte ýa-da Toyota Corolla modellerine üns bermegiňizi maslahat berýäris.";
            }

            return "Salam! Men Ulagym AI maslahatçysy. Býujetiňize laýyk ulag tapmakda, modelleri deňeşdirmekde ýa-da baha boýunça soraglaryňyzda hemişe kömek etmäge taýýar!";
        }

        // --- 2. РУССКИЙ ЯЗЫК ---
        if ($lang === 'ru') {
            if ($cars->isNotEmpty()) {
                $carList = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} г.) — \${$c->price}")->implode("\n");
                return "По вашему запросу в нашей базе сейчас есть отличные варианты:\n{$carList}\nВы можете найти их в каталоге на главной странице.";
            }

            if (str_contains($text, 'тойот') || str_contains($text, 'камри') || str_contains($text, 'toyota') || str_contains($text, 'camry')) {
                return "Toyota Camry и Corolla — одни из самых надежных и ликвидных автомобилей в Туркменистане. Они неприхотливы в обслуживании и легко продаются на вторичном рынке.";
            }

            if (str_contains($text, 'гибрид') || str_contains($text, 'электро') || str_contains($text, 'расход')) {
                return "Гибридные модели отлично экономят топливо в городском цикле. При покупке обязательно сделайте компьютерную диагностику батареи и инвертора.";
            }

            if (str_contains($text, 'бюджет') || str_contains($text, 'цен') || str_contains($text, 'посоветуй') || str_contains($text, 'выбрать')) {
                return "Для надежной и экономичной повседневной езды отлично подойдут Hyundai Elantra, Kia Cerato или Toyota Corolla 2012-2017 годов. Воспользуйтесь фильтром цен на сайте!";
            }

            return "Здравствуйте! Я ИИ-консультант Ulagym. Помогу подобрать автомобиль под ваш бюджет, сравнить характеристики и сориентировать по ценам. Задайте любой вопрос!";
        }

        // --- 3. ENGLISH ---
        if ($cars->isNotEmpty()) {
            $carList = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year}) — \${$c->price}")->implode("\n");
            return "Based on your request, here are matching vehicles currently available in our inventory:\n{$carList}\nYou can view more details on our home page.";
        }

        if (str_contains($text, 'toyota') || str_contains($text, 'camry') || str_contains($text, 'reliable')) {
            return "Toyota models like Camry and Corolla are renowned for their exceptional reliability and high resale value in Turkmenistan. Maintenance parts are widely available.";
        }

        if (str_contains($text, 'budget') || str_contains($text, 'price') || str_contains($text, 'recommend') || str_contains($text, 'best')) {
            return "For great fuel efficiency and low maintenance costs, consider 2014-2018 Hyundai Elantra, Kia Forte, or Toyota Corolla. Check our home page filters to browse live listings.";
        }

        return "Hello! I am Ulagym AI Assistant. I can help you find vehicles within your budget, compare specs, and provide price advice. How can I help you today?";
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
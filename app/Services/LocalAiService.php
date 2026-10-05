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

        // 1. Eger Ollama işläp duran bolsa, ilki LLM-e ugradýarys
        try {
            $langNames = [
                'tk' => 'Turkmen (Türkmen dili)',
                'ru' => 'Russian (Русский язык)',
                'en' => 'English'
            ];
            $targetLanguage = $langNames[$lang] ?? 'Turkmen';

            $systemPrompt = "You are 'Ulagym AI', an expert automotive consultant in Turkmenistan. " .
                            "RULES: 1. You MUST answer ONLY in {$targetLanguage}. " .
                            "2. Answer the user's specific question directly with expert insights. " .
                            "3. Be concise (2-4 sentences), factual, and polite.";

            $response = Http::timeout(4)->post("{$this->ollamaUrl}/api/generate", [
                'model' => $this->model,
                'prompt' => "System: {$systemPrompt}\nUser: {$userMessage}\nAssistant:",
                'stream' => false,
            ]);

            if ($response->successful() && !empty($response->json('response'))) {
                return trim($response->json('response'));
            }
        } catch (Throwable $e) {
            // Ollama ýapyk bolsa, aşakdaky giň gerimli awtonom motor işleýär
        }

        // 2. Akylly Köp Temaly 3 Dilli Awtonom Motor
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
            preg_match('/\b(salam|nähili|nahili|baha|bahasy|ulag|masyn|maşyn|aljak|näme|name|kemi|näçe|nace|haýsy|haysy|maslahat|ýangyç|yangyc|gibrid|ýag|yag|karopka|tomsuna|gyşyna|çalt|satmak)\b/ui', $text)) {
            return 'tk';
        }

        return 'en';
    }

    /**
     * Soragyň içinden arassa býujeti we markany çykarmak
     */
    protected function extractCriteria(string $query): array
    {
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
     * Giň gerimli sorag-jogap binýady
     */
    protected function smartMultilingualFallback(string $rawMsg, string $lang): string
    {
        $q = mb_strtolower(trim($rawMsg));
        [$budget, $brandId, $brandName] = $this->extractCriteria($rawMsg);

        // ==============================================================
        // 🇹🇲 1. TÜRKMEN DILI (TURKMEN KNOWLEDGE BASE)
        // ==============================================================
        if ($lang === 'tk') {
            // Salamlaşyk
            if (preg_match('/\b(salam|salow|ertiriňiz|agşamyňyz|privet|hi|hello)\b/ui', $q)) {
                return "Salam! Men Ulagym emeli aň maslahatçysy. Awtoulag saýlamak, bahalary deňeşdirmek, tehniki hyzmat ýa-da satyn almazdan öňki barlaglar boýunça islän soragyňyzy berip bilersiňiz!";
            }

            // Satyn almazdan öň nämeleri barlamaly? (Inspection)
            if (str_contains($q, 'almazdan') || str_contains($q, 'barlamaly') || str_contains($q, 'tekşir') || str_contains($q, 'satyn alanda')) {
                return "Ulag almazdan öň şu 4 zada hökman serediň: 1. Kuzow we lanjeronlar (galyňlyk ölçeýji bilen agyr zarba görenligini barlamak); 2. Motor we karopka (sowuk wagty otlap, ýag syzmasyny we tüssäni görmek); 3. Kondisioner we sowadyş ulgamy; 4. Kompýuter diagnostikasy (öçürilen Check Engine kodlaryny barlamak).";
            }

            // Ýag çalşyrmak (Oil & Maintenance)
            if (str_contains($q, 'ýag') || str_contains($q, 'yag') || str_contains($q, 'çalşyr') || str_contains($q, 'çalys')) {
                return "Türkmenistanyň yssy şertlerinde motor ýagyny her 5,000 - 7,000 km aralygynda çalşyrmak maslahat berilýär (5W-30 ýa-da 5W-40 ýaly ýokary hilli sintetik ýaglar). Awtomat karopkanyň ýagyny bolsa her 40,000 - 50,000 km-den täzeläp durmaly.";
            }

            // Kondisioner we yssy howa (AC & Summer)
            if (str_contains($q, 'kondisioner') || str_contains($q, 'sowat') || str_contains($q, 'gyzýar') || str_contains($q, 'gyzyar') || str_contains($q, 'yssy')) {
                return "Tomus gelmezden öň esasy 3 zady ediň: 1. Radiatoryň öňüni tozan-hapadan basyşly suw bilen ýuwuň; 2. Kondisioneriň freon basyşyny we kompressor ýagyny barladyň; 3. Salon filtrini täzeläň. Motor gyzmazlygy üçin antifriziň hiline aýratyn üns beriň.";
            }

            // Karopka: Awtomat vs Wariator (Transmission)
            if (str_contains($q, 'wariator') || str_contains($q, 'variator') || str_contains($q, 'cvt') || str_contains($q, 'karopka')) {
                return "Klassiki gidrotransformator awtomatlar (6-8 basgançakly) iň ygtybarly hasaplanýar. Wariator (CVT) ýangyjy tygşytlaýar we ýumşak sürülýär, ýöne ol aşa gyzmagy, çägä batyp gaz bermegi we agyr ýük çekmegi halamaýar.";
            }

            // Jip / Krossover vs Sedan
            if (str_contains($q, 'krossower') || str_contains($q, 'jip') || str_contains($q, 'sedan') || str_contains($q, 'suv')) {
                return "Eger esasan Aşgabat içinde sürýän bolsaňyz, Sedan (Toyota Camry, Corolla, Elantra) tygşytlylyk we ýumşaklyk taýdan amatly. Emma welaýat ýollaryna, oba ýa-da çöllük ýerlere köp çykýan bolsaňyz, ýerden beýik Krossover (RAV4, Santa Fe, RX) ýa-da Prado has ygtybarly bolar.";
            }

            // Probeg (High mileage)
            if (str_contains($q, 'probeg') || str_contains($q, '200') || str_contains($q, '300') || str_contains($q, 'ýörän')) {
                return "Ulag üçin probegiň sanyndan hem möhüm zat — oňa nähili seredilendigi we wagtynda ýagynyň çalşylandygadyr. Toyota we Lexus üçin 200,000 km kadaly probegdir. Ýöne nemes ýa-da wariatorly ulaglarda 200 müňden soň çynlakaý tehniki barlag gerekdir.";
            }

            // Ýangyç tygşytlamak (Fuel economy tips)
            if (str_contains($q, 'benzin köp') || str_contains($q, 'tygşyt') || str_contains($q, 'rasxod') || str_contains($q, 'rastehod')) {
                return "Ýangyç sarp edilişini azaltmak üçin: 1. Şemleri (sveçalary) we howa filtrini täzeläň; 2. Forsunkalary (injektorlary) arassaladyň; 3. Teperleriň basyşyny kadaly saklaň; 4. Aşa çalt tizlik almakdan we duýdansyz tormoz bermekden gaça duruň.";
            }

            // Ulagy çalt satmak (Selling tips)
            if (str_contains($q, 'çalt sat') || str_contains($q, 'satjak') || str_contains($q, 'satmak')) {
                return "Ulagyňyzy Ulagym sahypasynda çalt satmak üçin: 1. Gündiz arassa ýagdaýda 8-10 sany aýdyň surat düşüriň (daşy, salony, motory); 2. Bazara laýyk real baha goýuň; 3. Düşündirişde bar bolan gowy taraplaryny we aýratynlyklaryny anyk ýazyň.";
            }

            // Check Engine (Duýduryş çyrasy)
            if (str_contains($q, 'check') || str_contains($q, 'çek') || str_contains($q, 'çyra')) {
                return "Check Engine çyrasy ýananda ilki benzin bakynyň gapagynyň berk ýapylandygyny barlaň (basyş peselse hem ýanýar). Eger öçmese, tiz wagtdan OBD2 kompýuter diagnostikasyna baryp kody okadyň — kemçilik datçiklerden, l-zonddan ýa-da katalizatordan bolup biler.";
            }

            // Toyota modelleri
            if (str_contains($q, 'camry') || str_contains($q, 'corolla') || str_contains($q, 'avalon') || str_contains($q, 'toyota')) {
                return "Toyota modelleri (Camry, Corolla, Avalon) Türkmenistanyň iň ygtybarly we iň likwid maşynlarydyr. Olaryň kondisioneri 45°C yssyda hem güýçli sowadýar, podweskasy çydamly we islendik wagt satjak bolsaňyz müşderisi taýyndyr.";
            }

            // Lexus modelleri
            if (str_contains($q, 'lexus') || str_contains($q, 'es') || str_contains($q, 'rx')) {
                return "Lexus (aýratyn-da ES 350 we RX 350) — bu Toyota ygtybarlylygy bilen Premium derejeli amatlylygyň birleşmesidir. Motory we karopkasy örän uzak ömürli, emma Toyota bilen deňeşdirilende käbir kuzow we elektronika şaýlary gymmatrakdyr.";
            }

            // Hyundai & Kia
            if (str_contains($q, 'hyundai') || str_contains($q, 'kia') || str_contains($q, 'elantra') || str_contains($q, 'sonata') || str_contains($q, 'optima') || str_contains($q, 'k5')) {
                return "Hyundai we Kia (Elantra, Sonata, Optima, Sportage) döwrebap dizaýny, baý opsiýalary we elýeter bahasy bilen tapawutlanýar. 2.0 we 2.4 motorlarynda ýagy wagtynda çalyşmak (5000 km-den) motoryň uzak hyzmat etmeginiň esasy şertidir.";
            }

            // Nemes maşynlary (Mercedes / BMW)
            if (str_contains($q, 'mercedes') || str_contains($q, 'bmw') || str_contains($q, 'nemes')) {
                return "Mercedes-Benz we BMW iň ýokary howpsuzlyk, dolandyryş lezzeti we abraý hödürleýär. Ýöne olar yzygiderli professional ideg, diňe original şaýlar we gowy hilli ýag talap edýär. Satyn almazdan öň ähli bloklaryny kompýuterden geçiriň.";
            }

            // Gibrid vs Benzin
            if (str_contains($q, 'gibrid') || str_contains($q, 'hybrid')) {
                return "Gibrid ulaglar şäher içinde benzini 40-50% tygşytlaýar. Türkmenistanda gibrid alanyňyzda esasy zat batareýanyň sowadyş wentilýatorynyň arassalygyna we inwertoryň ýagdaýyna üns bermekdir.";
            }

            // Baha / Býujet boýunça gözleg
            if ($budget || $brandId) {
                return $this->renderCarRecommendations('tk', $budget, $brandId, $brandName);
            }

            return "Awtoulaglar boýunça islän zadyňyzy sorap bilersiňiz! Mysal üçin: 'Ulag almazdan öň nämeleri barlamaly?', 'Kondisioner näme üçin gowy sowatmaýar?', '15,000$ býujet üçin ulaglar' ýa-da 'Wariator nähili?'.";
        }

        // ==============================================================
        // 🇷🇺 2. РУССКИЙ ЯЗЫК (RUSSIAN KNOWLEDGE BASE)
        // ==============================================================
        if ($lang === 'ru') {
            // Приветствие
            if (preg_match('/\b(привет|здравствуй|салам|добрый|хай|hello|hi)\b/ui', $q)) {
                return "Здравствуйте! Я ИИ-консультант Ulagym. Помогу вам выбрать автомобиль, сравнить модели, расскажу о техническом обслуживании или проверке перед покупкой. Какой вопрос вас интересует?";
            }

            // Проверка перед покупкой
            if (str_contains($q, 'провер') || str_contains($q, 'покупк') || str_contains($q, 'осмотр') || str_contains($q, 'диагностик')) {
                return "При покупке авто с пробегом обязательно проверьте: 1. Кузов толщиномером на следы сильных ДТП и геометрию лонжеронов; 2. Двигатель на холодный пуск (дым, стуки, потеки масла); 3. Коробку на плавность переключения без пинков; 4. Компьютерная диагностика всех электронных блоков.";
            }

            // Масло и ТО (Maintenance)
            if (str_contains($q, 'масл') || str_contains($q, 'то') || str_contains($q, 'обслуживан')) {
                return "В условиях жаркого климата Туркменистана моторное масло рекомендуется менять каждые 5 000 – 7 000 км (синтетика 5W-30 или 5W-40). Масло в автоматической коробке передач (АКПП) лучше обновлять каждые 40 000 – 50 000 км.";
            }

            // Кондиционер и жара (AC & Summer)
            if (str_contains($q, 'кондиционер') || str_contains($q, 'жар') || str_contains($q, 'греется') || str_contains($q, 'перегрев') || str_contains($q, 'радиатор')) {
                return "Перед летним сезоном: 1. Тщательно промойте радиаторы от пыли и пуха; 2. Проверьте уровень фреона и давление компрессора; 3. Замените салонный фильтр; 4. Проверьте плотность антифриза и работу вентиляторов охлаждения.";
            }

            // АКПП vs Вариатор (Transmission)
            if (str_contains($q, 'вариатор') || str_contains($q, 'автомат') || str_contains($q, 'акпп') || str_contains($q, 'кпп') || str_contains($q, 'коробк')) {
                return "Классический гидротрансформаторный автомат — самый надежный и неприхотливый вариант. Вариатор (CVT) плавен и экономит топливо, но боится перегрева в песке, резких стартов со светофора и буксования.";
            }

            // Кроссовер vs Седан
            if (str_contains($q, 'кроссовер') || str_contains($q, 'седан') || str_contains($q, 'внедорожник') || str_contains($q, 'джип')) {
                return "Для города (Ашхабад) седан (Camry, Corolla, Elantra) удобнее, мягче и экономичнее. Если же вы часто выезжаете в велаяты, на трассу или природу, лучше выбрать кроссовер с высоким клиренсом (RAV4, RX, Santa Fe) или внедорожник Prado.";
            }

            // Пробег (Mileage)
            if (str_contains($q, 'пробег') || str_contains($q, '200') || str_contains($q, '300') || str_contains($q, 'скручен')) {
                return "Для японских авто (Toyota, Lexus) 150 000 – 200 000 км при регулярном уходе — это нормальный рабочий ресурс. Главное — реальная история обслуживания, а не цифра на одометре.";
            }

            // Расход топлива
            if (str_contains($q, 'расход') || str_contains($q, 'много ест') || str_contains($q, 'эконом')) {
                return "Чтобы снизить расход топлива: замените свечи зажигания и воздушный фильтр, промойте топливные форсунки, проверьте давление в шинах и избегайте агрессивных разгонов в городском потоке.";
            }

            // Продажа авто
            if (str_contains($q, 'быстро продать') || str_contains($q, 'продать') || str_contains($q, 'объявлен')) {
                return "Чтобы быстро продать авто на Ulagym: сделайте 8-10 качественных фото чистой машины при дневном свете, укажите адекватную рыночную цену и честно опишите комплектацию и состояние в описании.";
            }

            // Чек двигателя (Check Engine)
            if (str_contains($q, 'чек') || str_contains($q, 'check')) {
                return "При загорании Check Engine первым делом проверьте плотность закрытия крышки бензобака. Если ошибка не исчезает, сделайте сканирование OBD2 — причина может быть в датчике кислорода (лямбда), катализаторе или катушке зажигания.";
            }

            // Модели Toyota
            if (str_contains($q, 'camry') || str_contains($q, 'камри') || str_contains($q, 'тойот') || str_contains($q, 'toyota') || str_contains($q, 'corolla')) {
                return "Toyota (Camry, Corolla, Avalon) — абсолютные лидеры надежности и ликвидности в Туркменистане. У них мощные кондиционеры, выносливая подвеска под наши дороги и минимальное падение цены со временем.";
            }

            // Модели Lexus
            if (str_contains($q, 'lexus') || str_contains($q, 'лексус') || str_contains($q, 'rx') || str_contains($q, 'es')) {
                return "Lexus (особенно ES 350 и RX 350) сочетает надежность платформы Toyota с премиальным комфортом и отличной шумоизоляцией. Двигатели V6 3.5 л при хорошем масле практически вечные.";
            }

            // Корейцы (Hyundai / Kia)
            if (str_contains($q, 'hyundai') || str_contains($q, 'kia') || str_contains($q, 'хендай') || str_contains($q, 'киа') || str_contains($q, 'elantra') || str_contains($q, 'optima')) {
                return "Корейские авто (Elantra, Sonata, Optima, Sportage) привлекают современным оснащением и доступной ценой. Для двигателей 2.0 и 2.4 главное правило — менять качественное масло каждые 5-6 тысяч км во избежание задиров.";
            }

            // Немцы (Mercedes / BMW)
            if (str_contains($q, 'mercedes') || str_contains($q, 'bmw') || str_contains($q, 'мерседес') || str_contains($q, 'бмв')) {
                return "Mercedes-Benz и BMW дарят неповторимый комфорт и динамику, но требуют высококвалифицированного сервиса и качественных запчастей. Перед покупкой обязательно проверьте состояние электроники и цепей ГРМ.";
            }

            // Поиск по бюджету / марке
            if ($budget || $brandId) {
                return $this->renderCarRecommendations('ru', $budget, $brandId, $brandName);
            }

            return "Вы можете спросить меня о чем угодно: 'Как проверить авто перед покупкой?', 'Что лучше: вариатор или автомат?', 'Машины до 20000$' или 'Почему греется мотор летом?'.";
        }

        // ==============================================================
        // 🇬🇧 3. ENGLISH (ENGLISH KNOWLEDGE BASE)
        // ==============================================================
        // Greetings
        if (preg_match('/\b(hello|hi|hey|greetings|test)\b/ui', $q)) {
            return "Hello! I am Ulagym AI Assistant. I can help you with car appraisals, budget recommendations, pre-purchase inspections, and maintenance tips. How can I assist you today?";
        }

        // Pre-purchase inspection
        if (str_contains($q, 'inspect') || str_contains($q, 'check') || str_contains($q, 'buying tips') || str_contains($q, 'before buy')) {
            return "Before buying a used car, always: 1. Inspect the body panels with a paint depth gauge for prior collision repairs; 2. Cold-start the engine to check for smoke or unusual noises; 3. Test the gearbox for smooth shifts; 4. Perform an OBD2 diagnostic scan for hidden trouble codes.";
        }

        // Oil & Maintenance
        if (str_contains($q, 'oil') || str_contains($q, 'maintenance') || str_contains($q, 'service')) {
            return "In Turkmenistan's hot climate, change engine oil every 5,000 – 7,000 km using quality full-synthetic oil (5W-30 or 5W-40). Transmission fluid should typically be replaced every 40,000 – 50,000 km.";
        }

        // Transmission (CVT vs Automatic)
        if (str_contains($q, 'transmission') || str_contains($q, 'gearbox') || str_contains($q, 'cvt') || str_contains($q, 'automatic')) {
            return "Traditional torque-converter automatic transmissions are the most durable and reliable for local conditions. Continuously Variable Transmissions (CVTs) offer smooth driving and better fuel economy, but are sensitive to overheating and heavy towing.";
        }

        // Summer & AC overheating
        if (str_contains($q, 'ac') || str_contains($q, 'air conditioning') || str_contains($q, 'overheat') || str_contains($q, 'summer') || str_contains($q, 'heat')) {
            return "To prepare for summer: 1. Clean the exterior radiator fins of sand and debris; 2. Check AC refrigerant pressure and compressor performance; 3. Replace the cabin air filter; 4. Ensure engine coolant is fresh.";
        }

        // Hybrid vs Petrol
        if (str_contains($q, 'hybrid') || str_contains($q, 'electric') || str_contains($q, 'ev')) {
            return "Hybrids save up to 40-50% fuel in city stop-and-go traffic. In high summer temperatures, regularly inspect the traction battery cooling fan and intake filter to prevent overheating.";
        }

        // Toyota & Lexus
        if (str_contains($q, 'toyota') || str_contains($q, 'camry') || str_contains($q, 'corolla') || str_contains($q, 'lexus')) {
            return "Toyota and Lexus are the undisputed leaders in reliability and resale value in Turkmenistan. Their air conditioning systems handle 45°C heat with ease, and spare parts are readily available nationwide.";
        }

        // Korean cars (Hyundai / Kia)
        if (str_contains($q, 'hyundai') || str_contains($q, 'kia') || str_contains($q, 'elantra') || str_contains($q, 'sonata')) {
            return "Hyundai and Kia deliver modern styling and great technology at competitive price points. Regular oil changes every 5,000 km are crucial for long-term engine longevity.";
        }

        // Selling advice
        if (str_contains($q, 'sell') || str_contains($q, 'selling')) {
            return "To sell your car fast on Ulagym: take 8-10 clean, high-resolution daylight photos (exterior, interior, engine bay), price it competitively against similar listings, and write an honest description.";
        }

        // Search by budget / brand
        if ($budget || $brandId) {
            return $this->renderCarRecommendations('en', $budget, $brandId, $brandName);
        }

        return "You can ask me anything about cars! For example: 'What to check before buying a car?', 'CVT vs Automatic transmission', 'Car recommendations under $20,000', or 'How to keep your car cool in summer?'.";
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
            $query->where('price', '<=', $budget);
        }

        $cars = $query->orderBy('price', 'desc')->take(3)->get();

        if ($lang === 'tk') {
            if ($cars->isNotEmpty()) {
                $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} ýyl) — \${$c->price}")->implode("\n");
                $target = $budget ? "$" . number_format($budget) . " çenli" : $brandName;
                return "Siziň {$target} gözlegiňiz boýunça häzirki wagtda bazamyzdaky amatly ulaglar:\n{$list}\nGiňişleýin görmek üçin baş sahypadaky süzgüçden peýdalanyp bilersiňiz.";
            }
            return "Häzirki wagtda " . ($budget ? "$" . number_format($budget) . " çenli" : $brandName) . " tassyklanan ulag tapylmady. Baş sahypadaky süzgüçden baha çägini giňeldip gözläp bilersiňiz.";
        }

        if ($lang === 'ru') {
            if ($cars->isNotEmpty()) {
                $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year} г.) — \${$c->price}")->implode("\n");
                $target = $budget ? "до $" . number_format($budget) : $brandName;
                return "По вашему запросу {$target} в наличии есть следующие варианты:\n{$list}\nПодробности можно посмотреть на главной странице каталога.";
            }
            return "К сожалению, в данный момент вариантов " . ($budget ? "до $" . number_format($budget) : $brandName) . " нет в наличии. Попробуйте изменить параметры поиска.";
        }

        // English
        if ($cars->isNotEmpty()) {
            $list = $cars->map(fn($c) => "• {$c->brand->name} {$c->model} ({$c->year}) — \${$c->price}")->implode("\n");
            $target = $budget ? "under $" . number_format($budget) : $brandName;
            return "Here are top matches {$target} currently in our inventory:\n{$list}\nYou can browse full listings on our home page.";
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
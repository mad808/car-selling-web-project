<style>
    .footer-custom {
        background: linear-gradient(180deg, #135aa0 0%, #0d0f11 100%);
        color: #9aa0a6;
        font-size: 0.92rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .footer-heading {
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 1.25rem;
        font-size: 1.05rem;
        letter-spacing: -0.2px;
        position: relative;
    }

    .footer-heading::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 25px;
        height: 2px;
        background-color: #0d6efd;
        border-radius: 2px;
    }

    .footer-link {
        color: #9aa0a6;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .footer-link:hover {
        color: #ffffff;
        transform: translateX(4px);
    }

    .footer-link.ai-link {
        color: #0d6efd;
        font-weight: 600;
    }
    .footer-link.ai-link:hover {
        color: #3d8bfd;
    }

    .social-btn {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.06);
        color: #e9ecef;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        margin-right: 8px;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .social-btn:hover {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.35);
    }

    .newsletter-input {
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        border-radius: 10px 0 0 10px;
    }

    .newsletter-input:focus {
        background-color: rgba(255, 255, 255, 0.08);
        border-color: #0d6efd;
        color: #ffffff;
        box-shadow: none;
    }

    .footer-divider {
        border-color: rgba(255, 255, 255, 0.08);
    }
</style>

<footer class="footer-custom pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 justify-content-between">

            <!-- Column 1: Brand & About -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-bold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-car-front-fill text-primary"></i>
                    <span>Ulagym</span>
                </h5>
                <p class="mb-4" style="line-height: 1.7; font-size: 0.9rem;">
                    {{ __('site.Turkmenistans #1 digital marketplace for buying and selling cars. Secure, fast, and easy to use. Join thousands of happy drivers today.') }}
                </p>

                <!-- Social Icons -->
                <div class="d-flex">
                    <a href="https://github.com/mad808/car-selling-web-project/tree/main" target="_blank" class="social-btn" title="GitHub"><i class="bi bi-github"></i></a>
                    <a href="#" class="social-btn" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn" title="Telegram"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="social-btn" title="TikTok"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">{{ __('site.Quick Links') }}</h6>
                <ul class="list-unstyled mb-0">
                    <li><a href="{{ route('home') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i> {{ __('site.home') }}</a></li>
                    <li><a href="{{ route('cars.create') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i> {{ __('site.sell_car') }}</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i> {{ __('site.about') }}</a></li>
                    <!-- Täze: AI Maslahatçy Sahypasy -->
                    <li>
                        <a href="{{ route('ai.index') }}" class="footer-link ai-link">
                            <i class="bi bi-robot me-1"></i> AI Assistant
                        </a>
                    </li>
                    <li><a href="{{ route('login') }}" class="footer-link"><i class="bi bi-chevron-right me-1 small"></i> {{ __('site.login') }} / {{ __('site.register') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">{{ __('site.Contact Us') }}</h6>
                <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                    <li class="mb-3 d-flex align-items-start gap-2">
                        <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                        <span>Ashgabat, Turkmenistan<br>10 Yyl Abadanchylyk Str.</span>
                    </li>
                    <li class="mb-3">
                        <a href="tel:+99362240774" class="text-decoration-none text-light d-flex align-items-center gap-2">
                            <i class="bi bi-telephone-fill text-primary"></i> +993 62 240774
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="mailto:eziz5505@gmail.com" class="text-decoration-none text-light d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill text-primary"></i> eziz5505@gmail.com
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Newsletter -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">{{ __('site.Stay Updated') }}</h6>
                <p class="small text-light mb-3">{{ __('site.New features & top deals sent to your inbox.') }}</p>
                <form action="#" onsubmit="event.preventDefault();">
                    <div class="input-group">
                        <input type="email" class="form-control newsletter-input" placeholder="Your email...">
                        <button class="btn btn-primary px-3" type="button"><i class="bi bi-send-fill"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="footer-divider my-4">

        <!-- Bottom Footer -->
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small">&copy; {{ date('Y') }} <strong>Ulagym</strong>. {{ __('site.All rights reserved.') }}</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <a href="https://github.com/mad808/car-selling-web-project/tree/main" target="_blank" class="text-light small text-decoration-none me-3"><i class="bi bi-github me-1"></i> GitHub</a>
                <a href="#" class="text-light small text-decoration-none me-3">{{ __('site.Privacy Policy') }}</a>
                <a href="#" class="text-light small text-decoration-none">{{ __('site.Terms of Service') }}</a>
            </div>
        </div>
    </div>
</footer>
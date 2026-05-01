<!-- Custom Footer CSS (You can move this to your main CSS file) -->
<style>
    .footer-custom {
        background-color: #111315;
        /* Very dark gray, almost black */
        color: #adb5bd;
        font-size: 0.95rem;
    }

    .footer-heading {
        color: #fff;
        font-weight: 700;
        margin-bottom: 1.2rem;
        font-size: 1.1rem;
    }

    .footer-link {
        color: #adb5bd;
        text-decoration: none;
        transition: all 0.3s ease;
        display: block;
        margin-bottom: 0.8rem;
    }

    .footer-link:hover {
        color: #0d6efd;
        /* Primary Color */
        padding-left: 5px;
        /* Slide effect */
    }

    .social-btn {
        width: 38px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.1);
        color: #fff;
        border-radius: 50%;
        margin-right: 10px;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-btn:hover {
        background-color: #0d6efd;
        color: #fff;
        transform: translateY(-3px);
    }

    .footer-divider {
        border-color: rgba(255, 255, 255, 0.1);
    }
</style>

<footer class="footer-custom pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4 justify-content-between">

            <!-- Column 1: Brand & About -->
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-bold mb-3">
                    <i class="bi bi-car-front-fill text-primary"></i> Ulagym
                </h5>
                <p class="mb-4" style="line-height: 1.7;">
                    {{ __('site.Turkmenistans #1 digital marketplace for buying and selling cars. Secure, fast, and easy to use. Join thousands of happy drivers today.') }}
                </p>
                <!-- Social Icons -->
                <div class="d-flex">
                    <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-telegram"></i></a>
                    <a href="#" class="social-btn"><i class="bi bi-tiktok"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">{{ __('site.Quick Links') }}</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="footer-link">{{ __('site.home') }}</a></li>
                    <li><a href="{{ route('cars.create') }}" class="footer-link">{{ __('site.sell_car') }}</a></li>
                    <li><a href="{{ route('about') }}" class="footer-link">{{ __('site.about') }}</a></li>
                    <li><a href="{{ route('login') }}" class="footer-link">{{ __('site.login') }} / {{ __('site.register') }}</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Info -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">{{ __('site.Contact Us') }}</h6>
                <ul class="list-unstyled">
                    <li class="mb-3 d-flex">
                        <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
                        <span>Ashgabat, Turkmenistan<br>10 Yyl Abadanchylyk Str.</span>
                    </li>
                    <li class="mb-3">
                        <a href="tel:+99361000000" class="text-decoration-none text-light">
                            <i class="bi bi-telephone-fill text-primary me-2"></i> +993 62 240774
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="mailto:support@tmcars.com" class="text-decoration-none text-light">
                            <i class="bi bi-envelope-fill text-primary me-2"></i> eziz5505@gmail.com
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Column 4: Newsletter (Visual Only) -->
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">{{ __('site.Stay Updated') }}</h6>
                <p class="small text-light mb-3">{{ __('site.New features & top deals sent to your inbox.') }}</p>
                <form action="#">
                    <div class="input-group mb-2">
                        <input type="email" class="form-control bg-dark border-secondary text-light" placeholder="Your email">
                        <button class="btn btn-primary" type="button"><i class="bi bi-send-fill"></i></button>
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
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <a href="#" class="text-light small text-decoration-none me-3">{{ __('site.Privacy Policy') }}</a>
                <a href="#" class="text-light small text-decoration-none">{{ __('site.Terms of Service') }}</a>
            </div>
        </div>
    </div>
</footer>
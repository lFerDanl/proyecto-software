<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from silicon.createx.studio/account-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Aug 2022 19:42:42 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <title>Silicon | Account - Sign In</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Silicon - Multipurpose Technology Bootstrap Template">
    <meta name="keywords"
        content="bootstrap, business, creative agency, mobile app showcase, saas, fintech, finance, online courses, software, medical, conference landing, services, e-commerce, shopping cart, multipurpose, shop, ui kit, marketing, seo, landing, blog, portfolio, html5, css3, javascript, gallery, slider, touch, creative">
    <meta name="author" content="Createx Studio">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon and Touch Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('assets/favicon/site.webmanifest')}}">
    <link rel="mask-icon" href="{{asset('assets/favicon/safari-pinned-tab.svg')}}" color="#6366f1">
    <link rel="shortcut icon" href="{{asset('assets/favicon/favicon.ico')}}">
    <meta name="msapplication-TileColor" content="#080032">
    <meta name="msapplication-config" content="{{asset('assets/favicon/browserconfig.xml')}}">
    <meta name="theme-color" content="#ffffff">

    <!-- Vendor Styles -->
    <link rel="stylesheet" media="screen" href="{{asset('assets/vendor/boxicons/css/boxicons.min.css')}}" />

    <!-- Main Theme Styles + Bootstrap -->
    <link rel="stylesheet" media="screen" href="{{asset('assets/css/theme.min.css')}}">
    @vite(['resources/js/app.js'])
    <!-- Page loading styles -->
    <style>
        .page-loading {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100%;
            -webkit-transition: all .4s .2s ease-in-out;
            transition: all .4s .2s ease-in-out;
            background-color: #fff;
            opacity: 0;
            visibility: hidden;
            z-index: 9999;
        }

        .dark-mode .page-loading {
            background-color: #0b0f19;
        }

        .page-loading.active {
            opacity: 1;
            visibility: visible;
        }

        .page-loading-inner {
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            text-align: center;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            -webkit-transition: opacity .2s ease-in-out;
            transition: opacity .2s ease-in-out;
            opacity: 0;
        }

        .page-loading.active>.page-loading-inner {
            opacity: 1;
        }

        .page-loading-inner>span {
            display: block;
            font-size: 1rem;
            font-weight: normal;
            color: #9397ad;
        }

        .dark-mode .page-loading-inner>span {
            color: #fff;
            opacity: .6;
        }

        .page-spinner {
            display: inline-block;
            width: 2.75rem;
            height: 2.75rem;
            margin-bottom: .75rem;
            vertical-align: text-bottom;
            border: .15em solid #b4b7c9;
            border-right-color: transparent;
            border-radius: 50%;
            -webkit-animation: spinner .75s linear infinite;
            animation: spinner .75s linear infinite;
        }

        .dark-mode .page-spinner {
            border-color: rgba(255, 255, 255, .4);
            border-right-color: transparent;
        }

        @-webkit-keyframes spinner {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>

    <!-- Theme mode -->
    <script>
        let mode = window.localStorage.getItem('mode'),
            root = document.getElementsByTagName('html')[0];
        if (mode !== null && mode === 'dark') {
            root.classList.add('dark-mode');
        } else {
            root.classList.remove('dark-mode');
        }
    </script>

    <!-- Page loading scripts -->
    <script>
        (function() {
            window.onload = function() {
                const preloader = document.querySelector('.page-loading');
                preloader.classList.remove('active');
                setTimeout(function() {
                    preloader.remove();
                }, 1000);
            };
        })();
    </script>

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;

            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-WKV3GT5');
    </script>
</head>


<!-- Body -->

<body>

    <!-- Google Tag Manager (noscript)-->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-WKV3GT5" height="0" width="0"
            style="display: none; visibility: hidden;"></iframe>
    </noscript>

    <!-- Page loading spinner -->
    <div class="page-loading active">
        <div class="page-loading-inner">
            <div class="page-spinner"></div><span>Loading...</span>
        </div>
    </div>


    <!-- Page wrapper for sticky footer -->
    <!-- Wraps everything except footer to push footer to the bottom of the page if there is little content -->
    <main class="page-wrapper">


        <!-- Navbar -->
        <!-- Remove "fixed-top" class to make navigation bar scrollable with the page -->
        <header class="header navbar navbar-expand-lg position-absolute navbar-sticky">
            <div class="container px-3">
                <a href="index.html" class="navbar-brand pe-3">
                    <img src="{{asset('assets/img/logo.svg')}}" width="47" alt="Silicon">
                    Silicon
                </a>

                <div class="form-check form-switch mode-switch pe-lg-1 ms-auto me-4" data-bs-toggle="mode">
                    <input type="checkbox" class="form-check-input" id="theme-mode">
                    <label class="form-check-label d-none d-sm-block" for="theme-mode">Light</label>
                    <label class="form-check-label d-none d-sm-block" for="theme-mode">Dark</label>
                </div>


            </div>
        </header>


        <!-- Page content -->
        <section class="position-relative h-100 pt-5 pb-4">

            <!-- Sign in form -->
            <div class="container d-flex flex-wrap justify-content-center justify-content-xl-start h-100 pt-5">
                <div class="w-100 align-self-end pt-1 pt-md-4 pb-4" style="max-width: 526px;">
                    <h1 class="text-center text-xl-start">Welcome Back</h1>
                    <p class="text-center text-xl-start pb-3 mb-3">Don’t have an account yet? <a
                            href="{{ route('singup') }}">Register here.</a></p>
                            <form id="loginForm" action="{{ route('login.submit') }}" method="POST" class="needs-validation mb-2" novalidate>
    @csrf
    <div class="position-relative mb-4">
        <label for="loginCorreo" class="form-label fs-base">Email</label>
        <input type="email" id="loginCorreo" name="correo" class="form-control form-control-lg" required autocomplete="username">
        <div class="invalid-feedback position-absolute start-0 top-100">Please enter a valid email address!</div>
    </div>
    <div class="mb-4">
        <label for="loginContrasena" class="form-label fs-base">Password</label>
        <div class="password-toggle">
            <input type="password" id="loginContrasena" name="contrasena" class="form-control form-control-lg" required autocomplete="current-password">
            <label class="password-toggle-btn" aria-label="Show/hide password">
                <input class="password-toggle-check" type="checkbox">
                <span class="password-toggle-indicator"></span>
            </label>
            <div class="invalid-feedback position-absolute start-0 top-100">Please enter your password!</div>
        </div>
    </div>
    <div class="mb-4">
        <div class="form-check">
            <input type="checkbox" id="remember" name="remember" class="form-check-input">
            <label for="remember" class="form-check-label fs-base">Remember me</label>
        </div>
    </div>
    <button type="submit" class="btn btn-primary shadow-primary btn-lg w-100">Sign in</button>
</form>

                    <a href="#" class="btn btn-link btn-lg w-100">Forgot your password?</a>
                    <hr class="my-4">
                    <h6 class="text-center mb-4">Or sign in with your social network</h6>
                    <div class="row row-cols-1 row-cols-sm-2">
                        <div class="col mb-3">
                            <a href="#" class="btn btn-icon btn-secondary btn-google btn-lg w-100">
                                <i class="bx bxl-google fs-xl me-2"></i>
                                Google
                            </a>
                        </div>
                        <div class="col mb-3">
                            <a href="#" class="btn btn-icon btn-secondary btn-facebook btn-lg w-100">
                                <i class="bx bxl-facebook fs-xl me-2"></i>
                                Facebook
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-100 align-self-end">
                    <p class="nav d-block fs-xs text-center text-xl-start pb-2 mb-0">
                        &copy; All rights reserved. Made by
                        <a class="nav-link d-inline-block p-0" href="https://createx.studio/" target="_blank"
                            rel="noopener">Createx Studio</a>
                    </p>
                </div>
            </div>

            <!-- Background -->
            <div class="position-absolute top-0 end-0 w-50 h-100 bg-position-center bg-repeat-0 bg-size-cover d-none d-xl-block"
                style="background-image: url({{asset('assets/img/account/signin-bg.jpg')}});"></div>
        </section>
    </main>


    <!-- Back to top button -->
    <a href="#top" class="btn-scroll-top" data-scroll>
        <span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Top</span>
        <i class="btn-scroll-top-icon bx bx-chevron-up"></i>
    </a>


    <!-- Vendor Scripts -->
    <script src="{{asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js')}}"></script>

    <!-- Main Theme Script -->
    <script src="{{asset('assets/js/theme.min.js')}}"></script>
</body>

<!-- Mirrored from silicon.createx.studio/account-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 04 Aug 2022 19:42:43 GMT -->

</html>

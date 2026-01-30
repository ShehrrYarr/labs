<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Al Ghani Lab</title>
    <link rel="icon" href="homepage_assets/img/AZT.png" src="homepage_assets/img/image1.jpg" type="image/x-icon">


    <meta name="description" content="Creative Agency, Portfolio, Corporate, Startup & Technology">
    <meta name="subject" content="Creative Agency, Portfolio, Corporate, Startup & Technology">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <meta name="author" content="Codings">

    <link rel="shortcut icon" href="homepage_assets/images/favicon.ico">
    <link rel="apple-touch-icon" href="homepage_assets/images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="homepage_assets/images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="homepage_assets/images/apple-touch-icon-114x114.png">

    <link rel="stylesheet" href="homepage_assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="homepage_assets/css/vendor/slider.min.css">
    <link rel="stylesheet" href="homepage_assets/css/main.css">
    <link rel="stylesheet" href="homepage_assets/css/vendor/icons.min.css">
    <link rel="stylesheet" href="homepage_assets/css/vendor/animation.min.css">
    <link rel="stylesheet" href="homepage_assets/css/vendor/gallery.min.css">

    <link rel="stylesheet" href="homepage_assets/css/default.css">
    <link rel="stylesheet" href="homepage_assets/css/theme-indigo.css">

    <!-- ==============================================
        Theme Settings
        =============================================== -->
    <style>
        :root {
            --header-bg-color: #f5f5f5;
            --nav-item-color: #2f323a;
            --top-nav-item-color: #2f323a;
            --hero-bg-color: #f5f5f5;

            --section-1-bg-color: #f5f5f5;
            --section-2-bg-color: #f5f5f5;
            --section-3-bg-color: #f5f5f5;
            --section-4-bg-color: #f5f5f5;
            --section-5-bg-color: #f5f5f5;
            --section-6-bg-color: #f5f5f5;

            --footer-bg-color: #f5f5f5;
        }

        .custom-apple {
            font-size: 60px;
            /* Adjust the value as needed */
        }
        .custom-android {
            font-size: 60px;
            /* Adjust the value as needed */
        }
        .custom-access {
            font-size: 60px;
            /* Adjust the value as needed */
        }
    </style>

</head>

<body class="home">
    <header id="header">

        <!-- Navbar -->
        <nav data-aos="zoom-out" data-aos-delay="800" class="navbar navbar-expand"
            style="background-color: var(--section-1-bg-color)">
            <div class="container header">

                <a class="navbar-brand" href="/">
                    Al Ghani<i class="version-number">Lab</i>
                    <!--
                            Custom Logo
                            <img src="homepage_assets/images/logo.svg" alt="Leverage">
                        -->
                </a>
                

                <div class="ml-auto"></div>

                <ul class="navbar-nav items">
                    <li class="nav-item dropdown">
                        <a href="#slider" class="nav-link smooth-anchor">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#features" class="nav-link smooth-anchor">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link smooth-anchor">About</a>
                    </li>

                </ul>

                <!-- Navbar Icons -->
                <ul class="navbar-nav icons">
                    <li class="nav-item social">
                        <a href="https://www.tiktok.com/@alikhokhar2222?_t=ZS-8yZo1AGjsN6&_r=1"></i></a>
                    </li>
                    <li class="nav-item social">
                        <a href="https://www.facebook.com/share/1MCLU6RjLC/?mibextid=wwXIfr"></i></a>
                    </li>
                </ul>

                <!-- Navbar Toggle -->
                <ul class="navbar-nav toggle">
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-toggle="modal" data-target="#menu">
                            <i class="icon-menu m-0"></i>
                        </a>
                    </li>
                </ul>

            </div>
        </nav>

    </header>

    <!-- Hero -->

    <section id="slider" class="hero p-0 featured left">
        <div class="swiper-container no-slider animation slider-h-100">
            <div class="swiper-wrapper">

                <!-- Item 1 -->
                <div class="swiper-slide slide-center">
                    <img data-aos="zoom-out-up" data-aos-delay="800" src="homepage_assets/img/image1.jpg"
                        class="hero-image" alt="Hero Image">
                    <div class="slide-content row" data-mask-768="80">
                        <div class="col-12 d-flex inner">
                            <div class="left align-self-center text-center text-md-left">
                                <h1 data-aos="zoom-out-up" data-aos-delay="400" class="title effect-static-text">Al Ghani Lab</h1>
                                <p data-aos="zoom-out-up" data-aos-delay="800" class="description">We Perform
                                    Lab Tests at affordable prices</p>
                                <a href="#" data-toggle="modal" data-target="#login"
                                    class="smooth-anchor ml-auto mr-auto ml-md-0 mt-4 btn primary-button"><i
                                        class="icon-cup"></i>CLICK HERE TO LOGIN</a>
                                <div class="step-title">
                                    <!--<a href="#" data-toggle="modal" data-target="#register">-->
                                    <!--    <h5 style="color:rgb(78, 10, 181)">Create an Account</h5>-->
                                    <!--</a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="section-2 offers featured featured left">
        <div class="container">
            <div class="row justify-content-center text-center items">
                <div class="col-12 col-md-6 col-lg-4 item">
                    <div class="card no-hover">
                        <i class="bi bi-apple custom-apple"></i>
                        <h4>iphone</h4>
                        <p>We deal with all kind of lab tests
                        </p>
                    </div>
                </div>
                {{-- <div class="col-12 col-md-6 col-lg-4 item">
                    <div class="card no-hover">
                        <i class="bi bi-phone custom-android"></i>
                        <h4>Andriod</h4>
                        <p>We deal with all PTA non-PTA J.V Android Models including their accessories
                        </p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 item">
                    <div class="card no-hover">
                        <i class="bi bi-gear" style="font-size: 60px"></i>
                        <h4>Accessories</h4>
                        <p>We deal with all the accessories including Back-cover Front-Glass Headphones Handsfree Cables Chargers Wireless-Earbuds and more
                        </p>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>

    



    <!-- Footer -->
    <footer>

        <!-- Footer [links] -->
        <section id="about" class="footer">
            <div class="container">
                <div class="row items footer-widget">
                    <div class="col-12 col-lg-3 p-0">
                        <div class="row">
                            <div class="branding col-12 p-3 text-center text-lg-left item">
                                <div class="brand">
                                    <a href="/" class="logo">
                                       Al Ghani Lab
                                        <!--
                                                Custom Logo
                                                <img src="homepage_assets/images/logo.svg" alt="Leverage">
                                            -->
                                    </a>
                                </div>
                                <p>We are selling PTA,NON-PTA Iphones and Andriods at affordable prices</p>
                                <ul class="navbar-nav social share-list mt-0 ml-auto">

                                    <li class="nav-item">
                                        <a href=""><i class="icon-social-facebook"></i></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="" class="nav-link"><i class="bi bi-tiktok"></i></a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link"><i class="bi bi-whatsapp"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-9 p-0">
                        <div class="row">

                            <div class="col-12 col-lg-4 p-3 text-center text-lg-left item">
                            </div>
                            <div class="col-12 col-lg-4 p-3 text-center text-lg-left item">
                                <h4 class="title">Our Services</h4>
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">iphones</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">Andriod</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">Accessories</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-12 col-lg-4 p-3 text-center text-lg-left item">
                                <h4 class="title">Get in Touch</h4>
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="icon-phone mr-2"></i>
                                          +92300000000

                                        </a>
                                    </li>
                                    
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="icon-envelope mr-2"></i>
                                            hello@business.com
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" class="nav-link">
                                            <i class="icon-location-pin mr-2"></i>
                                            Office Street, 123
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Copyright -->
        <section id="copyright" class="p-3 copyright">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 p-3 text-center text-lg-left">
                        <p>We are tracking any intention of piracy.</p>
                    </div>
                    <div class="col-12 col-md-6 p-3 text-center text-lg-right">
                        <p>© 2023 <a href="https://www.linkedin.com/in/syed-shehryar-46a21a1b3/">Sherry</a>  </p>
                    </div>
                </div>
            </div>
        </section>

    </footer>

   

    <!-- Modal [sign] -->
     <div id="sign" class="p-0 modal fade" role="dialog" aria-labelledby="sign" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content full">
                <div class="modal-header" data-dismiss="modal">
                    Sign In Form <i class="icon-close"></i>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}" class="row">
                        @csrf
                        <div class="col-12 p-0 align-self-center">

                            <div class="row">
                                <div class="col-12 p-0 input-group">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 p-0 input-group">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0 input-group align-self-center">
                                    <button class="btn primary-button"><i class="icon-login"></i>LOGIN</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> 

   
   
    <div id="login" class="p-0 modal fade" role="dialog" aria-labelledby="register" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content full">
                <div class="modal-header" data-dismiss="modal">
                    Login Form <i class="icon-close"></i>
                </div>

                <div class="modal-body">
                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="row">
                        @csrf
                        <div class="col-12 p-0 align-self-center">
                            <div class="row">
                                <div class="col-12 p-0 pb-3">
                                    <h2>Log In</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0 input-group">
                                    <input id="email" type="text"
    class="form-control @error('email') is-invalid @enderror"
    name="email" value="{{ old('email') }}" placeholder="Enter Customer Id or Email" required autocomplete="off" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 p-0 input-group">
                                    <input placeholder="Password" id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 p-0 input-group align-self-center">
                                    <button class="btn primary-button"><i class="icon-login"></i>LOGIN</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal [responsive menu] -->
    <div id="menu" class="p-0 modal fade" role="dialog" aria-labelledby="menu" aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content full">
                <div class="modal-header" data-dismiss="modal">
                    Menu <i class="icon-close"></i>
                </div>
                <div class="menu modal-body">
                    <div class="row w-100">
                        <div class="items p-0 col-12 text-center">
                            <!-- Append [navbar] -->
                        </div>
                        <div class="contacts p-0 col-12 text-center">
                            <!-- Append [navbar] -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll [to top] -->
    <div id="scroll-to-top" class="scroll-to-top">
        <a href="#header" class="smooth-anchor">
            <i class="icon-arrow-up"></i>
        </a>
    </div>

  <script>
(function () {
  const form = document.getElementById('loginForm');
  if (!form) return;

  // IMPORTANT: select inside the form (avoids duplicate #email issue)
  const emailInput = form.querySelector('input[name="email"]');
  if (!emailInput) return;

  const domain = '@alghanilab.com';

  form.addEventListener('submit', function () {
    let v = (emailInput.value || '').trim();

    // Only if customer entered 8 digits (no @), convert silently right before submit
    if (/^\d{8}$/.test(v) && !v.includes('@')) {
      emailInput.value = v + domain;
    }
  });
})();
</script>
    <script src="homepage_assets/js/vendor/jquery.min.js"></script>
    <script src="homepage_assets/js/vendor/jquery.easing.min.js"></script>
    <script src="homepage_assets/js/vendor/jquery.inview.min.js"></script>
    <script src="homepage_assets/js/vendor/popper.min.js"></script>
    <script src="homepage_assets/js/vendor/bootstrap.min.js"></script>
    <script src="homepage_assets/js/vendor/ponyfill.min.js"></script>
    <script src="homepage_assets/js/vendor/slider.min.js"></script>
    <script src="homepage_assets/js/vendor/animation.min.js"></script>
    <script src="homepage_assets/js/vendor/progress-radial.min.js"></script>
    <script src="homepage_assets/js/vendor/bricklayer.min.js"></script>
    <script src="homepage_assets/js/vendor/gallery.min.js"></script>
    <script src="homepage_assets/js/vendor/shuffle.min.js"></script>
    <script src="homepage_assets/js/vendor/particles.min.js"></script>
    <script src="homepage_assets/js/main.js"></script>

</body>

</html>

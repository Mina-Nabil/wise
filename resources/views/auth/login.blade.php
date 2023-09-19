<!DOCTYPE html>
<html lang="en" dir="ltr" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <title>Wise - Login</title>
    <link rel="icon" type="image/png" href="assets/images/logo/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/rt-plugins.css">
    <link href="https://unpkg.com/aos@2.3.0/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="">
    <link rel="stylesheet" href="assets/css/app.css">
    <!-- START : Theme Config js-->
    <script src="assets/js/settings.js" sync></script>
    <!-- END : Theme Config js-->
</head>

<body class=" font-inter skin-default">
    <!-- [if IE]> <p class="browserupgrade">
            You are using an <strong>outdated</strong> browser. Please
            <a href="https://browsehappy.com/">upgrade your browser</a> to improve
            your experience and security.
        </p> <![endif] -->

    <div class="loginwrapper">
        <div class="lg-inner-column">
            <div class="right-column  relative">
                <div class="inner-content h-full flex flex-col bg-white dark:bg-slate-800">
                    <div class="auth-box h-full flex flex-col justify-center">
                        <div class="mobile-logo text-center mb-6 lg:hidden block">
                            <a href="index.html">
                                <img src="assets/images/logo/logo.svg" alt="" class="mb-10 dark_logo">
                                <img src="assets/images/logo/logo-white.svg" alt="" class="mb-10 white_logo">
                            </a>
                        </div>
                        <div class="text-center 2xl:mb-10 mb-4">
                            <h4 class="font-medium">Sign in</h4>
                            <div class="text-slate-500 text-base">
                                Sign in to your account to start using Wise
                                @if (session('msg'))
                                    <div class="alert alert-danger">
                                        {{ session('msg') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <!-- BEGIN: Login Form -->
                        <form class="space-y-4" action="{{ url('/login') }}" method="POST">
                            @csrf
                            <div class="formGroup"> <!-- Typo corrected from "fromGroup" to "formGroup" -->
                                <label class="block capitalize form-label">Username</label>
                                <div class="relative">
                                    <input type="text" name="username" class="form-control py-2"
                                        placeholder="Enter username">
                                </div>
                            </div>
                            <div class="formGroup"> <!-- Typo corrected from "fromGroup" to "formGroup" -->
                                <label class="block capitalize form-label">Password</label>
                                <!-- Typo corrected from "passwrod" to "Password" -->
                                <div class="relative">
                                    <input type="password" name="password" class="form-control py-2"
                                        placeholder="Enter Password">
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" class="hidden">
                                    <!-- Typo corrected from "hiddens" to "hidden" -->
                                    <span class="text-slate-500 dark:text-slate-400 text-sm leading-6 capitalize">Keep
                                        me signed in</span>
                                </label>
                                <a class="text-sm text-slate-800 dark:text-slate-400 leading-6 font-medium"
                                    href="forget-password-one.html">Forgot Password?</a>
                            </div>
                            <button class="btn btn-dark block w-full text-center" type="submit">Sign in</button>
                        </form>
                        <!-- END: Login Form -->
                    </div>
                    <div class="auth-footer text-center">
                        Copyright 2023, Wise All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- scripts -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/rt-plugins.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>

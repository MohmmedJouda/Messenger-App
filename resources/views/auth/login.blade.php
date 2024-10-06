<!DOCTYPE html>
<html lang="en">
<!-- Head -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <title>Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./assets/img/favicon/favicon.ico" type="image/x-icon" />

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700" rel="stylesheet" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="./assets/css/template.bundle.css" />
    <link rel="stylesheet" href="./assets/css/template.dark.bundle.css" media="(prefers-color-scheme: dark)" />
</head>
<style>
    #logoAuth {
        color: #2787f5;
        margin: 15px;
        margin-left:43%;
        margin-right: auto;
        margin-bottom: 30px
    }
    .error-input {
        color: #dc3545;
        font-size: 13px;
    }
</style>

<body class="bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-center min-vh-100 gx-0">
            <div class="col-12 col-md-5 col-lg-6">
                <svg id="logoAuth" version="1.1" width="65px" height="65px" fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                    viewBox="0 0 46 46" enable-background="new 0 0 46 46" xml:space="preserve">
                    <polygon opacity="0.7" points="45,11 36,11 35.5,1 "></polygon>
                    <polygon points="35.5,1 25.4,14.1 39,21 "></polygon>
                    <polygon opacity="0.4" points="17,9.8 39,21 17,26 "></polygon>
                    <polygon opacity="0.7" points="2,12 17,26 17,9.8 "></polygon>
                    <polygon opacity="0.7" points="17,26 39,21 28,36 "></polygon>
                    <polygon points="28,36 4.5,44 17,26 "></polygon>
                    <polygon points="17,26 1,26 10.8,20.1 "></polygon>
                </svg>
                <div class="card card-shadow border-0">
                    <div class="card-body">
                        <div class="row g-6">
                            <div class="col-12">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-2">Sign In</h3>
                                    <p>Login to your account</p>
                                </div>
                            </div>


                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email Address -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="signin-password" name="email"
                                            :value="old('email')" required autofocus autocomplete="username"
                                            placeholder="Email" required />
                                        <label for="signin-password">Email</label>
                                        <x-input-error :messages="$errors->get('email')" class="mt-2 error-input" />
                                    </div>
                                </div><br>

                                <!-- Password -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="signin-password" name="password"
                                            required autocomplete="current-password" placeholder="Password" />
                                        <label for="signin-password">Password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 error-input" />

                                    </div>
                                </div>

                                <!-- Remember Me -->
                                <div class="block mt-4">
                                    <label for="remember_me" class="inline-flex items-center">
                                        <input id="remember_me" type="checkbox"
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                            name="remember">
                                        <span
                                            class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                                    </label>
                                </div><br>

                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-primary w-100" type="submit">
                                        Sign In
                                    </button>
                                </div>


                            </form>


                        </div>
                    </div>
                </div>

                <!-- Text -->
                <div class="text-center mt-8">
                    <p>
                        Don't have an account yet? <a href="{{ route('register') }}">Sign up</a>
                    </p>
                    @if (Route::has('password.request'))
                        <p><a href="{{ route('password.request') }}">Forgot Password?</a></p>
                    @endif
                </div>
            </div>
        </div>
        <!-- / .row -->
    </div>

    <!-- Scripts -->
    <script src="./assets/js/vendor.js"></script>
    <script src="./assets/js/template.js"></script>
</body>

</html>

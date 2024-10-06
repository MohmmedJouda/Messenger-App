<!DOCTYPE html>
<html lang="en">
<!-- Head -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <title>Register</title>

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
        margin-left: 43%;
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
            <div class="col-12 col-md-5 col-lg-8">
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
                                    <h3 class="fw-bold mb-2">Sign Up</h3>
                                    <p>Follow the easy steps</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="signup-name" name="name"
                                                :value="old('name')" required autofocus autocomplete="name"
                                                placeholder="Name" />
                                            <label for="signup-name">Name</label>
                                            <x-input-error :messages="$errors->get('name')" class="mt-2 error-input" />
                                        </div>
                                    </div>

                                    <!-- Email Address -->
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="signup-email" name="email"
                                                :value="old('email')" required autocomplete="username"
                                                placeholder="Email" />
                                            <label for="signup-email">Email</label>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 error-input" />
                                        </div>
                                    </div>
                                </div><br>
                                <!-- Password -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="signup-password" name="password"
                                            required autocomplete="new-password" placeholder="Password" />
                                        <label for="signup-password">Password</label>
                                        <x-input-error :messages="$errors->get('password')" class="mt-2 error-input" />
                                    </div>
                                </div><br>
                                <!-- Confirm Password -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="signup-password"
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm Password" />
                                        <label for="signup-password">Confirm Password</label>
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 error-input" />
                                    </div>
                                </div><br>
                                <!-- Submit -->
                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-primary w-100" type="submit">
                                        Create Account
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


                <!-- Text -->
                <div class="text-center mt-8">
                    <p>Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
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

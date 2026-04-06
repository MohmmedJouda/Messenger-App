<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <title>Two-Step Verification</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./assets/img/favicon/favicon.ico" type="image/x-icon" />

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700" rel="stylesheet" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/template.bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/template.dark.bundle.css') }}" media="(prefers-color-scheme: dark)" />
</head>

<style>
    #logoAuth {
        color: #2787f5;
        margin: 15px;
        margin-left: 43%;
        margin-right: auto;
        margin-bottom: 30px;
    }
    .error-input {
        color: #dc3545;
        font-size: 13px;
    }
    .shield-icon {
        display: flex;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .shield-icon svg {
        color: #2787f5;
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
                                <!-- Shield icon -->
                                <div class="shield-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-2">Two-Step Verification</h3>
                                    <p class="text-muted">Enter the 6-digit code from your authenticator app to continue.</p>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="col-12">
                                    <div class="alert alert-danger py-2">
                                        {{ $errors->first() }}
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('2fa.verify') }}" class="col-12">
                                @csrf

                                <div class="form-floating mb-4">
                                    <input type="text" class="form-control text-center" id="2fa-code" name="code"
                                        placeholder="000000" maxlength="6" inputmode="numeric"
                                        autocomplete="one-time-code" autofocus required
                                        style="letter-spacing: 0.5rem; font-size: 1.5rem;" />
                                    <label for="2fa-code">Authentication Code</label>
                                </div>

                                <button class="btn btn-block btn-lg btn-primary w-100" type="submit">
                                    Verify & Sign In
                                </button>
                            </form>

                            <div class="col-12 text-center mt-2">
                                <a href="{{ route('login') }}" class="text-muted small">
                                    ← Back to Sign In
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/vendor.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
</body>

</html>

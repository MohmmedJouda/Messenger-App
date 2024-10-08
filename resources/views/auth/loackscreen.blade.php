<!DOCTYPE html>
<html lang="en">
<!-- Head -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, maximum-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <title>Messenger - 2.0.1</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./assets/img/favicon/favicon.ico" type="image/x-icon" />

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700" rel="stylesheet" />

    <!-- Template CSS -->
    <link rel="stylesheet" href="./assets/css/template.bundle.css" />
    <link rel="stylesheet" href="./assets/css/template.dark.bundle.css" media="(prefers-color-scheme: dark)" />
</head>

<body class="bg-light">
    <div class="container">
        <div class="row align-items-center justify-content-center min-vh-100 gx-0">
            <div class="col-12 col-md-5 col-lg-4">
                <div class="card card-shadow border-0">
                    <div class="card-body position-relative pt-0 mb-6">
                        <div class="position-absolute top-0 start-50 translate-middle">
                            <a href="#" class="avatar avatar-xl mx-auto border border-light border-5">
                                <img src="assets/img/avatars/1.jpg" alt="" class="avatar-img" />
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-6">
                            <div class="col-12">
                                <div class="text-center">
                                    <h4 class="fw-bold">William Pearson</h4>
                                    <p>
                                        Enter your password to <br />
                                        unlock the screen
                                    </p>
                                </div>
                            </div>
                            <form action="" method="POST">
                                @csrf
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control"
                                            id="lockscreen-password" placeholder="Password" />
                                        <label for="lockscreen-password">Password</label>
                                    </div>
                                </div><br>

                                <div class="col-12">
                                    <button class="btn btn-block btn-lg btn-primary w-100" type="submit">
                                        Unlock
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Text -->
                <div class="text-center mt-8">
                    Not you? Return <a href="{{ route('login') }}">Sign in.</a>
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

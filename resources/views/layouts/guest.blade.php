<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('libs/bootstrap/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="{{ asset('libs/bootstrap/css/sb-admin-2.css') }}">
    <link rel="stylesheet" href="{{ asset('libs/css/app.css') }}">

</head>

<body class="bg-gradient-light">
    <!-- Level tertinggi: penuhi 100% tinggi viewport dan jadikan flex container -->
    <div class=" container vh-100 d-flex justify-content-center align-items-center">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-4">
                <img src="{{ asset('libs/img/logo-smk.png') }}" alt="logo" style="max-width:20%;" class="mb-2">
                <p class="font-weight-bold h5">Lorem ipsum dolor sit amet consectetur!</p>
            </div>

            <!-- Kotak login -->
            <div class="bg-white rounded-lg px-5 py-4">
                {{ $slot }}
            </div>
        </div>
    </div>





    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('libs/bootstrap/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('libs/bootstrap/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('libs/bootstrap/js/sb-admin-2.min.js') }}"></script>

</body>

</html>

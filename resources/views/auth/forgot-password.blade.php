<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="default-style">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> AIM - Lead Engine - Reset Password </title>

    <!-- Main font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900"
        rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/linearicons.css') }}">

    <link rel="stylesheet" href="{{ mix('/css/login.css') }}">
</head>

<body>
    <div class="authentication-wrapper authentication-2 px-4">
        <div class="authentication-inner py-5">

        <!-- Logo -->
        <div class="d-flex justify-content-center align-items-center mb-5">
            <img src="/images/logo.png" style="height: 60px">
        </div>

        @if(session()->has('success-message'))
            <div class="alert alert-success">
                {{ session()->get('success-message') }}
            </div>    
        @endif

        @if(session()->has('error-message'))
            <div class="alert alert-danger">
                {{ session()->get('error-message') }}
            </div>    
        @endif

        <!-- / Logo -->
                  <!-- Form -->
        <form class="card" method="post" action="{{ route('password.do-forgot') }}">
            @csrf
            <div class="p-4 p-sm-6">

                <h5 class="text-center text-muted font-weight-normal mb-4">Reset Your Password</h5>
                <hr class="mt-0 mb-4">

                <p>
                    Enter your email address and we will send you a link to reset your password.
                </p>

                <div class="form-group">
                    <legend tabindex="-1" class="col-form-label pt-0">Email Address</legend>
                    <div tabindex="-1" role="group">
                        <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" tabindex="1" id="email">
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Send Password Reset Email</button>

            </div>
        </form>
        <!-- / Form -->

        </div>
    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="default-style">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> AIM - Lead Engine </title>

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
    <div class="authentication-wrapper authentication-1 px-4">
        <div class="authentication-inner py-5">
            <div class="d-flex justify-content-center align-items-center mb-5"><img src="images/logo.png"
                    style="height: 60px;"></div>
            @if($errors->has('combination'))
              <div class="alert alert-danger">
                {{ $errors->first('combination') }}
              </div>
            @endif

            @if(session()->has('success-message'))
                <div class="alert alert-success">
                    {{ session()->get('success-message') }}
                </div>
            @endif

            <form class="mb-5" method="post" action="{{ route('do-login') }}">
                @csrf
            
                <fieldset class="form-group" id="__BVID__11">
                    <legend tabindex="-1" class="col-form-label pt-0" id="__BVID__11__BV_label_">Email Address</legend>
                    <div tabindex="-1" role="group">
                    <input type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" tabindex="1" id="email">
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    </div>
                </fieldset>
                <fieldset class="form-group" id="__BVID__13">
                    <legend tabindex="-1" class="col-form-label pt-0" id="__BVID__13__BV_label_">
                        <div class="d-flex justify-content-between align-items-end">
                        <div>Password</div> <a href="{{ route('password.forgot') }}" class="d-block small">Forgot password?</a>
                        </div>
                    </legend>
                    <div tabindex="-1" role="group"> 
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" tabindex="2" id="password">
                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                </fieldset>
                <div class="d-flex justify-content-between align-items-center m-0">
                    <div class="m-0 custom-control custom-checkbox"><input type="checkbox" autocomplete="off"
                            class="custom-control-input" value="true" id="__BVID__15"><label
                            class="custom-control-label" for="__BVID__15">Stay Signed In</label></div> <button
                        tabindex="3" type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" class="default-style">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title></title>

    <!-- Main font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ mix('/vendor/fonts/linearicons.css') }}">
    <!-- <link rel="stylesheet" href="{{ mix('/vendor/fonts/open-iconic.css') }}"> -->
    <!-- <link rel="stylesheet" href="{{ mix('/vendor/fonts/pe-icon-7-stroke.css') }}"> -->

    <link rel="stylesheet" href="{{ mix('/vendor/css/uikit.css') }}">

    <!-- Layout helpers -->
    <script src="{{ mix('/vendor/js/layout-helpers.js') }}"></script>

    <style>
        #feedback {
            float: left;
            position: fixed;
            top: calc(50% - 47px);
            right: 0;
        }

        #feedback a {
            background-color: #F44336 !important;
            border-radius: 0 5px 5px 0;
            box-shadow: 0 0 3px rgba(0, 0, 0, .3);
            border: 2px solid #fff;
            color: #ffffff;
            border-left: 0;
            display: block;
            padding: 20px 3px;
            transition: all .2s ease-in-out;
            writing-mode: tb-rl;
            transform: rotate(-180deg);
        }

        #feedback a:hover {
            padding-left: 30px;
        }


    </style>



</head>
<body>
    <div id="app"></div>

    <script src="{{ mix('/admin/entry-point.js') }}"></script>

    <div id="feedback">
        <a href="https://sonidosoftware.featureupvote.com/" target="_blank">
            feedback
        </a>
    </div>

</body>
</html>

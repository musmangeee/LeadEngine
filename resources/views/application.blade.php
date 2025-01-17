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

   
</head>
<body>
    <div id="app"></div>
    <script src="{{ mix('/entry-point.js') }}"></script>
</body>
</html>

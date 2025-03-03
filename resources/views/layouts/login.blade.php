<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="{{ $baseUrl }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-ico">

    <title>CreoServer - Customer Portal</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
</head>
<body>
    <script type="application/javascript">
        window.promoTexts = <?php echo json_encode($loadingTitle); ?>
    </script>
    @yield('content')
</body>
<script type="text/javascript" src="{{ asset('js/login.js') }}" defer></script>
<script type="application/javascript">
    sessionStorage.setItem('login_timer', null);
</script>
<script type="text/javascript" src="{{ asset('js/validation.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('asset/jq3.6.0.js') }}" defer></script>
{{--<script type="text/javascript" src="{{ asset('asset/auth.js') }}" defer></script>--}}

</html>

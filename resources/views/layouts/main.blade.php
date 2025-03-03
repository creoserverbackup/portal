<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <base href="{{ config('app.url') }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-ico">

    <title>@yield('Title','CustomerPortal')</title>

    <link type="text/css" rel="stylesheet" href="{{ asset('css/screenLoader.css') }}">

    <?php

    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
//    $lang = str_replace('_', '-', app()->getLocale());

//    if ($lang !== 'nl' && $lang !== 'be') {
        echo "<script src='https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js'></script>
<script src='/asset/google-translate.js'></script>
<script src='//translate.google.com/translate_a/element.js?cb=TranslateInit'></script>";

//     }
//    echo '<script type="application/javascript">
//    window.lang = ' . $lang . '
//    </script>'
    ?>

    <script type="application/javascript">
        window.addEventListener("load", function () {
            (function fade() {
                let el = document.getElementById("start-loader");
                window.isLoaded = true;
                (el.style.opacity -= .05) < 0 ? el.parentNode.removeChild(el) : setTimeout(fade, 25)
            })();
        });

        // let language = window.navigator.language;
        // let languageFistTwo = language.substr(0, 2); // To only keep the first 2 characters.
        // let currentLocation = document.getElementsByTagName('html')[0].getAttribute('lang-js')
        //
        // console.log("currentLocation currentLocation currentLocation")
        // console.log(currentLocation)

        // console.log(" languageFistTwo languageFistTwo languageFistTwo")
        // console.log(languageFistTwo)


        // if (languageFistTwo !== 'nl') {

        //     // document.write("<script src='https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js'><\/script>");
        //     // document.write("<script src='/asset/google-translate.js'><\/script>");
        //     // document.write("<script src='//translate.google.com/translate_a/element.js?cb=TranslateInit'><\/script>");
        // }


    </script>

    @isset(auth()->user()->id)

    <meta name="user" content="{{ auth()->user()->id }}">
    <script type="application/javascript">
        window.username = "{!! auth()->user()->username !!}"
        window.user = {{ auth()->user()->id }};
    </script>
    @endisset
    @isset(auth()->user()->customerId)
    <script type="application/javascript">
        window.customerId = "{!! auth()->user()->customerId !!}"
    </script>
    @endisset

    <script type="application/javascript">
        window.lang = "{{ str_replace('_', '-', app()->getLocale()) }}";
        {{--window.lang = "{{ str_replace('_', '-', substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) }}";--}}
        // window.lang = "nl";
    </script>

    <!-- Styles -->
    @yield('styles')
</head>
<body>

<div class="curtain" style="opacity: 0.9; z-index: 1000000000000;" id="start-loader">
    <div class="loader">
        <div class="atom-spinner">
            <div class="spinner-inner">
                <div class="spinner-line"></div>
                <div class="spinner-line"></div>
                <div class="spinner-line"></div>
                <div class="spinner-circle"></div>
            </div>
        </div>
        @php
        if (isset($loadingTitle)) {
            echo "<div style=\"white-space: pre;text-align: center;\">" . $loadingTitle . "</div>";
            }
        @endphp
    </div>
</div>

@section('timeLine')
    <div class="workspace">
        @yield('content')
    </div>
@show

@yield('app')

</body>
</html>

@extends('layouts.main')

@section('styles')
    <link type="text/css" rel="stylesheet" href="{{ asset('css/app.css') }}">
@endsection

@section('app')
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
@endsection

@section('content')
    <noscript>
        <strong>We're sorry, but CreoWorkflow doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div hidden id="validation"></div>
    <div class="router-space" id="workflow">
        <router-view></router-view>
{{--        <cookie-popup></cookie-popup>--}}
    </div>
@endsection

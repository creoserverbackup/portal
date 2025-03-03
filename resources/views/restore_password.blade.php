@extends('layouts.login')

@section('content')
<?php
    $bgUrl = '/images/login-bg/authorize-bg-'.rand(1,12).'.jpg';
?>
    <div class="auth-main">
        <div hidden id="validation"></div>
        <div class="row align-items-center">
            <div class="auth-bg" style="background-image: url('{{ $bgUrl }}')"></div>
            <div class="left-form">
                <div class="auth-container">
                    <div class="inner-logo auth-main__logo d-none d-md-block">
                        <a class="inner-logo__img-wrap" href="{{ $pathCreodc }}/accounts/login">
                            <img class="inner-logo__img img-responsive" src="{{ asset('/images/logo.png') }}" alt="logotype">
                        </a>
                    </div>
                    <div class="log-in-section">
                        <div class="auth-intro">{{__('restore.authPageStrongNotification')}}</div>
                        <form method="POST" action="{{ $pathCreodc }}/accounts/login/restore_password" name="auth" class="auth-form">
                            @csrf
                            <input type="hidden" name="_lang" value="{{ app()->getLocale() }}"/>
                            <div class="auth-form__field">
                                <label class="auth-form__label">{{__('restore.authEmailLabel')}}</label>
                                <input type="text" class="auth-form__input" name="email" value="{{ $email }}">
                                <label class="auth-form__info-error-back">
                                    <p>{{ $error }}</p>
                                </label>
                            </div>
                            <button type="submit" class="btn btn--primary auth-form__btn">
                                <span>{{__('restore.authBtnRestore')}}</span></button>
                        </form>
                    </div>
                    @if(isset($mainTitle) && !empty($mainTitle) && !empty(json_decode($mainTitle)))
                        <aside class="alert-notification">
                            <div class="alert-notification__heading">Attentie!</div>
                            <p class="alert-notification__desc">{{ $mainTitle }}</p>
                        </aside>
                    @endif
                </div>
            </div>
            <div class="right-form">
                <div class="auth-promo-desc" id="promo-344">Bij CreoServer reinigen, controleren, en testen wij alles voordat u het product ontvangt</div>
            </div>
        </div>
    </div>
<script src="../../asset/jq3.6.0.js"></script>
@endsection

@extends('layouts.login')

@section('content')
    <?php
    use Illuminate\Support\Facades\Cookie;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Log;

    $bgUrl = '/images/login-bg/authorize-bg-' . rand(1, 12) . '.jpg';

    $email = Cookie::get('email') ?: '';
    $on = 'unchecked';

    session()->flush();

    if ($email != '')
        $on = 'checked';
    ?>

    <div class="auth-main">
        <div class="row align-items-center">
            <div class="auth-bg" style="background-image: url({{ $bgUrl }})"></div>
            <div class="left-form">
                <div class="auth-container">
                    <div class="inner-logo auth-main__logo d-none d-md-block">
                        <a class="inner-logo__img-wrap" href="{{ $pathCreodc }}/accounts/login">
                            <img class="inner-logo__img img-responsive" src="{{ asset('/images/logo.png') }}" alt="logotype">
                        </a>
                    </div>
                    <div class="log-in-section">
                        <div class="auth-intro">{{__('login.authPageIntro')}}</div>
                        {{--                        <form method="POST" action="{{ route('login.store') }}" name="auth" id="auth-form"--}}

{{--                        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>--}}
{{--                        <script>--}}
{{--                            function captcha(e) {--}}
{{--                                e.preventDefault()--}}
{{--                                grecaptcha.ready(function () {--}}
{{--                                    grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}', {action: 'contact'}).then(function (token) {--}}
{{--                                        if (token) {--}}
{{--                                            document.getElementById('recaptcha').value = token;--}}
{{--                                            document.getElementById('auth-form').submit()--}}
{{--                                        }--}}
{{--                                    });--}}
{{--                                });--}}

{{--                            }--}}
{{--                        </script>--}}



                        <div class="auth-main__language language">
{{--                            <img src="/images/lang/lang__ru.png" alt="ru" data-google-lang="ru" class="language__img">--}}
                            <img src="images/lang/lang__nl.png" alt="nl" data-google-lang="nl" class="language__img">
                            <img src="images/lang/lang__en.png" alt="en" data-google-lang="en" class="language__img">
                            <img src="images/lang/lang__de.png" alt="de" data-google-lang="de" class="language__img">
                            <img src="images/lang/lang__fr.png" alt="fr" data-google-lang="fr" class="language__img">
                            <img src="images/lang/lang__pt.png" alt="pt" data-google-lang="pt" class="language__img">
                            <img src="images/lang/lang__es.png" alt="es" data-google-lang="es" class="language__img">
                            <img src="images/lang/lang__it.png" alt="it" data-google-lang="it" class="language__img">
                            <img src="images/lang/lang__zh-CN.png" alt="zh" data-google-lang="zh-CN" class="language__img">
{{--                            <img src="images/lang/lang__ar.png" alt="ar" data-google-lang="ar" class="language__img">--}}
{{--                            <img src="images/lang/lang__sv.png" alt="sv" data-google-lang="sv" class="language__img">--}}
                        </div>

                        <div class="google-auth">
                            <a href="https://creoserver.com/accounts/auth/redirect/google" class="google-btn z-1000">
                                <img src="/images/google.svg" width="15" height="15" class="z-1000">
                                <span class="z-1000">Sign in with Google</span>
                            </a>
                        </div>

                        <form method="POST" action="{{ $pathCreodc }}/accounts/login" name="auth" id="auth-form"
{{--                              onsubmit="captcha(event)" class="auth-form">--}}
                              class="auth-form">
                            @csrf

                            <input type="hidden" name="recaptcha" id="recaptcha">
                            <input type="hidden" name="_lang" value="{{ app()->getLocale() }}"/>
                            <input type="hidden" name="transfer" value="{{ $_GET['transfer'] ?? '' }}"/>
                            <input type="hidden" name="uid" value="{{ $_GET['uid'] ?? '' }}"/>
                            <input type="hidden" id="validation"/>
                            <div class="auth-form__field">
                                <label class="auth-form__label" for="u-name">{{__('login.authNameLabel')}}</label>
                                <input type="text" class="auth-form__input" name="email" id="u-name"
                                       value="{{ $email }}">
                            </div>

                            <div class="auth-form__field">
                                <label class="auth-form__label"
                                       for="u-password">{{__('login.authPasswordLabel')}}</label>
                                <div class="auth-form__field-wrap">
                                    <input type="password" class="auth-form__input auth-form__input--icon"
                                           name="password" id="u-password">
                                    <span class="auth-form__field-icon" id="block-icon-eye-close"
                                          onclick="switchPasswordVisibility()">
                                        <div class="icon-eye-close"><img src="/images/icon-eye-close.png"></div>
                                    </span>
                                    <span class="auth-form__field-icon" id="block-icon-eye-open"
                                          onclick="switchPasswordVisibility(false)">
                                        <div class="icon-eye-open"><img src="/images/icon-eye-open.png"></div>
                                    </span>
                                </div>
                            </div>

                            <script type="application/javascript">
                                function switchPasswordVisibility(open = true) {
                                    if (open) {
                                        document.getElementById('block-icon-eye-open').style.display = 'block';
                                        document.getElementById('block-icon-eye-close').style.display = 'none';
                                        document.getElementById('u-password').type = 'text';
                                    } else {
                                        document.getElementById('block-icon-eye-open').style.display = 'none';
                                        document.getElementById('block-icon-eye-close').style.display = 'block';
                                        document.getElementById('u-password').type = 'password';
                                    }
                                }

                                // document.addEventListener('mouseup', function(){
                                //     document.getElementById('u-password').type = 'password';
                                // });
                            </script>
                            <div class="checkbox-label auth-form__checkbox">
                                <input class="checkbox-label__input" type="checkbox"
                                       name="privacy"
                                       id="u-privacy"
                                        {!! $on !!}
                                >
                                <label class="checkbox-label__main"
                                       for="u-privacy">{{__('login.authCheckboxPrivacy')}}</label>
                            </div>

                            @if(isset($msg))
                                <div class="auth-form__info-error-back">
                                    <p>{{ $msg[1] }}</p>
                                </div>
                            @endif
                            <div class="auth-form__info-error">
                                <p class="auth-form__info-error-message"></p>
                            </div>

                            <button class="btn btn--primary auth-form__btn"><span>{{__('login.authBtnText')}}</span>
                            </button>
                        </form>

                        <div class="auth-form__info">
                            <p>{{ __('login.forgotLogin') }} {{ __('login.forgotLogin_noProblemBefore') }}
                                <a href="{{ $pathCreodc }}/accounts/login/restore_password">{{ __('login.forgotLogin_noProblem') }}</a>
                                {{ __('login.forgotLogin_noProblemAfter') }}
                            </p>
                        </div>
                        @if(isset($mainTitle) && !empty($mainTitle) && !empty(json_decode($mainTitle)))
                            <aside class="alert-notification">
                                <div class="alert-notification__heading">Attentie!</div>
                                    <p class="alert-notification__desc scroll">{!!  json_decode($mainTitle) !!}</p>
                            </aside>
                        @endif
                    </div>
                </div>
            </div>

            @if(isset($loadingTitle) && !empty($loadingTitle))
                <div class="right-form">
                    <div class="auth-promo-desc" id="promo-344">{{ $loadingTitle[0] }}</div>
                </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src='/asset/google-translate.js'></script>
    <script src="//translate.google.com/translate_a/element.js?cb=TranslateInit"></script>
{{--        <script src="asset/jq3.6.0.js"></script>--}}

@endsection

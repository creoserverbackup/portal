@extends('layouts.login')
@section('content')
    <?php
    $bgUrl = '/images/login-bg/authorize-bg-'.rand(1,12).'.jpg';
    $itemsForForm = [
        'authNewPassword'  => __('passwords.authNewPassword'),
        'authNewPasswordConfirm' => __('passwords.authNewPasswordConfirm'),
        'pswRequirementParam1' => __('passwords.pswRequirementParam1'),
        'pswRequirementParam2' => __('passwords.pswRequirementParam2'),
        'pswRequirementParam3' => __('passwords.pswRequirementParam3'),
        'pswRequirementParam4' => __('passwords.pswRequirementParam4'),
        'authBtnReset' => __('passwords.authBtnReset'),
        'urlForm' => $pathCreodc . '/accounts/login/change_password',
    ];

    if (isset($errors)) {
        $itemsForForm['errors'] = $errors;
    }
    if (isset($temporaryPassword)) {
        $itemsForForm['temporaryPassword'] = $temporaryPassword;
    }
    ?>
    <div class="auth-main">
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
                        <div class="auth-intro">{{__('passwords.authResetPageStrongNotification')}}</div>

                        <div id="validation">
                            <validationPassword itemsstring="{{json_encode($itemsForForm)}}">
                            </validationPassword>
                        </div>
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
    <script src="asset/jq3.6.0.js"></script>
@endsection

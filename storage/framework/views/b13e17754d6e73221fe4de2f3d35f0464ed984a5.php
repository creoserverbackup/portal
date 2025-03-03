<?php $__env->startSection('content'); ?>
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
            <div class="auth-bg" style="background-image: url(<?php echo e($bgUrl); ?>)"></div>
            <div class="left-form">
                <div class="auth-container">
                    <div class="inner-logo auth-main__logo d-none d-md-block">
                        <a class="inner-logo__img-wrap" href="<?php echo e($pathCreodc); ?>/accounts/login">
                            <img class="inner-logo__img img-responsive" src="<?php echo e(asset('/images/logo.png')); ?>" alt="logotype">
                        </a>
                    </div>
                    <div class="log-in-section">
                        <div class="auth-intro"><?php echo e(__('login.authPageIntro')); ?></div>
                        



















                        <div class="auth-main__language language">

                            <img src="images/lang/lang__nl.png" alt="nl" data-google-lang="nl" class="language__img">
                            <img src="images/lang/lang__en.png" alt="en" data-google-lang="en" class="language__img">
                            <img src="images/lang/lang__de.png" alt="de" data-google-lang="de" class="language__img">
                            <img src="images/lang/lang__fr.png" alt="fr" data-google-lang="fr" class="language__img">
                            <img src="images/lang/lang__pt.png" alt="pt" data-google-lang="pt" class="language__img">
                            <img src="images/lang/lang__es.png" alt="es" data-google-lang="es" class="language__img">
                            <img src="images/lang/lang__it.png" alt="it" data-google-lang="it" class="language__img">
                            <img src="images/lang/lang__zh-CN.png" alt="zh" data-google-lang="zh-CN" class="language__img">


                        </div>

                        <div class="google-auth">
                            <a href="https://creoserver.com/accounts/auth/redirect/google" class="google-btn z-1000">
                                <img src="/images/google.svg" width="15" height="15" class="z-1000">
                                <span class="z-1000">Sign in with Google</span>
                            </a>
                        </div>

                        <form method="POST" action="<?php echo e($pathCreodc); ?>/accounts/login" name="auth" id="auth-form"

                              class="auth-form">
                            <?php echo csrf_field(); ?>

                            <input type="hidden" name="recaptcha" id="recaptcha">
                            <input type="hidden" name="_lang" value="<?php echo e(app()->getLocale()); ?>"/>
                            <input type="hidden" name="transfer" value="<?php echo e($_GET['transfer'] ?? ''); ?>"/>
                            <input type="hidden" name="uid" value="<?php echo e($_GET['uid'] ?? ''); ?>"/>
                            <input type="hidden" id="validation"/>
                            <div class="auth-form__field">
                                <label class="auth-form__label" for="u-name"><?php echo e(__('login.authNameLabel')); ?></label>
                                <input type="text" class="auth-form__input" name="email" id="u-name"
                                       value="<?php echo e($email); ?>">
                            </div>

                            <div class="auth-form__field">
                                <label class="auth-form__label"
                                       for="u-password"><?php echo e(__('login.authPasswordLabel')); ?></label>
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
                                        <?php echo $on; ?>

                                >
                                <label class="checkbox-label__main"
                                       for="u-privacy"><?php echo e(__('login.authCheckboxPrivacy')); ?></label>
                            </div>

                            <?php if(isset($msg)): ?>
                                <div class="auth-form__info-error-back">
                                    <p><?php echo e($msg[1]); ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="auth-form__info-error">
                                <p class="auth-form__info-error-message"></p>
                            </div>

                            <button class="btn btn--primary auth-form__btn"><span><?php echo e(__('login.authBtnText')); ?></span>
                            </button>
                        </form>

                        <div class="auth-form__info">
                            <p><?php echo e(__('login.forgotLogin')); ?> <?php echo e(__('login.forgotLogin_noProblemBefore')); ?>

                                <a href="<?php echo e($pathCreodc); ?>/accounts/login/restore_password"><?php echo e(__('login.forgotLogin_noProblem')); ?></a>
                                <?php echo e(__('login.forgotLogin_noProblemAfter')); ?>

                            </p>
                        </div>
                        <?php if(isset($mainTitle) && !empty($mainTitle) && !empty(json_decode($mainTitle))): ?>
                            <aside class="alert-notification">
                                <div class="alert-notification__heading">Attentie!</div>
                                    <p class="alert-notification__desc scroll"><?php echo json_decode($mainTitle); ?></p>
                            </aside>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if(isset($loadingTitle) && !empty($loadingTitle)): ?>
                <div class="right-form">
                    <div class="auth-promo-desc" id="promo-344"><?php echo e($loadingTitle[0]); ?></div>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src='/asset/google-translate.js'></script>
    <script src="//translate.google.com/translate_a/element.js?cb=TranslateInit"></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/alexey/projects/creoserver-portal/resources/views/login.blade.php ENDPATH**/ ?>
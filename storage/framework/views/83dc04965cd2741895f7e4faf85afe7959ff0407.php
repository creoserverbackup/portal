<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base href="<?php echo e($baseUrl); ?>" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <link href="<?php echo e(asset('favicon.ico')); ?>" rel="icon" type="image/x-ico">

    <title>CreoServer - Customer Portal</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/login.css')); ?>">
</head>
<body>
    <script type="application/javascript">
        window.promoTexts = <?php echo json_encode($loadingTitle); ?>
    </script>
    <?php echo $__env->yieldContent('content'); ?>
</body>
<script type="text/javascript" src="<?php echo e(asset('js/login.js')); ?>" defer></script>
<script type="application/javascript">
    sessionStorage.setItem('login_timer', null);
</script>
<script type="text/javascript" src="<?php echo e(asset('js/validation.js')); ?>" defer></script>
<script type="text/javascript" src="<?php echo e(asset('asset/jq3.6.0.js')); ?>" defer></script>


</html>
<?php /**PATH /home/alexey/projects/creoserver-portal/resources/views/layouts/login.blade.php ENDPATH**/ ?>
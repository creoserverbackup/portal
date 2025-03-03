<?php $__env->startSection('styles'); ?>
    <link type="text/css" rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('app'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/app.js')); ?>" defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <noscript>
        <strong>We're sorry, but CreoWorkflow doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div hidden id="validation"></div>
    <div class="router-space" id="workflow">
        <router-view></router-view>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/alexey/projects/creoserver-portal/resources/views/main.blade.php ENDPATH**/ ?>
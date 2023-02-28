


<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('frontend.components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
           <?php echo e($item->titleEn); ?>

        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <?php echo $item->contentEn; ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/frontend/about.blade.php ENDPATH**/ ?>
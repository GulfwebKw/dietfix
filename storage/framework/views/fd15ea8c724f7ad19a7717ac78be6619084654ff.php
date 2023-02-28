

<?php $__env->startSection('content'); ?>
<?php if(!empty(Request()->lang) && Request()->lang=="en"): ?>
<?php echo $item->contentEn; ?>

<?php elseif(!empty(Request()->lang) && Request()->lang=="ar"): ?>
<div dir="rtl" align="right"><?php echo $item->contentAr; ?></div>
<?php else: ?>
<?php echo $item->contentEn; ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appFrontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/frontend/about_app.blade.php ENDPATH**/ ?>
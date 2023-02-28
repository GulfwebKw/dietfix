<?php $__env->startSection('contents'); ?>
    <div align="center">
        
        
        
    </div>
    <h2>Password Reset</h2>

    <div>
        To reset your password, complete this form: <?php echo e(URL::to('user/reset', array($token))); ?>.
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/emails/name.blade.php ENDPATH**/ ?>
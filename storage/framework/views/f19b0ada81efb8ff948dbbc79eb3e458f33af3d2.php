<?php $__env->startSection('contents'); ?>

    <div class="portlet box red">

        <div class="portlet-title">

            <div class="caption"><i class="icon-money"></i><?php echo e(trans('main.Payment Failed')); ?></div>

        </div>

        <div class="portlet-body">

                <p><?php echo e($message); ?></p>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/payments/emptyError.blade.php ENDPATH**/ ?>
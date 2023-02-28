<?php $__env->startSection('contents'); ?>

    <div class="portlet box red">

        <div class="portlet-title">

            <div class="caption"><i class="icon-money"></i><?php echo e(trans('main.Payment Failed')); ?></div>
            <?php if(isset($message)): ?>
              <div class="caption"><i class="icon-money"></i><?php echo e($message); ?></div>
            <?php endif; ?>

        </div>

        <div class="portlet-body">

            <table class="table table-striped table-hover">

                <tbody>

                <tr>
                    <th><?php echo e(trans('Payment ID')); ?></th>
                    <td><?php echo e($payment->paymentID); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(trans('Post Date')); ?></th>
                    <td><?php echo e($payment->created_at); ?></td>
                </tr>




                <tr>
                    <th><?php echo e(trans('Amount')); ?></th>
                    <td><?php echo e($payment->total); ?></td>
                </tr>

                <tr>
                    <th><?php echo e(trans('Ref ID')); ?></th>
                    <td><?php echo e($payment->ref_id); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(trans('Error')); ?></th>
                    <td><?php echo e($payment->description); ?></td>
                </tr>

                </tbody>

            </table>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/payments/newError.blade.php ENDPATH**/ ?>
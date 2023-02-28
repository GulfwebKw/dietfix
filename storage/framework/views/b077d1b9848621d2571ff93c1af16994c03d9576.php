<?php $__env->startSection('contents'); ?>

    <div class="portlet box blue">


        <div class="portlet-body">

            <table class="table table-striped table-hover">

                <tbody>

                    <tr>
                        <th><?php echo e(trans('Payment ID')); ?></th>
                        <td><?php echo e($payment->paymentID); ?></td>
                    </tr>
                    <?php if($payment->status == 1): ?>
                        <tr>
                            <th><?php echo e(trans('Result')); ?></th>
                            <td>Success</td>
                        </tr>
                    <?php endif; ?>
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
                    <?php if(isset($giftTitle)): ?>
                        <tr>
                            <th><?php echo e(trans('Gift')); ?></th>
                            <td><?php echo e($giftTitle); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/payments/success.blade.php ENDPATH**/ ?>
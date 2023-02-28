<?php $__env->startSection('contents'); ?>

    <h2><?php echo e(trans('main.Cash_Back_Request')); ?></h2>


    <table>
        <tr>
            <th><?php echo e(trans('main.Username')); ?></th>
            <td><?php echo e(optional($user->user)->username); ?></td>
        </tr>
        <tr>
            <th><?php echo e(trans('main.Mobile')); ?></th>
            <td><?php echo e(optional($user->user)->mobile); ?></td>
        </tr>
        <tr>
            <th><?php echo e(trans('main.Amount')); ?></th>
            <td><?php echo e($cashBack->value); ?></td>
        </tr>
        <tr>
            <th><?php echo e(trans('main.Created Date')); ?></th>
            <td><?php echo e($cashBack->created_at); ?></td>
        </tr>

    </table>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/emails/cash_back.blade.php ENDPATH**/ ?>
<?php $__env->startSection('contents'); ?>

    <?php if($status==1): ?>
         <h2><?php echo e(trans('main.Payment Successfully')); ?></h2>
     <?php else: ?>
        <h2 style="color:#800"><?php echo e(trans('main.Payment Failed')); ?></h2>
     <?php endif; ?>


<table>
	<tr>
		<th><?php echo e(trans('main.Payment ID')); ?></th>
		<td><?php echo e($payment->paymentID); ?></td>
	</tr>
	<tr>
		<th><?php echo e(trans('main.Result')); ?></th>
        <?php if($payment->paid==1): ?>
            <td style="color: green">Success</td>
            <?php else: ?>
            <td style="color: red">Failed</td>
        <?php endif; ?>

	</tr>
	<tr>
		<th><?php echo e(trans('main.Created Date')); ?></th>
		<td><?php echo e($payment->created_at); ?></td>
	</tr>
	<tr>
		<th><?php echo e(trans('main.Username')); ?></th>
		<td><?php echo e(optional($payment->user)->username); ?></td>
	</tr>
	<tr>
		<th><?php echo e(trans('main.Amount')); ?></th>
		<td><?php echo e($payment->total); ?></td>
	</tr>
	<tr>
		<th><?php echo e(trans('main.RefId')); ?></th>
		<td><?php echo e($payment->ref); ?></td>
	</tr>
</table>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('emails.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/emails/user_payment_success.blade.php ENDPATH**/ ?>
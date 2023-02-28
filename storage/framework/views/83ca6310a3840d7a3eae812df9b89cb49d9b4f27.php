

<?php $__env->startSection('contents'); ?>
	
	<h3 class="hero">
		<?php echo e($user->username); ?>

	</h3>
	<?php echo e(Form::open(array('url' => 'user/saveprofile','class' => 'form-horizontal'))); ?>

	<?php echo e(Form::hidden('user_id',$user->id)); ?>

	<table class="table table-striped table-hover table-bordered table-responsive">
		<tbody>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Username')); ?></th>
				<td class="col-md-6"><?php echo e($user->username); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Email')); ?></th>
				<td class="col-md-6"><?php echo e($user->email); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Phone')); ?></th>
				<td class="col-md-6"><?php echo e($user->phone); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Country')); ?></th>
				<td class="col-md-6"><?php echo e($user->country->{'title'.LANG}); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Province')); ?></th>
				<td class="col-md-6"><?php echo e($user->province->{'title'.LANG}); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Area')); ?></th>
				<td class="col-md-6"><?php echo e($user->area->{'title'.LANG}); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Address')); ?></th>
				<td class="col-md-6"><?php echo e($user->address); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Sex')); ?></th>
				<td class="col-md-6"><?php echo e($user->sex); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Salt')); ?></th>
				<td class="col-md-6"><?php echo e(Form::select('salt', $salts ,$user->salt, ['class' => 'col-sm-3 form-control'])); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Height')); ?></th>
				<td class="col-md-6"><?php echo e(Form::text('height', $user->height, ['class' => 'col-sm-3 form-control'])); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Weight')); ?></th>
				<td class="col-md-6"><?php echo e(Form::text('weight', $user->weight, ['class' => 'col-sm-3 form-control'])); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.BMI')); ?></th>
				<td class="col-md-6"><?php echo e($user->bmi); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Package')); ?></th>
				<td class="col-md-6"><?php echo e($user->package->{'title'.LANG}); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Membership Start')); ?></th>
				<td class="col-md-6"><?php echo e($user->membership_start); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Membership End')); ?></th>
				<td class="col-md-6"><?php echo e($user->membership_end); ?></td>
			</tr>
			<tr>
				<th class="col-md-6"><?php echo e(trans('main.Created Date')); ?></th>
				<td class="col-md-6"><?php echo e($user->created_at); ?></td>
			</tr>
		</tbody>
	</table>
	<div class="text-center">
		<?php echo e(Form::submit(trans('main.Change'), array('class' => 'btn btn-primary btn-inversed'))); ?>

	</div>
	<?php echo e(Form::close()); ?>


	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/profile.blade.php ENDPATH**/ ?>
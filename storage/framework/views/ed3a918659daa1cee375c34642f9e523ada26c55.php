

<?php $__env->startSection('contents'); ?>
	<!-- BEGIN FORGOT PASSWORD FORM -->
	<?php echo e(Form::open(array('url' => 'user/reset' ,'class' => 'col-md-6 col-md-push-3 form-vertical login-form'))); ?>
		<h2 class="form-title"><?php echo e(trans('main.Lost Password')); ?></h2>
		<div class="clearfix"></div>
		<table class="table table-striped table-hover">
			<tr>
				<td>
					<?php echo e(trans('main.Email')); ?>
				</td>
				<td class="text-center">
					<?php echo e(Form::email('email')); ?>
				</td>
		 	</tr>
			<tr>
				<td><?php echo e(trans('main.New Password')); ?></td>
				<td class="text-center"><?php echo e(Form::password('password')); ?></td>
			</tr>
			<tr>
				<td><?php echo e(trans('main.New Password Repeat')); ?></td>
				<td class="text-center"><?php echo e(Form::password('password_confirmation')); ?></td>
			</tr>
			<tr>
				<tr>
				<td colspan="3" class="text-center">
					<p><?php echo e(trans('main.Type your email here to restore your password')); ?></p>
			 	</td>
		 	</tr>
			<tr>
				<td colspan="3" class="text-center">
					<?php echo e(Form::hidden('token', $token)); ?> 
					<?php echo e(Form::submit(trans('Reset'), array('class' => 'btn btn-primary tbutton'))); ?> 
			 	</td>
		 	</tr>
		</table>
	<?php echo e(Form::close()); ?>
	<!-- END FORGOT PASSWORD FORM -->
	
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/reset.blade.php ENDPATH**/ ?>


<?php $__env->startSection('custom_foot'); ?>
<script>
	$("#area_id").chained("#province_id");
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
	
	

	<!-- BEGIN LOGIN FORM -->
	<div class="row">
		<div class="col-md-6">

			<?php echo e(Form::open(array('url' => 'user/cp','class' => 'form-horizontal login-form'))); ?>


			<h3><?php echo e(trans('main.User Control Panel')); ?></h3>

			<div class="clearfix"></div>

			<div class="box2">
				<?php if($user->role_id == 1): ?>
					<div class="form-group">

					<?php echo e(Form::label('username', trans('main.Username') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<div class="form-control">
						<?php echo e($user->username); ?>

						</div>
						

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('email', trans('main.Email') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">
						<div class="form-control">

						<?php echo e($user->email); ?>

						</div>

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('phone', trans('main.Phone') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">
						<div class="form-control">

						<?php echo e($user->phone); ?>

						</div>

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('country_id', trans('main.Country') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">
						<div class="form-control">

						<?php echo e($user->country->{'title'.LANG}); ?>

						</div>

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('province_id', trans('main.Province') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">
						<div class="form-control">

						<?php echo e($user->province->{'title'.LANG}); ?>

						</div>

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('area_id', trans('main.Area') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">
						<div class="form-control">

						<?php echo e($user->area->{'title'.LANG}); ?>

						</div>

					</div>

				</div>

				<div class="form-group">
					<?php echo e(Form::label('address', trans('main.Address') , array('class' => 'col-sm-3 control-label'))); ?>

					<div class="col-sm-9">
						<div class="form-control">
						<?php echo e($user->address); ?>

					</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo e(Form::label('weight', trans('main.Weight') , array('class' => 'col-sm-3 control-label'))); ?>

					<div class="col-sm-9">
						<div class="form-control">
							<?php echo e($user->weight); ?>

						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo e(Form::label('height', trans('main.Height') , array('class' => 'col-sm-3 control-label'))); ?>

					<div class="col-sm-9">
						<div class="form-control">
							<?php echo e($user->height); ?>

						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo e(Form::label('bmi', trans('main.BMI') , array('class' => 'col-sm-3 control-label'))); ?>

					<div class="col-sm-9">
						<div class="form-control">
							<?php echo e($user->bmi); ?>

						</div>
					</div>
				</div>

				<div class="form-group">
					<?php echo e(Form::label('salt', trans('main.Salt') , array('class' => 'col-sm-3 control-label'))); ?>

					<div class="col-sm-9">
						<div class="form-control">
						<?php echo e($user->salt); ?>

						</div>
					</div>
				</div>
				<?php else: ?>
				<div class="form-group">

					<?php echo e(Form::label('username', trans('main.Username') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php if($user->username): ?>
						<div class="form-control">
						<?php echo e($user->username); ?>

						</div>
						<?php else: ?>
						<?php echo e(Form::text('username', $user->username, array('class' => 'form-control'))); ?>

						<?php endif; ?>

					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('email', trans('main.Email') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php echo e(Form::email('email', $user->email, array('class' => 'form-control'))); ?>


					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('phone', trans('main.Phone') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php echo e(Form::text('phone', $user->phone, array('class' => 'form-control'))); ?>


					</div>

				</div>


				<?php endif; ?>
<?php /* 
				<div class="form-group">

					{{ Form::label('address', trans('main.Address') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::textarea('address', $user->address, array('class' => 'form-control')) }}

					</div>

				</div>
*/ ?>
				<div class="form-group text-center">

					<?php echo e(Form::submit(trans('main.Edit'), array('class' => 'btn btn-primary btn-inversed'))); ?>


				</div>
			</div>

			<?php echo e(Form::close()); ?>


		</div>

		<div class="col-md-6">

			<?php echo e(Form::open(array('url' => 'user/password','class' => 'form-horizontal login-form'))); ?>


			<h3><?php echo e(trans('main.Password Change')); ?></h3>

			<div class="clearfix"></div>

			<div class="box2">
				<div class="form-group">

					<?php echo e(Form::label('password', trans('main.Old Password') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php echo e(Form::password('password', array('class' => 'form-control'))); ?>


					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('newpassword', trans('main.New Password') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php echo e(Form::password('newpassword', array('class' => 'form-control'))); ?>


					</div>

				</div>

				<div class="form-group">

					<?php echo e(Form::label('newpasswordconf', trans('main.New Password Repeat') , array('class' => 'col-sm-3 control-label'))); ?>


					<div class="col-sm-9">

						<?php echo e(Form::password('newpasswordconf', array('class' => 'form-control'))); ?>


					</div>

				</div>

				<div class="form-group text-center">

					<?php echo e(Form::submit(trans('main.Change'), array('class' => 'btn btn-primary btn-inversed'))); ?>


				</div>
			</div>
			<?php echo e(Form::close()); ?>


		</div>
	</div>

		

	

	<!-- END LOGIN FORM -->        

	

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/usercp.blade.php ENDPATH**/ ?>
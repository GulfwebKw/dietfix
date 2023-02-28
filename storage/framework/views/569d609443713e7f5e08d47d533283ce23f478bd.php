
<?php $__env->startSection('custom_foot'); ?>
<script>

	<?php if(Input::old('country_id')): ?>
		$("#country_id").val(<?php echo e(Input::old('country_id')); ?>);
	<?php endif; ?>
	<?php if(Input::old('province_id')): ?>
		$("#province_id").val(<?php echo e(Input::old('province_id')); ?>);
	<?php endif; ?>
	<?php if(Input::old('area_id')): ?>
		$("#area_id").val(<?php echo e(Input::old('area_id')); ?>);
	<?php endif; ?>
	<?php if(Input::old('role_id')): ?>
		$("#role_id").val(<?php echo e(Input::old('role_id')); ?>);
	<?php endif; ?>
	$("#area_id").chained("#province_id");
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
	<!-- BEGIN LOGIN FORM -->
	

		<div class="clearfix"></div>
		<div class="col-md-6 col-md-push-3 f_blog">
			<h2 class="form-title"><?php echo e(trans('main.User Sign Up')); ?></h2>
			<?php echo e(Form::open(array('url' => url('user/register'), 'class' => 'signup form-horizontal login-form', 'id' => 'signupForm'))); ?>


			<fieldset>
			  <div class="form-group">
			  <div class="input-group foruser">
			  		<span class="input-group-addon"><i class="fa fa-user"></i></span>
			      <?php echo e(Form::text('username', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Username')))); ?>


			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group forpass">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>

			      <?php echo e(Form::password('password', array('class' => 'form-control middle', 'placeholder' => trans('main.Password')))); ?>


			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group forpass">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
			      <?php echo e(Form::password('password_confirmation', array('class' => 'form-control middle', 'placeholder' => trans('main.Repeat Password')))); ?>


			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group foremail">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>

			      <?php echo e(Form::email('email', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Email')))); ?>


			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-phone"></i></span>

			      <?php echo e(Form::text('phone', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Phone')))); ?>


			  </div>
			  </div>

			  <div class="form-group">
				  <div class="input-group">
					<span class="input-group-addon"><i class="fa fa-globe"></i></span>
						<select name="country_id" id="country_id" class="form-control chosen">
							<option value=""><?php echo e(trans('main.Country')); ?></option>
							<?php $__currentLoopData = App\Models\Country::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<option value="<?php echo e($country->id); ?>"><?php echo e($country->{'title'.LANG}); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>


				  </div>
			  </div>


			  <div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-flag-o"></i></span>
						<select name="province_id" id="province_id" class="form-control chosen">
							<option value=""><?php echo e(trans('main.Province')); ?></option>
							<?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<option value="<?php echo e($province->id); ?>"><?php echo e($province->{'title'.LANG}); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
					</div>
				</div>



			  <div class="form-group">
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="fa fa-square"></i></span>
					<select name="area_id" id="area_id" class="form-control chosen">
						<option value=""><?php echo e(trans('main.Area')); ?></option>
						<?php $__currentLoopData = $areas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<option value="<?php echo e($area->id); ?>" class="<?php echo e($area->province_id); ?>"><?php echo e($area->{'title'.LANG}); ?></option>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
			  </div>
			  </div>
			  <div class="form-group">
			    <div class="input-group">
			  	<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
					<select name="role_id" id="role_id" class="form-control chosen">
						<option value="1"><?php echo e(trans('main.Type')); ?></option>
						<?php $__currentLoopData = App\Models\Role::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<?php if($role->id != 10): ?>
							<option value="<?php echo e($role->id); ?>"><?php echo e($role->{'roleName'.LANG}); ?></option>
							<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</select>
			  </div>
			</div>



<?php /*
			  <div class="form-group">

			      {{ Form::text('address', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Address'))) }}

			  </div>
			  */ ?>

			<div class="clearfix"></div>
			<div class="form-group text-center">
				<?php echo e(Form::submit(trans('main.Sign Up'), array('class' => 'btn btn-lg btn-primary btn-block tbutton'))); ?>

				<?php /*
				<div class="text-center" style="margin:10px;">{{ trans('main.OR') }}</div>
				<a href="{{ url('user/facebooklogin') }}" class="btn btn-default btn-block"><i class="fa fa-facebook"></i> {{ trans('main.Register With Facebook') }}</a>
				*/ ?>
				<br>
				<p class="text-center"><a href="<?php echo e(url('user/login')); ?>"><?php echo e(trans('main.Already have an account?')); ?></a></p>
			</div>
			</fieldset>
			<?php echo e(Form::close()); ?>

		</div>
		
	
	<!-- END LOGIN FORM -->        
	
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/register.blade.php ENDPATH**/ ?>
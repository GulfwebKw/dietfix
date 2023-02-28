<?php $__env->startSection('contents'); ?>

	<!-- BEGIN LOGIN FORM -->

		<div class="clearfix"></div>

		<div class="col-md-6 col-md-push-3 f_blog">

			<div class="section comment-wrap" id="section1">
			<div class="login-form">

				<h2 class="form-title"><?php echo e(trans('main.Sign In')); ?></h2>

				<?php echo e(Form::open(array('url' => url('user/login'), 'class' => 'form-signin login-form'))); ?>


				<fieldset>
<?php if(!empty(Session::get('username'))){$unm=Session::get('username');}else{$unm=null;}?>
				<div class="form-group">

					<div class="input-group">

						<span class="input-group-addon"><i class="fa fa-user"></i></span>

					    <?php echo e(Form::text('username', $unm, array('class' => 'form-control', 'placeholder' => trans('main.Username')))); ?>


					</div>

				</div>

				<div class="form-group">

					<div class="input-group">

						<span class="input-group-addon"><i class="fa fa-key"></i></span>

					    <?php echo e(Form::password('password', array('class' => 'form-control', 'placeholder' => trans('main.Password')))); ?>


					</div>

				</div>

				<a class="pull-left flip" href="<?php echo e(url('user/forget')); ?>"><?php echo e(trans('main.Forgot password?')); ?></a>

				<div class="checkbox pull-right flip">

			    	<label><input name="remember" type="checkbox" value="Remember Me"> <?php echo e(trans('main.Remember Me')); ?></label>

			    </div>

			    <?php if(isset($url)): ?>

			    	<?php echo e(Form::hidden('uri', $url)); ?>


			    <?php endif; ?>

			  	<?php echo e(Form::submit(trans('main.Login'), array('class' => 'btn btn-lg btn-primary btn-block tbutton'))); ?>


				<?php /*

				<div class="text-center" style="margin:10px;">{{ trans('main.OR') }}</div>

			  	<a href="{{ url('user/facebooklogin') }}" class="btn btn-default btn-block"><i class="fa fa-facebook"></i> {{ trans('main.Login With Facebook') }}</a>

				*/ ?>

			  	<br>

			  	

				</fieldset>

				<?php echo e(Form::close()); ?>


			</div>
			</div>


		</div>



	<!-- END LOGIN FORM -->



<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/user/login.blade.php ENDPATH**/ ?>
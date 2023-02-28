<?php $__env->startSection('custom_head_include'); ?>



	<link href="<?php echo e(url('cpassets/css/pages/login-soft-rtl.css')); ?>" rel="stylesheet" type="text/css"/>



<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>



	<!-- BEGIN LOGIN FORM -->


	<form class="form-vertical login-form" action="" method="post">


		<?php echo csrf_field(); ?>



		<h3 class="form-title"><?php echo app('translator')->getFromJson('main.Sign In'); ?></h3>



		<div class="alert alert-error hide">



			<button class="close" data-dismiss="alert"></button>



			<span><?php echo app('translator')->getFromJson('main.Type your user name and password'); ?></span>



		</div>



		<div class="control-group">



			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->



			<label class="control-label visible-ie8 visible-ie9"><?php echo app('translator')->getFromJson('main.Username'); ?></label>



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-user"></i>



					<input class="m-wrap placeholder-no-fix" type="text" placeholder="<?php echo app('translator')->getFromJson('main.Username'); ?>" autocomplete="off" name="username"/>



				</div>



			</div>



		</div>



		<div class="control-group">



			<label class="control-label visible-ie8 visible-ie9"><?php echo app('translator')->getFromJson('main.Password'); ?></label>



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-lock"></i>



					<input class="m-wrap placeholder-no-fix" type="password" placeholder="<?php echo app('translator')->getFromJson('main.Password'); ?>" autocomplete="off" name="password"/>



				</div>



			</div>



		</div>



		<div class="form-actions">



			<label class="checkbox">



			<input type="checkbox" name="remember" value="1"/> <?php echo app('translator')->getFromJson('main.Remember me'); ?>



			</label>



			<button type="submit" class="btn blue pull-left">



			<?php echo app('translator')->getFromJson('main.Login'); ?> <i class="m-icon-swapright m-icon-white"></i>



			</button>            



		</div>



		<div class="forget-password">



			<h4><?php echo app('translator')->getFromJson('main.Lost Password?'); ?></h4>



			<p>



				<?php echo app('translator')->getFromJson('main.Don\'t worry click <a href="javascript:;"  id="forget-password">here</a> to restore password'); ?>



			</p>



		</div>



	</form>



	<!-- END LOGIN FORM -->        



	<!-- BEGIN FORGOT PASSWORD FORM -->



	<?php echo e(Form::open( array('url' => url('user/remind'), 'class' => 'form-vertical forget-form') )); ?>




		<h3><?php echo app('translator')->getFromJson('main.Lost Password?'); ?></h3>



		<p><?php echo app('translator')->getFromJson('main.Type your email here to restore your password'); ?></p>



		<div class="control-group">



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-envelope"></i>



					<?php echo e(Form::email('email', null, array('class' => 'm-wrap placeholder-no-fix','placeholder' => trans('main.Email')))); ?>




				</div>



			</div>



		</div>



		<div class="form-actions">



			<button type="button" id="back-btn" class="btn">



			<i class="m-icon-swapleft"></i> <?php echo app('translator')->getFromJson('main.Back'); ?>



			</button>



			<button type="submit" class="btn blue pull-left">



			<?php echo app('translator')->getFromJson('main.Send'); ?> <i class="m-icon-swapright m-icon-white"></i>



			</button>            



		</div>



	<?php echo e(Form::close()); ?>




	<!-- END FORGOT PASSWORD FORM -->



<?php $__env->stopSection(); ?>



<?php $__env->startSection('copyright'); ?>



<div class="copyright">



	



</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/login.blade.php ENDPATH**/ ?>
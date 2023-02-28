<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />

	

	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="Space Zone | sz4h.com" name="author" />

	<link href="<?php echo e(url('cpassets/plugins/bootstrap/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/plugins/bootstrap/css/bootstrap-responsive-rtl.min.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/plugins/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/css/style-metro-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/css/style-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/plugins/gritter/css/jquery.gritter.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/css/style-responsive-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

	<link href="<?php echo e(url('cpassets/css/themes/default-rtl.css')); ?>" rel="stylesheet" type="text/css" id="style_color"/>

	<link href="<?php echo e(url('cpassets/plugins/uniform/css/uniform.default.css')); ?>" rel="stylesheet" type="text/css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo e(url('cpassets/plugins/select2/select2_metro_rtl.css')); ?>" />

	<!-- END GLOBAL MANDATORY STYLES -->

	<!-- BEGIN PAGE LEVEL STYLES -->



	<script type="text/javascript">

		var SITEPATH =  '<?php echo e(env('APP_URL')); ?>';

		var APPPATH = '<?php echo e(env('APP_URL')); ?>'+'/app/';

		var APPROOT = '<?php echo e(env('APP_URL')); ?>';

		var ADMINPATH = '<?php echo e(env('APP_URL')); ?>';

		var ADMINROOT = '<?php echo e(env('APP_URL')); ?>'+'/admin';

	</script>

	<?php echo $__env->yieldContent('custom_head_include'); ?>

	<script type="text/javascript" src="<?php echo e(url('cpassets/plugins/jquery-1.10.1.min.js')); ?>"></script>



</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="login">

	<?php echo $__env->make('admin.partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

	<div class="logo">
<?php
if(@$_GET['cid']):
$clinicUsers = DB::table('clinics')->where('id',$_GET['cid'])->first();
if(@$clinicUsers->logo):
?>
<img alt="" src="<?php echo e(url('media/clinics/'.$clinicUsers->logo)); ?>" width="100" />
<?php else: ?>
<h1><? echo $clinicUsers->titleEn;?></h1>
<?php endif; ?>
<?php else: ?>
<?php echo app('translator')->getFromJson('main.Administrator Area'); ?>
<?php endif; ?>
</div>

	<!-- END LOGO -->

	<!-- BEGIN LOGIN -->

	<div class="content">



		<?php echo $__env->yieldContent('content'); ?>

		

	</div>

	<!-- END LOGIN -->

	<!-- BEGIN COPYRIGHT -->

	<?php echo $__env->yieldContent('copyright'); ?>

	<!-- END COPYRIGHT -->

	<script src="<?php echo e(url('cpassets/plugins/jquery-migrate-1.2.1.min.js')); ?>" type="text/javascript"></script>

	<!-- IMPORTANT! Load jquery-ui-1.10.1.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->

	<script src="<?php echo e(url('cpassets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js')); ?>" type="text/javascript"></script>      

	<script src="<?php echo e(url('cpassets/plugins/bootstrap/js/bootstrap-rtl.min.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js')); ?>" type="text/javascript" ></script>

	<!--[if lt IE 9]>

	<script src="<?php echo e(url('cpassets/plugins/excanvas.min.js')); ?>"></script>

	<script src="<?php echo e(url('cpassets/plugins/respond.min.js')); ?>"></script>  

	<![endif]-->   

	<script src="<?php echo e(url('cpassets/plugins/gritter/js/jquery.gritter.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/plugins/jquery.blockui.min.js')); ?>" type="text/javascript"></script>  

	<script src="<?php echo e(url('cpassets/plugins/jquery.cookie.min.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/plugins/uniform/jquery.uniform.min.js')); ?>" type="text/javascript" ></script>

	<!-- END CORE PLUGINS -->

	<!-- BEGIN PAGE LEVEL PLUGINS -->

	<script src="<?php echo e(url('cpassets/plugins/jquery-validation/dist/jquery.validate.min.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/plugins/backstretch/jquery.backstretch.min.js')); ?>" type="text/javascript"></script>

	<script type="text/javascript" src="<?php echo e(url('cpassets/plugins/select2/select2.min.js')); ?>"></script>

	<!-- END PAGE LEVEL PLUGINS -->

	<!-- BEGIN PAGE LEVEL SCRIPTS -->

	<script src="<?php echo e(url('cpassets/scripts/app.js')); ?>" type="text/javascript"></script>

	<script src="<?php echo e(url('cpassets/scripts/login-soft.js')); ?>" type="text/javascript"></script>      

	<!-- END PAGE LEVEL SCRIPTS --> 

	<script>

		jQuery(document).ready(function() {     

		  App.init();

		  Login.init();

		});

	</script>

	<?php echo $__env->yieldContent('custom_foot'); ?>

</body>

<!-- END BODY -->

</html><?php /**PATH /home/dietfix/private_fix/resources/views/admin/layouts/login.blade.php ENDPATH**/ ?>
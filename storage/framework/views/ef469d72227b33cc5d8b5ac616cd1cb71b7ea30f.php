<!-- BEGIN GLOBAL MANDATORY STYLES -->

<!-- BEGIN OLD SZ4H -->

   	<!-- END OLD SZ4H -->

<link href="<?php echo e(url('cpassets/plugins/bootstrap-3/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css"/>



 <?php if($_adminLang == 'arabic'): ?>

<link href="<?php echo e(url('cpassets/plugins/bootstrap/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/plugins/bootstrap/css/bootstrap-responsive-rtl.min.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/plugins/bootstrap-3/css/bootstrap-rtl.min.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/css/style-metro-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/css/style-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/css/style-responsive-rtl.css')); ?>" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="<?php echo e(url('cpassets/plugins/jquery-multi-select/css/multi-select-metro-rtl.css')); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo e(url('cpassets/plugins/chosen-bootstrap/chosen/chosen-rtl.css')); ?>" />

<!-- COLOR START -->

<link href="<?php echo e(url('cpassets/css/themes/light-rtl.css')); ?>" rel="stylesheet" type="text/css" id="style_color"/>

<!-- COLOR END -->

<?php else: ?>

<link href="<?php echo e(url('cpassets/css/style-metro.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/css/style.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/css/style-responsive.css')); ?>" rel="stylesheet" type="text/css"/>

<link rel="stylesheet" type="text/css" href="<?php echo e(url('cpassets/plugins/jquery-multi-select/css/multi-select-metro.css')); ?>" />

<link rel="stylesheet" type="text/css" href="<?php echo e(url('cpassets/plugins/chosen-bootstrap/chosen/chosen.css')); ?>" />

<!-- COLOR START -->

<link href="<?php echo e(url('cpassets/css/themes/light.css')); ?>" rel="stylesheet" type="text/css" id="style_color"/>

<!-- COLOR END -->

<?php endif; ?>


<link href="<?php echo e(url('cpassets/plugins/uniform/css/uniform.default.css')); ?>" rel="stylesheet" type="text/css"/>

<!-- END GLOBAL MANDATORY STYLES -->

<!-- BEGIN PAGE LEVEL PLUGIN STYLES -->

<link href="<?php echo e(url('cpassets/plugins/gritter/css/jquery.gritter.css')); ?>" rel="stylesheet" type="text/css"/>

<?php if($_adminLang == 'arabic'): ?>

<link href="<?php echo e(url('cpassets/plugins/bootstrap-daterangepicker/daterangepicker-rtl.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(url('cpassets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro-rtl.css')); ?>" rel="stylesheet" type="text/css" />

<?php else: ?>

<link href="<?php echo e(url('cpassets/plugins/bootstrap-daterangepicker/daterangepicker.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(url('cpassets/css/pages/login-soft-rtl.css')); ?>" rel="stylesheet" type="text/css" />

<link href="<?php echo e(url('cpassets/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-metro.css')); ?>" rel="stylesheet" type="text/css" />

<?php endif; ?>

<link href="<?php echo e(url('cpassets/plugins/fullcalendar/fullcalendar/fullcalendar.css')); ?>" rel="stylesheet" type="text/css"/>

<link href="<?php echo e(url('cpassets/plugins/jqvmap/jqvmap/jqvmap.css')); ?>" rel="stylesheet" type="text/css" media="screen"/>

<link href="<?php echo e(url('cpassets/plugins/jquery-easy-pie-chart/jquery.easy-pie-chart.css')); ?>" rel="stylesheet" type="text/css" media="screen"/>

<link href="<?php echo e(url('cpassets/plugins/fancybox/source/jquery.fancybox.css')); ?>" rel="stylesheet" type="text/css" media="screen"/>

<link href="//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo e(url('cpassets/css/print.css')); ?>" rel="stylesheet" type="text/css" media="print"/>











<link rel="stylesheet" href="/fontawesome-stars.css">
<link rel="stylesheet" href="/css-stars.css">
<link rel="stylesheet" href="/bootstrap-stars.css">
<link rel="stylesheet" href="/css/evo.css">



<!-- END PAGE LEVEL PLUGIN STYLES -->

<!-- BEGIN PAGE LEVEL STYLES -->

<link href="<?php echo e(url('cpassets/css/custom.css')); ?>" rel="stylesheet" type="text/css" media="screen"/>
<link href="" rel="stylesheet" type="text/css" media="screen"/>

<style>

</style>
<!-- END PAGE LEVEL STYLES -->

<link rel="shortcut icon" href="<?php echo e(url('favicon.ico')); ?>" />

<!-- Main Javascript Load -->

<script src="<?php echo e(url('cpassets/plugins/jquery-1.10.1.min.js')); ?>" type="text/javascript"></script>

<script src="<?php echo e(url('cpassets/plugins/jquery-migrate-1.2.1.js')); ?>" type="text/javascript"></script>

<script type="text/javascript">

    var SITEPATH = '<?php echo e(env('APP_URL')); ?>';
    var APPPATH = '<?php echo e(env('APP_URL')); ?>'+'/app/';
    var APPROOT ='<?php echo e(env('APP_URL')); ?>';
	var ADMINPATH ='<?php echo e(env('APP_URL')); ?>';
	var ADMINROOT ='<?php echo e(env('APP_URL')); ?>';

</script>



<!-- Main Javascript Load -->
<?php /**PATH /home/dietfix/private_fix/resources/views/admin/partials/headinclude.blade.php ENDPATH**/ ?>
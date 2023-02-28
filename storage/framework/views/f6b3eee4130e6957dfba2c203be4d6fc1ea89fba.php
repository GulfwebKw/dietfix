

<?php echo e(HTML::style('design/css/bootstrap.css')); ?>


<?php if(session()->get('lang') == 'ar'): ?>
<?php echo e(HTML::style('//cdn.rawgit.com/morteza/bootstrap-rtl/master/dist/cdnjs/3.3.1/css/bootstrap-rtl.min.css')); ?>


<?php endif; ?>

<?php echo e(HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css')); ?>


<?php echo e(HTML::style('assets/css/y-style.css')); ?>


<?php echo e(HTML::style('style.css')); ?>


<style type="text/css" media="print">

    div.page

	{

	page-break-after: always;

	page-break-inside: avoid;

	}

	.page-break	{ display: block; page-break-before: always; page-break-after: always; }

	.labelbox {height:24.5mm;overflow: hidden;line-height: 1.1;}

</style>

<!--[if lt IE 9]>

	<?php echo e(HTML::script('//css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js')); ?>


    <?php echo e(HTML::script('//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js')); ?>


    <?php echo e(HTML::script('//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js')); ?>


<![endif]-->



<script>

	var APP_URL = '<?php echo e(url('')); ?>';

	var EMPTY_CART = '<?php echo e(trans('main.Your cart is empty')); ?>';

	var CURRENCY = '<?php echo e(CURRENCY); ?>';

	var LANG = '<?php echo e(LANG); ?>';

</script><?php /**PATH /home/dietfix/private_fix/resources/views/partials/headinclude.blade.php ENDPATH**/ ?>
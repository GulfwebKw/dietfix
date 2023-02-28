

<?php $__env->startSection('custom_foot'); ?>
	<?php echo e(HTML::script('cpassets/js/jquery.chained.min.js')); ?>

	<script>
		jQuery(document).ready(function($) {
      
			
		});
	</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php if(!empty(Session::get('success'))): ?>
<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <?php echo e(Session::get('success')); ?>

	</div>
<?php endif; ?>

<div class="row"><div class="col-sm-12"><h5><?php echo e($items->titleEn); ?></h5></div></div>
 <?php
 use Illuminate\Support\Facades\Cookie;
 $bgcolor='';
 ?>   
<?php if(!empty($categories) && count($categories)>0): ?>
<div class="row">
<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
if(!empty(Cookie::get('chosen_'.$items->id.'_'.$category->id))){
$bgcolor='background-color:#006600;color:#FFFFFF;';
}else{
$bgcolor='';
}
?>
<div class="col-sm-3" style="border:1px #CCCCCC solid; margin-top:5px;padding:5px;<?php echo e($bgcolor); ?>"><b><?php echo e($category->titleEn); ?></b><a href="<?php echo e(url('admin/items/choose/category/'.$items->id.'/'.$category->id)); ?>" class="btn btn-success pull-right"><i class="fa fa-plus"></i></a></div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/chooseItemcategory.blade.php ENDPATH**/ ?>

<?php $__env->startSection('custom_head_include'); ?>
<?php echo e(HTML::style('assets/datepicker/css/datepicker.css')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('custom_foot'); ?>
<?php echo e(HTML::script('http://momentjs.com/downloads/moment.js')); ?>

<?php echo e(HTML::script('assets/datepicker/js/bootstrap-datepicker.js')); ?>


<script>
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');

</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('contents'); ?>
	
	<?php if($orders->isEmpty()): ?>
		<div>
			<?php echo e(trans('main.No Results')); ?>

		</div>
	<?php else: ?>
		<div class="page">
			<?php $i = 0; ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="col-md-3 col-sm-3 col-xs-3 text-center labelbox">
				<?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?><br>
				<?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : ''); ?> <?php echo e(Input::get('date')); ?><br>
				<?php echo e($order->user->salt); ?><br>
				<?php if(!$order->addons->isEmpty()): ?>
					<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php echo e($addon->{'title'.LANG}); ?>,
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</div>
			<?php $i++ ?>
			<?php if($i%4 == 0): ?>
				<div class="clearfix"></div>
			<?php endif; ?>
			<?php if($i%44 == 0): ?>
				</div><div class="page">
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/labeling.blade.php ENDPATH**/ ?>
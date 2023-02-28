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

	<?php echo e(Form::open(array('url' => url('kitchen/get-'.$type), 'method' => 'get', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form'))); ?>


		<?php echo e(Form::label('date', trans('main.Date'))); ?>

		<div class="control-group form-group">
			<div class="controls">
				<?php echo e(Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date'))); ?>

			</div>
		</div>


		<div class="control-group form-group">
			<?php echo e(Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit'))); ?>

		</div>

	<?php echo e(Form::close()); ?>


	<h2 class="text-center"><?php echo e(trans('main.'.ucfirst($type))); ?></h2>
	<h3 class="text-center"><?php echo e(Input::get('date')); ?></h3>
	<div class="table-responsive">
		<table class="table table-hover">
			<tbody>
				<?php if(count($orders) <= 0): ?>
					<tr>
						<td><?php echo e(trans('main.No Results')); ?></td>
					</tr>
				<?php else: ?>
					<?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<tr style="display: block;">
						<th colspan="5" class="text-center"><h3><?php echo e($meal['meal']); ?></h3></th>
					</tr>
					<tr>
						<th><?php echo e(trans('main.Meal')); ?></th>
						<th><?php echo e(trans('main.Qty')); ?></th>
						<th><?php echo e(trans('main.Portion')); ?></th>
						<th><?php echo e(trans('main.Notes')); ?></th>
						<th><?php echo e(trans('main.Salt')); ?></th>
					</tr>
						<?php $__currentLoopData = $meal['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($order['title']); ?></td>
							<td><?php echo e($order['qty']); ?></td>
							<td><?php echo e(($order['portion']) ? $order['portion'] : 1); ?></td>
							<td><?php echo e($order['addons']); ?></td>
							<td><?php echo e($order['salt']); ?></td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</tbody>
				<?php endif; ?>
		</table>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/preparation.blade.php ENDPATH**/ ?>
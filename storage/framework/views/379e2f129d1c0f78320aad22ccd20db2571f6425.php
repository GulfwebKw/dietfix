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


	<?php if($orders->isEmpty()): ?>
		<div>
			<?php echo e(trans('main.No Results')); ?>

		</div>
	<?php else: ?>
	<h2 class="text-center"><?php echo e(trans('main.'.ucfirst($type))); ?></h2>
	<h3 class="text-center"><?php echo e(Input::get('date')); ?></h3>
		<div class="table-responsive">
			<table class="table table-hover">
				<tbody>
						<tr>
							<th class="text-center"><?php echo e(trans('main.ID')); ?></th>
							<th class="text-center"><?php echo e(trans('main.Username')); ?></th>
							<th class="text-center"><?php echo e(trans('main.Phone')); ?></th>
							<th class="text-center"><?php echo e(trans('main.Address')); ?></th>
						</tr>
						<?php $__currentLoopData = $orders->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $province; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php $__currentLoopData = $area; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr style="font-size: large;">
							<td class="text-center">
								<?php echo e($user->id); ?>

							</td>
							<td class="text-center">
								<?php echo e($user->username); ?>

							</td>
							<td class="text-center">
								<?php echo e($user->mobile_number); ?>

							</td>
							<td class="text-center">
								<?php if($weekEndAddress): ?>
									<?php echo e(optional($user->countryWeekends)->{'title'.LANG}); ?><br>
									<?php echo e(optional($user->provinceWeekends)->{'title'.LANG}); ?><br>
									<?php echo e(optional($user->areaWeekends)->{'title'.LANG}); ?><br>,
									Block:<?php echo e($user->block_work); ?><br>
									Street:<?php echo e($user->street_work); ?><br>
									Avenue:<?php echo e($user->avenue_work); ?><br>
									HouseNumber:<?php echo e($user->house_number_work); ?><br>
									Floor:<?php echo e($user->floor_work); ?><br>
									Address:<?php echo e($user->address_work); ?>

									<?php else: ?>
									<?php echo e($user->country->{'title'.LANG}); ?><br>
									<?php echo e($user->province->{'title'.LANG}); ?><br>
									<?php echo e($user->area->{'title'.LANG}); ?><br>
									Block:<?php echo e($user->block); ?><br>
									Street:<?php echo e($user->street); ?><br>
									Avenue:<?php echo e($user->avenue); ?><br>
									HouseNumber:<?php echo e($user->house_number); ?><br>
									Floor:<?php echo e($user->floor); ?><br>
									Address:<?php echo e($user->address); ?>

								<?php endif; ?>

							</td>
						</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
			</table>
		</div>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/delivery.blade.php ENDPATH**/ ?>
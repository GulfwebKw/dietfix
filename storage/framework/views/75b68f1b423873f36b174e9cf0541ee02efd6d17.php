
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
		<?php $__currentLoopData = $orders->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <?php $userdoctor = DB::table('users')->where('id', $user['user']->doctor_id)->first();?>
		<div class="page">
		<div class="table-responsive">
			<table class="table table-hover">
				<tbody>
						<tr style="font-size: large; font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th colspan="1"><h2><?php echo e($user['user']->id); ?></h2></th>
							<th colspan="2"><h2><?php echo e(optional($user['user'])->username); ?></h2></th>
							<th colspan="1"><h2><?php echo e(optional($user['user'])->mobile_number); ?></h2></th>
						</tr>
						<?php if(isset($user['user']->note) &&  $user['user']->note!=""): ?>
							<tr  style="font-size: 12px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
								<th colspan="2">
									<p>
										<?php echo e($user['user']->note); ?>

									</p>
								</th>
							</tr>
					     <?php endif; ?>

						<tr style="font-size: 20px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th colspan="2">
								<p>
									<?php if($weekEndAddress): ?>
										<?php echo e(optional($user['user']->countryWeekends)->{'title'.LANG}); ?> ,  <?php echo e(optional($user['user']->provinceWeekends)->{'title'.LANG}); ?> , <?php echo e(optional($user['user']->areaWeekends)->{'title'.LANG}); ?> , Block:<?php echo e($user['user']->block_work); ?> , Street:<?php echo e($user['user']->street_work); ?> , Avenue: <?php echo e($user['user']->avenue_work); ?> , HouseNumber:<?php echo e($user['user']->house_number_work); ?> ,  Floor:<?php echo e($user['user']->floor_work); ?><br>
										Address:<?php echo e($user['user']->address_work); ?>

										<?php else: ?>
										<?php echo e($user['user']->country->{'title'.LANG}); ?> ,  <?php echo e($user['user']->province->{'title'.LANG}); ?> , <?php echo e($user['user']->area->{'title'.LANG}); ?> , Block:<?php echo e($user['user']->block); ?> , Street:<?php echo e($user['user']->street); ?> , Avenue:<?php echo e($user['user']->avenue); ?> , HouseNumber:<?php echo e($user['user']->house_number); ?> ,  Floor:<?php echo e($user['user']->floor); ?><br>
										Address:<?php echo e($user['user']->address); ?>





									<?php endif; ?>




								</p>
							</th>
							<th colspan="2">
								<h2 class="text-center"><?php echo e(Input::get('date')); ?></h2>Dietitian: <?php echo optional($userdoctor)->username;?></th>
						</tr>
						<tr style="font-size: large;font-family:Tahoma,Arial, Helvetica, sans-serif;">
							<th><?php echo e(trans('main.Meal')); ?></th>
							<th><?php echo e(trans('main.Portion')); ?></th>
							<th><?php echo e(trans('main.Notes')); ?></th>
							<th><?php echo e(trans('main.Salt')); ?></th>
						</tr>
							<?php $__currentLoopData = $user['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr style="font-size:  16px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
								<td><?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?></td>
								<td><?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : 1); ?></td>
								<td>
									<?php if(!$order->addons->isEmpty()): ?>
										<?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<?php echo e($addon->{'title'.LANG}); ?>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									<?php endif; ?>
								</td>
								<td><?php echo e($order->user->salt); ?></td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					</tbody>
					
			</table>
		</div>
		</div>
		<div class="page-break"></div>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
	<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/packaging.blade.php ENDPATH**/ ?>
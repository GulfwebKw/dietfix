


<?php $__env->startSection('contents'); ?>
	<h3><?php echo e(trans('main.My Appointments')); ?></h3>
	
	<?php echo e(Form::open(['url' => 'appointments/manage','method' => 'get'])); ?>

	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th><?php echo e(trans('main.ID')); ?></th>
					<th><?php echo e(trans('main.Client')); ?></th>
					<th><?php echo e(trans('main.Date')); ?></th>
					<th><?php echo e(trans('main.Time')); ?></th>
					<th><?php echo e(trans('main.Notes')); ?></th>
					<th><?php echo e(trans('main.Attendance')); ?></th>
					<th></th>
				</tr>
				<tr>
					<th><?php echo e(Form::text('id',Input::get('id'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('client_id',Input::get('client_id'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('date',Input::get('date'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('hour',Input::get('hour'),['class' => 'form-control'])); ?></th>
					<th><?php echo e(Form::text('notes',Input::get('notes'),['class' => 'form-control'])); ?></th>
					<th></th>
					<th><?php echo e(Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-default yellow'))); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if($confirmed->isEmpty()): ?>
					<tr>
						<td colspan="7">
							<?php echo e(trans('main.No Results')); ?>

						</td>
					</tr>
				<?php else: ?>
					<?php $__currentLoopData = $confirmed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($appointment->id); ?></td>
							<td><?php echo e($appointment->user->username); ?></td>
							<td><?php echo e($appointment->date); ?></td>
							<td><?php echo e($appointment->hour); ?></td>
							<td><?php echo e($appointment->notes); ?></td>
							<td><?php echo e(($appointment->confirmed == 1) ? trans('main.Yes') : trans('main.No')); ?></td>
							<td><a href="<?php echo e(url('appointments/edit/'.$appointment->id.'/'.$appointment->user->id)); ?>" data-id="<?php echo e($appointment->id); ?>" class="btn btn-primary  btn-sm btn-block change"><i class="fa fa-edit"></i></a></td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<?php echo e(Form::close()); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/appointments/doctor.blade.php ENDPATH**/ ?>
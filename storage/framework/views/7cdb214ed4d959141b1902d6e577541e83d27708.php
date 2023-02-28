


<?php $__env->startSection('contents'); ?>
	<?php if(Auth::user()->role_id != 1): ?>
	<a href="<?php echo e(url('appointments/add/'.$user->id)); ?>" class="btn btn-success pull-right flip addnew"><i class="fa fa-plus"></i> <?php echo e(trans('main.Add')); ?></a>
	<?php endif; ?>

	
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th><?php echo e(trans('main.ID')); ?></th>
					<th><?php echo e(trans('main.Doctor')); ?></th>
					<th><?php echo e(trans('main.Date')); ?></th>
					<th><?php echo e(trans('main.Time')); ?></th>
					<th><?php echo e(trans('main.Height')); ?></th>
					<th><?php echo e(trans('main.Weight')); ?></th>
					<th><?php echo e(trans('main.BMI')); ?></th>
					<th><?php echo e(trans('main.Files')); ?></th>
					<?php if(Auth::user()->role_id != 1): ?>
					<th><?php echo e(trans('main.Notes')); ?></th>
					<?php endif; ?>
					<th><?php echo e(trans('main.Attendance')); ?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php if($appointments->isEmpty()): ?>
					<tr>
						<td colspan="11">
							<?php echo e(trans('main.No Results')); ?>

						</td>
					</tr>
				<?php else: ?>
					<?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td><?php echo e($appointment->id); ?></td>
							<td><?php echo e($appointment->doctor->username); ?></td>
							<td><?php echo e($appointment->date); ?></td>
							<td><?php echo e($appointment->hour); ?></td>
							<td><?php echo e($appointment->height); ?></td>
							<td><?php echo e($appointment->weight); ?></td>
							<td><?php echo e($appointment->bmi); ?></td>
							<td>
								<?php if(!$appointment->files->isEmpty()): ?>
									<?php $__currentLoopData = $appointment->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<a href="<?php echo e(url('media/files/'.$file->file)); ?>" target="_blank"><?php echo e(trans('main.Download')); ?></a><br>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php endif; ?>
							</td>
							<?php if(Auth::user()->role_id != 1): ?>
							<td><?php echo e($appointment->notes); ?></td>
							<?php endif; ?>
							<td><?php echo e(($appointment->confirmed == 1) ? trans('main.Yes') : trans('main.No')); ?></td>
							<td>
							<?php if(Auth::user()->role_id != 1 || (Auth::user()->role_id == 1 && !in_array($appointment->date, [date('Y-m-d'),date('Y-m-d',strtotime('+1 day'))]))): ?>
							<a href="<?php echo e(url('appointments/edit/'.$appointment->id.'/'.$user->id)); ?>" data-id="<?php echo e($appointment->id); ?>" class="btn btn-primary  btn-sm btn-block change"><i class="fa fa-edit"></i></a>
							<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	
	<div class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"></h4>
	      </div>
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary saveForm">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/appointments/index.blade.php ENDPATH**/ ?>




<?php $__env->startSection('forms2'); ?>

	


<?php if(isset($item->$_pk)): ?>
	<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-sitemap"></i><?php echo e(trans('main.Times')); ?>


			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<a href="<?php echo e(url(ADMIN_FOLDER.'/doctors_times/add/'.$item->id)); ?>" class="pull-right flip btn green popup"><?php echo e(trans('main.Add')); ?></a>
				<table class="table table-striped table-hover">
					<thead>
						<tr class="flip-content">
							<th width="50%"><?php echo e(trans('main.Day')); ?></th>
							<th width="25%"><?php echo e(trans('main.Start')); ?></th>
							<th width="25%"><?php echo e(trans('main.End')); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php if(!$item->times->isEmpty()): ?>
					<?php $__currentLoopData = $item->times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<tr>
							<td>
								<?php echo e($time->day->{'title'.LANG}); ?>


							</td>
							<td>
								<?php echo e($time->start); ?>

							</td>
							<td>
								<?php echo e($time->end); ?>

							</td>
							<td nowrap="nowrap">
								<a href="<?php echo e(url(ADMIN_FOLDER.'/doctors_times/edit/'.$time->id).'/'.$item->id); ?>" class="btn blue popup"><?php echo e(trans('main.Edit')); ?></a>
								<a href="<?php echo e(url(ADMIN_FOLDER.'/doctors_times/delete/'.$time->id.'/'.$item->id)); ?>" class="btn red"><?php echo e(trans('main.Delete')); ?></a>
							</td>
						</tr>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
					<?php else: ?>
						<tr>
							<td colspan="4"><?php echo e(trans('main.No Results')); ?></td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>


	<!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
	<div class="modal fade" id="ajax">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="closed btn btn-primary red pull-right flip"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
	      </div>
	      <div class="modal-body">
	      <iframe src="" style="zoom:0.60;margin:0 auto;" width="99.6%" height="550" frameborder="0" id="toShowIn"></iframe>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	
	<!-- /.modal -->
	<?php endif; ?>




<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>
##parent-placeholder-65f6cc516dc9059e8eafbaadacb996494c0a565c##
<script>
	jQuery(document).ready(function($) {  
		$('.popup').click(function(){
			$('#toShowIn').attr("src",'');
			var url = $(this).attr('href');
		    $('#ajax').on('show', function () {

		        $('#toShowIn').attr("src",url);
		      
			});
		    $('#ajax').modal({show:true});
		    return false;
		});
		$('button.closed').click(function () {
			$('#ajax').modal('hide');
			$('body').removeClass('modal-open');
		});
	});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/forms/doctors_form.blade.php ENDPATH**/ ?>


<?php $__env->startSection('contents'); ?>
	<div class="col-md-12">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

			<?php $i=0; ?>
			<?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $meals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo e($i); ?>" aria-expanded="true" aria-controls="collapse<?php echo e($i); ?>">
									<?php echo e($day); ?> - <?php echo e(date('l',strtotime($day))); ?>

								</a>
							</h4>
						</div>

						<div id="collapse<?php echo e($i); ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-body order-item">
								<?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<h3><?php echo e($meal); ?></h3>
									<img src="<?php echo e(!empty($item['order']['item']['photo'])?url(RESIZE_PATH.'?w=100&h=100&r=1&c=1&src=media/items/' . $item['order']['item']['photo']):url('/images/no-image.jpg')); ?>" class="pull-left flip" alt="<?php echo e($item['item']); ?>" style="max-width:100px;max-height:100px;">
									<a href="#" class="btn btn-primary pull-right flip edit-order" data-date="<?php echo e($day); ?>" data-user="<?php echo e($user->id); ?>" data-id="<?php echo e($item['order']['id']); ?>"><i class="fa fa-edit"></i></a>
									<?php if($item['order']['approved'] == 0): ?>
										<a href="#" class="btn btn-success pull-right flip approve-order" data-id="<?php echo e($item['order']['id']); ?>"><i class="fa fa-check"></i></a>
									<?php endif; ?>

									<h3><?php echo e($item['item']); ?><?php if($item['order']['portion']): ?>
											<sup><code><?php echo e($item['order']['portion']['title'.LANG]); ?></code></sup><?php endif; ?></h3>
									<p><?php echo e($item['order']['item']['details'.LANG]); ?></p>
									<div class="clearfix"></div>

									<?php if(count($item['order']['addons']) > 0): ?>
										<div class="alert alert-info">
											<?php $__currentLoopData = $item['order']['addons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<?php echo e($addon['title'.LANG]); ?><br>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>
									<?php endif; ?>

								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
					<?php $i++; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			<a href="<?php echo e(url('menu/approve-all/' . $user->id )); ?>" class="btn btn-block btn-success"><i class="fa fa-check"></i> <i class="fa fa-check"></i> <?php echo e(trans('main.Approve All')); ?></a>


		</div>
	</div>



	<div class="modal fade" style=" overflow: auto !important;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div id="content-model" class="modal-body">

				</div>



			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

<?php $__env->stopSection(); ?>





<?php $__env->startSection('custom_foot'); ?>

<script>

	// $('.noaction').click(function(event) {
	// 	event.preventDefault();
	// 	$("#messages").html('<div class="alert alert-danger"><?php echo e(trans('main.Can not modify next couple of days')); ?></div>');
	// });



	$(".edit-order").click(function  (e) {
		e.preventDefault();
		var orderId = $(this).attr('data-id');
		var user = $(this).attr('data-user');
		var date = $(this).attr('data-date');


		$('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
		  $('.modal').modal();

		$.get('/menu/order/listHtmlDoctor/'+user+"/"+orderId+"/"+date, null, function(json, textStatus) {
			$('#content-model').html(json);
			$('.modal').modal();
			$('.item-radio').click(function(){
				$(this).parent().parent().find('.item-checks').prop('checked', false);
			});
		});



	});
	$(".approve-order").click(function  (e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		$.getJSON(APP_URL+'/menu/approve-order/'+id, null, function(json, textStatus) {
  			$("#messages").html('<div class="alert alert-success"><?php echo e(trans('main.Saved')); ?></div>');
		});
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/menu/user.blade.php ENDPATH**/ ?>
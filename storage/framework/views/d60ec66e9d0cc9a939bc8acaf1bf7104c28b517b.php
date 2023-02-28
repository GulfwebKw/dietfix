<?php $__env->startSection('forms2'); ?>

	



	<div class="control-group permissions">

		<div class="controls">

			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php $i=0;?>
			<?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $meals): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingThree">
			      <h4 class="panel-title">
			        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo e($i); ?>" aria-expanded="true" aria-controls="collapse<?php echo e($i); ?>">
			         <?php echo e($day); ?>

			        </a>
			      </h4>
			    </div>
			    <div id="collapse<?php echo e($i); ?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
			      <div class="panel-body order-item">
                   <?php $j=1;?>
			        <?php $__currentLoopData = $meals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<h3><?php echo e($meal); ?> <?php if($it['order']['updated_on']<>'0000-00-00 00:00:00' && $j==1): ?><span style="float:right; font-size:12px;">Last Modified : <?php echo e($it['order']['updated_on']); ?>-<?php echo e($j); ?></span><?php endif; ?></h3>
						<img src="<?php echo e(url('resize?w=100&h=100&r=1&c=1&src=media/items/' . $it['order']['item']['photo'] )); ?>" class="pull-left flip" alt="<?php echo e($it['item']); ?>">
				  	 	<h3><?php echo e($it['item']); ?><?php if($it['order']['portion']): ?>
				  	 	<sup><code><?php echo e($it['order']['portion']['title'.LANG]); ?></code></sup><?php endif; ?></h3>
				  	 	<p><?php echo e($it['order']['item']['details'.LANG]); ?></p>
				  	 	<div class="clearfix"></div>
				  	 	<?php if(count($it['order']['addons']) > 0): ?>
				  	 	<div class="alert alert-info">
				  	 		<?php $__currentLoopData = $it['order']['addons']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				  	 			<?php echo e($addon['title'.LANG]); ?><br>
				  	 		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				  	 	</div>
				  	 	<?php endif; ?>
                        <?php $j++; ?>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php unset($j);?>
			      </div>
			    </div>
			  </div>
			  <?php $i++; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		</div>

	</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.forms.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/forms/menu_form.blade.php ENDPATH**/ ?>
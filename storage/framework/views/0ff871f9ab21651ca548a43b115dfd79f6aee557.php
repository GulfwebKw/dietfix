



<?php $__env->startSection('custom_head_include'); ?>



    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

    <?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>

    <?php if(!empty($customJS)): ?>
        <?php echo e(HTML::script($customJS)); ?>

    <?php endif; ?>


    <?php if($_adminLang == 'arabic'): ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap_rtl.css')); ?>


    <?php else: ?>

        <?php echo e(HTML::style('cpassets/plugins/data-tables/DT_bootstrap.css')); ?>


    <?php endif; ?>

<?php $__env->stopSection(); ?>







<?php $__env->startSection('content'); ?>

    <div class="table-toolbar">


    </div>
    <div class="clearfix"></div>
    <br>

    <?php if($weeks->count()>=1): ?>
       <?php $__currentLoopData = $weeks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colloect): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
           <div class="row">
               <?php $__currentLoopData = $colloect; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   <div class="col-md-4">
                       <div class="card">
                           <div class="card-header">
                               <?php echo e($item->titleEn); ?>

                           </div>
                           <div class="card-body">
                               <ul class="list-group list-group-flush">
                                   <li class="list-group-item">Water: <?php echo e($item->water); ?></li>
                                   <li class="list-group-item">Commitment: <?php echo e($item->commitment); ?></li>
                                   <li class="list-group-item">exercise: <?php echo e($item->exercise); ?></li>
                                   <li class="list-group-item">average: <?php echo e($item->average); ?></li>
                                   <li class="list-group-item">Date: <?php echo e($item->created_at); ?></li>
                               </ul>

                           </div>
                       </div>

                   </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
           </div>
           <hr/>
           <br/>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
    <div class="row"><div class="col-lg-12"><div class="alert alert-info">Progress report is not available for this user.</div></div></div>   
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>
    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/userWeekProgress.blade.php ENDPATH**/ ?>
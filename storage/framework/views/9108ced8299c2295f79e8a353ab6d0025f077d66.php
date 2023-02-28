



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

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">

            <tr>

                <th class="exclude-search">ID</th>
                <th><?php echo e(trans('main.Operation')); ?></th>
                <th><?php echo e(trans('main.Value')); ?></th>
                <th><?php echo e(trans('main.RefNumber')); ?></th>
                <th><?php echo e(trans('main.Date')); ?></th>

            </tr>

            </thead>
            <tbody>

            <?php
                $i=0;
            ?>
            <?php if($points->count()<=0): ?>
                <tr>
                    <h5>Not Found Point Record</h5>
                </tr>
            <?php endif; ?>
            <?php
              $sum=0;
              $dsum=0;
            ?>


            <?php $__currentLoopData = $points; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if($payment->operation=="decrement"): ?>
                        <?php
                            $dsum+=$payment->value;
                        ?>
                    <?php else: ?>


                    <?php
                        $sum+=$payment->value;
                    ?>
                 <?php endif; ?>

                <tr>
                    <td><?php echo e(++$i); ?></td>
                    <td><?php echo e($payment->operation); ?></td>
                    <td><?php echo e($payment->value); ?></td>


                    <?php if($payment->referral_number!="" && $payment->referral_number!=null): ?>
                        <td><?php echo e($payment->referral_number); ?></td>
                    <?php else: ?>
                        <td>----</td>
                     <?php endif; ?>

                    <td><?php echo e($payment->created_at); ?></td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>Sum Balance : <strong><?php echo e($sum-$dsum); ?></strong></tr>

            </tbody>



        </table>
        <?php echo $points->links(); ?>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/userPoints.blade.php ENDPATH**/ ?>
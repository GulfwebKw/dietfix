<?php $__env->startSection('custom_head_include'); ?>



    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/external/jquery/jquery.js')); ?>

    <?php echo e(HTML::script('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.min.js')); ?>

    <?php echo e(HTML::style('//cdn.datatables.net/autofill/1.2.1/css/dataTables.autoFill.css')); ?>

    <?php echo e(HTML::style('cpassets/plugins/jquery-ui-1.11.4/jquery-ui.theme.css')); ?>

    <?php echo e(HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css')); ?>


    <?php echo e(HTML::style('cpassets/css/pqgrid.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/pqgrid.min.js')); ?>

    <!--<?php echo e(HTML::style('cpassets/css/jquery.dataTables.min.css')); ?>

    <?php echo e(HTML::script('cpassets/js/datatables.min.js')); ?>-->
    <?php echo e(HTML::script('//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css')); ?>

    <?php echo e(HTML::script('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css')); ?>

    
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

        <table  class="table table-striped table-bordered" style="width:100%" id="grid">

            <thead class="flip-content">

            <tr>

                <th class="exclude-search">ID</th>
                <th><?php echo e(trans('main.Payment Type')); ?></th>
                <th><?php echo e(trans('main.pay_way_type')); ?></th>
                <th><?php echo e(trans('main.total_credit')); ?></th>
                <th><?php echo e(trans('main.Total')); ?></th>
                <th><?php echo e(trans('main.PaidCurrencyValue')); ?></th>
                <th><?php echo e(trans('main.DueValue')); ?></th>
                <th><?php echo e(trans('main.InvoiceDisplayValue')); ?></th>
                <th><?php echo e(trans('main.PaidCurrency')); ?></th>
                <th><?php echo e(trans('main.Currency')); ?></th>
                <th><?php echo e(trans('main.Paid?')); ?></th>
                <th><?php echo e(trans('main.RefId')); ?></th>
                <th><?php echo e(trans('main.Created Date')); ?></th>

            </tr>

            </thead>
            <tbody>

            <?php
                $i=0;
            ?>
            <?php if($payments->count()<=0): ?>
                <tr>
                    <h5>Not Found Payment Record</h5>
                </tr>
            <?php endif; ?>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <tr>
               <td><?php echo e(++$i); ?></td>
               <td><?php echo e($payment->type); ?></td>
               <td><?php echo e($payment->pay_way_type); ?></td>
               <td><?php echo e($payment->total_credit); ?></td>
               <td><?php echo e($payment->total); ?></td>
               <td><?php echo e($payment->PaidCurrencyValue); ?></td>
               <td><?php echo e($payment->DueValue); ?></td>
               <td><?php echo e($payment->InvoiceDisplayValue); ?></td>
               <td><?php echo e($payment->PaidCurrency); ?></td>
               <td><?php echo e($payment->Currency); ?></td>
               <td><?php echo e($payment->paid==1?"Yes":"No"); ?></td>
               <td><?php echo e($payment->ref); ?></td>
               <td><?php echo e($payment->created_at); ?></td>
           </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>


        </table>
        <?php echo $payments->links(); ?>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js')); ?>


  <script>

           $(document).ready(function($) {
           $('#grid').DataTable("displayLength": "50");
		   });
 </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/userTransaction.blade.php ENDPATH**/ ?>
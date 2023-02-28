



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
    <?php if(Session::get('status')=='danger'): ?>
    <div class="alert alert-danger"><?php echo e(Session::get('message')); ?></div>
    <?php endif; ?>
    <?php if(Session::get('status')=='success'): ?>
    <div class="alert alert-success"><?php echo e(Session::get('message')); ?></div>
    <?php endif; ?>
    
    <div class="clearfix"></div>
    
    <br>

    <div class="flip-scroll">

        <table class="table table-striped table-bordered table-hover table-condensed table-responsive flip-content" id="grid">

            <thead class="flip-content">
            <tr>
                <th class="exclude-search">ID</th>
                <th>User Details</th>
                <th>Package Details</th>
                <th>Trans. Details</th>
                <th>Dates</th>
                <th width="100"><?php echo e(trans('main.Active')); ?></th>
            </tr>

            </thead>
            <tbody>
            <?php $__currentLoopData = $renewDatas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $renewData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($renewData->id); ?></td>
                    <td>
                    <table width="100%" class="table">
                    <?php if(!empty($renewData->users->username)): ?>
                    <tr><td width="50">Name:</td><td><?php echo e($renewData->users->username); ?></td></tr>
                    <?php endif; ?>
                    <?php if(!empty($renewData->users->phone)): ?>
                    <tr><td>Mobile:</td><td><?php echo e($renewData->users->phone); ?></td></tr>
                    <?php endif; ?>
                    <?php if(!empty($renewData->users->email)): ?>
                    <tr><td>Email:</td><td><?php echo e($renewData->users->email); ?></td></tr>
                    <?php endif; ?>
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    <?php if(!empty(optional($renewData->package)->titleEn)): ?>
                    <tr><td width="50">Package:</td><td><?php echo e(optional($renewData->package)->titleEn); ?></td></tr>
                    <?php endif; ?>
                    <?php if(!empty(optional($renewData->packageduration)->titleEn)): ?>
                    <tr><td width="50">Duration:</td><td><?php echo e(optional($renewData->packageduration)->titleEn); ?></td></tr>
                    <?php endif; ?>
                    <?php if(!empty($renewData->payment->total)): ?>
                    <tr><td width="100">Amount:</td><td><?php echo e($renewData->payment->total); ?></td></tr>
                    <?php endif; ?>
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    <?php if(!empty($renewData->payment->paymentID)): ?>
                    <tr><td width="100">Pay ID:</td><td><?php echo e($renewData->payment->paymentID); ?></td></tr>
                    <?php endif; ?>
                    <?php if(!empty($renewData->payment->pay_way_type)): ?>
                    <tr><td width="100">Pay Mode:</td><td><?php echo e($renewData->payment->pay_way_type); ?></td></tr>
                    <?php endif; ?>
                   
                    <tr><td width="100">Status:</td><td>
                    <?php if(!empty($renewData->payment->paid)): ?> <font color="#006600">PAID</font> <?php else: ?> <font color="#ff0000">NOT PAID</font> <?php endif; ?>
                    </td></tr>
                    
                    </table>
                    </td>
                    <td>
                    <table width="100%" class="table">
                    <?php if(!empty($renewData->starting_date)): ?>
                    <tr><td width="100">Start Date:</td><td><?php echo e($renewData->starting_date); ?></td></tr>
                    <?php endif; ?>
                    <tr><td width="100">Created On:</td><td><?php echo e($renewData->created_at); ?></td></tr>
                    </table>
                    </td>
                    <td>
                    <?php
                    $strtDate = date("Y-m-d",strtotime($renewData->starting_date));
                    $crDate   = date("Y-m-d");
                    ?>
                    
                    <?php if(strtotime($crDate)>strtotime($strtDate)): ?>
                    <a href="/admin/future_pkg_subs_approve/<?php echo e($renewData->id); ?>"  class="btn btn-xs green btn-block ">Activate</a>
                    <?php else: ?>
                    <small><font color="#ff0000">Activation link will appear once starting date will cross the current date</font></small>
                    <?php endif; ?>    
                    </td>

                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>
        
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('custom_foot'); ?>

    <?php echo e(HTML::script('https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js')); ?>


    <?php echo e(HTML::script('https://cdn.datatables.net/plug-ins/725b2a2115b/integration/bootstrap/3/dataTables.bootstrap.js')); ?>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/FutureRenewalPackage.blade.php ENDPATH**/ ?>
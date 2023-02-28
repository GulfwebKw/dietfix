



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
                <th><?php echo e(trans('main.User Name')); ?></th>
                <th><?php echo e(trans('main.Email Address')); ?></th>
                <th><?php echo e(trans('main.Phone No.')); ?></th>
                <th><?php echo e(trans('main.Mobile')); ?></th>
                <th>Count Freez Day</th>
                <th><?php echo e(trans('main.Package')); ?></th>
                <th>Start Suspension</th>
                <th><?php echo e(trans('main.EndsBy')); ?></th>
                <th><?php echo e(trans('main.Active')); ?></th>
            </tr>

            </thead>
            <tbody>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td><?php echo e($user->phone); ?></td>
                    <td><?php echo e($user->mobile_number); ?></td>
                    <td><?php echo e($user->dates->count()); ?></td>
                    <td><?php echo e(optional($user->package)->titleEn); ?></td>
                    <td><?php echo e($user->dates->first()->date); ?></td>
                    <td><?php echo e($user->dates->last()->date); ?></td>

                    <td>
                        <a href="/admin/users/renew-or-addmembership/<?php echo e($user->id); ?>" data-id="4280" class="nwrap btn btn-xs green btn-block "><i class="fa fa-print"></i> Renew/Add Membership</a>
                        <a href="/admin/users/orders/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs red btn-block "><i class="fa fa-print"></i> Orders</a>
                        <a href="/admin/users/edit/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-edit"></i> Edit</a>
                        <a href="/admin/users/delete/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs yellow btn-block grid-del-but"><i class="fa fa-remove"></i> Delete</a>
                        <a href="/admin/orders/view/<?php echo e($user->id); ?>" class="btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>
                        <a href="/admin/users/progress/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs green btn-block "><i class="fa fa-print"></i> progress</a>
                        <a href="/admin/users/transactions/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-list"></i> Transactions</a>
                        <a href="/admin/users/points/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs blue btn-block "><i class="fa fa-list"></i> Points</a>
                        <a href="/admin/users/freeze/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs red btn-block "><i class="fa fa-lock"></i> Freeze</a>
                        <a href="/admin/users/unfreeze/<?php echo e($user->id); ?>" data-id="<?php echo e($user->id); ?>" class="nwrap btn btn-xs green btn-block "><i class="fa fa-unlock"></i> main.unFreeze</a>
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

<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/user/freezeUser.blade.php ENDPATH**/ ?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body { font-family: DejaVu Sans, sans-serif;font-size:8px;}
        @page  {
            margin-bottom:1px;
            margin-left: 5px;
            margin-right: 2px;
            margin-top:1px;
        }

    </style>

</head>


<body>

<?php if($orders->isEmpty()): ?>
    <div>
        <?php echo e(trans('main.No Results')); ?>

    </div>
<?php else: ?>
    <?php $__currentLoopData = $orders->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php $userdoctor = DB::table('users')->where('id', $user['user']->doctor_id)->first();?>
        <div class="page" >
            <div class="table-responsive">
                <table class="table table-hover"   style="height:310px;margin-bottom:10px">
                    <tbody>
                        <tr style="font-size: 11px; font-family:Tahoma,Arial, Helvetica, sans-serif;">
                            <th colspan="2" style="font-size: 15px" height="30"><h4><?php echo e($user['user']->id); ?></h4></th>
                            <th colspan="2"><h4><?php echo e(optional($user['user'])->username); ?></h4></th>
                            <th colspan="2"><h4><?php echo e($user['user']->mobile_number); ?></h4></th>
                            <th colspan="2" ><h4><?php echo e(Input::get('date')); ?></h4></th>
                            <th colspan="2" ><h4><?php echo e(optional($userdoctor)->username); ?></h4></th>
                        </tr>

                    <tr style="font-size: 11px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <th colspan="10" style="font-size: 11px">
                            <p>
                                <?php if($weekEndAddress): ?>
                                    <?php echo e(optional($user['user']->countryWeekends)->{'title'.LANG}); ?> ,  <?php echo e(optional($user['user']->provinceWeekends)->{'title'.LANG}); ?> , <?php echo e(optional($user['user']->areaWeekends)->{'title'.LANG}); ?> , Block:<?php echo e($user['user']->block_work); ?> , Street:<?php echo e($user['user']->street_work); ?> , Avenue: <?php echo e($user['user']->avenue_work); ?> , HouseNo.:<?php echo e($user['user']->house_number_work); ?> ,  Floor:<?php echo e($user['user']->floor_work); ?><br>
                                    <?php if($user['user']->address_work): ?>Address:<?php echo e(substr($user['user']->address_work,0,130)); ?><?php endif; ?>
                                <?php else: ?>
                                    <?php echo e($user['user']->country->{'title'.LANG}); ?> ,  <?php echo e(optional($user['user']->area->province)->{'title'.LANG}); ?> , <?php echo e($user['user']->area->{'title'.LANG}); ?> , Block:<?php echo e($user['user']->block); ?> , Street:<?php echo e($user['user']->street); ?> , Avenue:<?php echo e($user['user']->avenue); ?> , HouseNo.:<?php echo e($user['user']->house_number); ?> ,  Floor:<?php echo e($user['user']->floor); ?><br>
                                    <?php if($user['user']->address): ?>Address:<?php echo e(substr($user['user']->address,0,130)); ?><?php endif; ?>
                                <?php endif; ?>

                            </p>
                        </th>
                    </tr>

                    <tr style="font-size: 10px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                        <th colspan="3"><?php echo e(trans('main.Meal')); ?> <?php if(!empty($user['user']->package->{'title'.LANG})): ?> ( <?php echo e(($user['user']->package->{'title'.LANG})); ?> )<?php endif; ?></th>
                        <th colspan="1" align="center" width="20"><?php echo e(trans('main.Portion')); ?></th>
                        <th  colspan="1" align="center" width="20"><?php echo e(trans('main.Notes')); ?></th>
                        <th  colspan="1" align="center" width="20"><?php echo e(trans('main.Salt')); ?></th>
                        <th  colspan="1" align="center" width="20"><?php echo e(trans('main.PR')); ?></th>
                        <th  colspan="1" align="center" width="20"><?php echo e(trans('main.FT')); ?></th>
                        <th  colspan="1" align="center" width="20"><?php echo e(trans('main.CB')); ?></th>
                        <th  colspan="1" align="left" width="30"><?php echo e(trans('main.CL')); ?></th>
                    </tr>
                    <?php $__currentLoopData = $user['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="font-size:  10px;font-family:Tahoma,Arial, Helvetica, sans-serif;">
                            <td colspan="3"><?php echo e($order->category->{'title'.LANG}); ?> <?php echo e($order->item->{'title'.LANG}); ?></td>
                            <td colspan="1" align="center"><?php echo e(($order->portion) ? $order->portion->{'title'.LANG} : 1); ?></td>
                            <td style="font-size:8px;" colspan="1" align="center">
                                <?php if(!$order->addons->isEmpty()): ?>
                                    <?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($addon->{'title'.LANG}); ?>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:8px;" colspan="1" align="center"><?php echo e(str_replace('Salt','',$order->user->salt)); ?></td>
                            <td style="font-size:8px;" colspan="1" align="center"><?php echo e(!empty($order->item->protien)?$order->item->protien:'-'); ?></td>
                            <td style="font-size:8px;" colspan="1" align="center"><?php echo e(!empty($order->item->fat)?$order->item->fat:'-'); ?></td>
                            <td style="font-size:8px;" colspan="1" align="center"><?php echo e(!empty($order->item->carb)?$order->item->carb:'-'); ?></td>
                            <td style="font-size:8px;" colspan="1" align="left"><?php echo e(!empty($order->item->calories)?$order->item->calories:'-'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>

                </table>
            </div>
        </div>
        <div class="page-break"></div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

</body>
</html>






<?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/packaging2.blade.php ENDPATH**/ ?>
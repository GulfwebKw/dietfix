
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body { font-family: DejaVu Sans, sans-serif;font-size:10px;}
        @page  {
            margin-bottom:1px;
            margin-left: 5px;
            margin-right: 2px;
            margin-top:1px;
        }

    </style>

</head>


<body>

<?php $__currentLoopData = $data['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div style="height:115px!important;width:219px;margin-bottom:0px!important;margin-top:8px;overflow: hidden;">

        <?php echo e(substr($order->category->{'title'.LANG},0,32) . ' ' . substr($order->item->{'title'.LANG},0,32)); ?>

        <br>
        <?php if($order->portion): ?>
            <?php echo e($order->portion->{'title'.LANG}); ?> <br>
        <?php endif; ?>

        <?php echo e(' '.\Input::get('date')); ?><?php if(!empty($order->user->packageone->{'title'.LANG})): ?>,<?php echo e(($order->user->packageone->{'title'.LANG})); ?><?php endif; ?>

        <br>
        <p>
            <?php echo optional($order->user)->salt
               ."<br>".' ID:'.$order->user->id; ?>

       </p>
        <?php if(!$order->addons->isEmpty()): ?>
            <?php $addOnItem[$order->id]=[]; ?>
            <?php $__currentLoopData = $order->addons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <?php if(!in_array($addon->{'title'.LANG},$addOnItem[$order->id])): ?>
                    <p style="font-size:8px"><?php echo e($addon->{'title'.LANG}.','); ?></p>
                <?php endif; ?>
                <?php $addOnItem[$order->id][]=$addon->{'title'.LANG}; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>






<?php /**PATH /home/dietfix/private_fix/resources/views/kitchen/temp.blade.php ENDPATH**/ ?>
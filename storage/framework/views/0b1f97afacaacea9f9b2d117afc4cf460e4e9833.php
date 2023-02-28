<?php $__env->startSection('contents'); ?>
<div class="block-content block-content-small-padding">
<div class="comment-wrap">


        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <h1 style=\"background-color:#ffffff;\"><?php echo e($d->date."-".date('l',strtotime($d->date))); ?></h1>
                <?php $__currentLoopData = $d->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $od): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <h3><?php echo e(optional($od->meal)->{"title".LANG}); ?></h3>
                                 <img src="/resize?w=100&amp;h=100&amp;r=1&amp;c=1&amp;src=/media/items/<?php echo e(optional($od->item)->photo); ?>" class="" alt="<?php echo e(optional($od->meal)->{"title".LANG}); ?>">
                        <?php echo e(optional($od->item)->{"title".LANG}); ?>

                        <br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <table>
                        <tr><td></td></tr>
                </table>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="clearfix"></div>
</div><!-- /.block-content-inner -->
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/summary.blade.php ENDPATH**/ ?>
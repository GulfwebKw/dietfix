<option  selected value="none" >None</option>
<?php $__currentLoopData = $packageDuration; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $duration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option <?php if($duration->id==$selectedDuration): ?> selected <?php endif; ?> value="<?php echo e($duration->id); ?>"><?php echo e($duration->titleEn); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php /**PATH /home/dietfix/private_fix/resources/views/admin/thirdParty/listDuration.blade.php ENDPATH**/ ?>
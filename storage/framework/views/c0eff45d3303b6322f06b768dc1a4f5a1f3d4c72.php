

<?php $__env->startSection('contents'); ?>
<?php if(Auth::user()): ?>
  <div style='min-height:200px;' class="text-center">Welcome back to Dashboard</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/myhome.blade.php ENDPATH**/ ?>
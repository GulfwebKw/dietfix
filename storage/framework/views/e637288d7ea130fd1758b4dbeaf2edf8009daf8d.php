
<?php if(session()->has('message')): ?>
   <div class="block-content block-content-small-padding">
    <?php $__currentLoopData = session()->get('message'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k =>  $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="alert alert-<?php echo e((!is_numeric($k)) ? $k : 'info'); ?>">

                      <?php echo $m; ?>


        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<?php endif; ?>


<?php if(session()->has('error')): ?>


<div class="block-content block-content-small-padding">

    <?php if(is_string(session()->get('error'))): ?>

        <div class="alert alert-danger">

            <?php echo session()->get('error'); ?>

        </div>

          <?php else: ?>
        <?php $__currentLoopData = session()->get('error'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="alert alert-danger">

                          <?php echo $e; ?>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

       <?php endif; ?>

</div>

<?php endif; ?>

<?php if(isset($messages)): ?>

<div class="block-content block-content-small-padding">

    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="alert alert-<?php echo e((isset($type) && !is_numeric($type)) ? $type : 'success'); ?> fade in">

                      <?php echo $message; ?>


        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

<?php endif; ?>

<?php if($errors->count()>=1): ?>

<div class="block-content block-content-small-padding">

    <div class="alert alert-danger">

        <ul>

        <?php $__currentLoopData = $errors->all('<li>:message</li>'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php echo $message; ?>


        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </ul>

    </div>

</div>

<?php endif; ?>
<?php /**PATH /home/dietfix/private_fix/resources/views/partials/messages.blade.php ENDPATH**/ ?>
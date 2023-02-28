<?php if(isset($messages)): ?>

    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '<?php echo e($message); ?>'

                });

            });

        </script>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?>

<?php if(session()->has('messages')): ?>

    <?php $__currentLoopData = session()->get('messages'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '<?php echo e($message); ?>'

                });

            });

        </script>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?>

<?php if(isset($errors)): ?>

	<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

		<script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '<?php echo e($error); ?>'

                });

            });

        </script>

	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php endif; ?><?php /**PATH /home/dietfix/private_fix/resources/views/admin/partials/messages.blade.php ENDPATH**/ ?>
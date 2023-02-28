

<?php $__env->startSection('top_js'); ?>
    <?php echo NoCaptcha::renderJs(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('frontend.components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            CONTACTS
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>


        <p>
        <?php if(isset($contact->map)): ?>
            <iframe src="<?php echo $contact->map; ?>" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        <?php endif; ?>


        <div class="row">
            <div class="span4">
                  <h2>Contact info</h2>
                  <p><?php echo e($contact->address); ?></p>
                    <h5>
                        Telephone: <?php echo e($contact->telephone); ?><br />
                        Mobile: <?php echo e($contact->mobile); ?><br />
                        Fax: <?php echo e($contact->fax); ?><br />
                        E-mail: <a href="mailto:<?php echo e($contact->email); ?>"><?php echo e($contact->email); ?></a>
                        <br />
                    </h5>
           </div>

             <div class="span8">
        <h2>Contact form</h2>
        <div role="form" class="wpcf7" id="wpcf7-f208-p2082-o1" dir="ltr">
            <form action="<?php echo e(route('contact_post')); ?>" method="post" class="wpcf7-form" novalidate>
                <input type="hidden" name="action" value="contactx">
                <div class="row-fluid">
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-name"><input type="text" name="name" value="<?php echo e(old('name')); ?>" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Name:" /></span> </p>
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-email"><input type="email" name="email" value="<?php echo e(old('email')); ?>" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="E-mail:" /></span> </p>
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-phone"><input type="text" name="phone" value="<?php echo e(old('phone')); ?>" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="Phone:" /></span> </p>
                </div>
                <p class="field"><span class="wpcf7-form-control-wrap your-message"><textarea name="message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Message:"><?php echo e(old('message')); ?></textarea></span> </p>


                   <?php echo NoCaptcha::display(); ?>

                <p class="field">
                        <?php if($errors->has('g-recaptcha-response')): ?>
                            <span class="help-block">
                                <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                            </span>
                        <?php endif; ?>
                </p>

                <p class="submit-wrap"><input type="reset" value="clear" class="btn btn-primary" /><input type="submit" value="send" class="wpcf7-form-control  btn btn-primary" /></p>

                    <?php if($errors->any()): ?>
                        <div class="wpcf7-response-output " >
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                <?php if(session()->has('message')): ?>
                    <div class="wpcf7-response-output " >
                        <?php echo e(session()->get('message')); ?>

                    </div>
                <?php endif; ?>


            </form>
        </div>
    </div>
        </div>







<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/frontend/contacts.blade.php ENDPATH**/ ?>
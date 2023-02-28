<?php $__env->startSection('top_js'); ?>

    <script type="text/javascript" src="/js/highslide-with-gallery.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/highslide.css" />

    <!--
        2) Optionally override the settings defined at the top
        of the highslide.js file. The parameter hs.graphicsDir is important!
    -->

    <script type="text/javascript">
        hs.graphicsDir = '<?php echo e(env('APP_URL')); ?>/highslide/graphics/';
        hs.align = 'center';
        hs.transitions = ['expand', 'crossfade'];
        hs.outlineType = 'rounded-white';
        hs.fadeInOut = true;
        hs.numberPosition = 'caption';
        hs.dimmingOpacity = 0.75;

        // Add the controlbar
        if (hs.addSlideshow) hs.addSlideshow({
            //slideshowGroup: 'group1',
            interval: 5000,
            repeat: false,
            useControls: true,
            fixedControls: 'fit',
            overlayOptions: {
                opacity: .75,
                position: 'bottom center',
                hideOnMouseOut: true
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $__env->startComponent('frontend.components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?>
            MEALS SAMPLE
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>


    <div class="highslide-gallery">
       <?php $__currentLoopData = $resultslider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a id="thumb<?php echo e($item->id); ?>" href="/resize?w=600&h=400&src=/media/gallery/<?php echo e($item->photo); ?>" class="highslide" onClick="return hs.expand(this)">
                    <img src="/media/gallery/<?php echo e($item->photo); ?>" alt="Highslide JS" title="Click to enlarge"   style="margin:10px; max-width:265px;"/>
           </a>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>






<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/dietfix/private_fix/resources/views/frontend/examplesOfMeals.blade.php ENDPATH**/ ?>
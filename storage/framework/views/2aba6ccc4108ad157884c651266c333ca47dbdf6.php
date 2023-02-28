<!DOCTYPE html>

<!--[if lt IE 7 ]><html class="ie ie6" lang="en-US"> <![endif]-->

<!--[if IE 7 ]><html class="ie ie7" lang="en-US"> <![endif]-->

<!--[if IE 8 ]><html class="ie ie8" lang="en-US"> <![endif]-->

<!--[if IE 9 ]><html class="ie ie9" lang="en-US"> <![endif]-->

<html lang="en-US">

<head>

    <title>Diet Fix | Meal Sample</title>

    <meta name="description" content="Meal Sample" />

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <?php if ($__env->exists('frontend.partials.head')) echo $__env->make('frontend.partials.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('top_js'); ?>
    <style>
        .white-popup {
            position: relative;
            background: #FFF;
            padding: 20px;
            width: auto;
            max-width: 500px;
            margin: 20px auto;
        }
    </style>

</head>

<body class="home page-template page-template-page-home page-template-page-home-php page page-id-203 logged-in">
            <?php echo $__env->yieldContent('top_body'); ?>

    <div id="motopress-main" class="main-holder">

        <?php if ($__env->exists('frontend.partials.header')) echo $__env->make('frontend.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="motopress-wrapper content-holder clearfix">
            <div class="container">
                <div class="row">
                    <div class="span12" data-motopress-wrapper-file="page-home.php" data-motopress-wrapper-type="content">
                        <div class="row">
                            <div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-slider.php">
                                <?php echo $__env->yieldContent('content'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($__env->exists('frontend.partials.footer')) echo $__env->make('frontend.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>
</body>



<?php if ($__env->exists('frontend.partials.scripts')) echo $__env->make('frontend.partials.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php echo $__env->yieldContent('js'); ?>

</html><?php /**PATH /home/dietfix/private_fix/resources/views/layouts/frontend.blade.php ENDPATH**/ ?>
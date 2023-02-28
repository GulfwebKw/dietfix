<!doctype html>
<html>
<head>

  <title>
    <?php echo e($_setting['siteName'.LANG]); ?> | <?php echo e(isset($title) ? $title : trans('main.Home')); ?>


  </title>
  <meta charset="utf-8">
  <meta name="description" content="<?php echo e($_setting['metaDescription']); ?>">
  <meta name="keywords" content="<?php echo e($_setting['metaKeywords']); ?>">
  <meta name="author" content="Website - by SZ4H | http://sz4h.com">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

  <link rel="shortcut icon" href="<?php echo e(url('media/fav.png')); ?>">
  <?php echo $__env->make('partials.headinclude', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->yieldContent('custom_head_include'); ?>
  <?php echo $__env->yieldContent('otherheads'); ?>
</head>

<body class="no-transition  <?php echo e(str_replace('/', ' ',Request::path())); ?> sticky-responsive-menu <?php if(LANG=='Ar'): ?> rtl <?php else: ?> ltr <?php endif; ?>">

    
    <div id="messages">
      <?php echo $__env->make('partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php echo $__env->yieldContent('contents'); ?>


  <?php echo $__env->make('partials.foot_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html><?php /**PATH /home/dietfix/private_fix/resources/views/layouts/blank.blade.php ENDPATH**/ ?>
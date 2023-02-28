<!doctype html>
<html>
<head>

    <title>
        <?php echo $_setting['siteName'.LANG]['value']; ?> | <?php echo e(isset($title) ? $title : trans('main.Home')); ?>


    </title>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $_setting['metaDescription']['value']; ?>">
    <meta name="keywords" content="<?php echo $_setting['metaKeywords']['value']; ?>">
    <meta name="author" content="Website - by SZ4H | http://sz4h.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <link rel="shortcut icon" href="<?php echo e(url('media/fav.png')); ?>">
    <?php echo $__env->make('partials.headinclude', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('custom_head_include'); ?>
    <?php echo $__env->yieldContent('otherheads'); ?>

</head>

<body class="no-transition  <?php echo e(str_replace('/', ' ',request()->path())); ?> sticky-responsive-menu <?php if(LANG=='Ar'): ?> rtl <?php else: ?> ltr <?php endif; ?>">
<?php
if(!empty($_REQUEST['logid']) && !empty($_REQUEST['username'])){
    $usernames= $_GET['username'];
    $userInfo = \DB::table('users')->where('username', $usernames)->first();
    if(!empty($userInfo->clinic_id)){
        $clinicInfo = \DB::table('clinics')->where('id', $userInfo->clinic_id)->first();
        session()->put('username',  $usernames);
        if(!empty($clinicInfo->logo)){
            session()->put('cliniclogo',  $clinicInfo->logo);
        }else{
            session()->put('cliniclogo',  '');
            session()->put('clinicname',  $clinicInfo->titleEn);
        }
    }
}
?>
<div class="container">
    <div class="row y-header">
        <div class="col-xs-12 col-sm-9 col-md-10 col-lg-11">


        </div>
        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-1"><ul class="list-inline">
                <li class="lang-item lang-item-40 lang-item-ar"><a hreflang="ar" href="<?php echo e(url('lang/ar')); ?>"><img src="<?php echo e(url('assets/images/ar.jpg')); ?>" title="العربية" alt="العربية"></a></li>
                <li class="lang-item lang-item-43 lang-item-en current-lang"><a hreflang="en" href="<?php echo e(url('lang/en')); ?>"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAIAAAD5gJpuAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHzSURBVHjaYkxOP8IAB//+Mfz7w8Dwi4HhP5CcJb/n/7evb16/APL/gRFQDiAAw3JuAgAIBEDQ/iswEERjGzBQLEru97ll0g0+3HvqMn1SpqlqGsZMsZsIe0SICA5gt5a/AGIEarCPtFh+6N/ffwxA9OvP/7//QYwff/6fZahmePeB4dNHhi+fGb59Y4zyvHHmCEAAAW3YDzQYaJJ93a+vX79aVf58//69fvEPlpIfnz59+vDhw7t37968efP3b/SXL59OnjwIEEAsDP+YgY53b2b89++/awvLn98MDi2cVxl+/vl6mituCtBghi9f/v/48e/XL86krj9XzwEEEENy8g6gu22rfn78+NGs5Ofr16+ZC58+fvyYwX8rxOxXr169fPny+fPn1//93bJlBUAAsQADZMEBxj9/GBxb2P/9+S/R8u3vzxuyaX8ZHv3j8/YGms3w8ycQARmi2eE37t4ACCDGR4/uSkrKAS35B3TT////wADOgLOBIaXIyjBlwxKAAGKRXjCB0SOEaeu+/y9fMnz4AHQxCP348R/o+l+//sMZQBNLEvif3AcIIMZbty7Ly6t9ZmXl+fXj/38GoHH/UcGfP79//BBiYHjy9+8/oUkNAAHEwt1V/vI/KBY/QSISFqM/GBg+MzB8A6PfYC5EFiDAABqgW776MP0rAAAAAElFTkSuQmCC" title="English" alt="English"></a></li>
            </ul>
        </div>
    </div>
    <div class="row y-nav">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">



            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                <?php if(Auth::user()): ?>
                    <?php $sdat = Auth::user()->membership_end;if(!empty($sdat)){
                        $futuresdate = strtotime(date("Y-m-d")); //Future date.
                        $timefromdb = strtotime($sdat);//source time
                        if($timefromdb>$futuresdate){
                            $timeleft = $futuresdate-$timefromdb;
                            $daysleft = round((($timeleft/24)/60)/60);}else{ $daysleft="0";} }?>
                <?php endif; ?>
                <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            
                            <li><a href="<?php echo e(route('home_members')); ?>"><?php echo e(trans('main.Control Panel')); ?></a></li>
                            <?php if(Auth::user() && Auth::user()->role_id == 1): ?>
                                <li><a href="<?php echo e(url('menu')); ?>"><?php echo e(trans('main.Menu')); ?></a></li>
                                <li><a href="<?php echo e(url('summary')); ?>"><?php echo e(trans('main.Summary')); ?></a></li>
                                <?php if(Auth::user()->clinic->can_appointment): ?>
                                    <li><a href="<?php echo e(url('appointments')); ?>"><?php echo e(trans('main.Appointment')); ?></a></li>
                                <?php endif; ?>
                            <?php elseif(Auth::user() && Auth::user()->role_id == 2): ?>
                                <li><a href="<?php echo e(url('menu/users')); ?>"><?php echo e(trans('main.Users')); ?></a></li>
                                <?php if(Auth::user()->clinic->can_appointment): ?>
                                    <li><a href="<?php echo e(url('appointments/manage')); ?>"><?php echo e(trans('main.Appointments')); ?></a></li>
                                <?php endif; ?>
                            <?php elseif(Auth::user() && Auth::user()->role_id == 3): ?>
                                <li><a href="<?php echo e(url('kitchen/portioning')); ?>"><?php echo e(trans('main.Portioning')); ?></a></li>
                                <li><a href="<?php echo e(url('kitchen/preparation')); ?>"><?php echo e(trans('main.Preparation')); ?></a></li>
                                <li><a href="<?php echo e(url('kitchen/packaging')); ?>"><?php echo e(trans('main.Packaging')); ?></a></li>
                                <li><a href="<?php echo e(url('kitchen/labeling')); ?>"><?php echo e(trans('main.Labeling')); ?></a></li>
                                <li><a href="<?php echo e(url('kitchen/delivery')); ?>"><?php echo e(trans('main.Delivery')); ?></a></li>
                                <li><a href="<?php echo e(url('kitchen/pkreport')); ?>"><?php echo e(trans('main.Package Summary')); ?></a></li>
                            <?php elseif(Auth::user() && Auth::user()->role_id == 10): ?>
                                <li><a href="#" id="ulabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('main.User')); ?></a>
                                    <ul class="dropdown-menu" aria-labelledby="ulabel">
                                        <li><a href="<?php echo e(url('menu')); ?>"><?php echo e(trans('main.Menu')); ?></a></li>
                                        <li><a href="<?php echo e(url('appointments')); ?>"><?php echo e(trans('main.Appointment')); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#" id="dlabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('main.Doctor')); ?></a>
                                    <ul class="dropdown-menu" aria-labelledby="dlabel">
                                        <li><a href="<?php echo e(url('menu/users')); ?>"><?php echo e(trans('main.Users')); ?></a></li>
                                        <li><a href="<?php echo e(url('appointments/manage')); ?>"><?php echo e(trans('main.Appointments')); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#" id="klabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo e(trans('main.Kitchen')); ?></a>
                                    <ul class="dropdown-menu" aria-labelledby="klabel">
                                        <li><a href="<?php echo e(url('kitchen/portioning')); ?>"><?php echo e(trans('main.Portioning')); ?></a></li>
                                        <li><a href="<?php echo e(url('kitchen/preparation')); ?>"><?php echo e(trans('main.Preparation')); ?></a></li>
                                        <li><a href="<?php echo e(url('kitchen/packaging')); ?>"><?php echo e(trans('main.Packaging')); ?></a></li>
                                        <li><a href="<?php echo e(url('kitchen/labeling')); ?>"><?php echo e(trans('main.Labeling')); ?></a></li>
                                        <li><a href="<?php echo e(url('kitchen/delivery')); ?>"><?php echo e(trans('main.Delivery')); ?></a></li>
                                        <li><a href="<?php echo e(url('kitchen/pkreport')); ?>"><?php echo e(trans('main.Package Summary')); ?></a></li>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li><a href="<?php echo e(url('user/login')); ?>"><?php echo e(trans('main.Login')); ?></a></li>
                                <li><a href="<?php echo e(url('user/forget')); ?>"><?php echo e(trans('main.Forgot password?')); ?></a></li>
                            <?php endif; ?>

                            <?php if(Auth::user()): ?>
                                <li><a href="<?php echo e(url('user/cp')); ?>"><?php echo e(Auth::user()->username); ?></a></li>
                                <li><a href="<?php echo e(url('user/logout')); ?>"><?php echo e(trans('main.Logout')); ?></a></li>
                                <?php if(Auth::user() && Auth::user()->role_id == 1 && isset($daysleft)): ?>
                                    <?php  echo "<li><a>Remaining : ".$daysleft." Days</a></li>";?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>

                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>






        </div>
    </div>
    <div class="main-container">

        

        <div id="messages">
            <?php echo $__env->make('partials.messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <?php echo $__env->yieldContent('contents'); ?>

        

    </div>
    <div class="clearfix"></div>
    <?php if(empty(Session::get('cliniclogo')) && empty(Session::get('clinicname'))): ?>
        <div class="row y-footer">











        </div>
<?php endif; ?>

<!-- end contaniner -->
</div>


<?php echo $__env->make('partials.foot_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>


</html>
<?php /**PATH /home/dietfix/private_fix/resources/views/layouts/main2.blade.php ENDPATH**/ ?>
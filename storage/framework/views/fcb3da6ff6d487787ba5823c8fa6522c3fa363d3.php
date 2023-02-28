<ul class="nav pull-left">

	<li class="dropdown pull-left" id="header_inbox_bar">

		<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<i class="fa fa-envelope"></i>

		<span class="badge"><?php echo e(@$_adminMessages->count); ?></span>

		</a>

		

		<ul class="dropdown-menu extended inbox">

			<li>

				<p>لديك <?php echo e(@$_adminMessages->count); ?> رسائل جديدة</p>

			</li>

			<?php if(@$_adminMessages) :?>

			<li>

				<ul class="dropdown-menu-list scroller" style="height:250px">

					<?php $__currentLoopData = $_adminMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

					<li>

						<a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'message/view/'.$_message->id)); ?>">

						<span class="subject">

						<span class="from"><?php echo e($_message->messageFrom); ?></span>

						<span class="time"><?php echo e(date('Y-m-d',strtotime($_message->created_at))); ?></span>

						</span>

						<span class="message">

						<?php echo e($_message->messageTitle); ?>


						</span>  

						</a>

					</li>

					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

				</ul>

			</li>

			<?php endif; ?>

			<li class="external">

				<a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'message/')); ?>"><?php echo app('translator')->getFromJson('main.See all messages'); ?> <i class="m-fa fa-swapright"></i></a>

			</li>

		</ul>

		

	</li>

	<li class="dropdown language pull-left">

	<?php if(@$_adminLang == 'english'): ?>

		<a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/lang/en')); ?>" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<img alt="english" src="<?php echo e(url('cpassets/img/en.png')); ?>" />

		<span class="username">English</span>

		<i class="fa fa-angle-down"></i>

		</a>

		<ul class="dropdown-menu">

			<li><a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/lang/ar')); ?>"><img alt="" src="<?php echo e(url('cpassets/img/ar.png')); ?>" /> Arabic</a></li>

		</ul>

	<?php else: ?>

		<a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/lang/ar')); ?>" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<img alt="arabic" src="<?php echo e(url('cpassets/img/ar.png')); ?>" />

		<span class="username">Arabic</span>

		<i class="fa fa-angle-down"></i>

		</a>

		<ul class="dropdown-menu">

			<li><a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/lang/en')); ?>"><img alt="" src="<?php echo e(url('cpassets/img/en.png')); ?>" /> English</a></li>

		</ul>

	<?php endif; ?>

	</li>

	<!-- BEGIN USER LOGIN DROPDOWN -->

	<li class="dropdown user pull-left">

		<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
       
		<img alt="" src="<?php echo e(url('cpassets/img/avatar1_small.jpg')); ?>" /> 
       
		<span class="username"><?php echo e($_adminUser->name); ?></span>

		<i class="fa fa-angle-down"></i>

		</a>
       <!--<div id="account_info">-->
		<ul class="dropdown-menu">

			

			<li><a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'users/edit/'.$_adminUser->id)); ?>"><i class="fa fa-user"></i> <?php echo app('translator')->getFromJson('main.Edit Profile'); ?></a></li>

			<li><a href="<?php echo e($appUrl); ?>"><i class="fa fa-calendar"></i> <?php echo app('translator')->getFromJson('main.Website Interface'); ?></a></li>

			<!-- <li><a href="inbox.html"><i class="fa fa-envelope"></i> My Inbox <span class="badge badge-important">3</span></a></li>

			<li><a href="#"><i class="fa fa-tasks"></i> My Tasks <span class="badge badge-success">8</span></a></li> -->

			<li class="divider"></li>

			<li><a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-desktop"></i> <?php echo app('translator')->getFromJson('main.Full Screen'); ?></a></li>

			<li><a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/lock')); ?>"><i class="fa fa-lock"></i> <?php echo app('translator')->getFromJson('main.Lock Account'); ?></a></li>

			<li><a href="<?php echo e(url(env('ADMIN_FOLDER').'/'.'process/logout')); ?>"><i class="fa fa-key"></i> <?php echo app('translator')->getFromJson('main.Sign Out'); ?></a></li>

		</ul>
        <!--</div>-->

	</li>

	<!-- END USER LOGIN DROPDOWN -->

	<!-- END USER LOGIN DROPDOWN -->

</ul>

<!-- END TOP NAVIGATION MENU --><?php /**PATH /home/dietfix/private_fix/resources/views/admin/partials/topnav.blade.php ENDPATH**/ ?>
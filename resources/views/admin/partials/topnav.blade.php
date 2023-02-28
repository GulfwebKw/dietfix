<ul class="nav pull-left">

	<li class="dropdown pull-left" id="header_inbox_bar">

		<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<i class="fa fa-envelope"></i>

		<span class="badge">{{ @$_adminMessages->count }}</span>

		</a>

		

		<ul class="dropdown-menu extended inbox">

			<li>

				<p>لديك {{ @$_adminMessages->count }} رسائل جديدة</p>

			</li>

			<?php if(@$_adminMessages) :?>

			<li>

				<ul class="dropdown-menu-list scroller" style="height:250px">

					@foreach ($_adminMessages as $_message)

					<li>

						<a href="{{ url(env('ADMIN_FOLDER').'/'.'message/view/'.$_message->id) }}">

						<span class="subject">

						<span class="from">{{ $_message->messageFrom }}</span>

						<span class="time">{{ date('Y-m-d',strtotime($_message->created_at)) }}</span>

						</span>

						<span class="message">

						{{ $_message->messageTitle }}

						</span>  

						</a>

					</li>

					@endforeach

				</ul>

			</li>

			@endif

			<li class="external">

				<a href="{{ url(env('ADMIN_FOLDER').'/'.'message/') }}">@lang('main.See all messages') <i class="m-fa fa-swapright"></i></a>

			</li>

		</ul>

		

	</li>

	<li class="dropdown language pull-left">

	@if (@$_adminLang == 'english')

		<a href="{{ url(env('ADMIN_FOLDER').'/'.'process/lang/en') }}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<img alt="english" src="{{ url('cpassets/img/en.png') }}" />

		<span class="username">English</span>

		<i class="fa fa-angle-down"></i>

		</a>

		<ul class="dropdown-menu">

			<li><a href="{{ url(env('ADMIN_FOLDER').'/'.'process/lang/ar') }}"><img alt="" src="{{ url('cpassets/img/ar.png') }}" /> Arabic</a></li>

		</ul>

	@else

		<a href="{{ url(env('ADMIN_FOLDER').'/'.'process/lang/ar') }}" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">

		<img alt="arabic" src="{{ url('cpassets/img/ar.png') }}" />

		<span class="username">Arabic</span>

		<i class="fa fa-angle-down"></i>

		</a>

		<ul class="dropdown-menu">

			<li><a href="{{ url(env('ADMIN_FOLDER').'/'.'process/lang/en') }}"><img alt="" src="{{ url('cpassets/img/en.png') }}" /> English</a></li>

		</ul>

	@endif

	</li>

	<!-- BEGIN USER LOGIN DROPDOWN -->

	<li class="dropdown user pull-left">

		<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
       
		<img alt="" src="{{ url('cpassets/img/avatar1_small.jpg') }}" /> 
       
		<span class="username">{{ $_adminUser->name }}</span>

		<i class="fa fa-angle-down"></i>

		</a>
       <!--<div id="account_info">-->
		<ul class="dropdown-menu">

			

			<li><a href="{{ url(env('ADMIN_FOLDER').'/'.'users/edit/'.$_adminUser->id) }}"><i class="fa fa-user"></i> @lang('main.Edit Profile')</a></li>

			<li><a href="{{ $appUrl }}"><i class="fa fa-calendar"></i> @lang('main.Website Interface')</a></li>

			<!-- <li><a href="inbox.html"><i class="fa fa-envelope"></i> My Inbox <span class="badge badge-important">3</span></a></li>

			<li><a href="#"><i class="fa fa-tasks"></i> My Tasks <span class="badge badge-success">8</span></a></li> -->

			<li class="divider"></li>

			<li><a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-desktop"></i> @lang('main.Full Screen')</a></li>

			<li><a href="{{ url(env('ADMIN_FOLDER').'/'.'process/lock') }}"><i class="fa fa-lock"></i> @lang('main.Lock Account')</a></li>

			<li><a href="{{ url(env('ADMIN_FOLDER').'/'.'process/logout') }}"><i class="fa fa-key"></i> @lang('main.Sign Out')</a></li>

		</ul>
        <!--</div>-->

	</li>

	<!-- END USER LOGIN DROPDOWN -->

	<!-- END USER LOGIN DROPDOWN -->

</ul>

<!-- END TOP NAVIGATION MENU -->
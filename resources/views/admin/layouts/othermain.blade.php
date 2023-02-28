<!DOCTYPE html>

<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->

<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->

<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->

<!-- BEGIN HEAD -->

<head>

	<meta charset="utf-8" />


	{{--<title>{!! $_setting['siteNameAr'] !!} | {!! $_pageTitle !!}</title>--}}


	<meta content="width=device-width, initial-scale=1.0" name="viewport" />

	<meta content="" name="description" />

	<meta content="Space Zone | sz4h.com" name="author" />

	@include('admin.partials.headinclude')

	@yield('custom_head_include')



</head>

<!-- END HEAD -->

<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<div class="header navbar navbar-fixed-top">

		<!-- BEGIN TOP NAVIGATION BAR -->

		<div class="navbar-inner">

			<div class="container-fluid">

				<!-- BEGIN LOGO -->

				 <?php
						if(@$_adminUser->clinic_id):
						$userClinic = DB::table('clinics')->where('id', $_adminUser->clinic_id)->first();
						if(@$userClinic->logo):

						?>


						<a class="brand pull-right" href="{{env('APP_URL')}}/admin" style="color:#fff;">

							<img alt="" src="{{ url('media/clinics/'.$userClinic->logo) }}" height="40" />

					    </a>
					 @endif
					 @endif

				<!-- END LOGO -->

				<!-- BEGIN RESPONSIVE MENU TOGGLER -->

				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">

				<img src="{{ url('cpassets/img/menu-toggler.png') }}" alt="" />

				</a>

				<!-- END RESPONSIVE MENU TOGGLER -->

				<!-- BEGIN TOP NAVIGATION MENU -->

				@include('admin.partials.topnav')

			</div>

		</div>

		<!-- END TOP NAVIGATION BAR -->

	</div>

	<!-- END HEADER -->

	<!-- BEGIN CONTAINER -->

	<div class="page-container">

		<!-- BEGIN SIDEBAR -->

		<div class="page-sidebar nav-collapse collapse">

			<!-- BEGIN SIDEBAR MENU -->

			<ul class="page-sidebar-menu">

				<li>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

					<div class="sidebar-toggler hidden-phone"></div>

					<!-- BEGIN SIDEBAR TOGGLER BUTTON -->

				</li>

				<li class="cnt hidden-tablet">

					<small class="last-time">{{-- Lang::get('main.Your Last Logged In') }} {{ date('d/m '.Lang::get('main.Your Last Logged In').' H:i', $_adminUser['lastSeen']) --}}</small>

				</li>

				<li>
					{{-- @include('admin.partials.search') --}}

				</li>

				@include('admin.partials.menu')

			</ul>

			<!-- END SIDEBAR MENU -->

		</div>

		<!-- END SIDEBAR -->

		<!-- BEGIN PAGE -->

		<div class="page-content">

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">

				<!-- BEGIN PAGE HEADER-->

				<div class="row-fluid">

					@include('admin.partials.breadcrumb')

					@yield('messages')

					@yield('contents')

					@yield('content')

				</div>

			</div>

		</div>

		<!-- END PAGE -->

	</div>

	<!-- END CONTAINER -->

	<!-- BEGIN FOOTER -->

	<div class="footer">

		<div class="footer-inner">

@php  date("Y"); @endphp

			 {!! COPYRIGHTS !!}

		</div>

		<div class="footer-tools">

			<span class="go-top">

			<i class="fa fa-angle-up"></i>

			</span>

		</div>

	</div>

	@include('admin.partials.foot')

	@yield('custom_foot')

</body>

<!-- END BODY -->

</html>
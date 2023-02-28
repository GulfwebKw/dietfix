<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>{{ $_setting['siteNameAr'] }} | {{ $_pageTitle }}</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="Space Zone | sz4h.com" name="author" />
	@include('admin.partials.headinclude')
	@yield('custom_head_include')

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
	<!-- BEGIN CONTAINER -->
	<div class="page-container2">
		<!-- BEGIN PAGE -->
		<div class="page-content2">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					@include('admin.partials.breadcrumb')
					@yield('messages')
					@yield('content')
				</div>
			</div>
		</div>
		<!-- END PAGE -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->

	@include('admin.partials.foot')
	@yield('custom_foot')
</body>
<!-- END BODY -->
</html>
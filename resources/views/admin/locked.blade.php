@extends('admin.layouts.login')
@section('custom_head_include')
	<link href="{{ szCp::siteLink('cpassets/css/pages/lock-rtl.css') }}" rel="stylesheet" type="text/css"/>
@stop
@section('custom_foot')
	<script src="{{ szCp::siteLink('cpassets/scripts/lock.js') }}"></script>      
	<script>
		jQuery(document).ready(function() {    
		});
	</script>   
@stop

@section('content')
	<div class="page-lock">

		<div class="page-logo">
			<a class="brand" href="{{ szCp::adminLink() }}">
			Admin Panel
			</a>
		</div>
		<div class="page-body">
			<img class="page-lock-img" src="{{ szCp::siteLink('cpassets/img/locked.png') }}" alt="">
			<div class="page-lock-info">
				<h1>{{ $lock['admin_name'] }}</h1>
				<span>{{ $lock['admin_email'] }}</span>
				<span><em>@lang('main.Locked')</em></span>
				<form class="form-search" method="post" action="{{ szCp::adminLink('process/locked') }}">
					<div class="input-append">
						<input type="text" class="m-wrap" name="password" placeholder="@lang('main.Password')">
						<button type="submit" class="btn blue icn-only" ><i class="m-icon-swapleft m-icon-white"></i></button>
					</div>
					<div class="relogin">
						<a href="{{ szCp::adminLink('process/logout') }}">@lang('main.Not') {{ $lock['admin_name'] }}؟</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	       
@stop
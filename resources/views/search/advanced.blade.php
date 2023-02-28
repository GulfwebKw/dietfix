@extends('layouts.main')
@section('custom_foot')
<script>
	$("#area1").chained("#province1");
	$("#area2").chained("#province2");
	$("#area3").chained("#province3");
</script>
@stop
@section('contents')
	
	<div id="search-blocks">
		<div class="col_one_third">
			<div class="ribbon"><div class="ribbon-content">{{ trans('main.Puzzled') }}</div></div>
			@include('search.school')
		</div>

		
		<div class="col_one_third">
			<div class="ribbon"><div class="ribbon-content">{{ trans('main.Fervent') }}</div></div>
			@include('search.club')
		</div>

		
		<div class="col_one_third col_last">
			<div class="ribbon"><div class="ribbon-content">{{ trans('main.Needy') }}</div></div>
			@include('search.teacher')
		</div>
	</div>

@stop
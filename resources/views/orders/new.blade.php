@extends('layouts.main')
@if ($package->period != 'one_time')
	@section('custom_head_include')
		{{ HTML::style('assets/bootstrap-daterangepicker/daterangepicker-bs3.css') }}
	@stop
	@section('custom_foot')
		{{ HTML::script('assets/bootstrap-daterangepicker/moment.min.js') }}
		{{ HTML::script('assets/bootstrap-daterangepicker/daterangepicker.js') }}
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.daterange').daterangepicker({
		        format: 'YYYY-MM-DD',
		        startDate: moment(),
                singleDatePicker: true,
        		showDropdowns: true
        		<?php /*
		        @if ($package->period == 'monthly')
		        endDate: moment().add(30, 'days'),
		        @elseif ($package->period == 'yearly')
		        endDate: moment().add(365, 'days'),
		        @endif
		        minDate: '{{ date('d-m-Y') }}',
		        maxDate: '{{ date('d-m-Y',strtotime('+2 years')) }}',
		        @if ($package->period == 'monthly')
		        dateLimit: { days: 30 },
		        @elseif ($package->period == 'yearly')
		        dateLimit: { days: 365 },
		        @endif
		        opens: 'left',
		        separator: ' to ',
		    }, function(start, end, label) {
		        console.log(start.toISOString(), end.toISOString(), label);
		        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
		        */ ?>
		    });
		});
		</script>
	@stop
@endif

@section('contents')
	{{ Form::open(['url' => url('orders/save') , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}
	
	{{ Form::hidden('package_id',$id) }}
	{{ Form::hidden('user_id',Auth::user()->id) }}
	{{ Form::hidden('status','unpaid') }}

	@if ($package->period != 'one_time')
	<div class="form-group">
		{{ Form::label('from', trans('main.From') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::text('from' , null , array('class' => 'form-control daterange')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	@endif
	<div class="form-group">
		{{ Form::label('email', trans('main.Email') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::email('email' , Auth::user()->email , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group">
		{{ Form::label('phone', trans('main.Phone') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::text('phone' , Auth::user()->phone , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group">
		{{ Form::label('address', trans('main.Address') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::textarea('address' , null , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group">
		{{ Form::label('details', trans('main.Details') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::textarea('details' , null , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group">
		{{ Form::label('total', trans('main.Total') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			<div class="form-control">{{ $package->price }}</div>

		</div>
	</div>
	<div class="clearfix"></div>

	<div class="form-actions col-md-4 col-md-push-4">
		{{ Form::button('<i class="fa fa-ok"></i> '.trans('main.Save'), array('type' => 'submit', 'class' => 'btn btn-primary btn-block')) }}

	</div>

	{{ Form::close() }}

@stop

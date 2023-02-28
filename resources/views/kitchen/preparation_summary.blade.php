@extends('layouts.main')
@section('custom_head_include')
{{ HTML::style('assets/datepicker/css/datepicker.css') }}
@stop
@section('custom_foot')
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
{{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}

<script>
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');

</script>
@stop

@section('contents')
	{{ Form::open(array('url' => url('kitchen_summary/get-'.$type), 'method' => 'get', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}

		{{ Form::label('date', trans('main.Date')) }}
		<div class="control-group form-group">
			<div class="controls">
				{{ Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date')) }}
			</div>
		</div>


		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit')) }}
		</div>

	{{ Form::close() }}

	<h2 class="text-center">{{ trans('main.'.ucfirst($type)) }}</h2>
	<h3 class="text-center">{{ Input::get('date') }}</h3>
	<div class="table-responsive">
		<table class="table table-hover">
			<tbody>
				@if (count($orders) <= 0)
					<tr>
						<td>{{ trans('main.No Results') }}</td>
					</tr>
				@else
					@foreach ($orders['meals'] as $mealName => $mealRecord)
					<tr style="page-break-before:always;display: block;">
						<th colspan="7"  class="text-center"><h3>{{ $mealName }}</h3></th>
					</tr>
					<tr>
						<th>{{ trans('main.Meal') }}</th>
						<th>{{ trans('main.Qty') }}</th>						
						<th>{{ trans('main.No salt - Local') }}</th>
						<th>{{ trans('main.No salt') }}</th>
						<th>{{ trans('main.Medium salt - Local') }}</th>
						<th>{{ trans('main.Medium salt') }}</th>
					</tr>
						@foreach ($mealRecord['orders'] AS $mealItemName => $mealItemRecord)
						<tr>
						
							<td>{{ $mealItemName }}</td>
							<td>{{ !isset($mealItemRecord['qty']) ? 2 : $mealItemRecord['qty']}}</td>							
							<td>{{ isset($mealItemRecord['no_salt_local']) ? $mealItemRecord['no_salt_local'] : '' }}</td>
							<td>{{ isset($mealItemRecord['no_salt']) ? $mealItemRecord['no_salt'] : '' }}</td>
							<td>{{ isset($mealItemRecord['medium_salt_local']) ? $mealItemRecord['medium_salt_local'] : '' }}</td>
							<td>{{ isset($mealItemRecord['medium_salt']) ? $mealItemRecord['medium_salt'] : '' }}</td>
						</tr>
						@endforeach
					@endforeach
			</tbody>
				@endif
		</table>
	</div>
@stop
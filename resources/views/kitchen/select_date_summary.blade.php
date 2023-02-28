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
	{{ Form::open(array('url' => url('kitchen_summary/get-'.$type), 'method' => 'get', 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}
		<div class="control-group form-group">
			{{ Form::label('date', trans('main.Date') , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				{{ Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date')) }}
			</div>
		</div>


		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i> '.trans('main.Search'), array('class' => 'btn btn-primary col-sm-6 col-sm-push-3','id' => 'submit','type' => 'submit')) }}
		</div>


	{{ Form::close() }}
@stop
@extends('admin.layouts.main')
@section('custom_foot')
@parent
{{ HTML::style('cpassets/plugins/bootstrap-datepicker/css/datepicker.css') }}
{{ HTML::script('cpassets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
<script>
	jQuery(document).ready(function($) {
		$(".form_date.date").datepicker({
		    format: "yyyy-mm-dd",
		    todayBtn: "linked",
		    orientation: "auto left",
		    autoclose: true,
		    todayHighlight: true
		});
		$('.control-group.col-sm-6').each(function (i) {
			var z = i + 1;
			if(z % 2 == 0) {
				$(this).after('<div class="clearfix"></div>');
			}
		});

	});

</script>
@stop

@section('content')


	{{ Form::open(array('class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form')) }}


	<?php $val = Input::old('from'); ?>

	@if (isset($val) && !empty($val))
		<?php $value = $val; ?>
	@else
		<?php $value = null; ?>
	@endif

	<div class="control-group form-group col-sm-6">
		{{ Form::label('from', trans('main.From') , array('class' => 'control-label col-sm-4')) }}
		<div class="controls col-sm-8">
			<div class="input-append date form_date">
			{{ Form::text ('from' , $value , array('class' => 'form-control form_date', 'data-date' => $value, 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years')) }}
			<span class="add-on"><i class="fa fa-calendar"></i></span>
			</div>
		</div>

	</div>




	<?php $val = Input::old('to'); ?>

	@if (isset($val) && !empty($val))
		<?php $value = $val; ?>
	@else
		<?php $value = null; ?>
	@endif

	
	<div class="control-group form-group col-sm-6">

		{{ Form::label('to', trans('main.To') , array('class' => 'control-label col-sm-4')) }}
		<div class="controls col-sm-8">
			<div class="input-append date form_date">
			{{ Form::text ('to' , $value , array('class' => 'form-control form_date', 'data-date' => $value, 'data-date-format' => 'yyyy-mm-dd' ,'data-date-viewmode' => 'years')) }}
			<span class="add-on"><i class="fa fa-calendar"></i></span>
			</div>
		</div>

	</div>

	<?php $val = Input::old('type'); ?>

	@if (isset($val) && !empty($val))
		<?php $value = $val; ?>
	@else
		<?php $value = null; ?>
	@endif

	<div class="control-group form-group col-sm-12">

		{{ Form::label('type', 'النوع' , array('class' => 'control-label col-sm-3')) }}
		<div class="controls col-sm-9">
			{{ Form::select('type', @$types , $value, array('class' => 'chosen')) }}
		</div>

	</div>



	<div class="form-actions">
		{{ Form::button('<i class="fa fa-check"></i> '.trans('main.View'), array('type' => 'submit', 'class' => 'btn blue')) }}
	</div>


	{{ Form::close() }}
@stop
@extends('layouts.main')
@section('contents')
	{{ Form::open(['url' => url('orders/save-item') , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}


	<div class="form-group">
		{{ Form::label('title', trans('main.Product Title') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::text('title' , null , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="form-group">
		{{ Form::label('url', trans('main.Url') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::text('url' , null , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="form-group">
		{{ Form::label('qty', trans('main.Qty') , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			{{ Form::selectRange('qty' , 1, 50, null , array('class' => 'form-control')) }}

		</div>
	</div>
	<div class="clearfix"></div>
	@if(!$options->isEmpty())
	@foreach($options as $option)
	@if(!$option->values->isEmpty())
	<div class="form-group">
		{{ Form::label('option_' . $option->id, $option->{'title'.ucfirst(LANG_SHORT)} , array('class' => 'col-md-3 text-left')) }}

		<div class="col-md-9">
			<select class="form-control" name="option[{{ $option->id }}]" id="option_{{ $option->id }}">
				@foreach($option->values as $value)
				<option value="{{ $value->id }}">{{ $value->{'title'.ucfirst(LANG_SHORT)} }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="clearfix"></div>
	@endif
	@endforeach
	@endif

	<div class="form-actions col-md-6 col-md-push-3">
		{{ Form::button('<i class="fa fa-plus"></i> '.trans('main.Add More'), array('type' => 'submit','name' => 'add_more','value' => '1', 'class' => 'btn btn-primary col-md-5')) }}

		{{ Form::button('<i class="fa fa-ok"></i> '.trans('main.Finish Order'), array('type' => 'submit', 'class' => 'btn btn-primary col-md-push-2 col-md-5')) }}

	</div>

	{{ Form::close() }}


@stop

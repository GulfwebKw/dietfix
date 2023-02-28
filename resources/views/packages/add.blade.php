@extends('layouts.main')

@section('contents')


	{{ Form::open(['url' => url('packages/save') , 'files' => true , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}
	
	@if ($item->id)
		{{ Form::hidden('id',$item->id) }}
	@endif
	{{ Form::hidden($rel,$id) }}
	{{ Form::hidden('top',$type) }}

	<div class="form-group">
		{{ Form::label('titleAr', trans('main.Arabic Title') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::text('titleAr' , $item->titleAr , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('titleEn', trans('main.English Title') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::text('titleEn' , $item->titleEn , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('detailsAr', trans('main.Arabic Details') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::textarea('detailsAr' , $item->detailsAr , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('detailsEn', trans('main.English Details') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::textarea('detailsEn' , $item->detailsEn , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('price', trans('main.Price') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::text('price' , $item->price , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('period', trans('main.Period') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::select('period' , $periods , $item->period , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('active', trans('main.Active') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			<input type="radio" name="active" @if ($item->active == 1)
				checked="checked"
			@endif value="1" id="active1"> {{ trans('main.Yes') }}
			<input type="radio" name="active" @if ($item->active == 0)
				checked="checked"
			@endif value="0" id="active1"> {{ trans('main.No') }}
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="form-group text-center">
		{{ Form::submit(trans('main.Save'), array('class' => 'btn btn-lg btn-primary btn-block tbutton')) }}
	</div>

	{{ Form::close() }}

@stop
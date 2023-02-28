@extends('layouts.main')
@section('custom_foot')
{{ HTML::script('assets/bootstrap-multiselect/dist/js/bootstrap-multiselect.js')}}
{{ HTML::style('assets/bootstrap-multiselect/dist/css/bootstrap-multiselect.css')}}
<script>
	$("#area_id").chained("#province_id");
	jQuery(document).ready(function($) {
        $('#activities').multiselect();
    });
</script>
@stop
@section('contents')


	{{ Form::open(['url' => url('clubs/save') , 'files' => true , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}
	
	@if ($item->id)
		{{ Form::hidden('id',$item->id) }}
	@endif
	{{ Form::hidden('user_id',Auth::user()->id) }}

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
		{{ Form::label('notesAr', trans('main.Arabic Notes') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::textarea('notesAr' , $item->notesAr , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('notesEn', trans('main.English Notes') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::textarea('notesEn' , $item->notesEn , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('logo', trans('main.Photo') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::file('logo') }}
		</div>
		@if ($item->logo)
			<div class="center">
			<img src="{{ url('resize?w=80&h=60&r=1&c=1&src=media/clubs/' . $item->logo) }}" alt="{{ $item->{'title'.LANG} }}">
			</div>
		@endif
	</div>
	<div class="form-group">
		{{ Form::label('email', trans('main.Email') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::email('email' , $item->email , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('phone', trans('main.Phone') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::text('phone' , $item->phone , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('period', trans('main.Period') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::select('period' , $periods , $item->period , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('province_id', trans('main.Province') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::select('province_id' , $all_provinces , $item->province_id , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('area_id', trans('main.Area') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			<select name="area_id" id="area_id" class="form-control chosen">
				<option value="">{{ trans('main.Area') }}</option>
				@foreach($areas as $area)
				<option value="{{ $area->id }}" @if ($item->area_id && $item->area_id == $area->id)
					selected="selected"
				@endif class="{{ $area->province_id }}">{{ $area->{'title'.LANG} }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="form-group">
		{{ Form::label('activities', trans('main.Activities') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			<select id="activities" name="activities[]" class="form-control" multiple="multiple">
				@foreach ($activities as $k => $activity)
			    <option value="{{ $k }}" @if (isset($item->activities) && in_array($k,array_fetch($item->activities->toArray(),'id'))) selected="selected" @endif >{{ $activity }}</option>
				@endforeach
			</select>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group text-center">
		{{ Form::submit(trans('main.Save'), array('class' => 'btn btn-lg btn-primary btn-block tbutton')) }}
	</div>

	{{ Form::close() }}

@stop
@extends('layouts.main')

@section('contents')


	{{ Form::open(['url' => url('teachers/activites-save') , 'files' => true , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}
	
	@if ($item->id)
		{{ Form::hidden('id',$item->id) }}
	@endif
	{{ Form::hidden('teacher_id',$teacher->id) }}
	<div class="form-group">
		{{ Form::label('subject_id', trans('main.Subject') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::select('subject_id' , $subjects , $item->subject_id , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="form-group">
		{{ Form::label('year_id', trans('main.Grade') , array('class' => 'col-md-3 text-left')) }}
		<div class="col-md-9">
			{{ Form::select('year_id' , $years , $item->year_id , array('class' => 'form-control')) }}
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="form-group text-center">
		{{ Form::submit(trans('main.Save'), array('class' => 'btn btn-lg btn-primary btn-block tbutton')) }}
	</div>

	{{ Form::close() }}

@stop
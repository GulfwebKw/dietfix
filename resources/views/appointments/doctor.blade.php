@extends('layouts.main')


@section('contents')
	<h3>{{ trans('main.My Appointments') }}</h3>
	{{-- <div class="col-md-10 col-md-push-1"> --}}
	{{ Form::open(['url' => 'appointments/manage','method' => 'get']) }}
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('main.ID') }}</th>
					<th>{{ trans('main.Client') }}</th>
					<th>{{ trans('main.Date') }}</th>
					<th>{{ trans('main.Time') }}</th>
					<th>{{ trans('main.Notes') }}</th>
					<th>{{ trans('main.Attendance') }}</th>
					<th></th>
				</tr>
				<tr>
					<th>{{ Form::text('id',Input::get('id'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('client_id',Input::get('client_id'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('date',Input::get('date'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('hour',Input::get('hour'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('notes',Input::get('notes'),['class' => 'form-control']) }}</th>
					<th></th>
					<th>{{ Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-default yellow')) }}</th>
				</tr>
			</thead>
			<tbody>
				@if ($confirmed->isEmpty())
					<tr>
						<td colspan="7">
							{{ trans('main.No Results') }}
						</td>
					</tr>
				@else
					@foreach ($confirmed as $appointment)
						<tr>
							<td>{{ $appointment->id }}</td>
							<td>{{ $appointment->user->username }}</td>
							<td>{{ $appointment->date }}</td>
							<td>{{ $appointment->hour }}</td>
							<td>{{ $appointment->notes }}</td>
							<td>{{ ($appointment->confirmed == 1) ? trans('main.Yes') : trans('main.No') }}</td>
							<td><a href="{{ url('appointments/edit/'.$appointment->id.'/'.$appointment->user->id) }}" data-id="{{ $appointment->id }}" class="btn btn-primary  btn-sm btn-block change"><i class="fa fa-edit"></i></a></td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	{{ Form::close() }}

@stop
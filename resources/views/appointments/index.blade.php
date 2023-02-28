@extends('layouts.main')


@section('contents')
	@if (Auth::user()->role_id != 1)
	<a href="{{ url('appointments/add/'.$user->id) }}" class="btn btn-success pull-right flip addnew"><i class="fa fa-plus"></i> {{ trans('main.Add') }}</a>
	@endif

	{{-- <div class="col-md-10 col-md-push-1"> --}}
	<div class="table-responsive">
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>{{ trans('main.ID') }}</th>
					<th>{{ trans('main.Doctor') }}</th>
					<th>{{ trans('main.Date') }}</th>
					<th>{{ trans('main.Time') }}</th>
					<th>{{ trans('main.Height') }}</th>
					<th>{{ trans('main.Weight') }}</th>
					<th>{{ trans('main.BMI') }}</th>
					<th>{{ trans('main.Files') }}</th>
					@if (Auth::user()->role_id != 1)
					<th>{{ trans('main.Notes') }}</th>
					@endif
					<th>{{ trans('main.Attendance') }}</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				@if ($appointments->isEmpty())
					<tr>
						<td colspan="11">
							{{ trans('main.No Results') }}
						</td>
					</tr>
				@else
					@foreach ($appointments as $appointment)
						<tr>
							<td>{{ $appointment->id }}</td>
							<td>{{ $appointment->doctor->username }}</td>
							<td>{{ $appointment->date }}</td>
							<td>{{ $appointment->hour }}</td>
							<td>{{ $appointment->height }}</td>
							<td>{{ $appointment->weight }}</td>
							<td>{{ $appointment->bmi }}</td>
							<td>
								@if (!$appointment->files->isEmpty())
									@foreach ($appointment->files as $file)
										<a href="{{ url('media/files/'.$file->file) }}" target="_blank">{{ trans('main.Download') }}</a><br>
									@endforeach
								@endif
							</td>
							@if (Auth::user()->role_id != 1)
							<td>{{ $appointment->notes }}</td>
							@endif
							<td>{{ ($appointment->confirmed == 1) ? trans('main.Yes') : trans('main.No') }}</td>
							<td>
							@if (Auth::user()->role_id != 1 || (Auth::user()->role_id == 1 && !in_array($appointment->date, [date('Y-m-d'),date('Y-m-d',strtotime('+1 day'))])))
							<a href="{{ url('appointments/edit/'.$appointment->id.'/'.$user->id) }}" data-id="{{ $appointment->id }}" class="btn btn-primary  btn-sm btn-block change"><i class="fa fa-edit"></i></a>
							@endif
							</td>
						</tr>
					@endforeach
				@endif
			</tbody>
		</table>
	</div>
	{{-- </div> --}}
	<div class="modal fade">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"></h4>
	      </div>
	      <div class="modal-body">
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary saveForm">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

@stop
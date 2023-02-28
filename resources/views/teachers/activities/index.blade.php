@extends('layouts.main')

@section('contents')

	<h3>{{ trans('main.Subjects') }}</h3>
	<div class="portlet box purple">
		<div class="portlet-body">
			<div class="table-responsive" id="sub1">
				<a href="{{ url('teachers/activites-add/'.$item->id) }}" class="pull-right flip btn btn-success green">{{ trans('main.Add') }}</a>
				<table class="table table-striped table-hover" id="sub-grid">
					<thead>
						<tr class="flip-content">
							<th width="50%">{{ trans('main.Subject') }}</th>
							<th width="10%">{{ trans('main.Year') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr class="waiting text-center" style="display:none;"><td colspan="3">{{ trans('main.Please Wait') }}</td></tr>
					@if(!$item->teachers_activities->isEmpty())
					@foreach($item->teachers_activities as $activity)
						<tr>
							<td>
								{{ $activity->subject->{'title'.LANG} }}

							</td>
							<td>
								{{ $activity->year->{'title'.LANG} }}

							</td>
							<td nowrap="nowrap">
								<a href="{{ url('/teachers/activites-edit/'.$activity->id).'/'.$item->id }}" class="btn btn-info popup">{{ trans('main.Edit') }}</a>
								<a href="{{ url('/teachers/activites-delete/'.$activity->id.'/'.$item->id) }}" onclick="return confirm('{{ trans('main.Are you sure?') }}');" class="btn btn-danger red">{{ trans('main.Delete') }}</a>
							</td>
						</tr>
					@endforeach
					@else
						<tr>
							<td colspan="4">{{ trans('main.No Results') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

@stop

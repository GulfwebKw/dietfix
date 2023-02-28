@extends('layouts.main')

@section('contents')

	<h3>{{ trans('main.Packages') }}</h3>
	<div class="portlet box purple">
		<div class="portlet-body">
			<div class="table-responsive" id="sub1">
				<a href="{{ url('packages/add/' . $type . '/'.$item->id) }}" class="pull-right flip btn btn-success green">{{ trans('main.Add') }}</a>
				<table class="table table-striped table-hover" id="sub-grid">
					<thead>
						<tr class="flip-content">
							<th width="30%">{{ trans('main.Arabic Title') }}</th>
							<th width="30%">{{ trans('main.English Title') }}</th>
							<th width="20%">{{ trans('main.Price') }}</th>
							<th width="20%">{{ trans('main.Period') }}</th>
							<th width="10%">{{ trans('main.Active') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr class="waiting text-center" style="display:none;"><td colspan="3">{{ trans('main.Please Wait') }}</td></tr>
					@if(!$packages->isEmpty())
					@foreach($packages as $package)
						<tr>
							<td>
								{{ $package->titleAr }}
							</td>
							<td>
								{{ $package->titleEn }}
							</td>
							<td>
								{{ $package->price }}
							</td>
							<td>
								{{ $periods[$package->period] }}
							</td>
							<td>
								{{ ($package->active == 1) ? trans('main.Yes') : trans('main.No') }}
							</td>
							<td nowrap="nowrap">
								<a href="{{ url('/packages/edit/'.$type.'/'.$package->id.'/'.$item->id) }}" class="btn btn-info popup">{{ trans('main.Edit') }}</a>
								<a href="{{ url('/packages/delete/'.$type.'/'.$package->id.'/'.$item->id) }}" onclick="return confirm('{{ trans('main.Are you sure?') }}');" class="btn btn-danger red">{{ trans('main.Delete') }}</a>
							</td>
						</tr>
					@endforeach
					@else
						<tr>
							<td colspan="6">{{ trans('main.No Results') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

@stop

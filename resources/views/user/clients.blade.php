@extends('layouts.main')


@section('contents')
	
	<h3>{{ trans('main.My Clients') }}</h3>
	<table class="table table-condensed table-hover table-bordered table-striped">
		<thead>
			<tr>
				<th>{{ trans('main.Total') }}</th>
				<th>{{ trans('main.Phone') }}</th>
				<th>{{ trans('main.Email') }}</th>
				<th>{{ trans('main.From') }}</th>
				<th>{{ trans('main.To') }}</th>
				<th>{{ trans('main.Package') }}</th>
				<th>{{ trans('main.User') }}</th>
				<th>{{ trans('main.Status') }}</th>
				<th>{{ trans('main.Manage') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (empty($items))
			<tr>
				<td colspan="6" class="text-center">{{ trans('main.No Results') }}</td>
			</tr>
			@else
			@foreach ($items as $item)
			@foreach ($item['packages'] as $package)
			@foreach ($package['orders'] as $order)
			<tr>
				<td>{{ $order['total'] }}</td>
				<td>{{ $order['phone'] }}</td>
				<td>{{ $order['email'] }}</td>
				<td>{{ $order['from'] }}</td>
				<td>{{ $order['to'] }}</td>
				<td>{{ $package['title'.LANG] }}</td>
				<td>{{ $order['user']['username'] }}</td>
				<td>{{ $statuses[$order['status']] }}</td>
				<td>
					<a href="{{ url('user/clients-view/'. $order['id']) }}" class="btn btn-xs btn-block btn-info">{{ trans('main.View') }}</a>
				</td>
			</tr>
			@endforeach
			@endforeach
			@endforeach
			@endif
		</tbody>
	</table>
	<div class="clearfix"></div>
	<div class="line"></div>


@stop
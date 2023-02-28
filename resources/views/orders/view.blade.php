@extends('layouts.main')

@section('contents')

	<table class="table table-bordered table-striped table-condensed table-hover order-details">
		<tbody>
			<tr>
				<th>{{ trans('main.ID') }}</th>
				<td>{{ $order->id }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Package') }}</th>
				<td>{{ $order->package->{'title'.LANG} }}</td>
			</tr>
			@if ($order->from)
			<tr>
				<th>{{ trans('main.From') }}</th>
				<td>{{ $order->from }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.To') }}</th>
				<td>{{ $order->to }}</td>
			</tr>
			@endif
			<tr>
				<th>{{ trans('main.Email') }}</th>
				<td>{{ $order->email }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Phone') }}</th>
				<td>{{ $order->phone }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Address') }}</th>
				<td>{{ $order->address }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Order Details') }}</th>
				<td>{{ $order->details }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Total') }}</th>
				<td>
					@price($order->total)<br>
					@if($order->status == 'unpaid')
					<a href="{{ url('orders/pay/'.$order->id) }}" class="btn btn-xs btn-success"><i class="fa fa-money"></i> {{ trans('main.Pay') }}</a>
					@endif
				</td>
			</tr>
			<tr>
				<th>{{ trans('main.Status') }}</th>
				<td>{{ $statuses[$order->status] }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Created Date') }}</th>
				<td>{{ $order->created_at }}</td>
			</tr>
		</tbody>
	</table>
	
	<table class="table table-bordered table-striped table-condensed table-hover order-details">
		<tbody>
			<tr>
				<td colspan="2"><a href="{{ url('orders/pay/'.$order->id) }}" class="btn success btn-default btn-lg">{{ trans('main.Pay') }}</a></td>
			</tr>
		</tbody>
	</table>
@stop
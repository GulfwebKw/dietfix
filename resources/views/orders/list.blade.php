@extends('layouts.main')

@section('contents')

	<h2 class="text-center">{{ trans('main.My Orders') }}</h2>
	<div class="table-responsive">
	<table class="table table-bordered table-hover">
		<thead>
			<tr class="flip-content">
				<th class="text-center">{{ trans('main.ID') }}</th>
				<th class="text-center">{{ trans('main.Price') }}</th>
				<th class="text-center">{{ trans('main.Status') }}</th>
				<th class="text-center">{{ trans('main.Created Date') }}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			@if(!$orders->isEmpty())
			@foreach($orders as $order)
			<tr>
				<td class="text-center">{{ $order->id }}</td>
				<td class="text-center">@price($order->total)</td>
				<td class="text-center">{{ $statuses[$order->status] }}</td>
				<td class="text-center">{{ $order->created_at }}</td>
				<td>
					<a href="{{ url('orders/view/'.$order->id) }}" class="btn btn-primary"><i class="fa fa-check"></i> {{ trans('main.View') }}</a>
					@if($order->status == 'unpaid' && $order->price != '0.00')
					<a href="{{ url('orders/pay/'.$order->id) }}" class="btn btn-success"><i class="fa fa-money"></i> {{ trans('main.Pay') }}</a>
					@endif
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td colspan="5">
					{{ trans('main.No Results') }}

				</td>
			</tr>
			@endif
		</tbody>
	</table>
	</div>

	{{ $orders->links() }}

@stop
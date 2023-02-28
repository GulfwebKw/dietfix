@extends('layouts.main')

@section('contents')

	<h2 class="text-center">{{ trans('main.Pay Order') }} #{{ $order->id }}</h2>
	{{ Form::open(['url' => url('orders/pay') , 'class' => 'form-horizontal form-bordered form-row-stripped spaceForm', 'role' => 'form']) }}

	<table class="table table-bordered table-striped table-condensed table-hover">
		<tbody>
			<tr>
				<th>{{ trans('main.ID') }}</th>
				<td>{{ $order->id }}</td>
			</tr>
			<tr>
				<th>{{ trans('main.Price') }}</th>
				<td>
					@if($order->status == 'unpaid')
					@price($order->total) <br>
					@endif
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">
					{{ trans('main.Are you sure you want to pay?') }}
				</td>
			</tr>
			<tr>
				<td colspan="2" class="text-center">
					{{ Form::hidden('order_id',$order->id) }}

					{{ Form::button('<i class="fa fa-money"></i> '.trans('main.Confirm'), array('type' => 'submit', 'class' => 'btn btn-primary')) }}

				</td>
			</tr>
		</tbody>
	</table>
	{{ Form::close() }}


@stop
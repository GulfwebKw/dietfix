@extends('layouts.main')



@section('contents')

<div class="portlet box red">

	<div class="portlet-title">

		<div class="caption"><i class="icon-money"></i>{{ trans('main.Payment Failed') }}</div>

	</div>

	<div class="portlet-body">

		<table class="table table-striped table-hover">

			<tbody>

				<tr>
					<th>{{ trans('main.Payment ID') }}</th>
					<td>{{ $payment->paymentID }}</td>
				</tr>
				<tr>
					<th>{{ trans('main.Result') }}</th>
					<td>{{ $payment->payment_data['result'] }}</td>
				</tr>
				<tr>
					<th>{{ trans('main.Post Date') }}</th>
					<td>{{ $payment->created_at }}</td>
				</tr>
				<tr>
					<th>{{ trans('main.Order ID') }}</th>
					<td><a href="{{ url('orders/view/'.$payment->order_id) }}">{{ $payment->order_id }}</a></td>
				</tr>
				<tr>
					<th>{{ trans('main.Amount') }}</th>
					<td>{{ $order->total }}</td>
				</tr>
				<tr>
					<th>{{ trans('main.Ref ID') }}</th>
					<td>{{ $payment->payment_data['payid'] }}</td>
				</tr>

			</tbody>

		</table>

	</div>

</div>

@stop
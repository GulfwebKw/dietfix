@extends('emails.layouts.main')

@section('contents')

<h2 style="color:#800">{{ trans('main.Payment Failed') }}</h2>

<table>
	<tr>
		<th>{{ trans('main.Payment ID') }}</th>
		<td>{{ $paymentID }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Result') }}</th>
		<td>{{ $result }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Post Date') }}</th>
		<td>{{ $created_at }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Order ID') }}</th>
		<td>{{ $order_id }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Amount') }}</th>
		<td>@price($order->total)</td>
	</tr>
	<tr>
		<th>{{ trans('main.Ref ID') }}</th>
		<td>{{ $payid }}</td>
	</tr>
</table>

@stop
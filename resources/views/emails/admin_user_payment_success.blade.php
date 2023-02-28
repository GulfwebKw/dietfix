@extends('emails.layouts.main')

@section('contents')

<h2>{{ trans('main.Payment Order') . ' #' . $order->id }}</h2>


<table>
	<tr>
		<th>{{ trans('main.Email') }}</th>
		<td>{{ $order->email }}</td>
	</tr>
	<tr>
		<th>{{ trans('main.Phone') }}</th>
		<td>{{ $order->phone }}</td>
	</tr>
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
		<td>{{ $order->id }}</td>
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
<p>
	More info : 
	<br>
	User:
	<a href="{{ url(ADMIN_FOLDER.'/users/edit/'.$user->id) }}">Click Here</a>
	<br>
	Order : <a href="{{ url(ADMIN_FOLDER.'/orders/edit/'.$order->id) }}">Click Here</a>
</p>


@stop
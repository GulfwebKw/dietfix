@extends('layouts.main')


@section('contents')
	
	<h3>{{ trans('main.View Order') . ' # ' . $order->id }}</h3>


	<table class="table table-condensed table-hover table-bordered table-striped">
		<tbody>
			<tr>
				<td>{{ trans('main.ID') }}</td>
				<td>{{ $order->id }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.Total') }}</td>
				<td>{{ $order->total }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.Phone') }}</td>
				<td><a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></td>
			</tr>
			<tr>
				<td>{{ trans('main.Email') }}</td>
				<td><a href="mailto:{{ $order->email }}">{{ $order->email }}</a></td>
			</tr>
			<tr>
				<td>{{ trans('main.From') }}</td>
				<td>{{ $order->from }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.To') }}</td>
				<td>{{ $order->to }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.Package') }}</td>
				<td><a href="{{ url('packages/edit/' . $pural . '/' . $order->package->id . '/' . $order->package->$rel->id) }}">{{ $order->package->{'title'.LANG} }}</a></td>
			</tr>
			<tr>
				<td>{{ trans('main.Submission') }}</td>
				<td><a href="{{ url($pural . '/edit/' . $order->package->$rel->id) }}">{{ $order->package->$rel->{'title'.LANG} }}</a></td>
			</tr>
			<tr>
				<td>{{ trans('main.Address') }}</td>
				<td>{{ $order->address }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.Details') }}</td>
				<td>{{ $order->details }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.Status') }}</td>
				<td>{{ $statuses[$order->status] }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.User') }}</td>
				<td>{{ $order->user->username }}</td>
			</tr>
		</tbody>
	</table>

	<div class="clearfix"></div>
	<div class="line"></div>


@stop
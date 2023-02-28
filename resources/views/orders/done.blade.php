@extends('layouts.main')
@section('contents')

	<h3 class="alert alert-info text-center block">
		{{ trans('main.Order Done, Your order id is') }} : #{{ $order->id }}

	</h3>
	<h3 class="alert alert-info text-center block">
		{{ trans('main.You are now a member in our website you can check your email for credentials') }}

	</h3>

@stop

@extends('emails.layouts.main')

@section('contents')

<h2>{{ trans('main.New Order') }}</h2>



<div>

	<p>

		{{ trans('main.New order for your package has been added') }} {{ $package->{'title'.LANG} }}

	</p>

	<p>

		{{ trans('main.Email') }}: {{ $order->email }}

	</p>

	<p>

		{{ trans('main.Phone') }}: {{ $order->phone }}

	</p>

	<p>

		{{ trans('main.Address') }}: {{ $order->address }}

	</p>

	<p>

		{{ trans('main.Details') }}: {{ $order->details }}

	</p>

	<p>

		{{ trans('main.More info') }} : 
		<br>
		{{ trans('main.Order') }} : <a href="{{ url('/orders/view/'.$order->id) }}">{{ trans('main.Click Here') }}</a>

	</p>

	<p>

		{{ trans('main.Thank you') }}

	</p>

</div>

@stop
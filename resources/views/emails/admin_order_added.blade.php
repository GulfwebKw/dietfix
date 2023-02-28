@extends('emails.layouts.main')

@section('contents')

<h2>A new order has been signed up</h2>



<div>

	<p>

		{{ trans('main.Username') }}: {{ ($user->username) ? $user->username : 'Facebook User'}}

	</p>

	<p>

		{{ trans('main.Email') }}: {{ $order->email }}

	</p>

	<p>

		{{ trans('main.Phone') }}: {{ $order->phone }}

	</p>

	<p>

		{{ trans('main.More info') }} : 
		<br>
		{{ trans('main.User') }} :
		<a href="{{ url(ADMIN_FOLDER.'/users/edit/'.$user->id) }}">{{ trans('main.Click Here') }}</a>
		<br>
		{{ trans('main.Order') }} : <a href="{{ url(ADMIN_FOLDER.'/orders/edit/'.$order->id) }}">{{ trans('main.Click Here') }}</a>

	</p>

	<p>

		{{ trans('main.Thank you') }}

	</p>

</div>

@stop
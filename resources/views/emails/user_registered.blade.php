@extends('emails.layouts.main')

@section('contents')

<h2>{{ trans('main.Thank you for your registeration please find the below info for your login') }} </h2>



<div>

	<p>

		{{ trans('main.Username') }}: {{$user->username}}

	</p>

	<p>

		{{ trans('main.Username') }}: {{$password}}

	</p>

	<p>

		{{ trans('main.You may login from') }} : <a href="{{ url('user/login') }}">{{ trans('main.Click Here') }}</a>

	</p>

	<p>

		{{ trans('main.Thank you') }}

	</p>

</div>

@stop
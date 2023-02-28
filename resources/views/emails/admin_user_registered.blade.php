@extends('emails.layouts.main')

@section('contents')

<h2>A new user has been signed up</h2>



<div>

	<p>

		Username: {{ ($user->username) ? $user->username : 'Facebook User'}}

	</p>

	<p>

		Password: {{ ($password) ? $password : 'Facebook User' }}

	</p>

	<p>

		More info : <a href="{{ url(ADMIN_FOLDER.'/users/edit/'.$user->id) }}">Click Here</a>

	</p>

	<p>

		Thank you

	</p>

</div>

@stop
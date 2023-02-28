@extends('layouts.main')

@section('contents')
	<!-- BEGIN FORGOT PASSWORD FORM -->
	{{ Form::open(array('url' => 'user/reset' ,'class' => 'col-md-6 col-md-push-3 form-vertical login-form')) }}
		<h2 class="form-title">{{ trans('main.Lost Password') }}</h2>
		<div class="clearfix"></div>
		<table class="table table-striped table-hover">
			<tr>
				<td>
					{{ trans('main.Email') }}
				</td>
				<td class="text-center">
					{{ Form::email('email') }}
				</td>
		 	</tr>
			<tr>
				<td>{{ trans('main.New Password') }}</td>
				<td class="text-center">{{ Form::password('password') }}</td>
			</tr>
			<tr>
				<td>{{ trans('main.New Password Repeat') }}</td>
				<td class="text-center">{{ Form::password('password_confirmation') }}</td>
			</tr>
			<tr>
				<tr>
				<td colspan="3" class="text-center">
					<p>{{ trans('main.Type your email here to restore your password') }}</p>
			 	</td>
		 	</tr>
			<tr>
				<td colspan="3" class="text-center">
					{{ Form::hidden('token', $token) }} 
					{{ Form::submit(trans('Reset'), array('class' => 'btn btn-primary tbutton')) }} 
			 	</td>
		 	</tr>
		</table>
	{{ Form::close() }}
	<!-- END FORGOT PASSWORD FORM -->
	
@stop

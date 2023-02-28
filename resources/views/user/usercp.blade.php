@extends('layouts.main')

@section('custom_foot')
<script>
	$("#area_id").chained("#province_id");
</script>
@stop

@section('contents')
	
	

	<!-- BEGIN LOGIN FORM -->
	<div class="row">
		<div class="col-md-6">

			{{ Form::open(array('url' => 'user/cp','class' => 'form-horizontal login-form')) }}

			<h3>{{ trans('main.User Control Panel') }}</h3>

			<div class="clearfix"></div>

			<div class="box2">
				@if ($user->role_id == 1)
					<div class="form-group">

					{{ Form::label('username', trans('main.Username') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						<div class="form-control">
						{{ $user->username }}
						</div>
						

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('email', trans('main.Email') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">
						<div class="form-control">

						{{ $user->email }}
						</div>

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('phone', trans('main.Phone') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">
						<div class="form-control">

						{{ $user->phone }}
						</div>

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('country_id', trans('main.Country') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">
						<div class="form-control">

						{{ $user->country->{'title'.LANG} }}
						</div>

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('province_id', trans('main.Province') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">
						<div class="form-control">

						{{ $user->province->{'title'.LANG} }}
						</div>

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('area_id', trans('main.Area') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">
						<div class="form-control">

						{{ $user->area->{'title'.LANG} }}
						</div>

					</div>

				</div>

				<div class="form-group">
					{{ Form::label('address', trans('main.Address') , array('class' => 'col-sm-3 control-label')) }}
					<div class="col-sm-9">
						<div class="form-control">
						{{ $user->address }}
					</div>
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('weight', trans('main.Weight') , array('class' => 'col-sm-3 control-label')) }}
					<div class="col-sm-9">
						<div class="form-control">
							{{ $user->weight }}
						</div>
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('height', trans('main.Height') , array('class' => 'col-sm-3 control-label')) }}
					<div class="col-sm-9">
						<div class="form-control">
							{{ $user->height }}
						</div>
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('bmi', trans('main.BMI') , array('class' => 'col-sm-3 control-label')) }}
					<div class="col-sm-9">
						<div class="form-control">
							{{ $user->bmi }}
						</div>
					</div>
				</div>

				<div class="form-group">
					{{ Form::label('salt', trans('main.Salt') , array('class' => 'col-sm-3 control-label')) }}
					<div class="col-sm-9">
						<div class="form-control">
						{{ $user->salt }}
						</div>
					</div>
				</div>
				@else
				<div class="form-group">

					{{ Form::label('username', trans('main.Username') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						@if($user->username)
						<div class="form-control">
						{{ $user->username }}
						</div>
						@else
						{{ Form::text('username', $user->username, array('class' => 'form-control')) }}
						@endif

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('email', trans('main.Email') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::email('email', $user->email, array('class' => 'form-control')) }}

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('phone', trans('main.Phone') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::text('phone', $user->phone, array('class' => 'form-control')) }}

					</div>

				</div>


				@endif
<?php /* 
				<div class="form-group">

					{{ Form::label('address', trans('main.Address') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::textarea('address', $user->address, array('class' => 'form-control')) }}

					</div>

				</div>
*/ ?>
				<div class="form-group text-center">

					{{ Form::submit(trans('main.Edit'), array('class' => 'btn btn-primary btn-inversed')) }}

				</div>
			</div>

			{{ Form::close() }}

		</div>

		<div class="col-md-6">

			{{ Form::open(array('url' => 'user/password','class' => 'form-horizontal login-form')) }}

			<h3>{{ trans('main.Password Change') }}</h3>

			<div class="clearfix"></div>

			<div class="box2">
				<div class="form-group">

					{{ Form::label('password', trans('main.Old Password') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::password('password', array('class' => 'form-control')) }}

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('newpassword', trans('main.New Password') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::password('newpassword', array('class' => 'form-control')) }}

					</div>

				</div>

				<div class="form-group">

					{{ Form::label('newpasswordconf', trans('main.New Password Repeat') , array('class' => 'col-sm-3 control-label')) }}

					<div class="col-sm-9">

						{{ Form::password('newpasswordconf', array('class' => 'form-control')) }}

					</div>

				</div>

				<div class="form-group text-center">

					{{ Form::submit(trans('main.Change'), array('class' => 'btn btn-primary btn-inversed')) }}

				</div>
			</div>
			{{ Form::close() }}

		</div>
	</div>

		

	

	<!-- END LOGIN FORM -->        

	

@stop


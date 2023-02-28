@extends('layouts.main')
@section('custom_foot')
<script>

	@if (Input::old('country_id'))
		$("#country_id").val({{ Input::old('country_id') }});
	@endif
	@if (Input::old('province_id'))
		$("#province_id").val({{ Input::old('province_id') }});
	@endif
	@if (Input::old('area_id'))
		$("#area_id").val({{ Input::old('area_id') }});
	@endif
	@if (Input::old('role_id'))
		$("#role_id").val({{ Input::old('role_id') }});
	@endif
	$("#area_id").chained("#province_id");
</script>
@stop

@section('contents')
	<!-- BEGIN LOGIN FORM -->
	

		<div class="clearfix"></div>
		<div class="col-md-6 col-md-push-3 f_blog">
			<h2 class="form-title">{{ trans('main.User Sign Up') }}</h2>
			{{ Form::open(array('url' => url('user/register'), 'class' => 'signup form-horizontal login-form', 'id' => 'signupForm')) }}

			<fieldset>
			  <div class="form-group">
			  <div class="input-group foruser">
			  		<span class="input-group-addon"><i class="fa fa-user"></i></span>
			      {{ Form::text('username', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Username'))) }}

			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group forpass">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>

			      {{ Form::password('password', array('class' => 'form-control middle', 'placeholder' => trans('main.Password'))) }}

			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group forpass">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
			      {{ Form::password('password_confirmation', array('class' => 'form-control middle', 'placeholder' => trans('main.Repeat Password'))) }}

			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group foremail">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>

			      {{ Form::email('email', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Email'))) }}

			  </div>
			  </div>
			  <div class="form-group">
			  <div class="input-group">
				<span class="input-group-addon"><i class="fa fa-phone"></i></span>

			      {{ Form::text('phone', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Phone'))) }}

			  </div>
			  </div>

			  <div class="form-group">
				  <div class="input-group">
					<span class="input-group-addon"><i class="fa fa-globe"></i></span>
						<select name="country_id" id="country_id" class="form-control chosen">
							<option value="">{{ trans('main.Country') }}</option>
							@foreach(App\Models\Country::all() as $country)
							<option value="{{ $country->id }}">{{ $country->{'title'.LANG} }}</option>
							@endforeach
						</select>


				  </div>
			  </div>


			  <div class="form-group">
					<div class="input-group">
						<span class="input-group-addon"><i class="fa fa-flag-o"></i></span>
						<select name="province_id" id="province_id" class="form-control chosen">
							<option value="">{{ trans('main.Province') }}</option>
							@foreach($provinces as $province)
								<option value="{{ $province->id }}">{{ $province->{'title'.LANG} }}</option>
							@endforeach
						</select>
					</div>
				</div>



			  <div class="form-group">
			  <div class="input-group">
			  	<span class="input-group-addon"><i class="fa fa-square"></i></span>
					<select name="area_id" id="area_id" class="form-control chosen">
						<option value="">{{ trans('main.Area') }}</option>
						@foreach($areas as $area)
						<option value="{{ $area->id }}" class="{{ $area->province_id }}">{{ $area->{'title'.LANG} }}</option>
						@endforeach
					</select>
			  </div>
			  </div>
			  <div class="form-group">
			    <div class="input-group">
			  	<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
					<select name="role_id" id="role_id" class="form-control chosen">
						<option value="1">{{ trans('main.Type') }}</option>
						@foreach (App\Models\Role::all() as $role)
							@if ($role->id != 10)
							<option value="{{ $role->id }}">{{ $role->{'roleName'.LANG} }}</option>
							@endif
						@endforeach
					</select>
			  </div>
			</div>



<?php /*
			  <div class="form-group">

			      {{ Form::text('address', null, array('class' => 'form-control middle', 'placeholder' => trans('main.Address'))) }}

			  </div>
			  */ ?>

			<div class="clearfix"></div>
			<div class="form-group text-center">
				{{ Form::submit(trans('main.Sign Up'), array('class' => 'btn btn-lg btn-primary btn-block tbutton')) }}
				<?php /*
				<div class="text-center" style="margin:10px;">{{ trans('main.OR') }}</div>
				<a href="{{ url('user/facebooklogin') }}" class="btn btn-default btn-block"><i class="fa fa-facebook"></i> {{ trans('main.Register With Facebook') }}</a>
				*/ ?>
				<br>
				<p class="text-center"><a href="{{ url('user/login') }}">{{ trans('main.Already have an account?') }}</a></p>
			</div>
			</fieldset>
			{{ Form::close() }}
		</div>
		
	
	<!-- END LOGIN FORM -->        
	
@stop

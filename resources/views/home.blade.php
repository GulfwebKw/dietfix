@extends('layouts.main')
@section('head_title')
@stop
@section('contents')
@if (Auth::user())
  <p class="text-center">{{ $_setting['welcome'.LANG] }}</p>
@else
<link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<div class="row">
  <div class="col-md-4">
    <!-- LOGIN FORM -->
    <div class="text-center" style="padding:50px 0">
        <div class="logo">{{ trans('main.Client Sign In') }}</div>
      <!-- Main Form -->
      <div class="login-form-1">
        {{ Form::open(array('url' => url('user/login'), 'class' => 'form-signin login-form text-left','id' => 'login-form')) }}
          <div class="login-form-main-message"></div>
          <div class="main-login-form">
            <div class="login-group">
              <div class="form-group">
                <label for="lg_username" class="sr-only">{{ trans('main.Username') }}</label>
                {{ Form::text('username', null, array('class' => 'form-control', 'id' => 'lg_username', 'placeholder' => trans('main.Username'))) }}
              </div>
              <div class="form-group">
                <label for="lg_password" class="sr-only">{{ trans('main.Password') }}</label>
                {{ Form::password('password', array('class' => 'form-control', 'id' => 'lg_password', 'placeholder' => trans('main.Password'))) }}
              </div>
              <div class="form-group login-group-checkbox">
                <input type="checkbox" id="lg_remember" name="remember">
                <label for="lg_remember">{{ trans('main.Remember Me') }}</label>
              </div>
            </div>
            {{ Form::button('<i class="fa fa-chevron-right"></i>', array('class' => 'login-button','type' => 'submit')) }}
          </div>
          <div class="etc-login-form">
            <p><a href="{{ url('user/forget') }}">{{ trans('main.Forgot password?') }}</a></p>
            <p><a href="{{ url('user/register') }}">{{ trans('main.Register for an account?') }}</a></p>
          </div>
        {{ Form::close() }}
      </div>
      <!-- end:Main Form -->
    </div>
  </div>
  <div class="col-md-4">
    <!-- LOGIN FORM -->
    <div class="text-center" style="padding:50px 0">
        <div class="logo">{{ trans('main.Dietation Sign In') }}</div>
      <!-- Main Form -->
      <div class="login-form-1">
        {{ Form::open(array('url' => url('user/login'), 'class' => 'form-signin login-form text-left','id' => 'login-form')) }}
          <div class="login-form-main-message"></div>
          <div class="main-login-form">
            <div class="login-group">
              <div class="form-group">
                <label for="lg_username" class="sr-only">{{ trans('main.Username') }}</label>
                {{ Form::text('username', null, array('class' => 'form-control', 'id' => 'lg_username', 'placeholder' => trans('main.Username'))) }}
              </div>
              <div class="form-group">
                <label for="lg_password" class="sr-only">{{ trans('main.Password') }}</label>
                {{ Form::password('password', array('class' => 'form-control', 'id' => 'lg_password', 'placeholder' => trans('main.Password'))) }}
              </div>
              <div class="form-group login-group-checkbox">
                <input type="checkbox" id="lg_remember" name="remember">
                <label for="lg_remember">{{ trans('main.Remember Me') }}</label>
              </div>
            </div>
            {{ Form::button('<i class="fa fa-chevron-right"></i>', array('class' => 'login-button','type' => 'submit')) }}
          </div>
          <div class="etc-login-form">
            <p><a href="{{ url('user/forget') }}">{{ trans('main.Forgot password?') }}</a></p>
            <p><a href="{{ url('user/register') }}">{{ trans('main.Register for an account?') }}</a></p>
          </div>
        {{ Form::close() }}
      </div>
      <!-- end:Main Form -->
    </div>
  </div>
  <div class="col-md-4">
    <!-- LOGIN FORM -->
    <div class="text-center" style="padding:50px 0">
        <div class="logo">{{ trans('main.Kitchen Sign In') }}</div>
      <!-- Main Form -->
      <div class="login-form-1">
        {{ Form::open(array('url' => url('user/login'), 'class' => 'form-signin login-form text-left','id' => 'login-form')) }}
          <div class="login-form-main-message"></div>
          <div class="main-login-form">
            <div class="login-group">
              <div class="form-group">
                <label for="lg_username" class="sr-only">{{ trans('main.Username') }}</label>
                {{ Form::text('username', null, array('class' => 'form-control', 'id' => 'lg_username', 'placeholder' => trans('main.Username'))) }}
              </div>
              <div class="form-group">
                <label for="lg_password" class="sr-only">{{ trans('main.Password') }}</label>
                {{ Form::password('password', array('class' => 'form-control', 'id' => 'lg_password', 'placeholder' => trans('main.Password'))) }}
              </div>
              <div class="form-group login-group-checkbox">
                <input type="checkbox" id="lg_remember" name="remember">
                <label for="lg_remember">{{ trans('main.Remember Me') }}</label>
              </div>
            </div>
            {{ Form::button('<i class="fa fa-chevron-right"></i>', array('class' => 'login-button','type' => 'submit')) }}
          </div>
          <div class="etc-login-form">
            <p><a href="{{ url('user/forget') }}">{{ trans('main.Forgot password?') }}</a></p>
            <p><a href="{{ url('user/register') }}">{{ trans('main.Register for an account?') }}</a></p>
          </div>
        {{ Form::close() }}
      </div>
      <!-- end:Main Form -->
    </div>
  </div>
</div>
@endif

    
@stop
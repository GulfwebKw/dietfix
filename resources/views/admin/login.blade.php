@extends('admin.layouts.login')



@section('custom_head_include')



	<link href="{{ url('cpassets/css/pages/login-soft-rtl.css') }}" rel="stylesheet" type="text/css"/>



@stop


@section('content')



	<!-- BEGIN LOGIN FORM -->


	<form class="form-vertical login-form" action="" method="post">


		@csrf



		<h3 class="form-title">@lang('main.Sign In')</h3>



		<div class="alert alert-error hide">



			<button class="close" data-dismiss="alert"></button>



			<span>@lang('main.Type your user name and password')</span>



		</div>



		<div class="control-group">



			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->



			<label class="control-label visible-ie8 visible-ie9">@lang('main.Username')</label>



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-user"></i>



					<input class="m-wrap placeholder-no-fix" type="text" placeholder="@lang('main.Username')" autocomplete="off" name="username"/>



				</div>



			</div>



		</div>



		<div class="control-group">



			<label class="control-label visible-ie8 visible-ie9">@lang('main.Password')</label>



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-lock"></i>



					<input class="m-wrap placeholder-no-fix" type="password" placeholder="@lang('main.Password')" autocomplete="off" name="password"/>



				</div>



			</div>



		</div>



		<div class="form-actions">



			<label class="checkbox">



			<input type="checkbox" name="remember" value="1"/> @lang('main.Remember me')



			</label>



			<button type="submit" class="btn blue pull-left">



			@lang('main.Login') <i class="m-icon-swapright m-icon-white"></i>



			</button>            



		</div>



		<div class="forget-password">



			<h4>@lang('main.Lost Password?')</h4>



			<p>



				@lang('main.Don\'t worry click <a href="javascript:;"  id="forget-password">here</a> to restore password')



			</p>



		</div>



	</form>



	<!-- END LOGIN FORM -->        



	<!-- BEGIN FORGOT PASSWORD FORM -->



	{{ Form::open( array('url' => url('user/remind'), 'class' => 'form-vertical forget-form') ) }}



		<h3>@lang('main.Lost Password?')</h3>



		<p>@lang('main.Type your email here to restore your password')</p>



		<div class="control-group">



			<div class="controls">



				<div class="input-icon left">



					<i class="icon-envelope"></i>



					{{ Form::email('email', null, array('class' => 'm-wrap placeholder-no-fix','placeholder' => trans('main.Email'))) }}



				</div>



			</div>



		</div>



		<div class="form-actions">



			<button type="button" id="back-btn" class="btn">



			<i class="m-icon-swapleft"></i> @lang('main.Back')



			</button>



			<button type="submit" class="btn blue pull-left">



			@lang('main.Send') <i class="m-icon-swapright m-icon-white"></i>



			</button>            



		</div>



	{{ Form::close() }}



	<!-- END FORGOT PASSWORD FORM -->



@stop



@section('copyright')



<div class="copyright">



	{{--{{ COPYRIGHTS }}--}}



</div>



@stop
@extends('layouts.main2')



@section('contents')

    <!-- BEGIN LOGIN FORM -->

    <div class="clearfix"></div>

    <div class="col-md-6 col-md-push-3 f_blog">

        <div class="section comment-wrap" id="section1">
            <div class="login-form">

                <h2 class="form-title">{{ trans('main.Sign In') }}</h2>

                {{ Form::open(array('url' => url('user/login'), 'class' => 'form-signin login-form')) }}

                <fieldset>
                    <?php if(!empty(Session::get('username'))){$unm=Session::get('username');}else{$unm=null;}?>
                    <div class="form-group">

                        <div class="input-group">

                            <span class="input-group-addon"><i class="fa fa-user"></i></span>

                            {{ Form::text('username', $unm, array('class' => 'form-control', 'placeholder' => trans('main.Username'))) }}

                        </div>

                    </div>

                    <div class="form-group">

                        <div class="input-group">

                            <span class="input-group-addon"><i class="fa fa-key"></i></span>

                            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('main.Password'))) }}

                        </div>

                    </div>

                    <a class="pull-left flip" href="{{ url('user/forget') }}">{{ trans('main.Forgot password?') }}</a>

                    <div class="checkbox pull-right flip">

                        <label><input name="remember" type="checkbox" value="Remember Me"> {{ trans('main.Remember Me') }}</label>

                    </div>

                    @if (isset($url))

                        {{ Form::hidden('uri', $url)}}

                    @endif

                    {{ Form::submit(trans('main.Login'), array('class' => 'btn btn-lg btn-primary btn-block tbutton')) }}

                    <?php /*

				<div class="text-center" style="margin:10px;">{{ trans('main.OR') }}</div>

			  	<a href="{{ url('user/facebooklogin') }}" class="btn btn-default btn-block"><i class="fa fa-facebook"></i> {{ trans('main.Login With Facebook') }}</a>

				*/ ?>

                    <br>

                    {{-- <p class="text-center"><a href="{{ url('user/register') }}">{{ trans('main.Register for an account?') }}</a></p> --}}

                </fieldset>

                {{ Form::close() }}

            </div>
        </div>


    </div>



    <!-- END LOGIN FORM -->



@stop


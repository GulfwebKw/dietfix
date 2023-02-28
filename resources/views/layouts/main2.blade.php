<!doctype html>
<html>
<head>

    <title>
        {!! $_setting['siteName'.LANG]['value'] !!} | {{ isset($title) ? $title : trans('main.Home') }}

    </title>
    <meta charset="utf-8">
    <meta name="description" content="{!! $_setting['metaDescription']['value'] !!}">
    <meta name="keywords" content="{!! $_setting['metaKeywords']['value'] !!}">
    <meta name="author" content="Website - by SZ4H | http://sz4h.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

    <link rel="shortcut icon" href="{{ url('media/fav.png') }}">
    @include('partials.headinclude')
    @yield('custom_head_include')
    @yield('otherheads')

</head>

<body class="no-transition {{-- stretched --}} {{ str_replace('/', ' ',request()->path()) }} sticky-responsive-menu @if(LANG=='Ar') rtl @else ltr @endif">
<?php
if(!empty($_REQUEST['logid']) && !empty($_REQUEST['username'])){
    $usernames= $_GET['username'];
    $userInfo = \DB::table('users')->where('username', $usernames)->first();
    if(!empty($userInfo->clinic_id)){
        $clinicInfo = \DB::table('clinics')->where('id', $userInfo->clinic_id)->first();
        session()->put('username',  $usernames);
        if(!empty($clinicInfo->logo)){
            session()->put('cliniclogo',  $clinicInfo->logo);
        }else{
            session()->put('cliniclogo',  '');
            session()->put('clinicname',  $clinicInfo->titleEn);
        }
    }
}
?>
<div class="container">
    <div class="row y-header">
        <div class="col-xs-12 col-sm-9 col-md-10 col-lg-11">


        </div>
        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-1"><ul class="list-inline">
                <li class="lang-item lang-item-40 lang-item-ar"><a hreflang="ar" href="{{ url('lang/ar') }}"><img src="{{ url('assets/images/ar.jpg') }}" title="العربية" alt="العربية"></a></li>
                <li class="lang-item lang-item-43 lang-item-en current-lang"><a hreflang="en" href="{{ url('lang/en') }}"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAALCAIAAAD5gJpuAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAHzSURBVHjaYkxOP8IAB//+Mfz7w8Dwi4HhP5CcJb/n/7evb16/APL/gRFQDiAAw3JuAgAIBEDQ/iswEERjGzBQLEru97ll0g0+3HvqMn1SpqlqGsZMsZsIe0SICA5gt5a/AGIEarCPtFh+6N/ffwxA9OvP/7//QYwff/6fZahmePeB4dNHhi+fGb59Y4zyvHHmCEAAAW3YDzQYaJJ93a+vX79aVf58//69fvEPlpIfnz59+vDhw7t37968efP3b/SXL59OnjwIEEAsDP+YgY53b2b89++/awvLn98MDi2cVxl+/vl6mituCtBghi9f/v/48e/XL86krj9XzwEEEENy8g6gu22rfn78+NGs5Ofr16+ZC58+fvyYwX8rxOxXr169fPny+fPn1//93bJlBUAAsQADZMEBxj9/GBxb2P/9+S/R8u3vzxuyaX8ZHv3j8/YGms3w8ycQARmi2eE37t4ACCDGR4/uSkrKAS35B3TT////wADOgLOBIaXIyjBlwxKAAGKRXjCB0SOEaeu+/y9fMnz4AHQxCP348R/o+l+//sMZQBNLEvif3AcIIMZbty7Ly6t9ZmXl+fXj/38GoHH/UcGfP79//BBiYHjy9+8/oUkNAAHEwt1V/vI/KBY/QSISFqM/GBg+MzB8A6PfYC5EFiDAABqgW776MP0rAAAAAElFTkSuQmCC" title="English" alt="English"></a></li>
            </ul>
        </div>
    </div>
    <div class="row y-nav">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">



            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-controls="bs-navbar" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                @if (Auth::user())
                    <?php $sdat = Auth::user()->membership_end;if(!empty($sdat)){
                        $futuresdate = strtotime(date("Y-m-d")); //Future date.
                        $timefromdb = strtotime($sdat);//source time
                        if($timefromdb>$futuresdate){
                            $timeleft = $futuresdate-$timefromdb;
                            $daysleft = round((($timeleft/24)/60)/60);}else{ $daysleft="0";} }?>
                @endif
                <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            {{--<li><a href="{{route('main_index')}}">{{ trans('main.Home') }}</a></li>--}}
                            <li><a href="{{ route('home_members') }}">{{ trans('main.Control Panel') }}</a></li>
                            @if (Auth::user() && Auth::user()->role_id == 1)
                                <li><a href="{{ url('menu') }}">{{ trans('main.Menu') }}</a></li>
                                <li><a href="{{ url('summary') }}">{{ trans('main.Summary') }}</a></li>
                                @if(Auth::user()->clinic->can_appointment)
                                    <li><a href="{{ url('appointments') }}">{{ trans('main.Appointment') }}</a></li>
                                @endif
                            @elseif (Auth::user() && Auth::user()->role_id == 2)
                                <li><a href="{{ url('menu/users') }}">{{ trans('main.Users') }}</a></li>
                                @if(Auth::user()->clinic->can_appointment)
                                    <li><a href="{{ url('appointments/manage') }}">{{ trans('main.Appointments') }}</a></li>
                                @endif
                            @elseif (Auth::user() && Auth::user()->role_id == 3)
                                <li><a href="{{ url('kitchen/portioning') }}">{{ trans('main.Portioning') }}</a></li>
                                <li><a href="{{ url('kitchen/preparation') }}">{{ trans('main.Preparation') }}</a></li>
                                <li><a href="{{ url('kitchen/packaging') }}">{{ trans('main.Packaging') }}</a></li>
                                <li><a href="{{ url('kitchen/labeling') }}">{{ trans('main.Labeling') }}</a></li>
                                <li><a href="{{ url('kitchen/delivery') }}">{{ trans('main.Delivery') }}</a></li>
                                <li><a href="{{ url('kitchen/pkreport') }}">{{ trans('main.Package Summary') }}</a></li>
                            @elseif (Auth::user() && Auth::user()->role_id == 10)
                                <li><a href="#" id="ulabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('main.User') }}</a>
                                    <ul class="dropdown-menu" aria-labelledby="ulabel">
                                        <li><a href="{{ url('menu') }}">{{ trans('main.Menu') }}</a></li>
                                        <li><a href="{{ url('appointments') }}">{{ trans('main.Appointment') }}</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" id="dlabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('main.Doctor') }}</a>
                                    <ul class="dropdown-menu" aria-labelledby="dlabel">
                                        <li><a href="{{ url('menu/users') }}">{{ trans('main.Users') }}</a></li>
                                        <li><a href="{{ url('appointments/manage') }}">{{ trans('main.Appointments') }}</a></li>
                                    </ul>
                                </li>
                                <li><a href="#" id="klabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('main.Kitchen') }}</a>
                                    <ul class="dropdown-menu" aria-labelledby="klabel">
                                        <li><a href="{{ url('kitchen/portioning') }}">{{ trans('main.Portioning') }}</a></li>
                                        <li><a href="{{ url('kitchen/preparation') }}">{{ trans('main.Preparation') }}</a></li>
                                        <li><a href="{{ url('kitchen/packaging') }}">{{ trans('main.Packaging') }}</a></li>
                                        <li><a href="{{ url('kitchen/labeling') }}">{{ trans('main.Labeling') }}</a></li>
                                        <li><a href="{{ url('kitchen/delivery') }}">{{ trans('main.Delivery') }}</a></li>
                                        <li><a href="{{ url('kitchen/pkreport') }}">{{ trans('main.Package Summary') }}</a></li>
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{ url('user/login') }}">{{ trans('main.Login') }}</a></li>
                                <li><a href="{{ url('user/forget') }}">{{ trans('main.Forgot password?') }}</a></li>
                            @endif

                            @if (Auth::user())
                                <li><a href="{{ url('user/cp') }}">{{ Auth::user()->username }}</a></li>
                                <li><a href="{{ url('user/logout') }}">{{ trans('main.Logout') }}</a></li>
                                @if (Auth::user() && Auth::user()->role_id == 1 && isset($daysleft))
                                    <?php  echo "<li><a>Remaining : ".$daysleft." Days</a></li>";?>
                                @endif
                            @endif
                        </ul>

                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>






        </div>
    </div>
    <div class="main-container">

        {{-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> --}}

        <div id="messages">
            @include('partials.messages')
        </div>
        @yield('contents')

        {{-- </div> --}}

    </div>
    <div class="clearfix"></div>
    @if(empty(Session::get('cliniclogo')) && empty(Session::get('clinicname')))
        <div class="row y-footer">

{{--            <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">--}}
{{--                <div class="socialmedia text-right">--}}
{{--                    @if ($_setting['socialSnap']['value'])--}}
{{--                        <a href="{{ $_setting['socialSnap']['value'] }}"><img src="{{ url('assets/images/snapchat.png') }}" alt=""></a>--}}
{{--                    @endif--}}
{{--                    @if ($_setting['socialInstagram']['value'])--}}
{{--                        <a href="{{ $_setting['socialInstagram']['value'] }}"><img src="{{ url('assets/images/instagram.png') }}" alt=""></a>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
@endif

<!-- end contaniner -->
</div>


@include('partials.foot_script')
</body>


</html>

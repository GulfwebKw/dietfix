<header class="motopress-wrapper header">
    <div class="container">
        <div class="row">
            <div class="span12" data-motopress-wrapper-file="" data-motopress-wrapper-type="header" data-motopress-id="5a55de724bae3">

                <div class="row">
                    <div class="span6" data-motopress-type="static" data-motopress-static-file="">
                        <!-- BEGIN LOGO -->
                        <div class="logo pull-left">
                            <a href="https://www.dietfix.com/" class="logo_h logo_h__img"><img src="https://www.dietfix.com/uploads/logo/logo-1403364071.png" alt="Diet Fix" title="Diet Fix"></a>
                        </div>
                        <!-- END LOGO -->
                    </div>
                    <div class="span6 hidden-phone " data-motopress-type="static" data-motopress-static-file="" style="margin-top:50px;">
                        <!-- BEGIN SEARCH FORM -->
                        <!-- END SEARCH FORM -->
                        <a href="http://www.apple.com" target="_blank" class="logo_h pull-right right_img"><img src="https://www.dietfix.com/play1.png" alt="Diet Fix" title=""></a>
                        <a href="http://play.google.com" target="_blank" class="logo_h pull-right right_img"><img src="https://www.dietfix.com/play2.png" alt="Diet Fix" title=""></a>            </div>

                </div>

                <div class="row">
                    <div class="span12" data-motopress-type="static" data-motopress-static-file="">
                        <!-- BEGIN MAIN NAVIGATION -->
                        <nav class="nav nav__primary clearfix">
                            <ul id="topnav" class="sf-menu sf-js-enabled">
                                <li><a href="{{route('main_index')}}" @if(url()->current()==route('main_index')) class="active" @endif >Home</a></li>
                                <li><a href="{{route('about')}}" @if(url()->current()==route('about')) class="active" @endif   >About us</a></li>
                                <li><a href="{{route('membership')}}" @if(url()->current()==route('membership')) class="active" @endif   >Membership</a></li>
                                <li><a href="{{route('examplesOfMeals')}}" @if(url()->current()==route('examplesOfMeals')) class="active" @endif >Meals Sample</a></li>
                                @guest
                                    <li><a href="{{route('user_login')}}">Login</a></li>
                                @endguest
                                 @auth
                                    <li><a href="{{route('home_members')}}">Login</a></li>

                                @endauth

                                <li><a href="{{route('contacts')}}" @if(url()->current()==route('contacts')) class="active" @endif>Contact us</a></li>

                            </ul>
                            <select class="select-menu">
                                <option value="#">Navigate to...</option>
                                <option value="{{route('main_index')}}" @if(url()->current()==route('main_index')) selected="selected" @endif >Home</option>
                                <option value="{{route('about')}}"  @if(url()->current()==route('about')) selected="selected" @endif>About us</option>
                                <option value="{{route('membership')}}"  @if(url()->current()==route('membership')) selected="selected" @endif >Membership</option>
                                <option value="{{route('examplesOfMeals')}}" @if(url()->current()==route('examplesOfMeals')) selected="selected" @endif  >Meals Sample</option>
                                    @guest
                                       <option value="{{route('user_login')}}">Login</option>
                                    @endguest
                                <option value="{{route('contacts')}}" @if(url()->current()==route('contacts')) selected="selected" @endif  >Contact us</option>

                            </select>
                        </nav><div class="pseudoStickyBlock" style="position: relative; display: block;"></div><!-- END MAIN NAVIGATION -->
                    </div>
                </div>

                <div id="loginframe" style="display:none;">

                    <form method="POST" action="{{route('post_member_login')}}" accept-charset="UTF-8" class="form-signin login-form">

                        <fieldset>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>

                                    <input class="form-control" placeholder="Username" name="username" type="text">

                                </div>

                            </div>

                            <div class="form-group">

                                <div class="input-group">

                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>

                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">

                                </div>

                            </div>

                            <a class="pull-left flip" href="https://www.dietfix.com/members/public/user/forget">Forgot password?</a>

                            <div class="checkbox pull-right flip">

                                <label><input name="remember" type="checkbox" value="Remember Me"> Remember Me</label>

                            </div>

                            <input name="uri" type="hidden" value="/">

                            <input class="btn btn-lg btn-primary btn-block tbutton" type="submit" value="Login">

                            <br>

                        </fieldset>

                    </form>



                </div>

            </div>

        </div>

    </div>

</header>

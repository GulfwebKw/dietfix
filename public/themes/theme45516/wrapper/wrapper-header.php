<?php /* Wrapper Name: Header */ ?>

<script>

	var direction = 'left';

	<?php if (ICL_LANGUAGE_CODE == 'ar'): ?>

		var direction = 'right';

	<?php endif ?>

</script>

	

<div class="row">

<?php dynamic_sidebar( 'y_language' ); ?>

    <div class="span6" data-motopress-type="static" data-motopress-static-file="static/static-logo.php">

    	<?php get_template_part("static/static-logo"); ?>



    </div>

    <div class="span6 hidden-phone " data-motopress-type="static" data-motopress-static-file="static/static-search.php">

    	<?php get_template_part("static/static-search"); ?>
        <a href="http://dietfix.com/" class="logo_h pull-right right_img">
            <img src="http://dietfix.com/play1.png" alt="Diet Fix" title="">
            <img src="http://dietfix.com/play2.png" alt="Diet Fix" title="">
        </a>
    </div>

</div>

<div class="row">

    <div class="span12" data-motopress-type="static" data-motopress-static-file="static/static-nav.php">

    	<?php get_template_part("static/static-nav"); ?>

    </div>

</div>

<div id="loginframe" style="display:none;">

	<form method="POST" action="http://dietfix.com/members/public/user/login-external" accept-charset="UTF-8" class="form-signin login-form">

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

		<a class="pull-left flip" href="http://dietfix.com/members/public/user/forget">Forgot password?</a>

		<div class="checkbox pull-right flip">

	    	<label><input name="remember" type="checkbox" value="Remember Me"> Remember Me</label>

	    </div>

	    			    	<input name="uri" type="hidden" value="/">

	    			  	<input class="btn btn-lg btn-primary btn-block tbutton" type="submit" value="Login">

					  	<br>

		</fieldset>

	</form>



</div>

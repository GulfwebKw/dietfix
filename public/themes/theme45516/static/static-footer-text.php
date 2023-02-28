<?php /* Static Name: Footer text */ ?>
<div id="footer-text" class="footer-text">
	<?php $myfooter_text = of_get_option('footer_text'); ?>
	
	<?php if($myfooter_text){?>
		<?php echo of_get_option('footer_text'); ?>
	<?php } else { ?>
		<a href="<?php echo home_url(); ?>/" title="<?php bloginfo('description'); ?>" class="site-name"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/footer_logo.png" /></a>&#169; <?php echo date("Y"); ?> | <a href="<?php echo home_url(); ?>/privacy-policy/" title="<?php echo theme_locals('privacy_policy'); ?>"><?php echo theme_locals("privacy_policy"); ?></a>
	<?php } ?>
	<?php if( is_front_page() ) { ?>
		<a rel="nofollow" href="http://www.grassrootskw.com" target="_blank">Grassroots</a> Design. 
	<?php } ?>
</div>
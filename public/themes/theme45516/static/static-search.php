<?php /* Static Name: Search */ ?>
<!-- BEGIN SEARCH FORM -->
<?php if ( of_get_option('g_search_box_id') == 'yes') { ?>  
	<div class="search-form search-form__h hidden-phone clearfix">
		<form id="search-header" class="navbar-form pull-right" method="get" action="<?php echo home_url(); ?>/" accept-charset="utf-8">
			<input type="text" name="s" class="search-form_it"> <input type="submit" value="<?php echo __('search', CURRENT_THEME); ?>" id="submit" class="search-form_is btn btn-primary">
		</form>
	</div>  
<?php } ?>
<!-- END SEARCH FORM -->
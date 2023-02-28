<?php /* Static Name: Social Networks */ ?>
<!-- Social Links -->
<ul class="social">
	<?php
		$social_networks = array("google", "twitter", "flickr", "facebook", "feed", "youtube", "vimeo");
		$i = 0;
		do {
			if(of_get_option($social_networks[$i]) != "") {
				echo '<li><a href="'.of_get_option($social_networks[$i]).'" title="'.$social_networks[$i].'" class="'.$social_networks[$i].'""><img src="'.of_get_option($social_networks[$i]."_icon").'" alt="'.$social_networks[$i].'"></a></li>';
			};
 			$i++;
		} while ($social_networks[$i]);
	?>
</ul>
<!-- /Social Links -->
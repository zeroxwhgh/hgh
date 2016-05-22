<?php
hide($content['comments']);
hide($content['links']);

global $base_root, $base_path;

?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div<?php print $content_attributes; ?>>
  	<div class=""><h2><?php print $node->title; ?></h2>
        
    <?php
	print '<span class="class"> - '._hotel_class($node->field_star[LANGUAGE_NONE][0]['value']).'</span>';
  
	if ($node->field_star) {
		$hotel_star = _hotel_star($node->field_star[LANGUAGE_NONE][0]['value']);
		for ($i = 0; $i < $hotel_star; $i++) {
			if ($i == 0) print '<span class="first star"></span>';
			else print '<span class="star"></span>';	
		}
	}
	?>
	
    <div class="clear"></div>
    
    <div class="inquire">
    	<div class="icon">
        	<a href="<?php
			$node_path = drupal_get_path_alias('node/'.$node->nid);
			print $base_path.'inquiry/'.$node_path.''; ?>"> </a>
        </div>
        <ul>
        	<li><?php print t('Send Us an Inquiry'); ?></li>
        </ul>
    </div>
    
    </div>
    <div class="hotel-content">        
        <div class="content">
    		<div class="overview-tab-content">
            	<?php
					print $node->body['und'][0]['value'];

				print '<div class="hotel-facilities">
				<div class="title">'.t('Facilities').'</div><ul class="">';
				foreach($node->field_facilities[LANGUAGE_NONE] as $item) {
					print '<li>'.$item['value'].'</li>';	
				}
				print '</ul></div>';
				?>
                
                <input type="hidden" name="lat" id="hotel-lat" value="<?php print $node->field_location[LANGUAGE_NONE][0]['latitude']; ?>" />
                <input type="hidden" name="long" id="hotel-long" value="<?php print $node->field_location[LANGUAGE_NONE][0]['longitude']; ?>" />
                 <div class="title"><?php print t('ADDRESS'); ?></div>
                  <div class="address"><?php print $node->field_address[LANGUAGE_NONE][0]['value']; ?></div>
                <?php
                if (isset($node->field_location[LANGUAGE_NONE])) {
                ?>
                <div class="title"><?php print t('LOCATION'); ?></div>
                <div id="hotel-gmap"></div>
                <?php } ?>
            </div>
            <div class="clear"></div>
            <div class="social">
				<?php
                print '
                <a class="facebook" href="http://www.facebook.com/sharer/sharer.php?u=' .$base_root . $base_path . drupal_get_path_alias($_GET['q']).'&src=sp" target="_blank">&nbsp;</a>
                <a class="twitter" href="https://twitter.com/intent/tweet?text=' . drupal_get_title() . '&url=' . ($base_root . $base_path . drupal_get_path_alias($_GET['q'])) . '" target="_blank">&nbsp;</a>
                <a class="gplus" href="https://plus.google.com/share?url=' . $base_root . $base_path . drupal_get_path_alias($_GET['q']) . '" target="_blank">&nbsp;</a>
				<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url='.$base_root.$base_path.drupal_get_path_alias($_GET['q']).'&title='.$node->title.'&summary='.strip_tags($node->body['und'][0]['summary']).'&source=HGH TRavel" target="_blank">&nbsp;</a>
                ';
                ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
  </div>
  <div class="inquire">
    <div class="icon">
        <a href="<?php
        $node_path = drupal_get_path_alias('node/'.$node->nid);
        print $base_path.'inquiry/'.$node_path.''; ?>"> </a>
    </div>
    <ul>
        <li><?php print t('Send Us an Inquiry'); ?></li>
    </ul>
</div>
</div>

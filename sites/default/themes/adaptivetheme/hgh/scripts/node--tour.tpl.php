<?php
hide($content['comments']);
hide($content['links']);

global $base_root, $base_path, $language;
//dpm($node);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <div<?php print $content_attributes; ?>>
  	<div class=""><h2><?php print $node->title; ?></h2></div>
	<div id="view-on-map" class="showmap">Show on Map</div>
  	<?php
  	/*
    <div class="inquire">
    	<div class="icon">
        	<a href="<?php
			$node_path = drupal_get_path_alias('node/'.$node->nid);
			print $base_path.'inquiry/'.$node_path; ?>"> </a>
        </div>
        <ul>
        	<li><?php print t('Inquire or tailor make this tour'); ?></li>
        </ul>
    </div>
    */
    ?>
    
    <div class="clearfix"></div>
    
    <div class="tour-content">
		<div class="tabs tab-title">
			<div class="text tab-selected tab-title" id="overview-tab-content"><?php print t('Overview');?></div>
   			<div class="text" id="itinerary-tab-content"><?php print t('Tour Program');?></div>
   			<div class="text" id="book-tab-content"><?php print t('Book This Tour');?></div>
   			<div class="text" id="customize-tab-content"><?php print t('Customize This Tour');?></div>

            <?php
			if (!empty($node->field_hotel)) {
				print '<div class="text" id="hotels-tab-content">'.t('Hotels').'</div>';
			} ?>
			<div class="clear"></div>
		</div>
        
        <div class="content">
    		<div class="overview-tab-content">
            	<?php
            		$tour_body = $node->body[LANGUAGE_NONE][0]['value'];
            		if(isset($node->body[$language->language][0]['value'])) $tour_body = $node->body[$language->language][0]['value'];
					print $tour_body;
				?>
                
                <?php
				if (isset($node->field_download_tour_detail[LANGUAGE_NONE][0]['fid'])) {
					print '<div class="download-tour-details"><a href="'.file_create_url($node->field_download_tour_detail[LANGUAGE_NONE][0]['uri']).'"> </a></div>';
				}
				?>
                
                <?php

                if (isset($node->field_price[LANGUAGE_NONE][0]['value'])) {
                	print '<div class="hgh-price">
						<div class="title">'.t('PRICE').'</div>
						<div>'.$node->field_price[LANGUAGE_NONE][0]['value'].'</div>';
						if (isset($node->field_price_note[LANGUAGE_NONE][0]['value'])) {
							print '<div><em>'.$node->field_price_note[LANGUAGE_NONE][0]['value'].'</em></div>';
						}

                	print '</div>';
                }

				print '<div class="hgh-difference">
				<div class="title">'.t('WHY YOU SHOULD BOOK THIS TOUR WITH US?').'</div><ul class="hgh-difference">';

				$the_hgh_difference = $node->field_the_hgh_difference[LANGUAGE_NONE];
				if (isset($node->field_the_hgh_difference[$language->language])) {
					$the_hgh_difference = $node->field_the_hgh_difference[$language->language];
				}

				foreach($the_hgh_difference as $item) {
					print '<li>'.$item['value'].'</li>';	
				}
				print '</ul></div>';
				?>
                
                <div class="itinerary-highlight contentSlidecontainer">
                    <div class="title"><?php print t('Itinerary Highlights'); ?></div>
                    <div class="prevcontainer">
                        <div class="prev"> </div>
                    </div>
                    <div class="itinerary-content contentSlidewindow">
                        <div class="contentSlidewrapper" data-slidesperpage="3">
                        <?php

                        foreach ($node->field_tour_day[LANGUAGE_NONE] as $tour_day) {
                            if ($tour_day['node']->field_hightlight[LANGUAGE_NONE][0]['value'] == 1) {
								
								$day_title = explode(' Day', $tour_day['node']->title);
								if (isset($day_title[1])) {
									$day_title = t('Day').' '.$day_title[1]; 
								} else {
									$day_title = str_replace($node->title . ' - ', '', $tour_day['node']->title);
								}
								
                                $thumbnail = image_style_url('tour_thumbnail', 
									$tour_day['node']->field_thumbnail[LANGUAGE_NONE][0]['uri']);
                                print '<div class="highlight-item contentSlide">
									<img src="'.$thumbnail.'" class="tour-thumbnail" />
									<div>'.$day_title.'</div></div>';
                            }
                        }
                        ?>
                        </div>
                    </div>
                    <div class="nextcontainer">
                        <div class="next"> </div>
                    </div>
                    <div class="clear"></div>
                    <div class="pagingcontainer"></div>
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
            <div class="itinerary-tab-content">
            	<?php
				$total = count($node->field_tour_day[LANGUAGE_NONE]);
				$index = 0;
				foreach ($node->field_tour_day[LANGUAGE_NONE] as $tour_day) {
					$thumbnail = image_style_url('tour_thumbnail', $tour_day['node']->field_thumbnail[LANGUAGE_NONE][0]['uri']);
					
					$day_title = explode(' Day', $tour_day['node']->title);
					if (isset($day_title[1])) {
						$day_title = 'Day '.$day_title[1]; 
					} else {
						$day_title = str_replace($node->title . ' - ', '', $tour_day['node']->title);
					}
								
					$klass = 'tour-day';
					if ($index == $total - 1) $klass = 'tour-day last';

					// content translate
					$day_summary = $tour_day['node']->body[LANGUAGE_NONE][0]['summary'];
					$day_body = $tour_day['node']->body[LANGUAGE_NONE][0]['value'];
					if (isset($tour_day['node']->body[$language->language][0]['summary'])) {
						$day_summary = $tour_day['node']->body[$language->language][0]['summary'];
					}

					if (isset($tour_day['node']->body[$language->language][0]['value'])) {
						$day_body = $tour_day['node']->body[$language->language][0]['value'];
					}

					print '<div class="'.$klass.'" id="tour-day-'.$tour_day['node']->nid.'">';
					print '<div class="title">'.$day_title.'</div>';
					print '<div class="info">
						<div class="thumbnail"><a class="itinerary-colorbox" rel="itinerary-colorbox" href="'.file_create_url($tour_day['node']->field_thumbnail[LANGUAGE_NONE][0]['uri']).'" title="'.$tour_day['node']->title.'"><img src="'.$thumbnail.'" class="day-thumbnail"/></a></div>
						<div class="detail">
							<span class="summary">'.$day_summary.'</span>';
							
					if ($day_body != "") {
						print '	<span class="teaser">'.$day_body.'</span>';
						print ' <span class="read-more"><a>'.t('Read More').'</a>';
					}
					print '</div></div>';
					
					$addition_note = $tour_day['node']->field_addition_note[LANGUAGE_NONE][0]['value'];

					if (isset($tour_day['node']->field_addition_note[$language->language][0]['value'])) {
						$addition_note = $tour_day['node']->field_addition_note[$language->language][0]['value'];
					}
					if (!empty($tour_day['node']->field_addition_note)) {
						print '<div class="note">'.$addition_note.'</div>';
					}
					
					print '</div>'; // tour-day
					$index++;
				}
				?>
                <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery(".itinerary-colorbox").colorbox({rel:'itinerary-colorbox'});
					});
				</script>
                <div class="clear"></div>
            </div>
			
			<?php 
			// ============================
			// New tabs content
			?>
			<div class="book-tab-content page-inquiry">
				<?php print drupal_render(drupal_get_form('inquire_form'));?>
			</div>


            <?php            
            if (!empty($node->field_hotel)) {
				print '<div class="hotels-tab-content">';
				foreach ($node->field_hotel[LANGUAGE_NONE] as $hotel) {
					$thumbnail = '';
					if (!empty($hotel['node']->field_thumbnail)) {
						$thumbnail = image_style_url('tour_thumbnail', $hotel['node']->field_thumbnail[LANGUAGE_NONE][0]['uri']);
					}
					
					$hotel_body = $hotel['node']->body[LANGUAGE_NONE][0]['value'];
					if (isset($hotel['node']->body[$language->language][0]['value'])) {
						$hotel_body = $hotel['node']->body[$language->language][0]['value'];
					}
					print '<div id="hotel-'.$hotel['node']->nid.'" class="tour-day"><span class="title">'.$hotel['node']->title.'</span>
					<div class="info">
						<div class="thumbnail"><img class="day-thumbnail" src="'.$thumbnail.'"></div>
						<div class="detail">
							<span class="summary">'.$hotel_body.'</span>
						</div>
					</div></div>';				
				}
            	print '<div class="clear"></div></div>';
			} ?>
        </div>
        <div class="clear"></div>
    </div>
    
	<?php
	
	$similar_tour = $last_viewed = false;
	if (count($node->field_similar_tour) > 0) {
		$similar_tour = true;	
	}
	
	if ($_SESSION['last_viewed']) {
		$last_viewed = true;
		if (count($_SESSION['last_viewed']) == 1) {
			if ($_SESSION['last_viewed'][0] == $node->nid) {
				$last_viewed = false;
			}
		}
	}
	
	if ($similar_tour || $last_viewed) {
		// similar tours exist		
		if ($similar_tour) {
			$similar_tours_nid = array();
			foreach($node->field_similar_tour[LANGUAGE_NONE] as $nid) {
				$similar_tours_nid[] = $nid['nid'];
			}
			
			$nodes = array();
			$nodes = node_load_multiple($similar_tours_nid);
		}
		
		print '<div class="featured-tours" id="similar-tours">
			<div class="tabs">';
		
		if ($similar_tour) {
			print '<div class="text tab-selected" id="similar-content">'.t('Similar Tours').'</div>';
		}
		
		if ($last_viewed) {
			if ($similar_tour) print '<div class="text" id="last-viewed-content">'.t('Your Recent Visits').'</div>';
			else print '<div class="text tab-selected" id="last-viewed-content">'.t('Your Recent Visits').'</div>';
		}
		
		print 	'<div class="clear"></div>
				</div><div class="content">';
		
		if ($similar_tour) {
			print '<div class="contentSlidecontainer similar-content">
				<div class="prevcontainer">
				<div class="prev" style="display: none;"> </div>
				</div>
				<div class="contentSlidewindow featured-content">
				<div class="contentSlidewrapper">';
			
			foreach ($nodes as $n) {
				$thumbnail = image_style_url('tour_thumbnail_medium', $n->field_thumbnail[LANGUAGE_NONE][0]['uri']);
				print '<div class="item contentSlide"><img src="'.$thumbnail.'" class="tour-thumbnail"/>
					<div class="tour-title">'.l($n->title, 'node/'.$n->nid).'</div></div>';
			}
			
			print '</div>'; // contentSlidewrapper
			print '</div>'; // featured-content
			print '<div class="nextcontainer"><div class="next"> </div>';
			print '</div>'; // contentSlidecontainer
			print '<div class="clear"></div><div class="pagingcontainer"></div><div class="clear"></div></div>';
		}
		
		// ================================
		// last viewed
		if ($last_viewed) {
			$last_viewed_nid = array();
			// do not display current node
			foreach ($_SESSION['last_viewed'] as $nid) {
				if ($nid != $node->nid) $last_viewed_nid[] = $nid;
			}
			
			$nodes = node_load_multiple($last_viewed_nid);
			
			$style = 'display:none';
			if (!$similar_tour) $style = 'display:block';
			print '<div class="contentSlidecontainer last-viewed-content" style="'.$style.'">';
			print '<div class="prevcontainer">
					<div class="prev" style="display: none;"> </div>
				</div>
				<div class="contentSlidewindow featured-content">
				<div class="contentSlidewrapper">';
				
			foreach ($nodes as $n) {
				if (!empty($n->field_thumbnail)) {
					$thumbnail = image_style_url('tour_thumbnail_medium', $n->field_thumbnail[LANGUAGE_NONE][0]['uri']);
					print '<div class="item contentSlide"><img src="'.$thumbnail.'" class="tour-thumbnail"/>
					<div class="tour-title">'.l($node->title, 'node/'.$node->nid).'</div></div>';
				}
			}
			
			print '</div>'; // contentSlidewrapper
			print '</div>'; // featured-content
			print '<div class="nextcontainer"><div class="next"> </div>';
			print '</div>'; // contentSlidecontainer
			print '<div class="clear"></div><div class="pagingcontainer"></div><div class="clear"></div></div>';
		}
		
		print '<div class="clear"></div></div>';
		print '</div>';	
	}
	?>
    
    
    <div id="gmap" data-nid="<?php print $node->nid; ?>" data-maptype="tours">
  </div>
  
	<?php
	/*
	<div class="inquire">
    	<div class="icon">
        	<a href="<?php
			$node_path = drupal_get_path_alias('node/'.$node->nid);
			print $base_path.'inquiry/'.$node_path.''; ?>"> </a>
        </div>
        <ul>
        	<li><?php print t('Inquire or tailor make this tour'); ?></li>
        </ul>
    </div>
    */
    ?>
</div>

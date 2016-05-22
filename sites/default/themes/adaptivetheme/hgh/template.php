<?php

/**
 * @file
 * Process theme data.
 *
 * Use this file to run your theme specific implimentations of theme functions,
 * such preprocess, process, alters, and theme function overrides.
 *
 * Preprocess and process functions are used to modify or create variables for
 * templates and theme functions. They are a common theming tool in Drupal, often
 * used as an alternative to directly editing or adding code to templates. Its
 * worth spending some time to learn more about these functions - they are a
 * powerful way to easily modify the output of any template variable.
 * 
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function and instance of "adaptivetheme_subtheme" to match
 *    your subthemes name, e.g. if your theme name is "footheme" then the function
 *    name will be "footheme_preprocess_hook". Tip - you can search/replace
 *    on "adaptivetheme_subtheme".
 * 2. Uncomment the required function to use.
 */

/**
 * Process variables for the html template.
 */
/* -- Delete this line if you want to use this function
function adaptivetheme_subtheme_process_html(&$vars) {
}
// */


/**
 * Override or insert variables for the page templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_process_page(&$vars) {
}
// */


/**
 * Override or insert variables into the node templates.
 */
function adaptivetheme_hgh_preprocess_node(&$node) {
	if ($node['type'] == 'tour') {
		if (isset($_SESSION['last_viewed'])) {
			if (!in_array($node['nid'], $_SESSION['last_viewed'])) {
				$_SESSION['last_viewed'][] = $node['nid'];
			}
		} else {
			$_SESSION['last_viewed'][] = $node['nid'];
		}
	}
}
/*
function adaptivetheme_subtheme_process_node(&$vars) {
}
*/


/**
 * Override or insert variables into the comment templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_comment(&$vars) {
}
function adaptivetheme_subtheme_process_comment(&$vars) {
}
// */


/**
 * Override or insert variables into the block templates.
 */
/* -- Delete this line if you want to use these functions
function adaptivetheme_subtheme_preprocess_block(&$vars) {
}
function adaptivetheme_subtheme_process_block(&$vars) {
}
// */

function adaptivetheme_hgh_js_alter(&$javascript) {
	// ==============================
	// this array must be the same with its on adaptivetheme_hgh_preprocess_html method
	$apply_fix = array('home', 'inquiry');
	// if (in_array(arg(0), $apply_fix) || (arg(0) == 'node' && is_numeric(arg(1)))) {
	if (arg(0) == 'admin' || arg(0) == "imce") {
	} else {
		// unset($javascript['misc/jquery.js']);
	}
}

/**
 * Preprocess variables for the html template.
 */
function adaptivetheme_hgh_preprocess_html(&$vars) {
	global $theme_key;
	$theme_path = drupal_get_path('theme', 'adaptivetheme_hgh');
	$apply_fix = array('home', 'inquiry');
	$options = array('group' => 100);
	
	if (arg(0) == "search") {
		 $vars['head_title'] = t('Search')." | HGH Travel";
	}
	
	// if (in_array(arg(0), $apply_fix) || (arg(0) == 'node' && is_numeric(arg(1)))) {
	if (arg(0) != 'admin') {
		// drupal_add_js($theme_path.'/scripts/jquery.min.js', $options);
		// colorbox
		drupal_add_js($theme_path.'/scripts/jquery.colorbox-min.js', $options);
		drupal_add_css($theme_path.'/css/colorbox.css', $options);
	}
	
	if (arg(0) == "inquiry") {
		$options = array('group' => -100);
		drupal_add_js($theme_path.'/scripts/jquery-ui/minified/jquery.ui.core.min.js', $options);
		drupal_add_js($theme_path.'/scripts/jquery-ui/minified/jquery.ui.widget.min.js', $options);
		drupal_add_js($theme_path.'/scripts/jquery-ui/minified/jquery.ui.datepicker.min.js', $options);
		drupal_add_css($theme_path.'/css/jquery-ui-themes/base/jquery.ui.all.css', $options);	
	}
	
	if (arg(0) == "tours") {
		$options = array('group' => -100);
		drupal_add_js($theme_path.'/scripts/jquery.tablesorter.min.js', $options);	
	}
	
	// ============================
	// ajax page
	$ajax_page = array('map-data');
	if (in_array(arg(0), $ajax_page)) {
		$vars['theme_hook_suggestions'][] = 'html__ajax';
	}
	
	$status = drupal_get_http_header("status");  
	if($status == "404 Not Found") {      
		$vars['theme_hook_suggestions'][] = 'html__404';
	}

	$q = $_GET['q'];
	if ($q == 'destinations') {
		$vars['classes_array'][] = 'destinations-main';
	}
}

function adaptivetheme_hgh_preprocess_page(&$vars) {
	global $base_path, $user;

	if (isset($_GET['language'])) {
    	$_SESSION['language'] = $_GET['language'];
 	}

	$theme_path = drupal_get_path('theme', 'adaptivetheme_hgh');
	$vars['breadcrumb'] = NULL;
	$home_nid = 80;	
	
	// ============================
	// redirect
	if(arg(0) == 'user' && is_numeric(arg(1))) {
		
	}
	
	if (isset($vars['node'])) {
		if ($vars['node']->nid != $home_nid && ($vars['node']->type == 'tour' || $vars['node']->type == 'hotel')) {
			$node = $vars['node'];
			$settings['nid'] = $node->nid;
			drupal_add_js(array('hgh_travel' => $settings), 'setting');
		}
	}
	
	// ============================
	// page template for content type
	if (!empty($vars['node'])) {
		// $vars['theme_hook_suggestions'][] = 'page--node--' . $vars['node']->type;
		$vars['theme_hook_suggestions'][] = 'page__' . $vars['node']->type;
	}
	
	/*
	$status = drupal_get_http_header("status");  
	if($status == "404 Not Found") {      
		$vars['theme_hook_suggestions'][] = 'page__404';
	}
	*/
	
	// ============================
	// ajax page
	$ajax_page = array('map-data');
	if (in_array(arg(0), $ajax_page)) {
		$vars['theme_hook_suggestions'][] = 'page__ajax';
	}
	
	// ============================
	// Page title
	$hide_title = array('home', 'destinations', 'tours', 'hotel', 'inquiry');
	if (in_array(arg(0), $hide_title) || (arg(0) == 'node' && arg(1) == $home_nid)) {
		$vars['title'] = false;	
	}
	
	if (isset($node)) {
		if ($node->type == 'hotel') {
			$vars['title'] = false;		
		}
	}
		
	if (arg(0) == "search" && arg(1) == "google") {
		$vars['title'] = t("HGH Seach Result");
	}
		
	// ============================
	// process page slider
	$type = 'home';
	
	// node detail
	if (arg(0) == 'node' && is_numeric(arg(1)) && arg(1) != $home_nid) {
		$type = 'node-detail';
	}
	
	// destinations
	if (arg(0) == 'destinations') {
		if (arg(1) == '') {
			$settings['nid'] = 'destinations';
			drupal_add_js(array('hgh_travel' => $settings), 'setting');
		}
		
		$type = 'none';
		if (arg(1)) $type = 'country';
		if (arg(2)) $type = 'city';
	}

	$vars['hidden_content'] = null;
	$vars['images_slider'] = _images_slider($type, $home_nid);
	
	// ============================
	// social block
	// <div class="follow-us">'.t('Follow us on').' </div>
	$vars['social_block'] = '
	<div class="social-icons">
		<a href="https://www.facebook.com/HGHTravel"><img src="'.$base_path.$theme_path.'/images/facebook.png" /></a>
		<a href="https://plus.google.com/107725983414564031853/posts"><img src="'.$base_path.$theme_path.'/images/gplus.png" /></a>
		<a href="https://twitter.com/HGHTravel"><img src="'.$base_path.$theme_path.'/images/twitter.png" /></a>		
		<a href="http://www.youtube.com/playlist?list=FLNHUCKCZkUHM-AdvbkMtLqA"><img src="'.$base_path.$theme_path.'/images/youtube.png" /></a>
		<a href="http://www.hghue.com/rss.xml"><img src="'.$base_path.$theme_path.'/images/rss.png" /></a>
		<a href="http://www.linkedin.com/company/hgh-travel-indochina"><img src="'.$base_path.$theme_path.'/images/linkedin.png" /></a>
	</div>
	';
	
	// ============================
	// hide notice messages
	if (!in_array('administrator', $user->roles)){
		// This has to be checked, could be something similar.
		if (isset($vars['messages'])) unset($vars['messages']);
	}
	
	// ============================
	// menu under banner
	// about pages
	$about_pages = array(
		32 => t("About Us"), 
		33 => t("How we do business"), 
		34 => t("Why choosing HGH Travel is the best choice for consumer"), 
		35 => t("Our locations"), 
		36 => t("Career")
	);
	
	if (isset($vars['node'])) {
		if (in_array($vars['node']->nid, array_keys($about_pages))) {
			$nid = explode('/', current_path());
			$current_nid = 0;
			if (is_numeric($nid[1])) {
				$current_nid = $nid[1];
			}
			
			$vars['menu_under'] = '<div class="item">';
			foreach($about_pages as $key => $value) {
				$node_path = drupal_get_path_alias('node/'.$key);
				if ($key == $current_nid) {
					$vars['menu_under'] .= '<a href="'.$base_path.$node_path.'" class="active"><span>'.$value.'</span></a>';
				} else $vars['menu_under'] .= '<a href="'.$base_path.$node_path.'" class=""><span>'.$value.'</span></a>';
			}
			
			$vars['menu_under'] .= '</div>';
		}
	}
	
	// destinations
	$country_array = array('vietnam', 'laos', 'thailand', 'myanmar', 'cambodia');
	if (arg(0) == "destinations") {
		
	} else if (arg(0) == "tours") {
		$vars['menu_under'] = '<div class="item">';	
		$vars['menu_under'] .= '<a href="'.$base_path.'destinations/vietnam" class="active"><span>'.t('VIETNAM').'</span></a>';
		$vars['menu_under'] .= '<a href="'.$base_path.'destinations/laos" class="active"><span>'.t('LAOS').'</span></a>';
		$vars['menu_under'] .= '<a href="'.$base_path.'destinations/cambodia" class="active"><span>'.t('CAMBODIA').'</span></a>';
		$vars['menu_under'] .= '<a href="'.$base_path.'destinations/myanmar" class="active"><span>'.t('MYANMAR').'</span></a>';
		$vars['menu_under'] .= '<a href="'.$base_path.'destinations/thailand" class="active"><span>'.t('THAILAND').'</span></a>';
		$vars['menu_under'] .= '</div>';	
	}

	dpm($vars);

	if (!empty($vars['page']['sidebar_first']) && !empty($vars['page']['sidebar_second'])) {
		$vars['content_column_class'] = ' class="col-sm-4"';
	}
	elseif (!empty($vars['page']['sidebar_first']) || !empty($vars['page']['sidebar_second'])) {
		$vars['content_column_class'] = ' class="col-sm-9 col-xs-12"';
	}

	else {
		$vars['content_column_class'] = ' class="col-sm-12"';
	}

	// ----------------
	// classes for bootstrap responsive theme
	// $vars['header_attributes_array']['class'][] = 'container';
	//$vars['branding_attributes_array']['class'][] = 'container';
	//$vars['site_slogan_attributes_array']['class'][] = 'col-md-4';

}

function _images_slider($type, $home_nid = NULL) {
	global $base_path, $language;
	$theme_path = drupal_get_path('theme', 'adaptivetheme_hgh');
	
	if ($type != "") $slider  = '<div id="images-slider"><div class="slider-container cycle-slideshow" data-cycle-pager-template="<li><a href=#>{{slideNum}}</a></li>">';
	else $slider = '';
	$exist = false;

	switch ($type) { 
		case 'home': {
			$slider .= '<div class="cycle-pager"></div>';
			$node_home = node_load($home_nid);

			if (isset($node_home->field_feature_images[LANGUAGE_NONE])) {
				foreach ($node_home->field_feature_images[LANGUAGE_NONE] as $key => $val) {
					if ($val['fid'] > 0) {
						if (isset($node_home->field_tour_link[LANGUAGE_NONE][$key])) {
							if (!isset($node_home->field_tour_link[LANGUAGE_NONE][$key]['node'])) {
								$n = node_load($node_home->field_tour_link[LANGUAGE_NONE][$key]['nid']);
								$title = $n->title;
								if (isset($n->title_field[$language->language][0]['value'])) {
									$title = $n->title_field[$language->language][0]['value'];
								}

								$slider .= '<img src="'.file_create_url($val['uri']).'" title="'.$title.'" path="'.
									$base_path.drupal_get_path_alias('node/'.$node_home->field_tour_link[LANGUAGE_NONE][$key]['nid']).'" />';
							} else {
								$slider .= '<img src="'.file_create_url($val['uri']).'" title="'.$node_home->field_tour_link[LANGUAGE_NONE][$key]['node']->title.'" path="'.
									$base_path.drupal_get_path_alias('node/'.$node_home->field_tour_link[LANGUAGE_NONE][$key]['nid']).'" />';
							}
						} else {
							$slider .= '<img src="'.file_create_url($val['uri']).'" title="" path="" />';
						}
						$exist = true;
					}
				}
			}
		} break;
				
		case 'country': {
			// ---------------------
			// get country term from argument
			$country_arg = arg(1);
			$country_arg_tid = 0;
			$country_term = taxonomy_get_term_by_name($country_arg);
			if (count($country_term) > 0) {
				foreach($country_term as $term) {
					if (strtolower($term->name) == $country_arg) {
						$country_arg_tid = $term->tid;
						break;
					}
				}
			}
						
			$query = "SELECT feature.entity_id, file.uri, n.title FROM field_data_field_feature feature
				INNER JOIN node n ON n.nid = feature.entity_id
				INNER JOIN field_data_field_feature_images fi ON fi.entity_id = feature.entity_id
				INNER JOIN file_managed file ON fi.field_feature_images_fid = file.fid
				INNER JOIN taxonomy_index ti ON ti.nid = n.nid
				INNER JOIN taxonomy_term_data td ON td.tid = ti.tid
				WHERE feature.field_feature_value = 1 AND n.type = 'tour'
				AND td.tid = $country_arg_tid ORDER BY RAND()";
				
			$result = db_query($query);
			$entity_id_exist = array();
			$total = 0;
			foreach ($result as $row) {
				//if (!in_array($row->entity_id, $entity_id_exist)) {
					$entity_id_exist[] = $row->entity_id;
					$slider .= '<img src="'.file_create_url($row->uri).'" title="'.$row->title.'" path="'.$base_path.drupal_get_path_alias('node/'.$row->entity_id).'" />';
					$total++;
				//}
				$exist = true;
				if ($total == 10) break;
			}
		} break;
		
		case 'city':
			// ---------------------
			// get country term from argument
			$city_arg = arg(2);
			$city_arg_tid = 0;
			$city_term = taxonomy_get_term_by_name($city_arg);
			if (count($city_term) > 0) {
				foreach($city_term as $term) {
					if (strtolower($term->name) == $city_arg) {
						$city_arg_tid = $term->tid;
						break;
					}
				}
			}
						
			$query = "SELECT feature.entity_id, file.uri, n.title FROM field_data_field_feature feature
				INNER JOIN node n ON n.nid = feature.entity_id
				INNER JOIN field_data_field_feature_images fi ON fi.entity_id = feature.entity_id
				INNER JOIN file_managed file ON fi.field_feature_images_fid = file.fid
				INNER JOIN taxonomy_index ti ON ti.nid = n.nid
				INNER JOIN taxonomy_term_data td ON td.tid = ti.tid
				WHERE feature.field_feature_value = 1 AND n.type = 'tour'
				AND td.tid = $city_arg_tid ORDER BY RAND()";
				
			$result = db_query($query);
			$entity_id_exist = array();
			$total = 0;
			foreach ($result as $row) {
				//if (!in_array($row->entity_id, $entity_id_exist)) {
					$entity_id_exist[] = $row->entity_id;
					$slider .= '<img src="'.file_create_url($row->uri).'" title="'.$row->title.'" path="'.$base_path.drupal_get_path_alias('node/'.$row->entity_id).'" />';
					$total++;
				//}
				
				$exist = true;
				if ($total == 10) {
					break;
				}
				
			}
		break;
		
		case 'node-detail': {
			$nid = arg(1);
			$query = "SELECT fi.field_feature_images_fid, fi.entity_id, file.uri FROM field_revision_field_feature_images fi
				INNER JOIN file_managed file ON fi.field_feature_images_fid = file.fid
				WHERE fi.entity_id = $nid AND fi.entity_type = 'node'";
			
			$result = db_query($query);			
			foreach ($result as $row) {
				$slider .= '<img src="'.file_create_url($row->uri).'" />';
				$exist = true;
			}
			
		} break;
				
		default: {
			$query = "SELECT feature.entity_id, file.uri, n.title FROM field_data_field_feature feature
				INNER JOIN node n ON n.nid = feature.entity_id
				INNER JOIN field_data_field_feature_images fi ON fi.entity_id = feature.entity_id
				INNER JOIN file_managed file ON fi.field_feature_images_fid = file.fid
				WHERE feature.field_feature_value = 1 AND n.type = 'tour'";
				
			$result = db_query($query);
			$entity_id_exist = array();
			$total = 0;
			
			foreach ($result as $row) {
				if (!in_array($row->entity_id, $entity_id_exist)) {
					$entity_id_exist[] = $row->entity_id;
					$slider .= '<img src="'.file_create_url($row->uri).'" title="'.$row->title.'" path="'.$base_path.drupal_get_path_alias('node/'.$row->entity_id).'" />';
					$total++;
				}
				
				if ($total == 10) {
					break;
				}
				$exist = true;
			}
		} break;
		
		case "none":
			$exist = true;
		break;
	}
	
	if (!$exist) {
		$slider .= _images_slider('');	
	}
	
	if ($type != "") $slider .= '</div></div>';	
	return $slider;
}

function _breadcrums($location = null, $node = null) {
	global $base_path;
	//dpm($node);
	
	if (!$location) {
		$location = arg(0);	
	}
	
	$html = '<div class="breadcrums">';
	switch($location) {
		case "node-tour": {
			if (!empty($node->nid)) {
				$country = taxonomy_term_load($node->field_primary_city[LANGUAGE_NONE][0]['taxonomy_term']->field_country[LANGUAGE_NONE][0]['tid'])->name;
				$html .= '<a class="item" href="'.$base_path.'">'.t('Home').'</a><span class="sign"></span>';
				$html .= '<a class="item" href="'.$base_path.'tours">'.t('Tours').'</a><span class="sign"></span>';
				$html .= '<a class="item" href="'.$base_path.'tours/'.strtolower($country).'">'.$country.'</a><span class="sign"></span>';
				$html .= '<a class="item">'.$node->title.'</a>';
			}
		} break;
		
		case "tours": {
			$html .= '<a class="item" href="'.$base_path.'">'.t('Home').'</a><span class="sign"></span>';
			$html .= '<a class="item">'.t('Explore Our Tours').'</a>';
		} break;
		
		case "destinations": {
			$html .= '<a class="item" href="'.$base_path.'">'.t('Home').'</a><span class="sign"></span>';
			$html .= '<a class="item">'.t('Destinations').'</a>';
			
			$country_arg = (arg(1) != "") ? str_replace('-', ' ', strtoupper(arg(1))) : '';
			$city_arg = (arg(2) != "") ? str_replace('-', ' ', strtoupper(arg(2))) : '';
			
			if ($country_arg) {
				$html .= '<span class="sign"></span>
				<a class="item" href="'.$base_path.'destinations/'.strtolower(arg(1)).'">'.$country_arg.'</a>';
			}
			
			if ($city_arg) {
				$html .= '<span class="sign"></span>
				<a class="item" href="'.$base_path.'destinations/'.strtolower(arg(1)).'/'.strtolower(arg(2)).'">'.$city_arg.'</a>';
			}
		} break;

		case "node-cruise":
			$html .= '<a class="item" href="'.$base_path.'">'.t('Home').'</a><span class="sign"></span>';
			$html .= '<a class="item" href="'.$base_path.'cruise">Cruise</a>';

			if (isset($node->field_cruise_type['und'][0]['tid'])) {
				$html .= '<span class="sign"></span>';
				if ($node->field_cruise_type['und'][0]['tid'] == 83) {
					$html .= '<a class="item" href="'.$base_path.'cruise/halong-bay-cruises">Halong Bay Cruises</a>';
				}

				if ($node->field_cruise_type['und'][0]['tid'] == 84) {
					$html .= '<a class="item" href="'.$base_path.'cruise/mekong-river-cruises">Mekong River Cruises</a>';
				}

				if ($node->field_cruise_type['und'][0]['tid'] == 85) {
					$html .= '<a class="item" href="'.$base_path.'cruise/hue-cruises">Sightseeing Cruises</a>';
				}
			}
		break;
	}
	
	$html .= '</div>
	<div class="clearfix"></div>';
	
	return $html;
}

/**
 * teaser()
 * 
 * Accepts types: words or chars
 * 
 * Example Usage: echo teaser($really_long_string_with_no_html, 150, 'chars');
 * 
 * @param mixed $str
 * @param integer $num
 * @param string $type
 * @return
 */
function short_teaser($str, $num = 200, $type = 'chars') {
        $chunks = explode(' ', $str);
        if($type == 'chars' && strlen($str) <= $num) {
            return $str;
        } elseif($type == 'words' && count($chunks) <= $num) {
			return $str;
        }
        $ret = '';
        $i = 0;        
        foreach($chunks as $chunk) {
			if($type == 'chars' && strlen($ret.' '.$chunk) > $num) {
					break;
			} elseif($type == 'words' && $i == $num) {
					break;
			}
			$ret .= ' '.$chunk;
			$i++;
        }
        return trim($ret . '...');
}

function _get_share_buttons($node = null, $clear = true) {
	global $base_root, $base_path;
	$url = $base_root . $base_path . drupal_get_path_alias($_GET['q']);
	$output =
	'<div class="social-links-wrapper">
		 <div class="social-links">
			<a class="facebook" href="http://www.facebook.com/sharer/sharer.php?u=' .$url.'&src=sp" target="_blank">&nbsp;</a>
			<a class="twitter" href="https://twitter.com/intent/tweet?text=' . $node->title . '&url=' . GetGooglLink($url) . '" target="_blank">&nbsp;</a>
			<a class="gplus" href="https://plus.google.com/share?url=' . $base_root . $base_path . drupal_get_path_alias($_GET['q']) . '" target="_blank">&nbsp;</a>
			<a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url='.$base_root.$base_path.drupal_get_path_alias($_GET['q']).'&title='.$node->title.'&summary='.strip_tags($node->body['und'][0]['summary']).'&source=HGH TRavel" target="_blank">&nbsp;</a>
		</div>
	</div>';
	
	if ($clear) $output .= '<div class="clear"></div>';
	return $output;
}

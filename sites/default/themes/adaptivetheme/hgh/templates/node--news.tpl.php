<?php
hide($content['comments']);
hide($content['links']);

global $base_root, $base_path, $language;
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
    <div<?php print $content_attributes; ?>>
        <div class=""><h2><?php print $node->title; ?></h2></div>
        <?php
		if ($node->field_thumbnail[LANGUAGE_NONE][0]['fid'] > 0) {
	        $thumbnail = image_style_url('tour_thumbnail', $node->field_thumbnail[LANGUAGE_NONE][0]['uri']);
			print '<div class="thumbnail"><img src="'.$thumbnail.'" class="news-thumbnail"/></div>';
		}
        ?>
        <div class="author"><?php print $node->field_author['und'][0]['value']; ?></div>
        <div class="social">
        	<?php print _get_share_buttons($node, false); ?>
        </div>
        <div class="news-content">
        <?php
        $body = $node->body['und'][0]['value'];
        if (isset($node->body[$language->language][0]['value'])) {
         $body = $node->body[$language->language][0]['value'];
        }
        print $body;
        ?>
        <span class="source"><?php print '<a target="_blank" href="'.$node->field_source['und'][0]['url'].'">'.$node->field_source['und'][0]['title'].'</a>'; ?></span>
        </div>
    </div>
</div>

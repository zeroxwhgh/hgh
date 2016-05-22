<?php

/**
 * @file
 * Main view template.
 *
 * Variables available:
 * - $classes_array: An array of classes determined in
 *   template_preprocess_views_view(). Default classes are:
 *     .view
 *     .view-[css_name]
 *     .view-id-[view_name]
 *     .view-display-id-[display_name]
 *     .view-dom-id-[dom_id]
 * - $classes: A string version of $classes_array for use in the class attribute
 * - $css_name: A css-safe version of the view name.
 * - $css_class: The user-specified classes names, if any
 * - $header: The view header
 * - $footer: The view footer
 * - $rows: The results of the view query, if any
 * - $empty: The empty text to display if the view is empty
 * - $pager: The pager next/prev links to display, if any
 * - $exposed: Exposed widget form/info to display
 * - $feed_icon: Feed icon to display, if any
 * - $more: A link to view more, if any
 *
 * @ingroup views_templates
 */
?>
<div class="<?php print $classes; ?>">
  <?php print render($title_prefix); ?>
  <?php if ($title): ?>
    <?php print $title; ?>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
    <div class="view-header">
      <?php if ($header): ?>
      <?php print $header; ?>
      <?php endif; ?>
      <?php

		$halong = taxonomy_term_load(83);
		$hue = taxonomy_term_load(85);
		$mekong = taxonomy_term_load(84);

		$halong_uri = file_create_url($halong->field_thumbnail['und'][0]['uri']);
		$hue_uri = file_create_url($hue->field_thumbnail['und'][0]['uri']);
		$mekong_uri = file_create_url($mekong->field_thumbnail['und'][0]['uri']);

		print '
		<div class="our-hgh">
			<div class="">
        <div class="first-block">
    			<a href="/cruise/halong-bay-cruises">
    				<div class="thumbnail"><img style="width: 200px; height: 100px;" src="'.$halong_uri.'" alt=""></div>
    				<div class="intro">'.$halong->description.'</div>
    			</a>
        </div>

        <div class="third-block">
    			<a href="/cruise/mekong-river-cruises">
    				<div class="thumbnail"><img style="width: 200px; height: 100px;" src="'.$mekong_uri.'" alt=""></div>
    				<div class="intro">'.$mekong->description.'</div>
    			</a>
        </div>

        <div class="second-block">
          <a href="/cruise/hue-cruises">
            <div class="thumbnail"><img style="width: 200px; height: 100px;" src="'.$hue_uri.'" alt=""></div>
            <div class="intro">'.$hue->description.'</div>
          </a>
        </div>
        
			</div>
			<div class="clearfix"></div>
		</div>';
		?>
		
    </div>
  

  <?php if ($exposed): ?>
    <div class="view-filters">
      <?php print $exposed; ?>
    </div>
  <?php endif; ?>

  <?php if ($attachment_before): ?>
    <div class="attachment attachment-before">
      <?php print $attachment_before; ?>
    </div>
  <?php endif; ?>

  <?php if ($rows): ?>
    <div class="view-content">
      <?php print $rows; ?>
    </div>
  <?php elseif ($empty): ?>
    <div class="view-empty">
      <?php print $empty; ?>
    </div>
  <?php endif; ?>

  <?php if ($pager): ?>
    <?php print $pager; ?>
  <?php endif; ?>

  <?php if ($attachment_after): ?>
    <div class="attachment attachment-after">
      <?php print $attachment_after; ?>
    </div>
  <?php endif; ?>

  <?php if ($more): ?>
    <?php print $more; ?>
  <?php endif; ?>

  <?php if ($footer): ?>
    <div class="view-footer">
      <?php print $footer; ?>
    </div>
  <?php endif; ?>

  <?php if ($feed_icon): ?>
    <div class="feed-icon">
      <?php print $feed_icon; ?>
    </div>
  <?php endif; ?>

</div><?php /* class view */ ?>

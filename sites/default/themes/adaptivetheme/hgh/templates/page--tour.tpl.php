<?php
global $language;
?>

<div id="page-wrapper" class="page-<?php print $node->type;?>">
  <div id="page" class="<?php print $classes; ?>">
    <?php if($page['leaderboard']): ?>
    <div id="leaderboard-wrapper">
      <div class="container clearfix"> <?php print render($page['leaderboard']); ?> </div>
    </div>
    <?php endif; ?>
    <div id="header-wrapper">
      <div class="container clearfix"> <?php print $images_slider; ?>
        <header<?php print $header_attributes; ?>>
          <?php if ($site_logo || $site_name || $site_slogan): ?>
          <!-- start: Branding -->
          <div<?php print $branding_attributes; ?>>
            <?php if ($site_logo): ?>
            <div id="logo"> <?php print $site_logo; ?> </div>
            <?php if ($site_slogan): ?>
            <span<?php print $site_slogan_attributes; ?>><?php print $site_slogan; ?></span>
            <?php endif; ?>
            <?php endif; ?>
            
            <!-- Navigation elements --> 
            <?php print render($page['menu_bar']); ?>
            <?php if ($primary_navigation): print $primary_navigation; endif; ?>
            <?php if ($secondary_navigation): print $secondary_navigation; endif; ?>
            <?php print render($page['header']); ?>
            <?php if ($site_name): ?>
            <!-- start: Site name and Slogan hgroup -->
            <hgroup<?php print $hgroup_attributes; ?>>
              <?php if ($site_name): ?>
              <h1<?php print $site_name_attributes; ?>><?php print $site_name; ?></h1>
              <?php endif; ?>
            </hgroup>
            <!-- /end #name-and-slogan -->
            <?php endif; ?>
          </div>
          <!-- /end #branding -->
          <?php endif; ?>
        </header>
      </div>
    </div>
    <div class="clearfix"></div>
    <?php if ($breadcrumb): ?>
    <div id="breadcrumb-wrapper">
      <div class="container clearfix"> <?php print $breadcrumb; ?> </div>
    </div>
    <?php endif; ?>
    <div id="captions-wrapper" class="shadow">
      <div class="pagination-slider"></div>
      <div class="caption">
      	<div id="view-on-map" class="showmap"></div>
		<?php
        $fast_fact = $node->field_fast_fact[LANGUAGE_NONE][0]['value'];
        if (isset($node->field_fast_fact[$language->language][0]['value'])) {
          $fast_fact = $node->field_fast_fact[$language->language][0]['value'];
        }

        if (isset($fast_fact)) {
            print '<div class="fast-fact">';
            print '<span>'.t('Fast Facts').'</span>'.$fast_fact;
            print '</div>';
        } 
        ?>
        <div class="short-info">
        	<span style="display:block">&nbsp;</span>
        	<span class="duration"><?php print t('Duration');?>: </span><?php 
				$days = t('DAY');
				$nights = t('NIGHT');
				$day_number = $node->field_day[LANGUAGE_NONE][0]['value'];
				if ($day_number == 0) {
					$day_number = t('Half day');
					$days = '';
				}
				
				if ($node->field_day[LANGUAGE_NONE][0]['value'] > 1) {
					$days .= 'S';	
				}
				if ($node->field_day[LANGUAGE_NONE][0]['value'] > 1) {
					$nights .= 'S';	
				}
				print $day_number.' '. $days.'/'.$node->field_night[LANGUAGE_NONE][0]['value'].' '. $nights; ?>
			<span class="style"><?php print t('STYLE'); ?>: <?php print $node->field_style[LANGUAGE_NONE][0]['taxonomy_term']->name; ?></span>
        </div>
      </div>
      <div class="find-out-more"></div>
    </div>
    <div id="content-wrapper" class="shadow">
      <div class="container">
        <div class="clearfix"></div>
        <?php if ($messages || $page['help']): ?>
        <div id="messages-help-wrapper">
          <div class="container clearfix"> <?php print $messages; ?> <?php print render($page['help']); ?> </div>
        </div>
        <?php endif; ?>
        <?php if ($page['secondary_content']): ?>
        <div id="secondary-content-wrapper">
          <div class="container clearfix"> <?php print render($page['secondary_content']); ?> </div>
        </div>
        <?php endif; ?>
        <div id="columns">
          <div class="columns-inner clearfix">          
            <?php if ($page['content_top']): ?>
            <div class="content-top"><?php print render($page['content_top']); ?></div>
            <?php endif; ?>
            <div id="content-column">
              <div class="content-inner">
			    <?php print _breadcrums('node-tour', $node);?>
			    <?php print render($page['highlighted']); ?> <<?php print $tag; ?> id="main-content"> <?php print render($title_prefix); ?>
                <?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links = render($action_links)): ?>
                <header<?php print $content_header_attributes; ?>>
                  <?php if ($primary_local_tasks || $secondary_local_tasks || $action_links): ?>
                  <div id="tasks">
                    <?php if ($primary_local_tasks): ?>
                    <ul class="tabs primary clearfix">
                      <?php print render($primary_local_tasks); ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($secondary_local_tasks): ?>
                    <ul class="tabs secondary clearfix">
                      <?php print render($secondary_local_tasks); ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($action_links = render($action_links)): ?>
                    <ul class="action-links clearfix">
                      <?php print $action_links; ?>
                    </ul>
                    <?php endif; ?>
                  </div>
                  <?php endif; ?>
                </header>
                <?php endif; ?>
                <?php if ($content = render($page['content'])): ?>
                <div id="content"> <?php print $content; ?> <?php print $hidden_content; ?> </div>
                <?php endif; ?>
                <?php print $feed_icons; ?> <?php print render($title_suffix); // Prints page level contextual links ?> </<?php print $tag; ?>> <?php print render($page['content_aside']); ?> </div>
            </div>
            <?php print render($page['sidebar_first']); ?> <?php print render($page['sidebar_second']); ?>
            <div class="clearfix"></div>
            <?php if ($page['content_bottom']): ?>
            <div class="content-bottom"><?php print render($page['content_bottom']); ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <!-- #content-wrapper -->
    
    <?php if ($page['tertiary_content']): ?>
    <div id="tertiary-content-wrapper">
      <div class="container clearfix"> <?php print render($page['tertiary_content']); ?> </div>
    </div>
    <?php endif; ?>
  </div>
  <?php if ($page['footer']): ?>
  <div id="footer-wrapper">
    <div class="container clearfix">
      <footer<?php print $footer_attributes; ?>> <?php print render($page['footer']); ?> </footer>
    </div>
    
    <div class="footer-bottom-wrapper">
      <div class="footer-bottom">
        <div id="captions-wrapper-footer" class="shadow">
          <div class="caption"><?php print $social_block; ?></div>
        </div>
        <div class="terms" style="">
          <a href="<?php print $base_path;?>contact-us"><?php print t('Contact Us'); ?></a> | <a href="<?php print $base_path;?>terms"><?php print t('Terms'); ?></a>
        </div>
        <div class="copyright">
          &copy; <?php print date('Y');?> HGH Travel. All Rights Reserved
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

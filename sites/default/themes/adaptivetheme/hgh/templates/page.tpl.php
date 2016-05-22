<?php
/**
 * @file
 * Adaptivetheme implementation to display a single Drupal page.
 *
 * ###  Full Width Wrappers  ###
 *
 * This page template that has 100% width wrappers, which effectively
 * divide the page up into sections to you can style with full width
 * backgrounds. AT Commerce uses markup similar to this to achieve
 * its style - its worth checking out to see how I did this.
 *
 * To use copy this to your subtheme and rename it page.tpl.php,
 * or enable this in theme settings under "Site Tweaks".
 *
 * Available variables:
 *
 * Adaptivetheme supplied variables:
 * - $site_logo: Themed logo - linked to front with alt attribute.
 * - $site_name: Site name linked to the homepage.
 * - $site_name_unlinked: Site name without any link.
 * - $hide_site_name: Toggles the visibility of the site name.
 * - $visibility: Holds the class .element-invisible or is empty.
 * - $primary_navigation: Themed Main menu.
 * - $secondary_navigation: Themed Secondary/user menu.
 * - $primary_local_tasks: Split local tasks - primary.
 * - $secondary_local_tasks: Split local tasks - secondary.
 * - $tag: Prints the wrapper element for the main content.
 * - $is_mobile: Bool, requires the Browscap module to return TRUE for mobile
 *   devices. Use to test for a mobile context.
 * - *_attributes: attributes for various site elements, usually holds id, class
 *   or role attributes.
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Core Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * Adaptivetheme Regions:
 * - $page['leaderboard']: full width at the very top of the page
 * - $page['menu_bar']: menu blocks placed here will be styled horizontal
 * - $page['secondary_content']: full width just above the main columns
 * - $page['content_aside']: like a main content bottom region
 * - $page['tertiary_content']: full width just above the footer
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see adaptivetheme_preprocess_page()
 * @see adaptivetheme_process_page()
 */

/*<div class="collectonme"></div>
<div class="collectonme"></div>
<div class="collectonme"></div>*/
?>

<div id="page-wrapper" class="page-home">
  <div id="page" class="<?php print $classes; ?>">
    <div id="header-wrapper">
        <header id="header">
          <?php if ($site_logo || $site_name || $site_slogan): ?>
          <!-- start: Branding -->
          <div id="branding" class="branding-elements clearfix container">
                <?php if ($site_logo): ?>
                    <div id="logo" class="col-md-1 col-xs-1"> <?php print $site_logo; ?> </div>
                    <?php if ($site_slogan): ?>
                        <div id="site-slogan" class="col-md-5 col-xs-5"><?php print $site_slogan; ?></div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Navigation elements --> 
                <div class="search-icons col-md-6 col-xs-6"><?php print render($page['header']); ?></div>
                <?php if ($primary_navigation): print $primary_navigation; endif; ?>
                <div class="main-menu"><?php print render($page['menu_bar']); ?></div>
          </div>
          <!-- /end #branding -->
          <?php endif; ?>
        </header>
      </div>
    </div>
    <div class="clearfix"></div>

    <div class="slider-wrapper">
        <?php print $images_slider; ?>
    </div>

    <?php
    $breadcrumb = false;
	if ($breadcrumb): ?>
    <div id="breadcrumb-wrapper">
      <div class="container clearfix"> <?php print $breadcrumb; ?> </div>
    </div>
    <?php endif; ?>    
    <div id="content-wrapper" class="shadow">
      <div id="captions-wrapper" class="shadow">
        <div class="pagination-slider"></div>
        <div class="caption"></div>
        <div class="find-out-more"></div>
      </div>
      <div class="container">
        <div class="clearfix"></div>
        <?php if ($messages): ?>
        <div id="messages-help-wrapper">
          <div class="container clearfix"> <?php print $messages; ?></div>
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
			  <?php print _breadcrums();?>
			  <?php print render($page['highlighted']); ?> <<?php print $tag; ?> id="main-content"> <?php print render($title_prefix); ?>
                <?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links = render($action_links)): ?>
                <header<?php print $content_header_attributes; ?>>
                  <?php if ($title): ?>
                  <h1 id="page-title"><?php print $title; ?></h1>
                  <?php endif; ?>
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
            
            <?php
			if (arg(0) == 'destinations' && !arg(1)) {
				print '<div id="gmap" data-maptype="destinations">';
			}
			?>
          </div>
        </div>
      </div>
    </div>
    <!-- #content-wrapper -->
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
  			<div class="terms" style="display:none;">
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

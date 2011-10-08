<?php
/**
 * @file
 * Onemilliontweets page template
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $secondary_menu_heading: The title of the menu used by the secondary links.
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
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 * - $page['bottom']: Items to appear at the bottom of the page below the footer.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see zen_preprocess_page()
 * @see template_process()
 */
?>
<div id="page-wrapper"><div id="page">
  <div id="main-wrapper"><div id="main" class="clearfix<?php if ($main_menu || $page['navigation']) { print ' with-navigation'; } ?>">
     <?php if ($messages): ?>
      <div id="site-messages" class="m-top">
        <?php print $messages ; ?>
      </div>
    <?php endif; ?>
    <div id="content" class="column"><div class="section">
      <?php print render($page['highlighted']); ?>
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="title" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php if ($tabs = render($tabs)): ?>
        <div class="tabs"><?php print $tabs; ?></div>
      <?php endif; ?>
      <?php print render($page['help']); ?>
      <?php if ($action_links): ?>
        <ul class="action-links"><?php print render($action_links); ?></ul>
      <?php endif; ?>
      <?php print render($page['content']); ?>

      <?php print render($page['content_bottom']); ?>
      <?php print $feed_icons; ?>
    </div></div><!-- /.section, /#content -->

    <?php if ($page['navigation']): ?>
      <div id="navigation"><div class="section clearfix">

        <?php print render($page['navigation']); ?>

      </div></div><!-- /.section, /#navigation -->
    <?php endif; ?>

    <?php print render($page['sidebar_first']); ?>

    <?php print render($page['sidebar_second']); ?>

  </div></div><!-- /#main, /#main-wrapper -->

</div></div><!-- /#page, /#page-wrapper -->

    <?php if ($page['footer']): ?>
      <div id="footer-wrapper"><div id="footer"><div class="section clearfix">
        <?php print render($page['footer']); ?>
      </div></div></div>
    <?php endif; ?>

    <?php if ($page['site_closure']): ?>
    <div id="site-closure-wrapper"><div id="site-closure"><div class="section clearfix">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="small-logo"><img src="<?php print $small_logo_path; ?>" alt="<?php print t('Home'); ?>" /></a>
        <p>
          <span class="uppercase title">The best of Europe's wilderness</span></br>
          <span>network of national parks offering Europe's most undisturbed areas of nature, wild nature's holidays.</span>
        </p>
        <?php print render($page['site_closure']); ?>
        <div id ="macroweb"><small>designed by <a href="http://macroweb.hu">Macroweb</a>, powered by <a href="http://drupal.org">Drupal</a>.</small></div>
      </div></div></div><!-- /#site-closure -->
    <?php endif; ?>


<?php print render($page['bottom']); ?>

<?php if ($is_admin && $page['development']): ?>
  <?php print render($page['development']); ?>
<?php endif; ?>

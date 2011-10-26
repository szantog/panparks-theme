<?php
/**
 * @file
 * Custom template for visit main page
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
    <?php if ($logged_in): ?>
    <div id="user-logged-in" class="clearfix">
      <?php print theme('links__user_menu', array(
        'links' => $user_menu,
        'attributes' => array(
          'id' => 'user-menu',
          'class' => array('links'),
        ),
        'heading' => array(
          'text' => t('User menu'),
          'level' => 'h2',
          'class' => array('element-invisible'),
        ),
      ));?>
      <?php print render($page['user_menu']); ?>
    </div>
    <?php endif; ?>
  <div id="header"><div class="section clearfix">

    <?php if ($logo): ?>
      <div class="grey-fix"></div>
      <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" /></a>
    <?php endif; ?>

    <?php if ($site_name || $site_slogan): ?>
      <div id="name-and-slogan" class="element-invisible">
        <?php if ($site_name): ?>
          <?php if ($title): ?>
            <div id="site-name"><strong>
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
            </strong></div>
          <?php else: /* Use h1 when the content title is empty */ ?>
            <h1 id="site-name">
              <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
            </h1>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ($site_slogan): ?>
          <div id="site-slogan"><?php print $site_slogan; ?></div>
        <?php endif; ?>
      </div><!-- /#name-and-slogan -->
    <?php endif; ?>

    <div id="header-menu-search" class="">
      <div id="header-menu" class="">
        <div class="upper">
          <?php print theme('links__system_secondary_menu', array(
            'links' => $secondary_menu,
            'attributes' => array(
              'id' => 'secondary-menu',
              'class' => array('links', 'inline'),
            ),
            'heading' => array(
              'text' => $secondary_menu_heading,
              'level' => 'h2',
              'class' => array('element-invisible'),
            ),
          )); ?>

          <?php if ($search_form) : print drupal_render($search_form); endif; ?>
        </div>

        <?php print theme('links__menu_primary_menu', array(
          'links' => $main_menu,
          'attributes' => array(
            'id' => 'main-menu',
            'class' => array('links', 'inline', 'clearfix'),
          ),
          'heading' => array(
            'text' => t('Main menu'),
            'level' => 'h2',
            'class' => array('element-invisible'),
          ),
        )); ?>
      </div>
    </div>
    <div id="header-content" class="clearfix">
      <?php print render($page['header']); ?>
      <div id="section-description">
        <h2 id="section-title" class="section-description element-invisible"><?php if (isset($section_title)) : print render($section_title); endif; ?></h2>
        <h3 id="section-desc" class="section-description element-invisible"><?php if (isset($section_desc)) : print render($section_desc); endif; ?></h3>
      </div>
    </div>
  </div></div><!-- /.section, /#header -->

  <div id="main-wrapper"><div id="main" class="clearfix<?php if ($main_menu || $page['navigation']) { print ' with-navigation'; } ?>">
     <?php if ($messages): ?>
      <div id="site-messages" class="m-top">
        <?php print $messages ; ?>
      </div>
    <?php endif; ?>
    <div id="content" class="column"><div class="section">
      <?php print $breadcrumb; ?>
      <a id="main-content"></a>
      <?php print render($title_prefix); ?>
      <?php if ($title): ?>
        <h1 class="title element-invisible" id="page-title"><?php print $title; ?></h1>
      <?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php if ($tabs = render($tabs)): ?>
        <div class="tabs"><?php print $tabs; ?></div>
      <?php endif; ?>


        <div class="content-top clearfix">
          <h2 class="section-title"><?php print l(t('Get involved'), 'node/56'); ?></h2>
          <?php
            print render($page['content']['bean_46']);
            print render($page['content']['bean_45']);
            if (!$logged_in) {
              print render($page['content']['bean_50']);
            }
            else {
              print render($page['content']['bean_49']);
            }
          ?>
        </div>

        <div class="content-mid clearfix">
          <?php print l(t('Other ways to get involved'), 'node/56', array('attributes' => array('class' => 'button'))); ?>
        </div>

        <div class="content-bottom clearfix">
          <div class="content-bottom-left content-section left">
            <h2 class="section-title"><?php print l(t('Visit'), 'node/60'); ?></h2>
            <?php
              print render($page['content']['bean_33']);
              print render($page['content']['bean_34']);
            ?>
          </div>

          <div class="content-bottom-right content-section right">
            <h2 class="section-title"><?php print l(t('Support'), 'node/2637'); ?></h2>
            <?php
              print render($page['content']['bean_35']);
              print render($page['content']['bean_36']);
            ?>
          </div>
        </div>
    </div></div><!-- /.section, /#content -->

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
         <?php print render($page['site_closure']); ?>
        <div id ="macroweb">
          <?php print $macroweb; ?>
        </div>
      </div></div></div><!-- /#site-closure -->
    <?php endif; ?>


<?php print render($page['bottom']); ?>

<?php if ($is_admin && $page['development']): ?>
  <?php print render($page['development']); ?>
<?php endif; ?>

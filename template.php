<?php
/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can modify or override Drupal's theme
 *   functions, intercept or make additional variables available to your theme,
 *   and create custom PHP logic. For more information, please visit the Theme
 *   Developer's Guide on Drupal.org: http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to panparks_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: panparks_breadcrumb()
 *
 *   where panparks is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override either of the two theme functions used in Zen
 *   core, you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */

/* Set message, if less module doesn't exist. */
if (!module_exists('less')){
  drupal_set_message(t('The module <a href="http://drupal.org/project/less">less</a> doesn\'t exist. Download, and enable it"'), 'warning');
}

/**
 * Override or insert variables into the html templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */

function panparks_preprocess_html(&$vars, $hook) {

  //Remove extraa classes, when we are on colorbox page.
  $args = arg();
  if ((arg(1) && end($args) == 'colorbox') || (arg(0) == 'node' && arg(1) == 56 && !arg(2))) {
    $vars['classes_array'] = array_diff($vars['classes_array'], array('one-sidebar sidebar-first'));
    $vars['classes_array'][] = 'page-null';
  }
  //Add page-null class to get-involved/all-about-wilderness page
  //The node ids of page null
  $page_null = array(58, 3469);

  if (arg(0) == 'node' && in_array(arg(1) , $page_null)) {
    $vars['classes_array'][] = 'page-null';
  }
  // http://www.panparks.org/node/add/photo-shared on map the default icon is
  // the pencil. We need to change this to the arrow
  if (arg(1) == 'add' && arg(2) == 'photo-shared') {
    drupal_add_js('jQuery(document).ready(function () { jQuery("div.olControlModifyFeatureItemInactive").trigger("click"); });', 'inline');
  }
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */

function panparks_preprocess_page(&$vars, $hook) {
  global $user;
  global $base_url;
  $page = &$vars['page'];
  // $args is never used, just set it to checking
  //@todo remove
  $args = arg();
  $node = isset($vars['node']) ? $vars['node'] : NULL;
  //Set variable to print upper user menu for logged in users.
  //Logged in users settings
  if ($user->uid > 0) {
    $user_menu = menu_navigation_links('user-menu');
    foreach ($user_menu as $key => &$menu_item) {
      if ($key == 'menu-2') {
        $menu_item['title'] = check_plain($user->name);
        $menu_item['suffix'] = ' signed in';
      }
    }
    $vars['user_menu'] = $user_menu;

    //Hide join now block from logged in users
    if (isset($page['content']['bean_50'])) {
      hide($page['content']['bean_50']);
    }
    if (isset($page['content']['bean_48'])) {
      hide($page['content']['bean_48']);
    }
    if (isset($page['content_top']['bean_50'])) {
      hide($page['content_top']['bean_50']);
    }
    if (isset($page['content_top']['bean_48'])) {
      hide($page['content_top']['bean_48']);
    }
  }
  //Not logged in users settings.
  else {
    //Hide pictures of month block from logged in users
    if (isset($page['content']['bean_49'])) {
      hide($page['content']['bean_49']);
    }
    if (isset($page['content_top']['bean_49'])) {
      hide($page['content_top']['bean_49']);
    }
  }

  //Add only content tpl.php if we are on colorbox page and

  //The node ids of page null
  // @see _preprocess_html to add this nids too
  $page_null = array(58, 3469);

  if ((arg(1) && end($args) == 'colorbox') || (isset($node) && in_array($node->nid, $page_null))) {
    $vars['theme_hook_suggestions'][] = 'page__null' ;
    if (in_array($node->nid, $page_null)) {
      $vars['back'] = '<a class="d-green-button right" href="javascript: history.go(-1)">'. t('Back') . '</a>';
    }

    //Override previous on valentine day page
    if ($node->nid == 3469) {
      $vars['back'] = l(t('Home'), variable_get('site_frontpage'), array('attributes' => array('class' => array('d-green-button', 'right')), 'absolute' => TRUE));
    }

    if ($node && node_access('update', $node)) {
      $vars['page']['content'] = '<small>' . l(t('Edit'), "node/$node->nid/edit") . '</small>' . render($vars['page']['content']);
    }
  }

  //Add only content tpl.php if we are on colorbox page

  if (arg(1) && end($args) == 'colorbox-photo') {
    $vars['theme_hook_suggestions'][] = 'page__null_photo' ;
  }

  // Set variable to search form, no need to use block
  $vars['search_form'] = drupal_get_form('search_form');
  $vars['search_form']['basic']['submit']['#value'] = t('OK');
  $vars['search_form']['basic']['submit']['#prefix'] = '<div class="button-pre">';
  $vars['search_form']['basic']['submit']['#suffix'] = '</div>';
  $vars['search_form']['basic']['#attributes']['class'] = array();

  // This is the smal logo in page bottom
  $vars['small_logo_path'] = $base_url . '/' . drupal_get_path('theme', 'panparks') . '/images/small-logo.png';

// We use the primary menu as main menu
  $vars['main_menu'] = menu_navigation_links('menu-primary-menu');

  //Some pages (eg. Donate now never render the social beam block, need to insert code manually
  if (arg(0) == 'node' && arg(1) == 2637) {
    $vars['social'] = isset($page['footer']['bean_31']) ? drupal_render($page['footer']['bean_31']) : t('Social block was deleted.');
  }

  //Force ovveride the default page title on share photo add page
  //http://atrium.macroweb.hu/panparks-private/node/3860
  if (arg(0) == 'node' && arg(1) == 'add' && arg(2) == 'photo-shared') {
    drupal_set_title(t('Share a photo'));
  }

  if (arg(0) == 'node' && arg(1) == 2637 && !arg(2)) {
    drupal_add_css(drupal_get_path('theme', 'panparks') . '/css/donate.less');
    $vars['theme_hook_suggestions'][] = 'page__donate_now';
  }

  if (arg(0) == 'node' && arg(1) == 55 && !arg(2)) {
    drupal_add_css(drupal_get_path('theme', 'panparks') . '/css/what-you-can-do.less');
    $vars['theme_hook_suggestions'][] = 'page__what_you_can_do';
  }

  if (arg(0) == 'node' && arg(1) == 56 && !arg(2)) {
    drupal_add_css(drupal_get_path('theme', 'panparks') . '/css/get-involved.less');
    hide($vars['page']['content']['system_main']);
  }

  //Need to remove some items from buckaroo page.
  if (arg(0) == 'node' && arg(1) == 3440) {
    $vars['main_menu'] = array();
    $vars['secondary_menu'] = array();
    $vars['breadcrumb'] = NULL;
  }

  if (panparks_api_is_omt()) {
    //$vars['is_front'] == FALSE;
    drupal_add_css(drupal_get_path('theme', 'panparks') . '/css/onemilliontweets.less');
    $vars['theme_hook_suggestions'][] = 'page__onemilliontweets';
    //Hide the follow us on twitter block, will be printed manualy on page top
    hide($vars['page']['content']['bean_52']);

    //Change the one of the closure links href and title to panparks.org, and move it to first;
    //@todo this is a little bit hacky, need to find better solution for this transformation.
    if (isset($vars['page']['site_closure']['menu_menu-site-closure']['content'][1280])) {
      $vars['page']['site_closure']['menu_menu-site-closure']['content'][1280]['#href'] = 'http://panparks.org';
      $vars['page']['site_closure']['menu_menu-site-closure']['content'][1280]['#title'] = 'panparks.org';
      $vars['page']['site_closure']['menu_menu-site-closure']['content'][1280]['#localized_options']['attributes']['title'] = 'http://panparks.org';
      $tmp = $vars['page']['site_closure']['menu_menu-site-closure']['content'][1280];
      unset($vars['page']['site_closure']['menu_menu-site-closure']['content'][1280]);
      array_unshift($vars['page']['site_closure']['menu_menu-site-closure']['content'], $tmp);
    }
    else {
      $vars['page']['site_closure']['menu_menu-site-closure'][1280]['#href'] = 'http://panparks.org';
      $vars['page']['site_closure']['menu_menu-site-closure'][1280]['#title'] = 'panparks.org';
      $vars['page']['site_closure']['menu_menu-site-closure'][1280]['#localized_options']['attributes']['title'] = 'http://panparks.org';
      $tmp = $vars['page']['site_closure']['menu_menu-site-closure'][1280];
      unset($vars['page']['site_closure']['menu_menu-site-closure'][1280]);
      array_unshift($vars['page']['site_closure']['menu_menu-site-closure'], $tmp);
    }
  }

  $vars['macroweb'] =
    '<small>' . t('supported by') . '</br></small>' .
    theme('image', array('path' => drupal_get_path('theme', 'panparks') . '/images/eu.gif')) .
    '</br><small class="macroweb">' . t('designed by !link', array('!link' => l(t('Macroweb'), 'http://macroweb.hu'))) . ', ' .
    t('powered by !link', array('!link' => l(t('Drupal'), 'http://drupal.org'))) .'</small>';

  //kpr(get_defined_vars());

/*
 * Tmp unused section
 * @todo: delete before go to live
 */

//This is an alternative solution, to pick ip an entity_view up from node preprocess and render it in here
//  if (isset($vars['node']) && $vars['node']->type == 'park') {
//    $vars['page']['content_bottom'] =panparks_trespass_hook();
//  }

//  Old social links. Not yet delete
//  $social_links = array();
//  $social_links[] = l('', variable_get('site_email'), array('external' => TRUE, 'attributes' => array('class' => 'mail')));
//  $social_links[] = l('', 'http://www.facebook.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'facebook')));
//  $social_links[] = l('', 'http://twitter.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'twitter')));
//  $social_links[] = l('need', 'http://google.com', array('external' => TRUE, 'attributes' => array('class' => 'google')));
//  $social_links[] = l('need', 'http://twitter.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'digg')));
//  $social_links[] = l('need', 'http://delicious.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'delicious')));
//  $vars['social'] = theme('item_list', array('items' => $social_links, 'attributes' => array('class' => 'social-links')));
}


/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function panparks_preprocess_node(&$vars, $hook) {
  // Optionally, run node-type-specific preprocess functions, like
  // panparks_preprocess_node_page() or panparks_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $vars['node']->type;
  if (function_exists($function)) {
    $function($vars, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function panparks_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */

function panparks_preprocess_block(&$vars, $hook) {
  $block = &$vars['elements']['#block'];
  if (isset($block->bid)) {
    switch($block->bid) {
      case 'views-recent_blog_post-block':
        $vars['classes_array'][] = 'block-red';
        break;
      case 'content-navigation':
        $vars['classes_array'][] = 'block-red';
        break;
      case 'views-news-block_1':
        $block->subject .= theme('feed_icon', array('url' => 'rss.xml', 'title' => t('News rss')));
        break;
    }
  }
  // Add a count to all the blocks in the region.
  $vars['classes_array'][] = 'count-' . $vars['block_id'];
}

function panparks_preprocess_image_style(&$vars, $hook) {
  $style_name = $vars['style_name'];
  $path = $vars['path'];
  $style_path = image_style_path($style_name, $path);
  $file = file_uri_to_object($path);

  $title = isset($file) && !empty($file->media_title[LANGUAGE_NONE][0]['safe_value']) ? $file->media_title[LANGUAGE_NONE][0]['safe_value'] : '';
  $vars['alt'] = isset($vars['alt']) && $vars['alt'] != "" ? $vars['alt'] : $title;
  $vars['title'] = isset($vars['title']) && $vars['title'] != "" ? $vars['alt'] : $title;


  if (arg(2) == 'colorbox-photo') {
    //Image size should add to images rendered in colorbox.

    $info = image_get_info($style_path);

    if ($info = image_get_info($style_path)) {
      $vars['width'] = $info['width'];
      $vars['height'] = $info['height'];
    }
  }

  //We render large styles in colorbox, when clicked a square thumbnail image
  //While colorbox can't count the images witdh, we need to pregenerate the large style, when view a square_thumbnail image
  if ($style_name == 'square_thumbnail') {
    if (!file_exists(image_style_path('media_gallery_large', $path))) {
      image_style_create_derivative(image_style_load('media_gallery_large'), $path, image_style_path('media_gallery_large', $path));
    }
  }
  //dsm(get_defined_vars());
}

function panparks_preprocess_image(&$vars, $hook) {
  if ($file = file_uri_to_object($vars['path'])) {
    $title = !empty($file->media_title[LANGUAGE_NONE][0]['safe_value']) ? $file->media_title[LANGUAGE_NONE][0]['safe_value'] : '';
    $vars['alt'] = isset($vars['alt']) && $vars['alt'] != "" ? $vars['alt'] : $title;
    $vars['title'] = isset($vars['title']) && $vars['title'] != "" ? $vars['alt'] : $title;
  }
}
/**
 * Override or insert variables into theme_menu_local_task().
 */
function panparks_preprocess_menu_local_task(&$variables) {
  $link =& $variables['element']['#link'];

  // If the link does not contain HTML already, check_plain() it now.
  // After we set 'html'=TRUE the link will not be sanitized by l().
  if (empty($link['localized_options']['html'])) {
    $link['title'] = check_plain($link['title']);
  }
  $link['localized_options']['html'] = FALSE;
  //Remove spans original placed by zen.
  $link['title'] = strip_tags($link['title']);
  //kpr(get_defined_vars());
}

/*
 * Override theme_password() function to set image wrapper around
 */
function panparks_password($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'password';
  element_set_attributes($element, array('id', 'name', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));
  $pre = '<div class="input ' . $element['#type'] . '"><span class="input-pre"></span><span class="input-wrap">';
  $post = '</span></div>';

  return $pre . '<input' . drupal_attributes($element['#attributes']) . ' />' . $post;
}

/*
 * Override theme_textfield() function to set image wrapper around.
 */
function panparks_textfield($variables) {
  $element = $variables['element'];
  $element['#attributes']['type'] = 'text';
  element_set_attributes($element, array('id', 'name', 'value', 'size', 'maxlength'));
  _form_set_class($element, array('form-text'));

  $extra = '';
  if ($element['#autocomplete_path'] && drupal_valid_path($element['#autocomplete_path'])) {
    drupal_add_library('system', 'drupal.autocomplete');
    $element['#attributes']['class'][] = 'form-autocomplete';

    $attributes = array();
    $attributes['type'] = 'hidden';
    $attributes['id'] = $element['#attributes']['id'] . '-autocomplete';
    $attributes['value'] = url($element['#autocomplete_path'], array('absolute' => TRUE));
    $attributes['disabled'] = 'disabled';
    $attributes['class'][] = 'autocomplete';
    $extra = '<input' . drupal_attributes($attributes) . ' />';
  }

  $pre = '<div class="input ' . $element['#type'] . '"><span class="input-pre"></span><span class="input-wrap">';
  $post = '</span></div>';

  $output = $pre . '<input' . drupal_attributes($element['#attributes']) . ' />' . $post;
  return $output . $extra;
}

/*
 * Overridden theme_links function to support prefix and suffix.
 */
function panparks_links($variables) {
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
           && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['prefix'])) {
        $output .= check_plain($link['prefix']);
      }

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      if (isset($link['suffix'])) {
        $output .= check_plain($link['suffix']);
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

function panparks_feed_icon($variables) {
  $text = t('Subscribe to @feed-title', array('@feed-title' => $variables['title']));
  return l('', $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon'), 'title' => $text)));
}

/**
 * Displays a media item (entity) within a lightbox.
 *
 * Clicking a thumbnail within the gallery page opens a lightbox if all these
 * conditions are met:
 * - The gallery node's media_gallery_format field indicates to open a lightbox.
 * - The colorbox jQuery plugin is available.
 * - The user has JavaScript enabled.
 *
 * The lightbox contains some navigation functionality (including a "slideshow"
 * link) and a <div> containing the actual content. This function themes the
 * contents of that <div>.
 *
 * When any of the conditions for opening a lightbox aren't met, the user is
 * taken to a separate detail page instead, the contents of which are themed by
 * theme_media_gallery_media_item_detail().
 *
 * Overrides of original function
 *  - Remove copyright
 *  - Remove link to details
 */
function panparks_media_gallery_media_item_lightbox($variables) {
  $element = $variables['element'];
  $gallery_node = new FieldsRSIPreventor($element['#media_gallery_entity']);
  $file = $element['#file'];

  $panparks_file = file_view($file, 'media_gallery_lightbox', LANGUAGE_NONE);
  $panparks_file['media_title'][0]['#markup'] = '<h2>' . check_plain($gallery_node->title) . '</h2>';

  // The lightbox JavaScript requires width and height attributes to be set on
  // the displayed image, but if we're displaying an image derivative, we need
  // to create it in order to know its width and height.
  // @todo Improve the JavaScript to not require this.
  if ($element['file']['#theme'] == 'image_style') {
    $style_name = $element['file']['#style_name'];
    $style_path = image_style_path($style_name, $file->uri);
    if (!file_exists($style_path)) {
      $style = image_style_load($style_name);
      image_style_create_derivative($style, $file->uri, $style_path);
    }
    $info = image_get_info($style_path);
    $element['file'] += array('#attributes' => array());
    $element['file']['#attributes'] += array('width' => $info['width'], 'height' => $info['height']);
  }

  $panparks_file['file'] = $element['file'];
  $image = drupal_render($panparks_file);

  $gallery_id = $element['#media_gallery_entity']->nid;
  $media_id = $element['#file']->fid;

  // Create an array of variables to be added to the main image link.
  $link_vars = array();
  $link_vars['image'] = $image;
  $link_vars['link_path'] = "media-gallery/detail/$gallery_id/$media_id";
  //$link_vars['no_link'] = $element['#bundle'] == 'video' ? TRUE : FALSE;

  // Panparks override, never need to link to detail page
  $link_vars['no_link'] = TRUE;
  if ($gallery_node->getValue('media_gallery_allow_download') == TRUE) {
    $download_link = $element['#bundle'] == 'video' ? l(t('View detail page'), $link_vars['link_path']) : theme('media_gallery_download_link', array('file' => $file));
  }

  else {
    // Very ugly fix: This prevents the license info from being either hidden
    // or causing scrollbars (depending on the browser) in cases where a
    // download link is not being shown. There may be a CSS-only fix for this,
    // but we haven't found one yet.
    $download_link = '&nbsp;';
  }

  $media_gallery_detail =
      '<div class="lightbox-stack">' .
      theme('media_gallery_item', $link_vars) .
      '<div class="media-gallery-detail-info">' .
      $download_link .

      '</div></div>';
  // The license info has been themed already, keep it from being rendered as a child
  $element['field_license']['#access'] = FALSE;

  $output = 'Error';
  // If the format is to have the description as well, we add it here
  if (!empty($gallery_node->media_gallery_lightbox_extras[LANGUAGE_NONE][0]['value'])) {
    $output =
    '<div class="mg-lightbox-wrapper clearfix">' .
      '<div class="lightbox-title">' . drupal_render($element['media_title']) . '</div>' .
      '<div class="mg-lightbox-detail">' .
      $media_gallery_detail .
      '</div><div class="mg-lightbox-description">' .
        drupal_render_children($element) .
      '</div>' .
    '</div>';
  } else {
    $output = $media_gallery_detail;
  }
  //dsm(get_defined_vars());
  return $output;
}

/**
 * Returns themed html for individual tweets
 * We override it to use our template
 */
function panparks_twitter_block_tweets($tweet_object, $variables = array() ) {
  $tweet = get_object_vars($tweet_object['tweet']);
  $tweet['text'] = twitter_block_linkify($tweet['text']);
  $time = format_interval(time() - strtotime($tweet['created_at']) , 2) . t(' ago');
  $html = <<<EOHTML
  <div class="tweet-user-image">
    <a href="http://twitter.com/{$tweet['from_user']}">
      <img alt="{$tweet['from_user']}" src="{$tweet['profile_image_url']}" typeof="foaf:Image">
    </a>
  </div>
  <div class="tweet-data-wrapper">
    <div class="tweet-user-name"><strong>{$tweet['from_user']}</strong></div>
    <div class="tweet-text">{$tweet['text']}</div>
    <div class="tweet-created-at">{$time}</div>
  </div>
EOHTML;

  return $html;
}

/**
 * Theme the field with pdf reader
 * Need this to add link to original file under pdf view
 */
function panparks_pdf_reader($variables) {
  $output = '<div class="field-label">' . t('Download: ') . '</div><div class="field-item"> ' . theme('file_link', array('file' => (object) $variables['file'])) . '</div></br>' ;

  switch ($variables['settings']['renderer']) {
    case 0:
    default:
      $output .= '<div class="field-item no-ie"><iframe src="http://docs.google.com/viewer?embedded=true&url='
              . urlencode(file_create_url($variables['file']['uri']))
              . '" width="' . $variables['settings']['pdf_width']
              . '" height="' . $variables['settings']['pdf_height']
              . '" style="border: none;"></iframe></div>';
      break;

    case 1:
      $output .= '<div class="field-item no-ie"><iframe src="https://viewer.zoho.com/docs/urlview.do?embed=true&url='
              . urlencode(file_create_url($variables['file']['uri']))
              . '" width="' . $variables['settings']['pdf_width']
              . '" height="' . $variables['settings']['pdf_height']
              . '" style="border: none;"></iframe></div>';

    case 2:
      $output .= '<div class="field-item no-ie"><object data="' . file_create_url($variables['file']['uri']) . '#view=Fit' . '" '
              . 'type="application/pdf' . '" '
              . 'width="' . $variables['settings']['pdf_width'] . '" '
              . 'height="' . $variables['settings']['pdf_height'] . '">'
              . '<embed src="' . file_create_url($variables['file']['uri']) . '#view=Fit' . '"'
              . 'width="' . $variables['settings']['pdf_width'] . '" '
              . 'height="' . $variables['settings']['pdf_height'] . '" '
              . 'type="application/pdf">'
              . '<p>' . t('It appears your Web browser is not configured to display PDF files. ')
              . l(t('Download adobe Acrobat '), 'http://www.adobe.com/products/reader.html')
              . ' ' . t('or') . ' ' . l(t('click here to download the PDF file.'), file_create_url($variables['file']['uri'])) . '</p>'
              . '</embed></object></div>';
      break;
  }
  $output .= '<div class="messages warning only-ie">' . t('If you open this page in Firefox or Chrome, you can also view the publication directly in your browser with no download required.') . '</div>';
  return $output;
}

/**
 * Theme the summary page for user results.
 *
 *  Filtered text of the summary.
 * @return
 *  Themed html.
 *
 * @ingroup themeable
 */
function panparks_quiz_take_summary($variables) {
  $quiz = $variables['quiz'];
  $questions = $variables['questions'];
  $score = $variables['score'];
  $summary = $variables['summary'];
  // Set the title here so themers can adjust.
  drupal_set_title($quiz->title);

  // Display overall result.
  $output = '';
  if (!empty($score['possible_score'])) {
    if (!$score['is_evaluated']) {
      $msg = t('Parts of this @quiz have not been evaluated yet. The score below is not final.', array('@quiz' => QUIZ_NAME));
      drupal_set_message($msg, 'warning');
    }
    $output .= '<div id="quiz_score_possible">' . t('You got %num_correct out of %question_count points.', array('%num_correct' => $score['numeric_score'], '%question_count' => $score['possible_score'])) . '</div>' . "\n";
    $output .= '<div id="quiz_score_percent">' . t('Your score is: %score %', array('%score' => $score['percentage_score'])) . '</div>' . "\n";
  }
  if (isset($summary['passfail'])) {
    $output .= '<div id="quiz_summary">' . $summary['passfail'] . '</div>' . "\n";
  }
  if (isset($summary['result'])) {
    $output .= '<div id="quiz_summary">' . $summary['result'] . '</div>' . "\n";
  }
  // Get the feedback for all questions. These are included here to provide maximum flexibility for themers
  if ($quiz->display_feedback) {
    $output .= drupal_render(drupal_get_form('quiz_report_form', $questions));
  }
  return $output;
}

/*
 * Override theme_image_style.
 * Because of core update 7.9 our predefined image width and height was blocked because of image_style_transform_dimensions
 */
function panparks_image_style($variables) {
  // Determine the dimensions of the styled image.
  $dimensions = array(
    'width' => $variables['width'],
    'height' => $variables['height'],
  );

  if (arg(2) != 'colorbox-photo') {
    image_style_transform_dimensions($variables['style_name'], $dimensions);

    $variables['width'] = $dimensions['width'];
    $variables['height'] = $dimensions['height'];
  }

  // Determine the url for the styled image.
  $variables['path'] = image_style_url($variables['style_name'], $variables['path']);
  return theme('image', $variables);
}


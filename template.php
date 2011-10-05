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
  if (arg(1) && end($args) == 'colorbox') {
    $vars['classes_array'] = array_diff($vars['classes_array'], array('one-sidebar sidebar-first'));
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
  if ($user->uid > 0) {
    $user_menu = menu_navigation_links('user-menu');
    foreach ($user_menu as $key => &$menu_item) {
      if ($key == 'menu-2') {
        $menu_item['title'] = check_plain($user->name);
        $menu_item['suffix'] = ' signed in';
      }
    }
    $vars['user_menu'] = $user_menu;
  }
  //Add only content tpl.php if we are on colorbox page
  $args = arg();
  if (arg(1) && end($args) == 'colorbox') {
    $vars['theme_hook_suggestions'][] = 'page__null' ;

  }
  $social_links = array();
  $social_links[] = l('', variable_get('site_email'), array('external' => TRUE, 'attributes' => array('class' => 'mail')));
  $social_links[] = l('', 'http://www.facebook.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'facebook')));
  $social_links[] = l('', 'http://twitter.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'twitter')));
  $social_links[] = l('need', 'http://google.com', array('external' => TRUE, 'attributes' => array('class' => 'google')));
  $social_links[] = l('need', 'http://twitter.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'digg')));
  $social_links[] = l('need', 'http://delicious.com/panparks', array('external' => TRUE, 'attributes' => array('class' => 'delicious')));

  //$vars['social'] = theme('item_list', array('items' => $social_links, 'attributes' => array('class' => 'social-links')));

  $vars['social'] = "<span  class='st_email_large' ></span><span  class='st_facebook_large' ></span><span  class='st_twitter_large' ></span><span  class='st_google_large' ></span><span  class='st_digg_large' ></span><span  class='st_delicious_large' ></span>";
  $vars['search_form'] = drupal_get_form('search_form');
  $vars['search_form']['basic']['submit']['#value'] = t('OK');
  $vars['search_form']['basic']['submit']['#prefix'] = '<div class="button-pre">';
  $vars['search_form']['basic']['submit']['#suffix'] = '</div>';
  $vars['search_form']['basic']['#attributes']['class'] = array();

  global $base_url;
  $vars['small_logo_path'] = $base_url . '/' . drupal_get_path('theme', 'panparks') . '/images/small-logo.png';
  //We use the primary menu as main menu
  $vars['main_menu'] = menu_navigation_links('menu-primary-menu');

    //This is an alternative solution, to pick ip an entity_view up from node preprocess and render it in here
//  if (isset($vars['node']) && $vars['node']->type == 'park') {
//    $vars['page']['content_bottom'] =panparks_trespass_hook();
//  }

  //Force ovveride the default page title on share photo add page
  //http://atrium.macroweb.hu/panparks-private/node/3860
  if (arg(0) == 'node' && arg(1) == 'add' && arg(2) == 'photo-shared') {
    drupal_set_title(t('Share a photo'));
  }
  //kpr(get_defined_vars());
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

//Tmp depricated

//function panparks_preprocess_node_park(&$vars, $hook) {
//  if (isset($vars['content']['group_tours_and_holidays'])) {
//    panparks_trespass_hook($vars['content']['group_tours_and_holidays']);
//  }
//  kpr($vars);
//}
//
//function panparks_trespass_hook($var = NULL) {
//  $cache = &drupal_static('tresspass');
//  if (is_null($cache)) {
//    $cache = $var;
//  }
//  dsm(get_defined_vars());
//  return $cache;
//}
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
  $block = $vars['elements']['#block'];
  if ($block->bid) {
    switch($block->bid) {
      case 'views-recent_blog_post-block':
        $vars['classes_array'][] = 'block-red';
        break;
      case 'content-navigation':
        $vars['classes_array'][] = 'block-red';
        break;
    }
  }
  // Add a count to all the blocks in the region.
  $vars['classes_array'][] = 'count-' . $vars['block_id'];
  //dsm($block);
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
  $pre = '<span class="input-pre"></span><div class="input ' . $element['#type'] . '">';
  $post = '</div>';

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

  $pre = '<div class="input ' . $element['#type'] . '"><span class="input-pre"></span>';
  $post = '</div>';

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

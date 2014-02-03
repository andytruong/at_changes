<?php
/**
 * @file
 * changes_features.features.menu_links.inc
 */

/**
 * Implements hook_menu_default_menu_links().
 */
function changes_features_menu_default_menu_links() {
  $menu_links = array();

  // Exported menu link: main-menu_commits:commits
  $menu_links['main-menu_commits:commits'] = array(
    'menu_name' => 'main-menu',
    'link_path' => 'commits',
    'router_path' => 'commits',
    'link_title' => 'Commits',
    'options' => array(
      'identifier' => 'main-menu_commits:commits',
    ),
    'module' => 'system',
    'hidden' => 0,
    'external' => 0,
    'has_children' => 0,
    'expanded' => 0,
    'weight' => -49,
    'customized' => 1,
  );
  // Translatables
  // Included for use with string extractors like potx.
  t('Commits');


  return $menu_links;
}

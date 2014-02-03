<?php
/**
 * @file
 * changes_features.features.taxonomy.inc
 */

/**
 * Implements hook_taxonomy_default_vocabularies().
 */
function changes_features_taxonomy_default_vocabularies() {
  return array(
    'issue_component' => array(
      'name' => 'Component',
      'machine_name' => 'issue_component',
      'description' => '',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
    ),
    'issue_tags' => array(
      'name' => 'Issue Tags',
      'machine_name' => 'issue_tags',
      'description' => 'Use tags to group articles on similar topics into categories.',
      'hierarchy' => 0,
      'module' => 'taxonomy',
      'weight' => 0,
    ),
  );
}

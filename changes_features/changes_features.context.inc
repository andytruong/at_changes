<?php
/**
 * @file
 * changes_features.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function changes_features_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'changes_sidebar';
  $context->description = '';
  $context->tag = 'changes';
  $context->conditions = array(
    'path' => array(
      'values' => array(
        '~user' => '~user',
        '~admin' => '~admin',
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'views-components-block' => array(
          'module' => 'views',
          'delta' => 'components-block',
          'region' => 'sidebar_second',
          'weight' => '-10',
        ),
        'tagclouds-1' => array(
          'module' => 'tagclouds',
          'delta' => '1',
          'region' => 'sidebar_second',
          'weight' => '-9',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('changes');
  $export['changes_sidebar'] = $context;

  return $export;
}

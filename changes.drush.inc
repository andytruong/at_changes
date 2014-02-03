<?php

/**
 * Implements hook_drush_command()
 */
function changes_drush_command() {
    $cmds['changes_import']['callback'] = 'changes_drush_command_import';
    return $cmds;
}

/**
 * Callback for changes_import — drush command.
 */
function changes_drush_command_import($limit = 0) {
    at_container('changes.importer')
      ->setLimit($limit)
      ->import();
}

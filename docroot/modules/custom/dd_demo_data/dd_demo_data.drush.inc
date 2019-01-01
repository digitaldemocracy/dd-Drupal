<?php

/**
 * @file
 * Drush commands for GovReady module.
 */

/**
 * Implements hook_drush_command().
 */
function dd_demo_data_drush_command() {
  $items['dd-demo-data'] = array(
    'description' => 'Create or delete Digital Democracy demo content.',
    'aliases' => array('demo-data'),
    'arguments' => array('action' => 'Action to perform [create (default), remove]', 'type' => 'Type of content [(all if empty), clips, campaigns]. Remove requires clips or campaigns.'),
    'examples' => array(
      'demo-users' => 'Create DD demo content.',
    ),
  );

  return $items;
}

/**
 * Adds demo content.
 *
 * @param string $action
 *   Action (create, remove)
 * @param string $type
 *   Type (clips, campaigns)
 *
 * @return bool
 *   TRUE if successful.
 */
function drush_dd_demo_data($action = 'create', $type = '') {
  $action_labels = [
    'create' => 'Creating',
    'remove' => 'Removing',
  ];

  $enabled_types = [];

  // Validate args.
  $valid_actions = ['', 'create', 'remove'];
  $valid_types = ['', 'clips', 'campaigns'];

  if (!in_array($action, $valid_actions)) {
    drush_print(dt('Invalid action specified!'));
    return FALSE;
  }

  if (!in_array($type, $valid_types)) {
    drush_print(dt('Invalid type specified!'));
    return FALSE;
  }

  if ($action == 'create' && $type == '') {
    $enabled_types = ['clips', 'actions', 'campaigns'];
    $type = 'all';
  }
  elseif ($type != '') {
    $enabled_types[] = $type;
  }

  if (!count($enabled_types)) {
    drush_print(dt('No types specified!'));
    return FALSE;
  }

  drush_print(dt('@action Demo Content for @enabled_types type(s)...', ['@action' => $action_labels[$action], '@enabled_types' => implode(', ', $enabled_types)]));

  // Load a random id.
  $time = time();

  // Make clips.
  if (in_array('clips', $enabled_types)) {
    if ($action == 'create') {
      foreach (_dd_demo_data_clips() as $clip) {
        _dd_demo_data_create_clip($clip);
      }
    }
    elseif ($action == 'remove') {
      $ids = _dd_demo_data_remove($type);
      drush_print(dt('Removed @count clip nodes', ['@count' => count($ids)]));
    }
  }

  // Make target actions, campaigns.
  if (in_array('campaigns', $enabled_types)) {
    if ($action == 'create') {
      $pgs = [];
      $actions = [];
      foreach (_dd_demo_data_paragraphs() as $pg) {
        $pgs[] = _dd_demo_data_create_paragraph($pg);
      }

      // Make target actions.
      foreach (_dd_demo_data_actions($pgs, $time) as $action) {
        $actions[] = _dd_demo_data_create_action($action);
      }
      _dd_demo_data_create_campaign(_dd_demo_data_campaign($time));
    }
    elseif ($action == 'remove') {
      $ids = _dd_demo_data_remove($type);
      drush_print(dt('Removed @count campaign/target action nodes', ['@count' => count($ids)]));
    }
  }

  return TRUE;
}

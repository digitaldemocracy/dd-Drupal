<?php

namespace Drupal\dd_clean_contents\Commands;

use Drush\Commands\DrushCommands;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\dd_clip\Entity\DdClip;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class DdCleanContentsCommands extends DrushCommands {

  /**
   * remove contents from database.
   *
    * @param array $options An associative array of options whose values come from cli, aliases, config, etc.
   * @option exclude
   *   specifies a comma separated list of ids of clips to exclude.
   *
   * @command remove:contents
   * @aliases ddcc,remove-contents
   */
  public function contents(array $options = ['exclude' => null]) {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    print "Hello World!\n";
    $node_count = $this->cleanUsersContents(null);
    $clip_count = $this->cleanClips(null);
    print 'node and user deleted:'.$node_count['node'].' '.$node_count['user']."\n";
    print 'clips deleted:'.$clip_count."\n";
  }

  /**
   * remove contents from database.
   *
   * @command update:entities
   * @aliases ddue
   */
  public function entities() {
    // See bottom of https://weitzman.github.io/blog/port-to-drush9 for details on what to change when porting a
    // legacy command.
    print "Hello World!\n";
    //\Drupal\dd_clean_contents\DdEntityDefinitionUpdateManager()->applyUpdates();
    try {
      \Drupal::entityDefinitionUpdateManager()->applyUpdates();
    }
    catch (EntityStorageException $e) {
      print_r($e);
    }
  }

  function cleanUsersContents($exclude=null) {
    $query = \Drupal::entityQuery('user');
    $result = $query->execute();
    $users = User::loadMultiple($result);
    $node_count = 0;
    $user_count = 0;
    foreach ($users as $user) {
      print $user->getAccountName()."\n";
      $is_admin = False;
      $roles = $user->getRoles();

      foreach ($roles as $role) {
	if ($role->name === "administrator") {
          $is_admin = True;
          break;
	}
      }
      if (!$is_admin) {
	$membership_role = $role->plan;
	$uid = $user->get('uid')->value;
	$nodes = $this->getNodes($uid);
	$node_count += $this->cleanNodes($nodes);
        print $node_count." nodes deleted.\n";
	$user->delete();
	$user_count += 1;
        print $user_count." user deleted.\n";
      }
    }
    return array('node'=>$node_count,'user'=>$user_count);
  }

  function cleanNodes($nodes) {
    $count = 0;
    foreach ($nodes as $node) {
      $node->delete();
      $count += 1;
    }
    return $count;
  }

  /*
   * Function to get dd_video_alert nodes.
   * @return Array nodes. Array of Drupal\node\Entity\Node.
   */
  function getNodes($uid) {
    $query = \Drupal::entityQuery('node');
    $query->condition('uid', $uid);
    $nids = $query->execute();

    $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

    return $nodes;
  }

  /**
   * a main function which runs the alerting process.
   *
   * @param array $exclude
   *   List of clip dr_ids to be excluded. 
   */
  function cleanClips($exclude=null) {
    $count = 0;
    $field_vals = array();
    if ($exclude) {
      foreach ($exclude as $id) {
	$field_vals[] = ['field' => 'id', 'value' => $id, 'op' => '!='];
      }
    }

    $clips = DdClip::loadByFields($field_vals);
    foreach ($clips as $clip) {
      echo $clip->id()."\n";
      $clip->delete();
      $count += 1;
      print $count." clips deleted.\n";
    }
    return $count;
  }

}

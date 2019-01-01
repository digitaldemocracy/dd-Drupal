<?php

/**
 * @file
 * Contains dd_clip.page.inc.
 *
 * Page callback for DD Clip entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for DD Clip templates.
 *
 * Default template: dd_clip.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dd_clip(array &$variables) {
  global $base_url;

  $variables['elements']['is_embed'] = False;
  $option = \Drupal::request()->get('contentonly');
  if ($option && trim($option) === "true") {
    $variables['elements']['is_embed'] = True;
  }

  $variables['elements']['is_transcript'] = False;
  $option = \Drupal::request()->get('transcript');
  if ($option && trim($option) === "true") {
    $variables['elements']['is_transcript'] = True;
  }

  // Fetch DdClip Entity Object.
  $dd_clip = $variables['elements']['#dd_clip'];
  $clip_id = $dd_clip->field_videoid->first()->value;
  $variables['elements']['base_url'] = $base_url;
  $variables['elements']['clip_id'] = $clip_id;
  $variables['elements']['clip_url'] = $base_url.'/clip/'.$clip_id;
  $variables['elements']['clip_title'] = $dd_clip->getName();
  $variables['elements']['is_published'] = $dd_clip->isPublished() ? 1 : 0;
  $variables['elements']['email_url'] = $base_url . '/email_legislator/find/clip/'
    . $clip_id;
  $variables['elements']['embed_video_url'] = $base_url . '/clip/'
    . $clip_id . '?contentonly=true';
  $variables['elements']['embed_transcript_url'] = $base_url . '/clip/'
    . $clip_id . '?contentonly=true&transcript=true';
  if ($dd_clip->field_file_size->first()) {
    $variables['elements']['file_size'] = dd_clip_format_size_units(
      (int) $dd_clip->field_file_size->first()->value
    );
  }

  $variables['elements']['created'] = date("F jS, Y", $dd_clip->getCreatedTime());

  if ($dd_clip->field_transcript->first()) {
    $variables['elements']['transcripts']
      = dd_clip_prepare_transcripts($dd_clip->field_transcript->first()->value);
  }

  $variables['elements']['annotations']
    = $dd_clip->field_annotations->first() ?
    $dd_clip->field_annotations->first()->value : '[]';

  $variables['elements']['display_transcript']
    = $dd_clip->field_display_transcript->first() ?
    $dd_clip->field_display_transcript->first()->value : False;

  $variables['elements']['is_owner']
    = ($dd_clip->getOwnerId() === \Drupal::currentUser()->id());

  if (!empty($dd_clip->get('campaign_id')->getValue())) {
    $campaign = $variables['elements']['#dd_clip']->get('campaign_id')->referencedEntities()[0];
    $action = $variables['elements']['#dd_clip']->get('action_id')->referencedEntities()[0];
    $variables['elements']['campaign_id'] = $campaign->id();
    $variables['elements']['field_video_description'] = $action->get('field_video_description')->value;
    $variables['elements']['field_sample_tweet'] = $action->get('field_sample_tweet')->value;
    $variables['elements']['campaign_url'] = $base_url . Url::fromRoute('entity.node.canonical', ['node' => $campaign->id()])->toString();

  }
}

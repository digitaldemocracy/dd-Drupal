<?php

namespace Drupal\dd_action_center\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\dd_action_center\Utility\DdActionCenterCampaignHelper;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdUser;
use Drupal\dd_email_legislator\DdEmailLegislator;
use Drupal\dd_fax_service\Utility\CommonHelper;
use Drupal\dd_legislator\Entity\DdGovernor;
use Drupal\dd_legislator\Entity\DdLegislator;
use Drupal\dd_legislator\Entity\DdTerm;
use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;

/**
 * Provides a 'DdContactRepresentativesBlock' block.
 *
 * @Block(
 *  id = "dd_contact_representatives_block",
 *  admin_label = @Translation("Contact Representatives Block"),
 * )
 */
class DdContactRepresentativesBlock extends BlockBase {
  private $assemblyDistrict;
  private $senateDistrict;
  private $assemblyLegislator;
  private $senateLegislator;
  private $legislatorPids;
  private $terms;

  /**
   * Set Assembly District of visitor.
   *
   * @param int $district
   *   District
   */
  protected function setAssemblyDistrict($district) {
    $this->assemblyDistrict = $district;
    $legislator = DdLegislator::getLegislatorByDistrict($district, 'Assembly');
    $this->setAssemblyLegislator($legislator);
    $this->clearLegislatorPids('assembly');
    $this->legislatorPids[$legislator->id()] = ['pid' => $legislator->id(), 'house' => 'assembly'];
  }

  /**
   * Get Assembly District.
   *
   * @return int
   *   District
   */
  protected function getAssemblyDistrict() {
    return $this->assemblyDistrict;
  }

  /**
   * Set Senate District of visitor.
   *
   * @param int $district
   *   District
   */
  protected function setSenateDistrict($district) {
    $this->senateDistrict = $district;
    $legislator = DdLegislator::getLegislatorByDistrict($district, 'Senate');
    $this->setSenateLegislator($legislator);
    $this->clearLegislatorPids('senate');
    $this->legislatorPids[$legislator->id()] = ['pid' => $legislator->id(), 'house' => 'senate'];
  }

  /**
   * Get Senate District.
   *
   * @return int
   *   District
   */
  protected function getSenateDistrict() {
    return $this->senateDistrict;
  }

  /**
   * Clear Legislator Pids for a house.
   *
   * @param string $house
   *   assembly or senate.
   */
  protected function clearLegislatorPids($house) {
    if ($this->legislatorPids) {
      foreach ($this->legislatorPids as $pid => $house_pid) {
        if ($house_pid['house'] == $house) {
          unset($this->legislatorPids[$pid]);
        }
      }
    }
  }
  /**
   * Get Assembly Legislator.
   *
   * @return DdLegislator
   *   Legislator Entity
   */
  public function getAssemblyLegislator() {
    return $this->assemblyLegislator;
  }

  /**
   * Set Assembly Legislator entity.
   *
   * @param DdLegislator $legislator
   *   Legislator Entity
   */
  public function setAssemblyLegislator($legislator) {
    $this->assemblyLegislator = $legislator;
    $this->legislatorPids[$legislator->id()] = ['pid' => $legislator->id(), 'house' => 'assembly'];
    $this->setTerm($legislator->id());
  }

  /**
   * Get Senate Legislator.
   *
   * @return DdLegislator
   *   Legislator Entity
   */
  public function getSenateLegislator() {
    return $this->senateLegislator;
  }

  /**
   * Set Senate Legislator entity.
   *
   * @param DdLegislator $legislator
   *   Legislator Entity
   */
  public function setSenateLegislator($legislator) {
    $this->senateLegislator = $legislator;
    $this->legislatorPids[$legislator->id()] = ['pid' => $legislator->id(), 'house' => 'senate'];
    $this->setTerm($legislator->id());
  }

  /**
   * Get Legislators.
   *
   * @return array
   *   Array of Legislator Entities
   */
  public function getLegislators() {
    return [$this->getAssemblyLegislator(), $this->getSenateLegislator()];
  }

  /**
   * Get Legislator by house.
   *
   * @param string $house
   *   assembly or senate
   *
   * @return DdLegislator
   *   Legislator Entity
   */
  public function getLegislatorByHouse($house) {
    if ($house == 'senate') {
      return $this->getSenateLegislator();
    }
    elseif ($house == 'assembly') {
      return $this->getAssemblyLegislator();
    }
    return NULL;
  }

  /**
   * Set Assembly Legislator entity by pid.
   *
   * @param int $pid
   *   Legislator Entity PID
   */
  public function setAssemblyLegislatorByPid($pid) {
    $this->assemblyLegislator = DdLegislator::load($pid);
    $this->legislatorPids[$pid] = ['pid' => $pid, 'house' => 'assembly'];
    $this->setTerm($pid);
  }

  /**
   * Set Assembly Legislator entity by pid.
   *
   * @param int $pid
   *   Legislator Entity PID
   */
  public function setSenateLegislatorByPid($pid) {
    $this->senateLegislator = DdLegislator::load($pid);
    $this->legislatorPids[$pid] = ['pid' => $pid, 'house' => 'senate'];
    $this->setTerm($pid);
  }

  /**
   * Get Array of Senate/Assembly Legislator Pids.
   *
   * @return array
   *   Senate/Assembly PIDs array with keys of pid/house.
   */
  public function getLegislatorPids() {
    return $this->legislatorPids;
  }

  /**
   * Set term by house.
   *
   * @param int $pid
   *   Legislator pid
   */
  public function setTerm($pid) {
    $params = [
      ['field' => 'current_term', 'value' => 1],
      ['field' => 'pid', 'value' => $pid],
      ['field' => 'state', 'value' => DdBase::getCurrentState()],
    ];
    $term = DdTerm::loadByFields($params);

    $this->terms[$pid] = reset($term);
  }

  /**
   * Get Term by pid.
   *
   * @param int $pid
   *   Legislator PID
   *
   * @return DdTerm
   *   DdTerm Entity
   */
  public function getTerm($pid) {
    return $this->terms[$pid];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $dd_user = NULL;
    $user = NULL;
    $form['#cache'] = ['max-age' => 0];
    $show_block = TRUE;
    $parameters = \Drupal::routeMatch()->getParameters();

    $targeted_paragraph_types = [
      'email_action',
      'fax_action',
      'phone_action',
      'tweet_legislator_action',
    ];

    $governor_actions = [
      'email_governor',
      'fax_governor',
      'call_governor',
    ];

    $non_legislator_targeted_paragraph_types = [
      'download_action',
      'display_text',
      'display_video_clip_montage',
      'letter_to_editor',
    ];

    $all_legislator_paragraph_types = array_merge($targeted_paragraph_types, $non_legislator_targeted_paragraph_types);

    // Get district info.

    $user = DdActionCenterCampaignHelper::getVisitor();
    // If no user is logged in yet, don't show the block.
    if (empty($user)) {
      return $form;
    }

    $dd_user = new DdUser();
    $dd_user->createFromUser($user);

    if ($dd_user->validateFields()) {
      $result = \Drupal\dd_find_legislators\Utility\CommonHelper::findLegislators($dd_user->toArray());

      if ($result['message'] != 'success' || !isset($result['data'])) {
        $msg = t('Failed to locate your address: @street @city @state @zip',
          [
            '@street' => $dd_user->getStreet(),
            '@city' => $dd_user->getCity(),
            '@state' => $dd_user->getState(),
            '@zip' => $dd_user->getZip(),
          ]
        );
        drupal_set_message($msg);
      }
      else {
        $this->setAssemblyDistrict($result['data']['legislators']['Assembly']['district']);
        $this->setSenateDistrict($result['data']['legislators']['Senate']['district']);
      }
    }

    // @todo Get county from findLegislators and update $dd_user.

    // Allow district override.
    $session = \Drupal::service('session');
    $location_override = $session->get('campaign_preview_location_override');
    if ($location_override) {
      if (!empty($session->get('campaign_preview_location_assembly_district'))) {
        $this->setAssemblyDistrict($session->get('campaign_preview_location_assembly_district'));
      }

      if (!empty($session->get('campaign_preview_location_senate_district'))) {
        $this->setSenateDistrict($session->get('campaign_preview_location_senate_district'));
      }
    }

    // Determine statewide actions.
    $catchall_actions = [];
    $campaign = $parameters->get('node');
    if ($campaign) {
      $catchall_actions_unkeyed = $parameters->get('node')->get('field_actions')->referencedEntities();
      if ($catchall_actions_unkeyed) {
        foreach ($catchall_actions_unkeyed as $catchall_action) {
          $catchall_actions[$catchall_action->getType()]['paragraph'] = $catchall_action;
        }
      }
    }
    else {
      drupal_set_message('No Campaign ID found!');
      return $form;
    }

    // Check campaign targets.
    $nids = \Drupal::entityQuery('node')
      ->condition('type', 'target')
      ->condition('field_campaign.target_id', $campaign->id())
      ->condition('field_legislator.target_id', [$this->getAssemblyLegislator()->id(), $this->getSenateLegislator()->id()], 'IN')
      ->execute();

    // Build a list of target legislators w/actions.
    $targeted_legislators = [];
    $actions = [];
    if ($nids) {
      $target_nodes = Node::loadMultiple($nids);
      foreach ($target_nodes as $target_node) {
        $legislator = $target_node->field_legislator->referencedEntities()[0];
        $targeted_legislators[$legislator->id()]['legislator'] = $legislator;
        if ($target_node->field_campaign_action) {
          $campaign_action = $target_node->field_campaign_action->referencedEntities();
          if ($campaign_action) {
            $paragraph_actions = $campaign_action[0]->field_actions->referencedEntities();
            $targeted_legislators[$legislator->id()]['actions'] = $paragraph_actions;
          }
        }
      }

      // Determine which actions match legislator / action type.
      foreach ($targeted_legislators as $pid => $targeted_legislator) {
        if ($pid == $this->getSenateLegislator()->id() || $pid == $this->getAssemblyLegislator()->id()) {
          if (isset($targeted_legislator['actions'])) {
            foreach ($targeted_legislator['actions'] as $targeted_legislator_action) {
              $actions[$pid][$targeted_legislator_action->getType()]['paragraph'] = $targeted_legislator_action;
            }
          }
        }
      }
    }
    else {
      // Only catch-all, use those actions.
      foreach ($catchall_actions as $action_type => $catchall_action) {
        if (in_array($action_type, $all_legislator_paragraph_types)) {
          $actions[$this->getSenateLegislator()->id()][$action_type] = $catchall_action;
          $actions[$this->getAssemblyLegislator()->id()][$action_type] = $catchall_action;
        }
      }
    }

    // Fill in catch all actions.
    $senator_use_catch_all = FALSE;
    $assemblymember_use_catch_all = FALSE;

    if (
      !isset($actions[$this->getSenateLegislator()->id()]) ||
      !count($actions[$this->getSenateLegislator()->id()])
    ) {
      $senator_use_catch_all = TRUE;
    }
    if (
      !isset($actions[$this->getAssemblyLegislator()->id()]) ||
      !count($actions[$this->getAssemblyLegislator()->id()])
    ) {
      $assemblymember_use_catch_all = TRUE;
    }

    foreach ($all_legislator_paragraph_types as $paragraph_type) {
      if (isset($catchall_actions[$paragraph_type])) {
        if ($senator_use_catch_all) {
          $actions[$this->getSenateLegislator()
            ->id()][$paragraph_type] = $catchall_actions[$paragraph_type];
        }
        elseif ($assemblymember_use_catch_all) {
          $actions[$this->getAssemblyLegislator()
            ->id()][$paragraph_type] = $catchall_actions[$paragraph_type];
        }
      }
    }

    // Add in governor actions.
    $legislator_governor_pids = $this->getLegislatorPids();
    $governor = DdGovernor::getCurrentGovernor();

    // Only add governor actions if no senator/assemblymember actions set.
    if (!isset($actions[$this->getSenateLegislator()->id()]) && !isset($actions[$this->getAssemblyLegislator()->id()])) {
      foreach ($governor_actions as $governor_action) {
        if (isset($catchall_actions[$governor_action])) {
          $actions[$governor->id()][$governor_action] = $catchall_actions[$governor_action];
        }
      }

      // Determine links for all actions.
      $legislator_governor_pids[$governor->id()] = ['pid' => $governor->id(), 'house' => ''];
    }

    foreach ($legislator_governor_pids as $house_pid) {
      if (isset($actions[$house_pid['pid']])) {
        foreach ($actions[$house_pid['pid']] as &$pid_action) {
          self::processActionTokens($pid_action['paragraph'], $campaign, $user);

          switch ($pid_action['paragraph']->getType()) {
            case 'email_action':
              $result = DdEmailLegislator::getEmailUrl(
                $dd_user,
                $this->getLegislatorByHouse($house_pid['house']),
                $this->getTerm($house_pid['pid']),
                strip_tags($pid_action['paragraph']->get('field_email_body')->value),
                strip_tags($pid_action['paragraph']->get('field_email_subject')->value),
                $campaign->id()
              );
              $pid_action['email_url'] = $result['url'];
              $pid_action['url_target'] = $result['target'];
              break;
          }
        }
      }
    }

    if ($show_block) {
      if ($user) {
        $first_name = $user->get('field_first_name')->value;
        $last_name = $user->get('field_last_name')->value;
        $field_address = $user->get('field_address')[0];

        if ($field_address) {
          $street = $field_address->get('address_line1')->getValue();
          $city = $field_address->get('locality')->getValue();
          $state = $field_address->get('administrative_area')->getValue();
          $zipcode = $field_address->get('postal_code')->getValue();

          $form['#attached']['library'][] = 'dd/action-link';

          // Add Governor actions;
          if (
            isset($actions[$governor->id()]) &&
            (isset($catchall_actions['call_governor']) || isset($catchall_actions['email_governor']) || isset($catchall_actions['fax_governor']))
          ) {

            $form['governor'] = [
              '#type' => 'container',
              '#attributes' => ['class' => ['state-governor']],
              ];
            $form['governor']['name'] = [
              '#type' => 'item',
              '#markup' => 'Governor ' . $governor->getFullNameFirstLast(),
            ];
            $form['governor']['governor_actions'] = [
              '#type' => 'container',
              '#attributes' => ['class' => ['legislator-actions']],
            ];

            if (isset($actions[$governor->id()]['email_governor']) && !empty($governor->getEmail())) {
              $governor_action = $actions[$governor->id()]['email_governor']['paragraph'];
              $url = Url::fromUri(
                'mailto:' . $governor->getEmail(),
                [
                  'query' => [
                    'body' => strip_tags($governor_action->get('field_email_body')->value),
                    'subject' => strip_tags($governor_action->get('field_email_subject')->value),
                  ]
                ]
              );
              $form['governor']['governor_actions']['email_governor_action'] = [
                '#type' => 'container',
                '#attributes' => ['class' => ['action-link-wrapper']],
                'link' => [
                  '#type' => 'link',
                  '#title' => 'Send An Email',
                  '#url' => $url,
                  '#attributes' => [
                    'class' => ['button--action', 'action-link--email'],
                    'target' => '_blank',
                    'onClick' => "Drupal.behaviors.campaigns.logActionMetric('" . $campaign->id() . "', 'email_governor', '0','" . $governor_action->id() . "', '" . $governor->id() . "');",
                  ],
                ],
              ];
            }

            if (isset($actions[$governor->id()]['fax_governor']) && !empty($governor->getFax())) {
              $governor_action = $actions[$governor->id()]['fax_governor']['paragraph'];

              $fax_args = [
                'first' => $first_name,
                'last' => $last_name,
                'street' => $street,
                'city' => $city,
                'state' => $state,
                'zip' => $zipcode,
                'to' => urlencode('Governor ' . $governor->getFullNameFirstLast()),
                'subject' => urlencode(strip_tags($governor_action->get('field_subject')->value)),
                'body' => urlencode(strip_tags($governor_action->get('field_action_body')->value)),
                'campaign_id' => $campaign->id(),
                'campaign_action_id' => 0,
                'campaign_action_paragraphs_id' => $governor_action->id(),
                'action_id' => 'fax_governor',
                'target_pid' => $governor->id(),
              ];

              $url = Url::fromRoute(
                'dd_fax_service.fax_form',
                array(
                  'fax_num' => CommonHelper::cleanDigit($governor->getFax()),
                  'arg' => http_build_query($fax_args))
              );

              $form['governor']['governor_actions']['fax_action'] = [
                '#type' => 'container',
                '#attributes' => ['class' => ['action-link-wrapper']],
                'link' => [
                  '#type' => 'link',
                  '#title' => 'Send A Fax',
                  '#url' => $url,
                  '#attributes' => [
                    'class' => ['button--action', 'action-link--fax'],
                    'target' => '_blank',
                  ],
                ],
              ];
            }

            if (isset($actions[$governor->id()]['call_governor']) && !empty($governor->getPhone())) {
              $governor_action = $actions[$governor->id()]['call_governor']['paragraph'];
              $phone_form_url = \Drupal\Core\Url::fromRoute('dd_action_center.dd_phone_action_form', [
                'node' => $campaign->id(),
                'legislator_pid' => $governor->id(),
                'paragraph' => $governor_action->id(),
                'is_governor' => 1,
              ]);

              $form['governor']['governor_actions']['phone_form'] = [
                '#type' => 'container',
                '#attributes' => ['class' => ['action-link-wrapper']],
                'link' => [
                  '#type' => 'link',
                  '#url' => $phone_form_url,
                  '#attributes' => [
                    'class' => ['use-ajax', 'phone-action-open', 'button--action', 'action-link--phone'],
                    'data-dialog-type' => 'modal',
                    'data-dialog-options' => \Drupal\Component\Serialization\Json::encode([
                      'resizable' => TRUE,
                      'width' => 'auto',
                    ]),
                  ],
                  '#title' => t('Call Governor'),
                ],
                '#attached' => [
                  'library' => 'dd/action-phone',
                ]
              ];
            }
          }


          if ($legislators = $this->getLegislators()) {

            foreach ($legislators as $legislator) {
              $legislator_form = [];
              $term = $this->getTerm($legislator->id());
              $legislator_info = [
                'name_' . $legislator->id() => [
                  '#type' => 'container',
                  '#attributes' => ['class' => ['legislator-name-wrapper']],
                  'legislator-name' => [
                    '#type' => 'link',
                    '#title' => $legislator->getFirstName()  . ' ' . $legislator->getLastName() . ' | ' . $term->getHouse(),
                    '#attributes' => ['class' => ['legislator-name']],
                    '#url' => Url::fromRoute('entity.dd_person.canonical', ['dd_person' => $legislator->id()]),
                  ],
                ],
                'info_' . $legislator->id() => [
                  '#type' => 'item',
                  '#name' => 'legislator-party-district',
                  '#title' => $term->getParty() . ' | District ' . $term->getDistrict(),
                ],
              ];

              if (isset($actions[$legislator->id()]) && isset($actions[$legislator->id()]['email_action']) && isset($actions[$legislator->id()]['email_action']['email_url'])) {
                $action = $actions[$legislator->id()]['email_action']['paragraph'];
                $campaign_action_id = self::getCampaignActionIdForParagraph($action);
                $legislator_form['email_action'] = [
                  '#type' => 'container',
                  '#attributes' => ['class' => ['action-link-wrapper']],
                  'link' => [
                    '#type' => 'link',
                    '#title' => 'Send An Email',
                    '#url' => $actions[$legislator->id()]['email_action']['email_url'],
                    '#attributes' => [
                      'class' => ['button--action', 'action-link--email'],
                      'target' => $actions[$legislator->id()]['email_action']['url_target'],
                      'onClick' => "Drupal.behaviors.campaigns.logActionMetric('" . $campaign->id() . "', 'email_action', '" . $campaign_action_id . "','" . $action->id() . "', '" . $legislator->id() . "');",
                    ],
                  ],
                ];
              }

              if (isset($actions[$legislator->id()]) && isset($actions[$legislator->id()]['fax_action'])) {
                $action = $actions[$legislator->id()]['fax_action']['paragraph'];
                $campaign_action_id = self::getCampaignActionIdForParagraph($action);

                $leg_name = ($term->getHouse() === "Senate" ? "Senator " : "Assemblymember ") .
                  $legislator->getFirstName() . ' ' . $legislator->getLastName();

                $fax_args = [
                  'first' => $first_name,
                  'last' => $last_name,
                  'street' => $street,
                  'city' => $city,
                  'state' => $state,
                  'zip' => $zipcode,
                  'to' => urlencode($leg_name),
                  'subject' => urlencode(strip_tags($action->get('field_subject')->value)),
                  'body' => urlencode(strip_tags($action->get('field_action_body')->value)),
                  'campaign_id' => $campaign->id(),
                  'campaign_action_id' => $campaign_action_id,
                  'campaign_action_paragraphs_id' => $action->id(),
                  'action_id' => 'fax_action',
                  'target_pid' => $legislator->id(),
                ];

                $url = Url::fromRoute(
                  'dd_fax_service.fax_form',
                  array(
                    'fax_num' => CommonHelper::cleanDigit($legislator->getCapitolFax()),
                    'arg' => http_build_query($fax_args))
                  );
                $legislator_form['fax_action'] = [
                  '#type' => 'container',
                  '#attributes' => ['class' => ['action-link-wrapper']],
                  'link' => [
                    '#type' => 'link',
                    '#title' => 'Send A Fax',
                    '#url' => $url,
                    '#attributes' => [
                      'class' => ['button--action', 'action-link--fax'],
                      'target' => '_blank',
                    ],
                    '#attached' => [
                      'library' => 'dd/action-fax',
                    ],

                  ],
                ];
              }

              if (isset($actions[$legislator->id()]) && isset($actions[$legislator->id()]['tweet_legislator_action'])) {
                $action = $actions[$legislator->id()]['tweet_legislator_action']['paragraph'];
                $campaign_action_id = self::getCampaignActionIdForParagraph($action);
                $current_url = Url::fromRoute('<current>',[],['absolute' => true]);
                $twitter_handle = strtolower($legislator->getTwitterHandle());
                $is_append_url =
                  \Drupal::configFactory()->getEditable('dd_action_center.settings')
                  ->get('action_setting.twitter_append_url');
                // Legislator table has @ in front of handle already.
                if (!empty ($twitter_handle)) {
                  $url = Url::fromUri('https://twitter.com/intent/tweet', [
                    'query' => [
                      'text' => '.' . $twitter_handle . ' ' . $action->get('field_sample_tweet')->value,
                      'url' => ($is_append_url ? $current_url->toString():'')
                    ],
                  ]);
                  $legislator_form['tweet_legislator_action'] = [
                    '#type' => 'container',
                    '#attributes' => ['class' => ['action-link-wrapper']],
                    'link' => [
                      '#type' => 'link',
                      '#title' => 'Send a Tweet',
                      '#url' => $url,
                      '#attributes' => [
                        'class' => ['button--action', 'action-link--tweet'],
                        'onClick' => "Drupal.behaviors.campaigns.logActionMetric('" . $campaign->id() . "', 'tweet_legislator', '" . $campaign_action_id . "','" . $action->id() . "', '" . $legislator->id() . "');",
                      ],
                    ],
                    '#attached' => [
                      'library' => 'dd/action-tweet',
                    ],
                  ];
                }
              }

              if (isset($actions[$legislator->id()]) && isset($actions[$legislator->id()]['phone_action'])) {
                $action = $actions[$legislator->id()]['phone_action']['paragraph'];
                $capitol_phone = $legislator->getCapitolPhone();
                if (!empty($capitol_phone)) {
                  $phone_form_url = \Drupal\Core\Url::fromRoute('dd_action_center.dd_phone_action_form', [
                    'node' => $campaign->id(),
                    'legislator_pid' => $legislator->id(),
                    'paragraph' => $action->id(),
                  ]);

                  $legislator_form['phone_form'] = [
                    '#type' => 'container',
                    '#attributes' => ['class' => ['action-link-wrapper']],
                    'link' => [
                      '#type' => 'link',
                      '#url' => $phone_form_url,
                      '#attributes' => [
                        'class' => ['use-ajax', 'phone-action-open', 'button--action', 'action-link--phone'],
                        'data-dialog-type' => 'modal',
                        'data-dialog-options' => \Drupal\Component\Serialization\Json::encode([
                          'resizable' => TRUE,
                          'width' => 'auto',
                        ]),
                      ],
                      '#title' => t('Call Legislator'),
                    ],
                    '#attached' => [
                      'library' => 'dd/action-phone',
                    ]
                  ];
                }
              }

              $non_legislator_targeted_actions = [];
              if (isset($actions[$legislator->id()])) {
                foreach ($actions[$legislator->id()] as $paragraph_type => $action_paragraph) {
                  if (in_array($paragraph_type, $non_legislator_targeted_paragraph_types)) {
                    $non_legislator_targeted_actions[$paragraph_type][] = $action_paragraph['paragraph'];
                  }
                }
              }

              $legislator_form['non_legislator_target_actions'] = self::buildNonLegislatorTargetedActions($non_legislator_targeted_actions, $legislator->id());

              // Only show the legislator if they have actions.
              if (isset($actions[$legislator->id()])) {
                $form[] = $legislator_info;
                $form[] = [
                  '#type' => 'container',
                  '#attributes' => ['class' => ['legislator-actions']],
                  'legislator_actions' => $legislator_form
                ];
              }
            }
          }
        }
      }
    }

    $form['#attached']['library'][] = 'dd/action-grouping';

    return $form;
  }

  /**
   * Build Non Legislator Targeted actions form.
   *
   * @param array $non_legislator_targeted_actions
   *   Array of paragraphs.
   * @param int $legislator_pid
   *   Legislator PID for button clicked.
   *
   * @return array
   *   Form array for built paragraphs.
   */
  public static function buildNonLegislatorTargetedActions($non_legislator_targeted_actions, $legislator_pid = 0) {
    $form = [];
    $view_builder = \Drupal::entityTypeManager()
      ->getViewBuilder('paragraph');


    foreach ($non_legislator_targeted_actions as $paragraphs) {
      foreach ($paragraphs as $paragraph) {
        $build = $view_builder->view($paragraph, 'full');
        $build['#legislator_pid'] = $legislator_pid;
        $description_wrapper = [];
        $build_wrapper = [
          '#type' => 'container',
          'action' => $build,
        ];
        if ($paragraph->getType() != 'display_text') {
          $build_wrapper['#attributes'] = [
            'class' => [
              'action',
              'display'
            ]
          ];
        }

        // Download action has description field, move it outside button.
        if ($paragraph->getType() == 'download_action') {
          $description_wrapper = [
            '#type' => 'container',
            '#attributes' => ['class' => ['description-wrapper']],
            'description' => [
              '#type' => 'item',
              '#name' => 'action_description',
              '#markup' => $paragraph->get('field_download_description')->value,
            ],
          ];
        }
        $form['non_legislator_paragraphs'][] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['action-wrapper']],
          'description' => $description_wrapper,
          'build' => $build_wrapper,
        ];
      }
    }
    return $form;
  }
  /**
   * Process tokens for actions.
   *
   * @param Paragraph $action
   *   Paragraph action
   * @param Node $campaign
   *   Campaign Node
   * @param User $user
   *   User
   */
  public static function processActionTokens(&$action, $campaign, $user) {
    $token = \Drupal::token();

    switch ($action->getType()) {
      case 'email_action':
        $body = $token->replace(
          $action->get('field_email_body')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_email_body', $body);

        $subject = $token->replace(
          $action->get('field_email_subject')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_email_subject', $subject);

        break;

      case 'email_governor':
        $body = $token->replace(
          $action->get('field_email_body')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_email_body', $body);

        $subject = $token->replace(
          $action->get('field_email_subject')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_email_subject', $subject);
        break;

      case 'fax_action':
        $body = $token->replace(
          $action->get('field_action_body')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );

        // Only plain text allowed for fax.
        $body = trim(strip_tags(html_entity_decode($body)));
        $action->set('field_action_body', $body);

        $subject = $token->replace(
          $action->get('field_subject')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_subject', $subject);
        break;

      case 'fax_governor':
        $body = $token->replace(
          $action->get('field_action_body')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );

        // Only plain text allowed for fax.
        $body = trim(strip_tags(html_entity_decode($body)));
        $action->set('field_action_body', $body);

        $subject = $token->replace(
          $action->get('field_subject')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_subject', $subject);
        break;

      case 'tweet_legislator_action':
        $body = $token->replace(
          $action->get('field_sample_tweet')->value,
          [
            'user' => $user,
            'campaign' => $campaign,
          ]
        );
        $action->set('field_sample_tweet', $body);
        break;
    }
  }

  /**
   * Get the Campaign Action ID for the paragraph if available.
   *
   * @param Paragraph $paragraph
   *   Paragraph
   *
   * @return int
   *   Campaign Action ID, or 0 if not found.
   */
  public static function getCampaignActionIdForParagraph($paragraph) {
    if (
      in_array('parent_field_name', array_keys($paragraph->getFields())) &&
      $paragraph->get('parent_field_name')->value == 'field_actions'
    ) {
      $node = \Drupal\node\Entity\Node::load($paragraph->get('parent_id')->value);
      if ($node->getType() == 'campaign_action') {
        return $node->id();
      }
      elseif ($node->getType() == 'campaign') {
        return 0;

      }
    }
    return 0;
  }
}


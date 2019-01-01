<?php

use Drupal\views\ViewExecutable;
use Drupal\views\Plugin\views\query\QueryPluginBase;

/**
 * Implements hook_views_query_alter().
 */
function dd_hearing_views_query_alter(ViewExecutable $view, QueryPluginBase $query) {
  if ($view->id() == 'hearing_components' && $view->current_display == 'bill_votes') {
    /** @var $query \Drupal\views\Plugin\views\query\Sql */
    $query->addWhereExpression(1, 'BillVoteSummary_BillDiscussion.VoteDate = Hearing.date');
  }
  elseif ($view->id() == 'hearings_faceted' && $view->current_display == 'page_1') {
    // Process faceted filters and apply to views_field_view view.
    $exposed_input = $view->getExposedInput();
    $pids = array();
    $bill_type = '';
    $bill_number = '';
    if (isset($exposed_input['type_number'])) {
      // Split the bill type/number into vars.
      $bill_type_number = explode(' ', $exposed_input['type_number'], 2);
      if (count($bill_type_number) == 2) {
        $bill_type = $bill_type_number[0];
        $bill_number = $bill_type_number[1];
      }
    }

    if (isset($exposed_input['f'])) {
      foreach ($exposed_input['f'] as $input) {
        list($key, $value) = explode(':', $input);

        if ($key == 'hearing_speaker_name') {
          list($last, $first) = explode(',', $value, 2);
          $last = trim($last);
          $first = trim($first);
          if ($last != '' && $first != '') {
            // Get PIDs that match the name facet.
            $name_pids = \Drupal\dd_person\Entity\DdPerson::getPidByName($last, $first);
            $pids = array_merge($pids, $name_pids);
          }
        }
      }
      $pid_args = implode('+', $pids);
      // Alter the views_field_view arguments to add in PIDs bill type/number.
      $has_pids = ($pid_args != '');
      $has_bill_type_number = ($bill_type != '' && $bill_number != '');

      if ($has_pids && $has_bill_type_number) {
        $view->field['view']->options['arguments'] = '{{ raw_fields.hid }}/' . $pid_args . '/' . $bill_type . '/' . $bill_number . '/{{ raw_fields.name }}';
      }
      elseif ($has_pids) {
        $view->field['view']->options['arguments'] = '{{ raw_fields.hid }}/' . $pid_args . '/all/all/{{ raw_fields.name }}';
      }
      elseif ($has_bill_type_number) {
        $view->field['view']->options['arguments'] = '{{ raw_fields.hid }}/all/' . $bill_type . '/' . $bill_number . '/{{ raw_fields.name }}';
      }
    }
    elseif ($bill_type != '' && $bill_number != '') {
      $view->field['view']->options['arguments'] = '{{ raw_fields.hid }}/all/' . $bill_type . '/' . $bill_number . '/{{ raw_fields.name }}';
    }
  }

  // Perform Committee, Hearing Inclusion / Exclusions.
  if (\Drupal\dd_base\DdBase::getSiteType() == \Drupal\dd_base\DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_WHITELABEL) {
    $config = Drupal::config(\Drupal\dd_admin\DdAdmin::getDdAdminContentSettingsName());
    $hearings_include_exclude = $config->get('hearings_include_exclude');
    $hids = $config->get('hearing_hids');
    $committees_include_exclude = $config->get('committees_include_exclude');
    $cn_ids = $config->get('committee_cn_ids');

    if ($hids) {
      if ($view->id() == 'hearings_faceted') {
        $query->addCondition('hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
      elseif ($view->id() == 'person_testimony_years') {
        $query->addWhere(0, 'Hearing_Video.hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
      elseif ($view->id() == 'organization_testimony_years' || $view->id() == 'organization_testimony_per_year') {
        $query->addWhere(0, 'CombinedRepresentations.hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
      elseif ($view->id() == 'bill_discussion_list' || $view->id() == 'bill_hearings') {
        $query->addWhere(0, 'Hearing_BillDiscussion.hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
      elseif ($view->id() == 'hearing_agenda') {
        $query->addWhere(0, 'Hearing_HearingAgenda.hid', $hids, $hearings_include_exclude ? 'IN' : 'NOT IN');
      }
    }

    if ($cn_ids) {
      $cids = \Drupal\dd_committee\Entity\DdCommittee::getCommitteeIdsForCommitteeNameId($cn_ids);
      if ($cids) {
        if ($view->id() == 'hearings_faceted') {
          $query->addCondition('cn_ids', $cn_ids, $committees_include_exclude ? 'IN' : 'NOT IN');
        }
        elseif ($view->id() == 'organization_testimony_per_year') {
          $query->addWhere(0, 'Committee_CommitteeHearings.cid', $cids, $committees_include_exclude ? 'IN' : 'NOT IN');
        }
        elseif ($view->id() == 'bill_discussion_list' || $view->id() == 'bill_hearings' || $view->id() == 'hearing_agenda') {
          // Use Committee IDs since no cn_ids in view available.
          $query->addWhere(0, 'Committee_CommitteeHearings.cid', $cids, $committees_include_exclude ? 'IN' : 'NOT IN');
        }
      }
    }
  }
}

<?php

namespace Drupal\dd_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\dd_base\DdBase;
use Drupal\dd_base\DdEnvironment;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_person\Entity\DdPerson;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class DdSearchAutocompleteController extends ControllerBase {
  private $allMatches = [];
  private $matches = [];

  private $numBillMatches = 4;
  private $numCommitteeMatches = 4;
  private $numPersonMatches = 4;
  private $numOrganizationMatches = 4;
  private $state = '';
  private $stateDomainUrl = '';

  /**
   * DdSearchAutocompleteController constructor.
   */
  public function __construct() {
    $this->state = DdBase::getCurrentState();
  }

  /**
   * Get state for autocomplete.
   *
   * @return string
   *   State for autocomplete.
   */
  protected function getState() {
    return $this->state;
  }

  /**
   * Set state for autocomplete.
   *
   * @param string $state
   *   State for autocomplete.
   *
   * @return \Drupal\dd_search\Controller\DdSearchAutocompleteController
   *   This DdSearchAutocompleteController object.
   */
  protected function setState($state) {
    $this->state = strtoupper($state);
    return $this;
  }

  /**
   * Set state domain url.
   *
   * @param string $url
   *   Url.
   *
   * @return \Drupal\dd_search\Controller\DdSearchAutocompleteController
   *   This DdSearchAutocompleteController object.
   */
  protected function setStateDomainUrl($url) {
    $this->stateDomainUrl = $url;
    return $this;
  }

  /**
   * Get State Domain URL.
   *
   * @return string
   *   State domain url.
   */
  protected function getStateDomainUrl() {
    return $this->stateDomainUrl;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static();
  }

  /**
   * Build All Matches from each match array.
   * @return array
   *   Array of matches
   */
  public function buildAllMatches() {
    $areas = ['hearings', 'bills', 'speakers', 'committees', 'organizations'];
    foreach ($areas as $area) {
      if (isset($this->matches[$area])) {
        $this->allMatches[] = array(
          'label' => '---',
          'value' => '',
        );

        foreach ($this->matches[$area] as $key => $val) {
          $this->allMatches[] = $val;
        }
      }
    }
    return $this->allMatches;
  }
  /**
   * Autocomplete the label of an entity.
   *
   * @param string $state
   *   State to run autocomplete on.
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object that contains the typed tags.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The matched entity labels as a JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown if the selection settings key is not found in the key/value store
   *   or if it does not match the stored data.
   */
  public function handleAutocomplete($state, Request $request) {
    $all_matches = [];

    if ($state == '') {
      if (DdBase::getSiteType() == DdEnvironment::DD_ENVIRONMENT_SITE_TYPE_BASE) {
        // Use CA as default state for base site.
        $state = 'CA';
      }
      else {
        $state = DdBase::getCurrentState();
      }
    }

    $this->setState($state);
    $state_domain_urls = DdBase::getStateDomains(TRUE);
    $this->setStateDomainUrl($state_domain_urls[$state]);

    if ($input = $request->query->get('q')) {

      // @todo security filtering on input.
      // Show Keyword Matches.
      $this->matches['hearings'][] = array(
        'label' => '<span class="dd-search-text">' . $input . '</span> - <span class="dd-search-action--search-hearings">Search Hearings Text</span>',
        'value' => $this->getStateDomainUrl() . '/search?fulltext=' . urlencode($input),
      );

      // Only check for >= 3 chars.
      if (strlen($input) >= 3) {
        // Get Bill Matches.
        $this->buildBillMatches($input);

        // Get Person Matches.
        $this->buildPersonMatches($input);

        // Get Committee Matches.
        $this->buildCommitteeMatches($input);

        // Get Organization Matches.
        $this->buildOrganizationMatches($input);

        $all_matches = $this->buildAllMatches();
      }
    }

    return new JsonResponse($all_matches);
  }

  /**
   * Get Bill matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildBillMatches($term) {
    $bills = DdBill::getBillMatches($term, $this->numBillMatches, $this->getState());
    if ($bills) {
      foreach ($bills as $bill) {
        $this->matches['bills'][] = array(
          'label' => '(' . $bill->sessionYear . ') <span class="dd-search-text">' . $bill->type . ' ' . $bill->number . '</span>: ' . $bill->subject,
          'value' => $this->getStateDomainUrl() . '/bill/' . $bill->bid,
        );
        $this->matches['bills'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--view-bill">- View Bill</span>',
          'value' => $this->getStateDomainUrl() . '/bill/' . $bill->bid,
        );
        $this->matches['bills'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--search-hearings">- Search Hearings on Bill</span>',
          'value' => $this->getStateDomainUrl() . '/search?type=' . $bill->type . '&number=' . $bill->number,
        );
      }
    }
  }

  /**
   * Build Person matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildPersonMatches($term) {
    $persons = DdPerson::getPersonMatches($term, $this->numPersonMatches, $this->getState(), FALSE);
    if ($persons) {
      foreach ($persons as $person) {
        $this->matches['speakers'][] = array(
          'label' => '<span class="dd-search-text">' . $person->fullname . '</span> <span class="dd-search-text--person-type">' . $person->type_label . '</span>',
          'value' => $this->getStateDomainUrl() . '/person/' . $person->pid,
        );

        $this->matches['speakers'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--view-speaker">- View Profile</span>',
          'value' => $this->getStateDomainUrl() . '/person/' . $person->pid,
        );

        $this->matches['speakers'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--search-hearings">- Search Hearings by Speaker</span>',
          'value' => $this->getStateDomainUrl() . '/search?speaker_pid=' . urlencode($person->first . ' ' . $person->last . ' (' . $person->pid . ')'),
        );
      }
    }
  }

  /**
   * Build Committee matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildCommitteeMatches($term) {
    $committees = DdCommittee::getCommitteeMatches($term, $this->numCommitteeMatches, $this->getState());
    if ($committees) {
      foreach ($committees as $committee) {
        $this->matches['committees'][] = array(
          'label' => '<span class="dd-search-text">' . $committee->name . '</span>',
          'value' => $this->getStateDomainUrl() . '/committee/' . $committee->cn_id,
        );
        $this->matches['committees'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--view-committee">- View Committee</span>',
          'value' => $this->getStateDomainUrl() . '/committee/' . $committee->cn_id,
        );

        $this->matches['committees'][] = array(
          'label' => '<span class="dd-search-action dd-search-action--search-hearings">- Search Hearings by Committee</span>',
          'value' => $this->getStateDomainUrl() . '/search?cn_id=' . $committee->cn_id,
        );
      }
    }
  }
  /**
   * Build Organization matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildOrganizationMatches($term) {
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('Organizations', 'o');
    $query->fields('o', array('name', 'oid'));
    $query->join('OrganizationStateAffiliation', 'osa', 'osa.oid = o.oid');
    $query->condition('osa.state', $this->getState());
    $query->condition('name', '%' . $term . '%', 'LIKE');
    $query->range(0, $this->numOrganizationMatches);
    $results = $query->execute()->fetchAll();
    if ($results) {
      foreach ($results as $result) {
        $this->matches['organizations'][] = array(
          'label' => '<span class="dd-search-text">' . $result->name . '</span> (Organization)',
          'value' => $this->getStateDomainUrl() . '/organization/' . $result->oid,
        );
      }
    }
  }
}

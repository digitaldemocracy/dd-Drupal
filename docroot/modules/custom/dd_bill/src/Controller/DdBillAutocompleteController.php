<?php

namespace Drupal\dd_bill\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\dd_base\DdBase;
use Drupal\dd_bill\Entity\DdBill;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class DdBillAutocompleteController extends ControllerBase {
  private $numBillMatches = 20;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static();
  }

  /**
   * Autocomplete the label of an entity.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object that contains the typed tags.
   * @param string $session_years
   *   Filter by session year, comma delimited.
   * @param bool $use_bid_value
   *   If TRUE, will use BID value for option.
   * @param bool $use_entity_id
   *   If TRUE, will add entity id in () to value for entity autocomplete.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The matched entity labels as a JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown if the selection settings key is not found in the key/value store
   *   or if it does not match the stored data.
   */
  public function handleAutocomplete(Request $request, $session_years = '', $use_bid_value = FALSE, $use_entity_id = FALSE) {
    $matches = [];

    if ($input = $request->query->get('q')) {
      // @todo security filtering on input.
      // Only check for >= 2 chars.
      if (strlen($input) >= 1) {
        if ($session_years != '') {
          $session_years = explode(':', $session_years);
        }
        else {
          $session_years = [];
        }
        // Get Bill Matches.
        $matches = $this->buildBillMatches($input, $session_years, $use_bid_value, $use_entity_id);
      }
    }

    return new JsonResponse($matches);
  }

  /**
   * Get Bill matches.
   *
   * @param string $term
   *   Term to search by
   * @param array $session_years
   *   Filter by session years.
   * @param bool $use_bid_value
   *   If TRUE, will use BID value for option.
   * @param bool $use_entity_id
   *   If TRUE, will add entity id in () to value for entity autocomplete.
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildBillMatches($term, $session_years = [], $use_bid_value = FALSE, $use_entity_id = FALSE) {
    $matches = [];
    $bills = DdBill::getBillMatches($term, $this->numBillMatches, '', $session_years);

    if ($bills) {
      foreach ($bills as $bill) {
        if ($use_bid_value) {
          $value = $bill->bid;
        }
        else {
          $value = $bill->type . ' ' . $bill->number;
        }

        if ($use_entity_id) {
          $value .= ' (' . $bill->dr_id . ')';
        }
        // Get Bill Version Current for bill.
        $matches[] = array(
          'label' => '(' . $bill->sessionYear . ') <span class="dd-search-text">' . $bill->type . ' ' . $bill->number . '</span>: ' . $bill->subject,
          'value' => $value,
        );
      }
    }
    return $matches;
  }
}

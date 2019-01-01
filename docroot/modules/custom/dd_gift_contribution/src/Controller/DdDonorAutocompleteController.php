<?php

namespace Drupal\dd_gift_contribution\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_base\DdBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class DdDonorAutocompleteController extends ControllerBase {
  protected $numDonorMatches = 10;

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
   *
   * @param string $source
   *   Source of gift or contribution.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The matched entity labels as a JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown if the selection settings key is not found in the key/value store
   *   or if it does not match the stored data.
   */
  public function handleAutocomplete(Request $request, $source = 'gift') {
    $all_matches = [];

    if ($input = $request->query->get('q')) {
      // @todo security filtering on input.

      // Only check for >= 3 chars.
      if (strlen($input) >= 3) {
        // Get Donor Matches.
        $all_matches = ($source == 'contribution') ? $this->buildContributionDonorMatches($input) : $this->buildGiftDonorMatches($input);
      }
    }

    return new JsonResponse($all_matches);
  }

  /**
   * Build Gift Donor matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildGiftDonorMatches($term) {
    $matches = [];
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = \Drupal::database()->select('GiftCombined', 'gc');
    $query->fields('gc', array('sourceName'));
    $query->condition('gc.state', DdBase::getCurrentState());
    $query->condition('gc.sourceName', '%' . $term . '%', 'LIKE');
    $query->groupBy('gc.sourceName');
    $query->range(0, $this->numDonorMatches);
    $query->orderBy('gc.sourceName');
    $results = $query->execute()->fetchAll();
    if ($results) {
      foreach ($results as $result) {
        $matches[]  = array(
          'label' => '<span class="dd-gift-contribution--donor">' . $result->sourceName . '</span>',
          'value' => $result->sourceName,
        );
      }
    }
    return $matches;
  }
  /**
   * Build Contribution Donor matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildContributionDonorMatches($term) {
    $matches = [];
    /** @var \Drupal\Core\Database\Query\Select $query */
    $query = \Drupal::database()->select('Contribution', 'c');
    $query->fields('c', array('donorName'));
    $query->condition('c.state', DdBase::getCurrentState());
    $query->condition('c.donorName', '%' . $term . '%', 'LIKE');
    $query->groupBy('c.donorName');
    $query->range(0, $this->numDonorMatches);
    $query->orderBy('c.donorName');
    $results = $query->execute()->fetchAll();
    if ($results) {
      foreach ($results as $result) {
        $matches[]  = array(
          'label' => '<span class="dd-gift-contribution--donor">' . $result->donorName . '</span>',
          'value' => $result->donorName,
        );
      }
    }
    return $matches;
  }
}

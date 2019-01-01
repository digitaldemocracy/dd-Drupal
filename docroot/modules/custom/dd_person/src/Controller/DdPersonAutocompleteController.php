<?php

namespace Drupal\dd_person\Controller;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Tags;
use Drupal\Core\Controller\ControllerBase;
use Drupal\dd_person\Entity\DdPerson;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a route controller for entity autocomplete form elements.
 */
class DdPersonAutocompleteController extends ControllerBase {
  private $numPersonMatches = 10;

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
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The matched entity labels as a JSON response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   Thrown if the selection settings key is not found in the key/value store
   *   or if it does not match the stored data.
   */
  public function handleAutocomplete(Request $request) {
    $matches = [];

    if ($input = $request->query->get('q')) {
      // @todo security filtering on input.
      // Only check for > 2 chars.
      if (strlen($input) > 2) {
        // Get Person Matches.
        $matches = $this->buildPersonMatches($input);
      }
    }

    return new JsonResponse($matches);
  }

  /**
   * Get Person matches.
   *
   * @param string $term
   *   Term to search by
   *
   * @return array
   *   Array of key/value matches
   */
  protected function buildPersonMatches($term) {
    $matches = [];
    $persons = DdPerson::getPersonMatches($term, $this->numPersonMatches, '', FALSE);

    if ($persons) {
      foreach ($persons as $person) {
        // Create "term (pid)" like taxonomy autocomplete.
        $key = $person->first . ' ' . $person->last . ' (' . $person->pid . ')';
        $key = preg_replace('/\s\s+/', ' ', str_replace("\n", '', trim(Html::decodeEntities(strip_tags($key)))));
        // Names containing commas or quotes must be wrapped in quotes.
        $key = Tags::encode($key);

        $matches[] = array(
          'label' => '<span class="dd-search-text">' . $person->fullname . '</span> <span class="dd-search-text--person-type">' . $person->type_label . '</span>',
          'value' => $key,
        );
      }
    }
    return $matches;
  }
}

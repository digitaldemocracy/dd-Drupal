<?php

namespace Drupal\dd_committee\Controller;

use Drupal\Core\Entity\Controller\EntityViewController;
use Drupal\Core\Url;
use Drupal\dd_clip\Entity\DdClip;
use Drupal\dd_committee\Entity\DdCommittee;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DdCommitteeViewController extends EntityViewController {

  /**
   * View Committee by Committee Name Id.
   *
   * @param string $cn_id
   *   Committee Name Id to load.
   *
   * @return mixed
   *   Rendered view.
   */
  public function viewCommitteeByCommitteeNameId($cn_id) {
    $entity = DdCommittee::loadCommitteeByNameId($cn_id);
    if ($entity) {
      return $this->view($entity);
    }
    throw new NotFoundHttpException();
  }
}

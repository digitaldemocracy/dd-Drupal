<?php

namespace Drupal\dd_bill\Entity;
use Drupal\Core\Database\Database;
use Drupal\dd_base\DdBase;

/**
 * Defines the DD Bill Version Current entity.
 *
 * @ingroup dd_bill
 *
 * @ContentEntityType(
 *   id = "dd_bill_version_current",
 *   label = @Translation("DD Bill Version Current"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_bill\DdBillVersionCurrentListBuilder",
 *     "views_data" = "Drupal\dd_bill\Entity\DdBillVersionCurrentViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_bill\Form\DdBillVersionForm",
 *       "edit" = "Drupal\dd_bill\Form\DdBillVersionForm",
 *     },
 *     "access" = "Drupal\dd_bill\DdBillVersionAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_bill\DdBillVersionHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "BillVersionCurrent",
 *   admin_permission = "administer dd bill version entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "vid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_bill_version_current/{dd_bill_version_current}",
 *     "edit-form" = "/admin/structure/dd_bill_version_current/{dd_bill_version_current}/edit",
 *     "collection" = "/admin/structure/dd_bill_version_current",
 *   },
 *   field_ui_base_route = "dd_bill_version_current.settings"
 * )
 */
class DdBillVersionCurrent extends DdBillVersion implements DdBillVersionInterface {

  /**
   * Get dr_id field from given bid.
   *
   * @param string $bid
   *   Bill ID
   *
   * @return object
   *   Bill object with dr_id property.
   */
  public static function getByBid($bid) {
    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('BillVersionCurrent', 'b');
    $query->fields('b', ['dr_id']);
    $query->condition('b.state', DdBase::getCurrentState());
    $query->condition('b.bid', $bid);
    $dr_id = $query->execute()->fetchField();

    if (!empty($dr_id)) {
      return self::load($dr_id);
    }
    return NULL;
  }
}

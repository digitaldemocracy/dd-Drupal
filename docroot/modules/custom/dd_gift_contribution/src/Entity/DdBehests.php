<?php

namespace Drupal\dd_gift_contribution\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\dd_base\Entity\DdBaseStateField;
use Drupal\user\UserInterface;

/**
 * Defines the DD Behests entity.
 *
 * @ingroup dd_gift_contribution
 *
 * @ContentEntityType(
 *   id = "dd_behests",
 *   label = @Translation("DD Behests"),
 *   handlers = {
 *     "storage" = "Drupal\dd_base\Entity\Sql\DdBaseStateFieldSqlContentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dd_gift_contribution\DdBehestsListBuilder",
 *     "views_data" = "Drupal\dd_gift_contribution\Entity\DdBehestsViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dd_gift_contribution\Form\DdBehestsForm",
 *       "edit" = "Drupal\dd_gift_contribution\Form\DdBehestsForm",
 *     },
 *     "access" = "Drupal\dd_gift_contribution\DdBehestsAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dd_gift_contribution\DdBehestsHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "Behests",
 *   admin_permission = "administer dd behests entities",
 *   entity_keys = {
 *     "id" = "dr_id",
 *     "label" = "description",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dd_behests/{dd_behests}",
 *     "edit-form" = "/admin/structure/dd_behests/{dd_behests}/edit",
 *     "collection" = "/admin/structure/dd_behests",
 *   },
 *   field_ui_base_route = "dd_behests.settings"
 * )
 */
class DdBehests extends DdBaseStateField implements DdBehestsInterface {
  /**
   * @inheritDoc
   */
  public function getOfficial() {
    return $this->get('official')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setOfficial($official) {
    $this->set('official', $official);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDatePaid() {
    return $this->get('datePaid_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDatePaid($date_paid) {
    $this->set('datePaid_ts', $date_paid);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getSessionYear() {
    return $this->get('sessionYear')->value;
  }

  /**
   * @inheritDoc
   */
  public function setSessionYear($session_year) {
    $this->set('sessionYear', $session_year);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPayor() {
    return $this->get('payor')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setPayor($payor) {
    $this->set('payor', $payor);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getAmount() {
    return $this->get('amount')->value;
  }

  /**
   * @inheritDoc
   */
  public function setAmount($amount) {
    $this->set('amount', $amount);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPayee() {
    return $this->get('payee')->getValue();
  }

  /**
   * @inheritDoc
   */
  public function setPayee($payee) {
    $this->set('payee', $payee);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * @inheritDoc
   */
  public function setDescription($description) {
    $this->set('description', $description);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getPurpose() {
    return $this->get('purpose')->value;
  }

  /**
   * @inheritDoc
   */
  public function setPurpose($purpose) {
    $this->set('purpose', $purpose);
    return $this;
  }

  /**
   * @inheritDoc
   */
  public function getNoticeReceived() {
    return $this->get('noticeReceived_ts')->value;
  }

  /**
   * @inheritDoc
   */
  public function setNoticeReceived($notice_received) {
    $this->set('noticeReceived_ts', $notice_received);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['official'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Official ID'))
      ->setSettings(array(
        'target_type' => 'dd_person',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['datePaid_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Date Paid'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['sessionYear'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Session Year'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['payor'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Payor ID'))
      ->setSettings(array(
        'target_type' => 'dd_payor',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['amount'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Amount'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['payee'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Payee Organization ID'))
      ->setSettings(array(
        'target_type' => 'dd_organization',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['purpose'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Purpose'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['noticeReceived_ts'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Notice Received'))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}

<?php

namespace Drupal\dd_bill\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\dd_bill\Form\DdBillStatementSearchForm;

/**
 * Provides a 'DdBillStatementSearchBlock' block.
 *
 * @Block(
 *  id = "dd_bill_statement_search_block",
 *  admin_label = @Translation("Bill Statement Search Block"),
 * )
 */
class DdBillStatementSearchBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = DdBillStatementSearchForm::create(\Drupal::getContainer());
    $builtForm = \Drupal::formBuilder()->getForm('Drupal\dd_bill\Form\DdBillStatementSearchForm');
    $render['form'] = $builtForm;

    return $render;
  }

}

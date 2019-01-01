<?php

namespace Drupal\dd_bill\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\dd_bill\Entity\DdBill;
use Drupal\dd_committee\Entity\DdCommittee;
use Drupal\dd_committee\Entity\DdCommitteeAuthors;
use Drupal\dd_person\Entity\DdPerson;
use Drupal\views\Plugin\views\field\PrerenderList;
use Drupal\views\ResultRow;

/**
 * Field handler to flag the node type.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dd_bill_authors")
 */
class DdBillAuthors extends PrerenderList {
  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['show_image'] = array('default' => 0);
    $options['bid'] = array('default' => '');
    $options['vid'] = array('default' => '');

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['show_image'] = [
      '#title' => $this->t('Output Author Image'),
      '#description' => $this->t('When enabled, outputs rendered person instead of list of authors text.'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['show_image'],
    ];
    $form['bid'] = [
      '#title' => $this->t('Bill ID'),
      '#description' => $this->t('BID such as CA_201520160SB844, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['bid'],
    ];
    $form['vid'] = [
      '#title' => $this->t('Bill Version ID'),
      '#description' => $this->t('VID such as CA_20150SB84495CHP, supports tokens'),
      '#type' => 'textfield',
      '#default_value' => $this->options['vid'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * {@inheritdoc}
   */
  public function getItems(ResultRow $values) {
    $index = 0;
    $items = array();

    // Call to getRenderTokens necessary ensure tokens are current for row.
    $this->getRenderTokens(NULL);

    if (!empty($this->options['bid'])) {
      $bid = $this->tokenizeValue($this->options['bid'], $values->index);
      $author_pids = DdBill::getBillAuthorsPidForBid($bid);
      if ($author_pids) {
        $authors = DdPerson::loadMultiple($author_pids);

        if ($this->options['show_image']) {
          $author = array_shift($authors);
          $image_file = $author->getImage();
          if ($image_file != '') {
            $url = Url::fromRoute('entity.dd_person.canonical', array('dd_person' => $author->id()));
            $image_array = [
              '#type' => '#markup',
              '#markup' => '<img src="https://s3-us-west-2.amazonaws.com/dd-drupal-files/images/' . $image_file . '" alt="Author photo">',
              ];
            $image_url = Link::fromTextAndUrl($image_array, $url);
            $items[0]['raw'] = $image_url->toRenderable();
          }
        }
        else {
          foreach ($authors as $author) {
            $url = Link::createFromRoute($author->getFullNameLastFirst(), 'entity.dd_person.canonical', array('dd_person' => $author->id()));
            $items[$index++]['raw'] = $url->toRenderable();
          }
        }
      }
      else {
        if (!$this->options['show_image']) {

          $vid = '';
          if (!empty($this->options['vid'])) {
            $vid = $this->tokenizeValue($this->options['vid'], $values->index);
          }
          $committee_cids = DdCommitteeAuthors::getCommitteeAuthorsForBill($bid, $vid);
          if ($committee_cids) {
            $committees = DdCommittee::loadMultiple($committee_cids);
            foreach ($committees as $committee) {
              $url = Link::createFromRoute($committee->getName(), 'entity.dd_committee.canonical', array('dd_committee' => $committee->id()));
              $items[$index++]['raw'] = $url->toRenderable();
            }
          }
        }
      }
    }

    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function render_item($count, $item) {
    return render($item['raw']);
  }
}

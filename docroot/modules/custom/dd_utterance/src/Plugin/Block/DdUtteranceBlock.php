<?php

namespace Drupal\dd_utterance\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Drupal\dd_base\DdBase;

/**
 * Provides a 'DdUtteranceBlock' block.
 *
 * @Block(
 *  id = "dd_utterance_block",
 *  admin_label = @Translation("DD Utterance Block"),
 * )
 */
class DdUtteranceBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['dd_utterance_block'] = array(
      '#type' => 'container',
      '#attributes' => array(
        'class' => array(
          'utterances',
        ),
        'id' => 'utterances',
      ),
    );

    return $build;
  }

  /**
   * Generate Utterance Markup.
   *
   * @param int $hid
   *   Hearing ID
   *
   * @return string
   *   Utterance Markup
   */
  public function generateUtteranceMarkup($hid) {
    $person_row_start_html = '<tr class="utterance"><td class="views-field views-field-first">';
    $person_row_end_html = '</td>';
    $text_row_start_html = '<td class="views-field views-field-text">';
    $text_row_end_html = '</td></tr>';
    $table_header = '<table class="views-table views-view-table cols-0"><tbody>';
    $table_footer = '</tbody></table>';

    $concat_field = <<<HDOC
CONCAT('{$person_row_start_html}', Person_Utterance.first, ' ', Person_Utterance.last, '{$person_row_end_html}',
'{$text_row_start_html}',
'<div class="utterance-text" data-time="', Utterance.time ,'" data-youtubeid="', Video_Utterance.fileId, '" data-bid="', BillDiscussion_Utterance.bid,'" data-pid="', Person_Utterance.pid,'">',Utterance.text,
'<div class="col-share"><a class="link-blog hide" href="/node/add/blog/startTime=', Utterance.time, '&endTime=', Utterance.endTime, '&vid=', Video_Utterance.fileId, '">blog</a><a class="link-share hide" href="#"><span data-time="', Utterance.time, '" data-youtubeid="', Video_Utterance.fileId, '"></span></a></div>',
'</div>{$text_row_end_html}')
HDOC;

    $query = Database::getConnection('default', \Drupal\dd_base\DdBase::getDddbName())->select('currentUtterance', 'Utterance');
    $query->addExpression($concat_field, 'Text');
    $query->innerJoin('Video', 'Video_Utterance', 'Utterance.vid = Video_Utterance.vid');
    $query->innerJoin('Person', 'Person_Utterance', 'Utterance.pid = Person_Utterance.pid');
    $query->innerJoin('BillDiscussion', 'BillDiscussion_Utterance', 'Utterance.did = BillDiscussion_Utterance.did');
    $query->condition('Video_Utterance.hid', $hid);
    $query->condition('Utterance.state', DdBase::getCurrentState());
    $query->orderBy('Video_Utterance.position');
    $query->orderBy('Utterance.time');
    $result = $query->execute()->fetchCol();

    $data = $table_header . implode("\n", $result) . $table_footer;
    return $data;
  }
}

<?php

namespace Drupal\dd_fax_service\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface;

/**
 * Class DdFaxServiceHistoryController.
 *
 *  Returns responses for Dd fax service history routes.
 *
 * @package Drupal\dd_fax_service\Controller
 */
class DdFaxServiceHistoryController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Dd fax service history  revision.
   *
   * @param int $dd_fax_service_history_revision
   *   The Dd fax service history  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($dd_fax_service_history_revision) {
    $dd_fax_service_history = $this->entityManager()->getStorage('dd_fax_service_history')->loadRevision($dd_fax_service_history_revision);
    $view_builder = $this->entityManager()->getViewBuilder('dd_fax_service_history');

    return $view_builder->view($dd_fax_service_history);
  }

  /**
   * Page title callback for a Dd fax service history  revision.
   *
   * @param int $dd_fax_service_history_revision
   *   The Dd fax service history  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($dd_fax_service_history_revision) {
    $dd_fax_service_history = $this->entityManager()->getStorage('dd_fax_service_history')->loadRevision($dd_fax_service_history_revision);
    return $this->t('Revision of %title from %date', array('%title' => $dd_fax_service_history->label(), '%date' => format_date($dd_fax_service_history->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Dd fax service history .
   *
   * @param \Drupal\dd_fax_service\Entity\DdFaxServiceHistoryInterface $dd_fax_service_history
   *   A Dd fax service history  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(DdFaxServiceHistoryInterface $dd_fax_service_history) {
    $account = $this->currentUser();
    $langcode = $dd_fax_service_history->language()->getId();
    $langname = $dd_fax_service_history->language()->getName();
    $languages = $dd_fax_service_history->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $dd_fax_service_history_storage = $this->entityManager()->getStorage('dd_fax_service_history');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $dd_fax_service_history->label()]) : $this->t('Revisions for %title', ['%title' => $dd_fax_service_history->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all dd fax service history revisions") || $account->hasPermission('administer dd fax service history entities')));
    $delete_permission = (($account->hasPermission("delete all dd fax service history revisions") || $account->hasPermission('administer dd fax service history entities')));

    $rows = array();

    $vids = $dd_fax_service_history_storage->revisionIds($dd_fax_service_history);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\dd_fax_service\DdFaxServiceHistoryInterface $revision */
      $revision = $dd_fax_service_history_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $dd_fax_service_history->getRevisionId()) {
          $link = $this->l($date, new Url('entity.dd_fax_service_history.revision', ['dd_fax_service_history' => $dd_fax_service_history->id(), 'dd_fax_service_history_revision' => $vid]));
        }
        else {
          $link = $dd_fax_service_history->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('dd_fax_service_history.revision_revert_translation_confirm', ['dd_fax_service_history' => $dd_fax_service_history->id(), 'dd_fax_service_history_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('dd_fax_service_history.revision_revert_confirm', ['dd_fax_service_history' => $dd_fax_service_history->id(), 'dd_fax_service_history_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('dd_fax_service_history.revision_delete_confirm', ['dd_fax_service_history' => $dd_fax_service_history->id(), 'dd_fax_service_history_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['dd_fax_service_history_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}

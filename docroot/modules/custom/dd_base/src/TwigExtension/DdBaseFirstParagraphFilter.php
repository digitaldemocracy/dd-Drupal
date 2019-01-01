<?php
namespace Drupal\dd_base\TwigExtension;


class DdBaseFirstParagraphFilter extends \Twig_Extension {

/**
* Generates a list of all Twig filters that this extension defines.
*/
public function getFilters() {
return [
new \Twig_SimpleFilter('getparagraph', array($this, 'getFirstParagraph')),
];
}

/**
* Gets a unique identifier for this Twig extension.
*/
public function getParagraph() {
return 'dd_base.twig_extension';
}

  /**
   * Cut the full string at the first linebreak. If it returns empty, display the first 150 chars
   *
   * @param string $fulltext
   *   Text body
   *
   * @return string
   *   First paragraph of text, or 2 paragraphs if 1st is < 100 chars.
   */
  function getFirstParagraph($fulltext) {
    $fulltext = trim($fulltext);
    $first_paragraph = substr($fulltext, 0, strpos($fulltext, "\n"));
    $after_first_paragraph = substr($fulltext, strpos($fulltext, "\n") + 1);
    $second_paragraph = substr($after_first_paragraph, 0, strpos($after_first_paragraph, "\n"));
    $substring_default = substr($fulltext, 0, 150);

    if ($first_paragraph === '') {
      $output = $substring_default;
    }
    elseif (strlen($first_paragraph) < 200) {
      $output = $first_paragraph . "\n" . $second_paragraph;
    }
    else {
      $output = $first_paragraph;
    }
    return $output;
  }

}
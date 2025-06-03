<?php

namespace Drupal\deims_budyko_formatter\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'DeimsBudykoFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "deims_budyko_formatter",
 *   label = @Translation("DEIMS Budyko Formatter"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "string",
 *   },
 *   quickedit = {
 *     "editor" = "disabled"
 *   }
 * )
 */
 
class DeimsBudykoFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
   
 
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Formats a deims.id field of Drupal');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    // Render each element as markup in case of multi-values.

    foreach ($items as $delta => $item) {
      
      $budyko_uuid_list = json_decode(file_get_contents(__DIR__ .  '/budyko_uuid_list.json'), true);
      $record_uuid = $item->value;

      if (array_key_exists($record_uuid, $budyko_uuid_list)) {
        $table_string = '<b>There are calculated Budyko curves available for this site:</b><br>';
        $table_string .= '<table class="table">';    


        $table_string .= '</table><br>';
        $table_string .= '<p>You can also <a target="_blank" href="';
        $table_string .= $budyko_uuid_list[$record_uuid];
		$table_string .= '">download the Budyko curves dataset for this site</a> from the EUDAT B2SHARE data store.</p>';

      }
      else {
        // need to return empty array for Drupal to realise the field is empty without throwing an error
        return array();
      }
      
      $elements[$delta] = [
        '#markup' => $table_string,
      ];

    }

    return $elements;

  }
	
}

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
      
	  $record_uuid = $item->value;
	  $module_path = \Drupal::service('extension.list.module')->getPath('deims_budyko_formatter');
	  $json_path = DRUPAL_ROOT . '/' . $module_path . "/files/$record_uuid.json";

	  if (file_exists($json_path)) {  
		// fetch image file
		$module_path = \Drupal::service('extension.list.module')->getPath('deims_budyko_formatter');
		$file_generator = \Drupal::service('file_url_generator');
		$image_path = $file_generator->generateAbsoluteString("$module_path/files/$record_uuid.png");
      
        $table_string = 'There are calculated Budyko curves available for this site:<br>';
        $table_string .= '<ul>';
        $table_string .= "<li><a href='$image_path' target='_blank'>View a chart of all calculated Budyko curves</a></li>";
        $table_string .= '<li>You can also <a target="_blank" href="https://doi.org/10.5281/zenodo.17036642">';
		$table_string .= 'download the entire Budyko curves dataset for this site</a> from Zenodo.';
		$table_string .= '</ul>';
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


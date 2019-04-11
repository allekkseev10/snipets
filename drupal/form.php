<?php

namespace Drupal\app\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\synhelper\Controller\AjaxResult;

/**
 * Implements the form controller.
 */
class Form extends FormBase {
  private $wrapper = 'app-form';

  /**
   * AJAX ajaxPrev.
   */
  public function ajaxSubmit(array &$form, $form_state) {
    $otvet = "ajax submit:\n";
    $inputs = $form_state->getValue('foo');
    $data = [];
    foreach (explode("\n", $input) as $input) {
      if ($input = trim($input)) {
        $res = \Drupal::service('bar')->get($input);
        $data[] = $res;
      }
    }
    return AjaxResult::ajax($this->wrapper, $otvet, $data);
  }

  /**
   * Build the simple form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $extra = NULL) {
    $form_state->extra = $extra;
    $form_state->setCached(FALSE);
    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
      '#suffix' => '<div id="' . $this->wrapper . '"></div>',
      'foo' => [
        '#title' => 'Foo',
        '#type' => 'textarea',
      ],
      'exec' => AjaxResult::button('::ajaxSubmit', 'Submit'),
    ];
    return $form;
  }

  /**
   * Implements a form submit handler.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'app-form-id';
  }

}



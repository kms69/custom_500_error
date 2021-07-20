<?php

namespace Drupal\custom_500_error\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CustomErrorConfigForm.
 */
class CustomErrorConfigForm extends ConfigFormBase {

  /**
   * Drupal\Core\Config\ConfigManagerInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->configManager = $container->get('config.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'custom_500_error.customerrorconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_error_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('custom_500_error.customerrorconfig');
    $form['custom_error_markup'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Markup which you want to be shown in the error page ( your custom text and html/css)'),
      '#description' => $this->t('customze error message and html/css which you want to be shown on the page'),
      '#default_value' => $config->get('custom_error_markup'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('custom_500_error.customerrorconfig')
      ->set('custom_error_markup', $form_state->getValue('custom_error_markup')['value'])
      ->save();
  }

}

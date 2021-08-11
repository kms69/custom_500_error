<?php

namespace Drupal\custom_500_error\EventSubscriber;

use Drupal\Core\Config\ConfigManagerInterface;
use Drupal\Core\EventSubscriber\HttpExceptionSubscriberBase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Last-chance handler for exceptions.
 *
 * This handler will catch any exceptions not caught elsewhere and send a themed
 * error page as a response.
 */
class ExceptionSubscriber extends HttpExceptionSubscriberBase {

  /**
   * @var \Drupal\Core\Config\ConfigManagerInterface
   */
  protected $configManager;

  /**
   * Constructs a ExceptionHandler
   *
   * @param \Drupal\Core\Config\ConfigManagerInterface $config_manager
   *   The configuration manager.
   */
  public function __construct(ConfigManagerInterface $config_manager) {
    $this->configManager = $config_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected static function getPriority() {
    // A very low priority so that custom handlers are almost certain to fire
    // before it, even if someone forgets to set a priority.
    return 4;
  }

  /**
   * {@inheritdoc}
   */
  protected function getHandledFormats() {
    return [
      'html',
    ];
  }

  /**
   * The default 500 content.
   *
   * @param ExceptionEvent $event
   *   The event to process.
   */
  public function on500(ExceptionEvent $event) {
    $config = $this->configManager->getConfigFactory()
      ->get('custom_500_error.customerrorconfig');
    $content = $config->get('custom_error_markup');
    $response = new Response($content, 500);
    $event->setResponse($response);
  }

}


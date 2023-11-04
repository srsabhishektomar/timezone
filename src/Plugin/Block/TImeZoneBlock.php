<?php

namespace Drupal\timezone\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\UncacheableDependencyTrait;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\timezone\Service\CurrentTimezone;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'timezone' Block.
 *
 * @Block(
 *   id = "timezone_block",
 *   admin_label = @Translation("Timezone Block"),
 *   category = @Translation("custom"),
 * )
 */
class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  use UncacheableDependencyTrait;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current timezone service.
   *
   * @var \Drupal\timezone\Service\CurrentTimezone
   */
  protected $currentTimezone;

  /**
   * The account interface.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Constructs a new TimezoneBlock.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\timezone\Service\CurrentTimezone $current_timezone
   *   The current timezone service.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The account interface.
   */
  public function __construct(array $configuration, $plugin_id, array $plugin_definition, ConfigFactoryInterface $config_factory, CurrentTimezone $current_timezone, AccountInterface $account) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->currentTimezone = $current_timezone;
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('current.timezone'),
      $container->get('current_user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = [];
    $build = [];
    $config = $this->configFactory->get('timezone.settings');
    $data['country'] = $config->get('country');
    $data['city'] = $config->get('city');
    $timezone = $this->currentTimezone->getCurrentDateTime();
    $dateTime = explode('-', $timezone);
    $data['date'] = $this->currentTimezone->getDateTimeFormat('l, d F Y');
    $data['time'] = $dateTime[1];
    if ($data) {
      $build = [
        '#theme' => 'timezone_block',
        '#data' => $data,
        '#cache' => [
          'contexts' => [
            'user',
          ],
          'tags' => [
            'node:' . $this->account->id(),
          ],
        ],
      ];
    }
    return $build;
  }

}

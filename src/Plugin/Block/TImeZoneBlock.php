<?php
namespace Drupal\timezone\Plugin\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "time_zone_block",
 *   admin_label = @Translation("Time Zone Block"),
 * )
 */
class TimeZoneBlock extends BlockBase {
    /**
   * {@inheritdoc}
   */
  public function build() {
    $country = \Drupal::config('timezone.settings')->get('country');
    $city = \Drupal::config('timezone.settings')->get('city');
    $timezone = \Drupal::config('timezone.settings')->get('timezone');
    return [
        '#theme' => 'timezone_data_listing',
        '#record' => [
          'country' => $country,
          'city' => $city,
          'timezone' => $timezone
        ],
        '#attributes' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), array('route'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), ['timezone_display']);
  }

}
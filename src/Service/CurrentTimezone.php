<?php

namespace Drupal\timezone\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Service to return current timezone of a country.
 */
class CurrentTimezone {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The controller constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get current timezone based on timezone settings value.
   *
   * @return string
   *   The date time format.
   */
  public function getCurrentDateTime() {
    $format = 'jS M Y - h:i A';
    return $this->getDateTimeFormat($format);
  }

  /**
   * Get current datetime format.
   *
   * @param string $dateFormat
   *   The date Format.
   *
   * @return string
   *   The date time format
   */
  public function getDateTimeFormat($dateFormat) {
    $config = $this->configFactory->get('timezone.settings');
    $currentTime = new DrupalDateTime('now', 'UTC');
    $timezone = new \DateTimeZone($config->get('timezone'));
    $dateTime = new DrupalDateTime();
    $timezoneOffset = $timezone->getOffset($dateTime->getPhpDateTime());
    $timeInterval = \DateInterval::createFromDateString($timezoneOffset . 'seconds');
    $currentTime->add($timeInterval);
    return $currentTime->format($dateFormat);
  }

}

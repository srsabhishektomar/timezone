<?php
namespace Drupal\timezone\Services;

/**
 * Declaring service class
 */

class CurrentTime {
   /**
   * Constructs a new CustomService object.
   */
    public function __construct() {

    }
    /**
     * Current Time method
     */
    public function current_time($timezone) {
        date_default_timezone_set($timezone);
        $date= date('jS M Y - g:i A') ;
        return $date;
    }
}
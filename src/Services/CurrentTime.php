<?php
namespace Drupal\timezone\Services;

/**
 * Declaring service class
 */

class CurrentTime {
    /**
     * Current time method
     */
    public function current_time($timezone) {
        date_default_timezone_set($timezone);
        $date= date('jS M Y - g:i A') ;
        return $date;
    }
}
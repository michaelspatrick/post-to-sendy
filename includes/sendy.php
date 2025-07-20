<?php
if (!defined('ABSPATH')) exit;

/**
 * Utility functions for Sendy scheduling
 */

function post_to_sendy_convert_datetime($date, $time) {
    return date_i18n("F d, Y h:i A", strtotime($date . ' ' . $time));
}

function get_optimal_post_datetime() {
    $day = (int) current_time('w');
    $hour = (int) current_time('H');

    $preferred_days = range(1, 5); // Monday to Friday
    $preferred_hours = range(8, 10); // 8am to 10am

    if (in_array($day, $preferred_days) && in_array($hour, $preferred_hours)) {
        return current_time('mysql');
    }

    // Find next preferred day at preferred hour
    $timestamp = current_time('timestamp');
    do {
        $timestamp += 3600; // add 1 hour
        $new_day = (int) date('w', $timestamp);
        $new_hour = (int) date('G', $timestamp);
    } while (!(in_array($new_day, $preferred_days) && in_array($new_hour, $preferred_hours)));

    return date('Y-m-d H:i:s', $timestamp);
}

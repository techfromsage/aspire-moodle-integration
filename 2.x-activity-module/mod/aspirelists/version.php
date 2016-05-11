<?php
defined('MOODLE_INTERNAL') || die();
global $CFG;

if (!isset($plugin)) {
    // Avoid warning message in M2.5 and below.
    $plugin = new stdClass();
}

$plugin->version   = 2016051100;
$plugin->requires  = 2012062507; // See http://docs.moodle.org/dev/Moodle_Versions
$plugin->cron      = 0;
$plugin->component = 'mod_aspirelists';
$plugin->maturity  = MATURITY_ALPHA;
$plugin->release   = '.0001';

if ($CFG->branch < 26) {
    // Used by Moodle 2.5 and below.
    $module->version = $plugin->version;
    $module->cron = $plugin->cron;
    $module->maturity = $plugin->maturity;
    $module->release = $plugin->release;
    $module->requires = $plugin->requires;
    $module->component = $plugin->component;
}

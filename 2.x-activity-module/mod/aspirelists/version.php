<?php
defined('MOODLE_INTERNAL') || die();
global $CFG;

if (!isset($plugin)) {
    // Avoid warning message in M2.5 and below.
    $plugin = new stdClass();
}

$plugin->version   = 2017070400; // Version for this plugin - based on the date and then an increment number
$plugin->requires  = 2012062507; // See http://docs.moodle.org/dev/Moodle_Versions
$plugin->cron      = 0;
$plugin->component = 'mod_aspirelists';
$plugin->maturity  = MATURITY_BETA;
$plugin->release   = '.0001';

if (isset($CFG->version))
{
    if($CFG->version < 2013111800) {
        // Used by Moodle 2.5 and below.
        $module->version = $plugin->version;
        $module->cron = $plugin->cron;
        $module->maturity = $plugin->maturity;
        $module->release = $plugin->release;
        $module->requires = $plugin->requires;
        $module->component = $plugin->component;
    }
}


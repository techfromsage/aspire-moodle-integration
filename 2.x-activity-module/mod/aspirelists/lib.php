<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Library of interface functions and constants for module aspirelists
 *
 * @package    mod
 * @subpackage aspirelists
 * @copyright  2013 Talis Education Ltd.
 * @license    MIT
 */

defined('MOODLE_INTERNAL') || die();

////////////////////////////////////////////////////////////////////////////////
// Moodle core API                                                            //
////////////////////////////////////////////////////////////////////////////////

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function aspirelists_supports($feature) {
    $aspirelists_cfg = get_config('aspirelists');
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        case FEATURE_MOD_ARCHETYPE:     return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:    return true;
        default:                        return null;
    }
}

/**
 * Saves a new instance of the aspirelists into the database
 *
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $aspirelists An object from the form in mod_form.php
 * @param mod_aspirelists_mod_form $mform
 * @return int The id of the newly inserted aspirelists record
 */
function aspirelists_add_instance(stdClass $aspirelists, mod_aspirelists_mod_form $mform = null) {
  error_log("we are in aspirelists_add_instance");
    global $CFG;
    require_once ($CFG->dirroot.'/mod/lti/lib.php');

    require_once ($CFG->dirroot.'/mod/lti/locallib.php');
    error_log("aspirelists output: " . print_r($mform, true));
    return lti_add_instance($aspirelists, $mform);
}

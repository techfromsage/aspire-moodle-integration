<?php
require_once($CFG->dirroot . '/lib/completionlib.php');

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

/** Display reading list on a separate page */
define('ASPIRELISTS_DISPLAY_PAGE', 0);
/** Embed reading list inline in a course */
define('ASPIRELISTS_DISPLAY_INLINE', 1);
/** URL of the TARL LTI launch path */
define('ASPIRELISTS_LTI_LAUNCH_PATH', '/lti/launch');

/**
 * Returns the information on whether the module supports a feature
 *
 * @see plugin_supports() in lib/moodlelib.php
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed true if the feature is supported, null if unknown
 */
function aspirelists_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:         return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;
        case FEATURE_MOD_ARCHETYPE:     return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:    return true;
        case FEATURE_GRADE_OUTCOMES:     return false;
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
    global $DB, $CFG;
    $aspirelists->id = $DB->insert_record('aspirelists', $aspirelists);
    return $aspirelists->id;
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod.html) this function
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function aspirelists_update_instance($aspirelists, $mform) {
    global $DB, $CFG;

    $aspirelists->timemodified = time();
    $aspirelists->id = $aspirelists->instance;
    return $DB->update_record('aspirelists', $aspirelists);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function aspirelists_delete_instance($id) {
    global $DB;

    if (! $list = $DB->get_record("aspirelists", array("id" => $id))) {
        return false;
    }

    return $DB->delete_records("aspirelists", array("id" => $list->id));
}

/**
 * Add the necessary properties to an aspirelists object to pass it to mod_lti and succesfully launch a request
 * @param stdClass &$aspirelist
 */
function aspirelists_add_lti_properties(&$aspirelist)
{
    global $CFG;

    $pluginSettings = get_config('mod_aspirelists');

    $aspirelist->toolurl = $pluginSettings->targetAspire . ASPIRELISTS_LTI_LAUNCH_PATH;
    $aspirelist->instructorchoiceacceptgrades = false;
    $aspirelist->instructorchoicesendname = false;
    $aspirelist->instructorchoicesendemailaddr = false;
    if($CFG->version >= 2015051100 ) { // greater or in Moodle 2.9.
        $aspirelist->instructorchoiceallowroster = false;
    }
    $aspirelist->launchcontainer = null;
    $aspirelist->servicesalt = uniqid('', true);
    if(function_exists('get_course'))
    {
        $course = get_course($aspirelist->course);
    } else {
        global $DB;
        $course = $DB->get_record('course', array('id' => $aspirelist->course), '*', MUST_EXIST);
    }
    $customLTIParams = array('launch_identifier='.uniqid());
    $baseKGCode = $course->{$pluginSettings->courseCodeField};
    if(isset($pluginSettings->moduleCodeRegex))
    {
        if(preg_match("/".$pluginSettings->moduleCodeRegex."/", $baseKGCode, $matches))
        {
            if(!empty($matches) && isset($matches[1]))
            {
                $baseKGCode = $matches[1];
            }
        }
    }
    $customLTIParams[] = 'knowledge_grouping_code='.$baseKGCode;
    if(isset($pluginSettings->timePeriodRegex) && isset($pluginSettings->timePeriodMapping))
    {
        $timePeriodMapping = json_decode($pluginSettings->timePeriodMapping, true);
        if(preg_match("/".$pluginSettings->timePeriodRegex."/", $course->{$pluginSettings->courseCodeField}, $matches))
        {
            if(!empty($matches) && isset($matches[1]) && isset($timePeriodMapping[$matches[1]]))
            {
                $customLTIParams[] = 'time_period='.$timePeriodMapping[$matches[1]];
            }
        }
    }
    // Custom Attrs to track inline resource usage
    if(isset($aspirelist->display)){
        $customLTIParams[] = 'display_inline='.(string)$aspirelist->display;
    }
    if(isset($aspirelist->showexpanded)){
        $customLTIParams[] = 'display_inline_expanded='.(string)$aspirelist->showexpanded;
    }
    $plugin = get_config('mod_aspirelists');
    if(isset($plugin->version)){
        $customLTIParams[] = 'moodle_lti_plugin_version='.$plugin->version;
    }
    $aspirelist->instructorcustomparameters= implode("\n", $customLTIParams);
    $aspirelist->debuglaunch = false;
}

/**
 *
 * @param cm_info $cm
 */
function aspirelists_cm_info_view(cm_info $cm) {
    global $CFG,$PAGE;
    if ($cm->uservisible && $cm->customdata) {
        // Restore folder object from customdata.
        // Note the field 'customdata' is not empty IF AND ONLY IF we display contens inline.
        // Otherwise the content is default.
        $aspirelist = $cm->customdata;
        $aspirelist->id = (int)$cm->instance;
        $aspirelist->course = (int)$cm->course;
        $aspirelist->display = ASPIRELISTS_DISPLAY_INLINE;
        $aspirelist->name = $cm->name;
        $aspirelist->cmid = $cm->id;
        if (empty($aspirelist->intro)) {
            $aspirelist->intro = '';
        }
        if (empty($aspirelist->introformat)) {
            $aspirelist->introformat = FORMAT_MOODLE;
        }
        $aspirelist->showdescription = $cm->showdescription;
        // display reading list
        $renderer = $PAGE->get_renderer('mod_aspirelists');
        $cm->set_content($renderer->display_aspirelists($aspirelist));
    }
}


/**
 * Sets dynamic information about a course module
 *
 * This function is called from cm_info when displaying the module
 * mod_folder can be displayed inline on course page and therefore have no course link
 *
 * @param cm_info $cm
 */
function aspirelists_cm_info_dynamic(cm_info $cm) {
    global $CFG;
    if ($cm->customdata) {
        // the field 'customdata' is not empty IF AND ONLY IF we display contens inline
        require_once($CFG->dirroot . '/mod/aspirelists/load_js.php');
        $cm->set_extra_classes('aspirelists_inline_readings_toggle');
        $aspirelist = $cm->customdata;
        if(isset($aspirelist->showexpanded) && $aspirelist->showexpanded === '1')
        {
            $afterLink = get_string('accordion_open', 'aspirelists');
        } else {
            $afterLink = get_string('accordion_closed', 'aspirelists');
        }

        $cm->set_after_link("<span class=\"aspirelists_inline_accordion\">" . $afterLink . "</span>");
    }
}

/**
 * Given a coursemodule object, this function returns the extra
 * information needed to print this activity in various places.
 *
 * If folder needs to be displayed inline we store additional information
 * in customdata, so functions {@link folder_cm_info_dynamic()} and
 * {@link folder_cm_info_view()} do not need to do DB queries
 *
 * @param cm_info $cm
 * @return cached_cm_info info
 */
function aspirelists_get_coursemodule_info($cm) {
    global $DB;
    if (!($list = $DB->get_record('aspirelists', array('id' => $cm->instance),
        'id, name, display, showexpanded, intro, introformat'))) {
        return NULL;
    }
    $cminfo = new cached_cm_info();
    $cminfo->name = $list->name;
    if ($list->display == ASPIRELISTS_DISPLAY_INLINE) {
        // prepare list object to store in customdata
        $ldata = new stdClass();
        $ldata->showexpanded = $list->showexpanded;
        if ($cm->showdescription && strlen(trim($list->intro))) {
            $ldata->intro = $list->intro;
            if ($list->introformat != FORMAT_MOODLE) {
                $ldata->introformat = $list->introformat;
            }
        }
        $cminfo->customdata = $ldata;
    } else {
        if ($cm->showdescription) {
            // Convert intro to html. Do not filter cached version, filters run at display time.
            $cminfo->content = format_module_intro('aspirelists', $list, $cm->id, false);
        }
    }
    return $cminfo;
}

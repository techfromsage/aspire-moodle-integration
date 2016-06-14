<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/mod/aspirelists/lib.php');
require_once ($CFG->dirroot.'/mod/lti/lib.php');
require_once ($CFG->dirroot.'/mod/lti/locallib.php');
require_once ($CFG->dirroot.'/mod/lti/mod_form.php');

// Require the plugin manager
if($CFG->version >= 2013111800) {
    // From Moodle 2.6 onwards we need to load the plugin manager class
    require_once ($CFG->dirroot.'/lib/classes/plugin_manager.php');
}
else{
    // Prior to Moodle 2.6 we need to load pluginlib.php to get the plugin manager
    require_once($CFG->dirroot . '/lib/pluginlib.php');
}

class mod_aspirelists_mod_form extends mod_lti_mod_form {
     function definition() {
         global $CFG, $OUTPUT, $PAGE, $COURSE, $DB;
         $pluginSettings = get_config('mod_aspirelists');

         $launchUrl = $pluginSettings->targetAspire . ASPIRELISTS_LTI_LAUNCH_PATH;
         $ltiTool = lti_get_tool_by_url_match($launchUrl);
         $ltiPlugin = $this->getPluginManager()->get_plugin_info('mod_lti');

         $ltiPluginId = $DB->get_field('modules', 'id', array('name'=>$ltiPlugin->name));

         $mform =& $this->_form;
         $mform->addElement('header', 'general', get_string('generalheader', 'aspirelists'));
         $mform->addElement('text', 'name', get_string('section_title', 'aspirelists'));
         $mform->setDefault('name', get_string('default_section_title', 'aspirelists'));
         $mform->addRule('name', null, 'required', null, 'client');
         $mform->setType('name', PARAM_TEXT);

         if($CFG->version >= 2015111600 ) { // greater or in Moodle 3.0.
             $this->standard_intro_elements(false);
         } else {
             $this->add_intro_editor(false);
         }

         $mform->setAdvanced('introeditor');

         // Display the label to the right of the checkbox so it looks better & matches rest of the form
         $coursedesc = $mform->getElement('showdescription');
         if(!empty($coursedesc)){
             $coursedesc->setText(' ' . $coursedesc->getLabel());
             $coursedesc->setLabel('&nbsp');
         }
         $mform->setAdvanced('showdescription');
         //-------------------------------------------------------
         $mform->addElement('header', 'course_display', get_string('displayheader', 'aspirelists'));
         $mform->addElement('select', 'display', get_string('display', 'aspirelists'),
             array(ASPIRELISTS_DISPLAY_PAGE => get_string('displaypage', 'aspirelists'),
                 ASPIRELISTS_DISPLAY_INLINE => get_string('displayinline', 'aspirelists')));
         $mform->addHelpButton('display', 'display', 'mod_aspirelists');

         if(method_exists($mform, 'setExpanded'))
         {
            $mform->setExpanded('course_display');
         }

         // Adding option to show sub-folders expanded or collapsed by default.
         $mform->addElement('advcheckbox', 'showexpanded', get_string('showexpanded', 'aspirelists'));
         $mform->addHelpButton('showexpanded', 'showexpanded', 'mod_aspirelists');
         $mform->setDefault('showexpanded', 0);

         $this->standard_coursemodule_elements();
         $this->add_action_buttons(true, get_string('save_and_continue', 'aspirelists'), false);

     }

    /**
     * Return the plugin manager instance
     *
     * @return mixed
     */
    function getPluginManager(){
        global $CFG;
        // Prior to moodle 2.6
        if($CFG->version < 2013111800) {
            return plugin_manager::instance();
        }
        // After moodle 2.6
        return core_plugin_manager::instance();
    }
}

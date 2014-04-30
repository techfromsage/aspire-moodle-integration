<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/mod/aspirelists/lib.php');
require_once ($CFG->dirroot.'/mod/lti/lib.php');
require_once ($CFG->dirroot.'/mod/lti/locallib.php');
require_once ($CFG->dirroot.'/mod/lti/mod_form.php');
require_once ($CFG->dirroot.'/lib/pluginlib.php');

class mod_aspirelists_mod_form extends mod_lti_mod_form {
     function definition() {
         global $CFG, $OUTPUT, $PAGE, $COURSE, $DB;
         $pluginSettings = get_config('mod_aspirelists');

         $launchUrl = $pluginSettings->targetAspire . ASPIRELISTS_LTI_LAUNCH_PATH;
         $ltiTool = lti_get_tool_by_url_match($launchUrl);
         $ltiPlugin = plugin_manager::instance()->get_plugin_info('mod_lti');

         $ltiPluginId = $DB->get_field('modules', 'id', array('name'=>$ltiPlugin->name));
//         $this->current->module = (string)$ltiPluginId;
//         $this->current->modulename = $ltiPlugin->name;
//         $this->current->add = $ltiPlugin->name;
//         $this->_formname = 'mod_lti_mod_form';

         $mform =& $this->_form;
//         $mform->_formName = 'mod_lti_mod_form';
         $mform->addElement('header', 'general', get_string('generalheader', 'aspirelists'));
         $mform->addElement('text', 'name', get_string('section_title', 'aspirelists'));
         $mform->setDefault('name', get_string('default_section_title', 'aspirelists'));
         $mform->addRule('name', null, 'required', null, 'client');
         $mform->setType('name', PARAM_TEXT);
         $this->add_intro_editor(false);
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
         if (!$this->courseformat->has_view_page()) {
             $mform->setConstant('display', ASPIRELISTS_DISPLAY_PAGE);
             $mform->hardFreeze('display');
         }
         $mform->setExpanded('course_display');

         // Adding option to show sub-folders expanded or collapsed by default.
         $mform->addElement('advcheckbox', 'showexpanded', get_string('showexpanded', 'aspirelists'));
         $mform->addHelpButton('showexpanded', 'showexpanded', 'mod_aspirelists');
         $mform->setDefault('showexpanded', 0);

         $this->standard_coursemodule_elements();
         $this->add_action_buttons(true, get_string('save_and_continue', 'aspirelists'), false);

     }
}

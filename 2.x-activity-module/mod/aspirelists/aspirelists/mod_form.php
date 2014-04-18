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

         $mform->addElement('hidden', 'instructorcustomparameters');
         $mform->setType('instructorcustomparameters', PARAM_TEXT);
         $customLTIParams = array('launch_identifier='.uniqid());
         $baseKGCode = $COURSE->{$pluginSettings->courseCodeField};
         if(isset($pluginSettings->targetKG))
         {
             $customLTIParams[] = "knowledge_grouping=".$pluginSettings->targetKG;
         }
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
             if(preg_match("/".$pluginSettings->timePeriodRegex."/", $COURSE->{$pluginSettings->courseCodeField}, $matches))
             {
                 if(!empty($matches) && isset($matches[1]) && isset($timePeriodMapping[$matches[1]]))
                 {
                     $customLTIParams[] = 'time_period='.$timePeriodMapping[$matches[1]];
                 }
             }
         }
         $mform->setDefault('instructorcustomparameters', implode("\n", $customLTIParams));
         $mform->addElement('hidden', 'instructorchoiceacceptgrades', "1");
         $mform->setType('instructorchoiceacceptgrades', PARAM_BOOL);
         $mform->addElement('hidden', 'typeid',$ltiTool->id);
         $mform->setType('typeid', PARAM_INT);
         $mform->addElement('hidden', 'toolurl', $ltiTool->baseurl);
         $mform->setType('toolurl', PARAM_TEXT);
         $mform->addElement('hidden', 'launchcontainer', LTI_LAUNCH_CONTAINER_EMBED);
         $mform->setType('launchcontainer', PARAM_TEXT);
         $mform->addElement('hidden', 'icon', get_string('icon_url', 'aspirelists'));
         $mform->setType('icon', PARAM_TEXT);

         // We don't actually need any user information right now
         $mform->addElement('hidden', 'instructorchoicesendname', '', '0');
         $mform->setType('instructorchoicesendname', PARAM_BOOL);

         $mform->addElement('hidden', 'instructorchoicesendemailaddr', '', '0');
         $mform->setType('instructorchoicesendemailaddr', PARAM_BOOL);



         $this->standard_coursemodule_elements();
         $this->add_action_buttons(true, get_string('save_and_continue', 'aspirelists'), false);

     }
}

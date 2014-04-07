<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/mod/lti/lib.php');
require_once ($CFG->dirroot.'/mod/lti/locallib.php');
require_once ($CFG->dirroot.'/mod/lti/mod_form.php');
require_once ($CFG->dirroot.'/lib/pluginlib.php');

class mod_aspirelists_mod_form extends mod_lti_mod_form {
     function definition() {
         global $CFG, $OUTPUT, $PAGE, $COURSE, $DB;
         $launchUrl = get_config('aspirelists', 'targetAspire') . '/lti/launch';
         $ltiTool = lti_get_tool_by_url_match($launchUrl);
         $ltiPlugin = plugin_manager::instance()->get_plugin_info('mod_lti');
         $pluginSettings = get_config('aspirelists');

         $ltiPluginId = $DB->get_field('modules', 'id', array('name'=>$ltiPlugin->name));
         $this->current->module = (string)$ltiPluginId;
         $this->current->modulename = $ltiPlugin->name;
         $this->current->add = $ltiPlugin->name;
         $this->_formname = 'mod_lti_mod_form';

         $mform =& $this->_form;
         $mform->_formName = 'mod_lti_mod_form';
         $mform->addElement('text', 'name', get_string('section_title', 'aspirelists'));
         $mform->setDefault('name', get_string('default_section_title', 'aspirelists'));
         $mform->addRule('name', null, 'required', null, 'client');
         $this->add_intro_editor(false);
         // Display the label to the right of the checkbox so it looks better & matches rest of the form
         $coursedesc = $mform->getElement('showdescription');
         if(!empty($coursedesc)){
             $coursedesc->setText(' ' . $coursedesc->getLabel());
             $coursedesc->setLabel('&nbsp');
         }
         $mform->addElement('textarea', 'instructorcustomparameters', get_string('custom', 'lti'), array('rows'=>4, 'cols'=>60));
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
         $mform->setDefault('instructorcustomparameters', implode("&", $customLTIParams));
         $mform->addElement('hidden', 'instructorchoiceacceptgrades', "1");
         $mform->addElement('hidden', 'typeid',$ltiTool->id);
         $mform->addElement('hidden', 'toolurl', $ltiTool->baseurl);
         $mform->addElement('hidden', 'launchcontainer', LTI_LAUNCH_CONTAINER_EMBED);
         $mform->addElement('hidden', 'icon', 'http://b65a3c5a45eb7c0136ca-3a802cea7cd9c7fa6dc4f29b6a88c582.r90.cf3.rackcdn.com/2014-02-17-10-13-16/block_29240.png');

         // We don't actually need any user information right now
         $mform->addElement('hidden', 'instructorchoicesendname', '', '0');
         $mform->addElement('hidden', 'instructorchoicesendemailaddr', '', '0');
         $mform->addElement('hidden', 'instructorchoiceacceptgrades', '', '0');

         $this->standard_coursemodule_elements();
         $this->add_action_buttons(true, get_string('save_and_continue', 'aspirelists'), false);

     }
}

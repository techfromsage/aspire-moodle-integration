<?php

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/aspirelists/lib.php');
require_once($CFG->dirroot.'/mod/lti/lib.php');
require_once($CFG->dirroot.'/mod/lti/locallib.php');
require_once($CFG->dirroot.'/mod/aspirelists/classes/event/aspirelists_launch.php');

// make sure that this launch.php page is not cached by any proxies.
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
header("Pragma: no-cache");

$id = required_param('id', PARAM_INT); // Course Module ID

$cm = get_coursemodule_from_id('aspirelists', $id, 0, false, MUST_EXIST);
$list = $DB->get_record('aspirelists', array('id' => $cm->instance), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$context = context_module::instance($cm->id);

if($CFG->version < 2014051200) {
    add_to_log($course->id, "aspirelists", "launch", "launch.php?id=$cm->id", "$list->id");
} else {
    $event = \aspirelists\event\aspire_lists_launch::create(
        array(
            'objectid' => $cm->instance,
            'context' => $context,
            'other' => $list->id
        )
    );
    $event->set_legacy_logdata(array($course->id, "aspirelists", "launch", "launch.php?id=$cm->id", "$list->id"));
    $event->trigger();
}

$list->cmid = $cm->id;
aspirelists_add_lti_properties($list);
if($CFG->version >= 2015111600) {
    // Moodle 3.0 renamed the method (lti_view has been re-purposed)
    lti_launch_tool($list);
}
else{
    // Moodle 1.x/2.x
    lti_view($list);
}

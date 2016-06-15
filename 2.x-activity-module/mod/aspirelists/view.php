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
//
// This file is part of mod_aspirelists and was copied nearly verbatim from mod_lti's view.php.  If Moodle ever
// separates the model from the view, we would ideally just use mod_lti's view.php, and launch.php.  Until then, we
// can only acknowledge the efforts of those who helped make this so much simpler and copy their hard work.

/**
 * This file contains all necessary code to view a Talis Aspire reading list by launching via LTI
 *
 * @package    mod
 * @subpackage aspirelists
 * @copyright  2009 Marc Alier, Jordi Piguillem, Nikolas Galanis
 *  marc.alier@upc.edu
 * @copyright  2014 Talis Education Ltd., http://www.talis.com
 * @author     Marc Alier
 * @author     Jordi Piguillem
 * @author     Nikolas Galanis
 * @author     Chris Scribner
 * @author     Ross Singer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->dirroot.'/mod/aspirelists/lib.php');
require_once($CFG->dirroot.'/mod/lti/lib.php');
require_once($CFG->dirroot.'/mod/lti/locallib.php');
require_once($CFG->dirroot . '/lib/completionlib.php');
require_once($CFG->dirroot.'/mod/aspirelists/classes/event/course_module_viewed.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
$l  = optional_param('l', 0, PARAM_INT);  // aspirelists ID

if ($l) {  // Two ways to specify the module
    $list = $DB->get_record('aspirelists', array('id' => $l), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('aspirelists', $list->id, $list->course, false, MUST_EXIST);

} else {
    $cm = get_coursemodule_from_id('aspirelists', $id, 0, false, MUST_EXIST);
    $list = $DB->get_record('aspirelists', array('id' => $cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

// Turn our aspirelist object more into something that the lti module is expecting
aspirelists_add_lti_properties($list);

$tool = lti_get_tool_by_url_match($list->toolurl);
if ($tool) {
    $toolconfig = lti_get_type_config($tool->id);
} else {
    $toolconfig = array();
}
//
$list->cmid = $cm->id;

$PAGE->set_cm($cm, $course); // set's up global $COURSE
$context = context_module::instance($cm->id);
$PAGE->set_context($context);

$url = new moodle_url('/mod/lti/view.php', array('id'=>$cm->id));
$PAGE->set_url($url);

$launchcontainer = lti_get_launch_container($list, $toolconfig);

if ($launchcontainer == LTI_LAUNCH_CONTAINER_EMBED_NO_BLOCKS) {
    $PAGE->set_pagelayout('frametop'); //Most frametops don't include footer, and pre-post blocks
    $PAGE->blocks->show_only_fake_blocks(); //Disable blocks for layouts which do include pre-post blocks
} else if ($launchcontainer == LTI_LAUNCH_CONTAINER_REPLACE_MOODLE_WINDOW) {
    redirect('launch.php?id=' . $cm->id);
} else {
    $PAGE->set_pagelayout('incourse');
}

require_login($course);

// Mark viewed by user (if required).
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$event = \aspirelists\event\course_module_viewed::create(
    array(
        'objectid' => $PAGE->cm->instance,
        'context'  => $context,
        'other'    => $list->id
    )
);
$event->add_record_snapshot('course', $PAGE->course);
$event->set_legacy_logdata(array($course->id, "aspirelists", "view", "view.php?id=$cm->id", "$list->id"));
$event->trigger();

$pagetitle = strip_tags($course->shortname.': '.format_string($list->name));
$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);

// Print the page header
echo $OUTPUT->header();

echo $OUTPUT->heading(format_string($list->name, true, array('context' => $context)));


if ($list->intro) {
    echo $OUTPUT->box($list->intro, 'generalbox description', 'intro');
}

if ( $launchcontainer == LTI_LAUNCH_CONTAINER_WINDOW ) {
    echo "<script language=\"javascript\">//<![CDATA[\n";
    echo "window.open('launch.php?id=".$cm->id."','aspirelists');";
    echo "//]]\n";
    echo "</script>\n";
    echo "<p>".get_string("basiclti_in_new_window", "lti")."</p>\n";
} else {
    // Request the launch content with an iframe tag instead of the standard moodle LTI object tag
    echo '<iframe id="contentframe" height="600px" width="100%" type="text/html" src="launch.php?id='.$cm->id.'" frameborder="0"></iframe>';

    //Output script to make the object tag be as large as possible
    $resize = '
        <script type="text/javascript">
        //<![CDATA[
            YUI().use("yui2-dom", function(Y) {
                //Take scrollbars off the outer document to prevent double scroll bar effect
                document.body.style.overflow = "hidden";

                var dom = Y.YUI2.util.Dom;
                var frame = document.getElementById("contentframe");

                var padding = 15; //The bottom of the iframe wasn\'t visible on some themes. Probably because of border widths, etc.

                var lastHeight;

                var resize = function(){
                    var viewportHeight = dom.getViewportHeight();

                    if(lastHeight !== Math.min(dom.getDocumentHeight(), viewportHeight)){

                        frame.style.height = viewportHeight - dom.getY(frame) - padding + "px";

                        lastHeight = Math.min(dom.getDocumentHeight(), dom.getViewportHeight());
                    }
                };

                resize();

                setInterval(resize, 250);
            });
        //]]
        </script>
';

    echo $resize;
}

// Finish the page
echo $OUTPUT->footer();

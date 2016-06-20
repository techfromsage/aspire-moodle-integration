<?php
// Copyright (c) Talis Education Limited, 2013
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.

$string['modulename'] = 'Course Resource List';
$string['pluginname'] = 'Course Resource List';

$string['eventAspireLaunch'] = 'Talis Aspire Launch Event';

$string['pluginadministration'] = 'Course resource list administration';


$string['modulenameplural'] = 'Resource lists';
$string['aspirelists'] = 'Resource Lists';
$string['no_base_url_configured'] = 'Talis Aspire base URL not configured. Contact the system administrator.';
$string['no_resource_lists_msg'] = 'No resource lists found';
$string['aspirelists:addinstance'] = 'Add course reading';
$string['aspirelists:updateinstance'] = 'Update course reading';
$string['aspirelists:view'] = 'View course reading';
$string['aspirelists:download'] = 'Download course reading';
$string['aspirelists:addcoursetool'] = '';
$string['aspirelists:requesttooladd'] = '';
$string['aspirelists:manage'] = '';
$string['config_targetAspire'] = 'Target Aspire URL';
$string['config_targetAspire_desc'] = 'Enter the target Talis Aspire Campus Edition base URL. IMPORTANT: Do not add a trailing slash to the URL.';
$string['config_targetAspire_default'] = 'Default: http://demo.talisaspire.com';
$string['config_targetAspire_ex'] = 'http://demo.talisaspire.com';

$string['config_moduleCodeRegex'] = 'Module Code Regex';
$string['config_moduleCodeRegex_desc'] = 'A regular expression which will capture the module code part of a Moodle Course ID. <br />This will be mapped to the module code as defined in Talis Aspire, the regex pattern must have a single group which will be the part of the course id which is used. The default would match ABC123_201213 and provide us with the value ABC123 in the regex group';
$string['config_moduleCodeRegex_ex'] = '^([A-Za-z0-9]{6})_[0-9]{6}$' ;

$string['config_timePeriodRegex'] = 'Time Period Regex';
$string['config_timePeriodRegex_desc'] = 'A regular expression which will capture the time period part of a Moodle Course ID. <br />This will be mapped to the Time Period slug as defined in Talis Aspire, the regex pattern must have a single group which will be the part of the course id which is used. The default would match ABC123_201213 and provide us with the value 201213 in the regex group';
$string['config_timePeriodRegex_ex'] = '^[A-Za-z0-9]{6}_([0-9]{6})$' ;

$string['config_timePeriodMapping'] = 'Time Period Mapping';
$string['config_timePeriodMapping_desc'] = 'A JSON object describing how moodle time periods map to Talis Aspire time period slugs. <br />The form is key value pairs separated by commas {"moodleTimePeriodCode":"talisAspireTimePeriodCode"}';
$string['config_timePeriodMapping_ex'] = '{"201213":"summer-2013","201314":"autumn-2013"}';

$string['config_noResourceListsMessage'] = 'Message: no lists available';
$string['config_noResourceListsMessage_desc'] = 'The text of the message to display when there are no lists available.';

$string['modules'] = 'Modules';
$string['courses'] = 'Courses';
$string['units'] = 'Units';
$string['programmes'] = 'Programmes';
$string['subjects'] = 'Subjects';

// singular or plurals for displaying the number of items on a list
$string['item'] = 'item';
$string['items'] = 'items';

// label for use when showing date the list was last updated
$string['lastUpdated'] = 'last updated';

// Form fields
$string['section_title'] = 'Section title';
$string['default_section_title'] = ucfirst(strtolower($string['modulename']));

$string['course_code_field'] = 'Module code field';
$string['course_code_field_desc'] = 'The Moodle Course Field that corresponds to the Aspire module code';

$string['save_and_continue'] = "Link to resource list or section";

$string['icon_url'] = "http://b65a3c5a45eb7c0136ca-3a802cea7cd9c7fa6dc4f29b6a88c582.r90.cf3.rackcdn.com/2014-02-17-10-13-16/block_29240.png";

$string['display'] = 'Display resource list contents';
$string['display_help'] = '';
$string['displaypage'] = 'On a separate page';
$string['displayinline'] = 'Inline on a course page';
$string['noautocompletioninline'] = 'Automatic completion on viewing of activity can not be selected together with "Display inline" option';
$string['showexpanded'] = 'Show list sections expanded';
$string['showexpanded_help'] = 'If set to \'yes\', list sections are shown expanded by default; otherwise they are shown collapsed.';
$string['displayheader'] = 'Display';
$string['generalheader'] = 'General';
$string['accordion_closed'] = '&#9664;';
$string['accordion_open'] = '&#9660;';

$string['config_defaultInlineListHeight'] = 'Default height of an embedded list';
$string['config_defaultInlineListHeight_desc'] = 'The default height of an inline embedded list in the course view';
$string['config_defaultInlineListHeight_default'] = '400px' ;

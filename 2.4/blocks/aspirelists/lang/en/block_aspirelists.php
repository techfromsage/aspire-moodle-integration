<?php
// Copyright (c) Talis Education Limited, 2013
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.

$string['pluginname'] = 'Talis Aspire Resource Lists';
$string['aspirelists'] = 'Resource Lists';
$string['no_base_url_configured'] = 'Talis Aspire base URL not configured. Contact the system administrator.';
$string['no_resource_lists_msg'] = 'No resource lists found';

$string['config_targetAspire'] = 'Target Aspire URL';
$string['config_targetAspire_desc'] = 'Enter your Talis Aspire base URL. IMPORTANT: Do not add a trailing slash to the URL.';
$string['config_targetAspire_default'] = 'Default: http://demo.talisaspire.com';
$string['config_targetAspire_ex'] = 'http://demo.talisaspire.com';

$string['config_targetAspireAlias'] = 'Aspire URL HTTPS Alias';
$string['config_targetAspireAlias_desc'] = 'Enter the Talis Aspire HTTPS base URL. IMPORTANT: Do not add a trailing slash to the URL.';
$string['config_targetAspireAlias_default'] = 'Default: https://{tenancyShortCode}.rl.talis.com';
$string['config_targetAspireAlias_ex'] = 'https://broadminster.rl.talis.com';

$string['config_kg'] = 'Target knowledge group';
$string['config_kg_desc'] = 'Choose target knowledge grouping level you want to link at, e.g. course or module. <br />This terminology should match that implemented in your Talis Aspire hierarchy.';
$string['config_kg_ex'] = 'Default: modules';

$string['config_moduleCodeRegex'] = 'Module Code Regex';
$string['config_moduleCodeRegex_desc'] = 'A regular expression which will capture the module code part of a Moodle Course ID. <br />This will be mapped to the module code as defined in Talis Aspire, the regex pattern must have a single group which will be the part of the course id which is used. The default would match ABC123_201213 and provide us with the value ABC123 in the regex group';
$string['config_moduleCodeRegex_ex'] = '^([A-Za-z0-9]{6})_[0-9]{6}$' ;

$string['config_timePeriodRegex'] = 'Time Period Regex';
$string['config_timePeriodRegex_desc'] = 'A regular expression which will capture the time period part of a Moodle Course ID. <br />This will be mapped to the Time Period slug as defined in Talis Aspire, the regex pattern must have a single group which will be the part of the course id which is used. The default would match ABC123_201213 and provide us with the value 201213 in the regex group';
$string['config_timePeriodRegex_ex'] = '^[A-Za-z0-9]{6}_([0-9]{6})$' ;

$string['config_timePeriodMapping'] = 'Time Period Mapping';
$string['config_timePeriodMapping_desc'] = 'A JSON object describing how moodle time periods map to Talis Aspire time period slugs. <br />The form is key value pairs separated by commas {"moodleTimePeriodCode":"talisAspireTimePeriodCode"}';
$string['config_timePeriodMapping_ex'] = '{"201213":"summer-2013","201314":"autumn-2013"}';

$string['config_openNewWindow'] = 'Open list in new window';
$string['config_openNewWindow_desc'] = 'When ticked the list is opened in a new window.';

$string['config_AspireBlockTitle'] = 'Block Title';
$string['config_AspireBlockTitle_desc'] = 'The title of the block as it appears to users in Moodle';

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

// added this to prove this was really a UTF-8 FILE!! on a mac 'file filename.txt' reports a UTF-8 file as ASCII if there are NO diacritics in the file!
$spuriousVar = 'î';

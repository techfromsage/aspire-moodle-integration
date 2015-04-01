<?php
// Copyright (c) Talis Education Limited, 2013
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.


    $settings->add(new admin_setting_configtext('aspirelists/targetAspire',get_string('config_targetAspire', 'block_aspirelists'),get_string('config_targetAspire_desc', 'block_aspirelists'),get_string('config_targetAspire_ex', 'block_aspirelists')));

    $settings->add(new admin_setting_configtext('aspirelists/targetAspireAlias',get_string('config_targetAspireAlias', 'block_aspirelists'),get_string('config_targetAspireAlias_desc', 'block_aspirelists'),get_string('config_targetAspireAlias_ex', 'block_aspirelists')));

    $options = array(
        'modules'=>get_string('modules', 'block_aspirelists'), 
        'courses'=>get_string('courses', 'block_aspirelists'), 
        'units'=>get_string('units', 'block_aspirelists'), 
        'programmes'=>get_string('programmes', 'block_aspirelists'), 
        'subjects'=>get_string('subjects', 'block_aspirelists'));
    
    $settings->add(new admin_setting_configselect('aspirelists/targetKG', get_string('config_kg', 'block_aspirelists'),
                   get_string('config_kg_desc', 'block_aspirelists'), 'modules', $options));

    $settings->add(new admin_setting_configtext('aspirelists/moduleCodeRegex',get_string('config_moduleCodeRegex', 'block_aspirelists'), get_string('config_moduleCodeRegex_desc', 'block_aspirelists'), get_string('config_moduleCodeRegex_ex', 'block_aspirelists') ));

    $settings->add(new admin_setting_configtext('aspirelists/timePeriodRegex',get_string('config_timePeriodRegex', 'block_aspirelists'), get_string('config_timePeriodRegex_desc', 'block_aspirelists'), get_string('config_timePeriodRegex_ex', 'block_aspirelists') ));

    $settings->add(new admin_setting_configtext('aspirelists/timePeriodMapping',get_string('config_timePeriodMapping', 'block_aspirelists'), get_string('config_timePeriodMapping_desc', 'block_aspirelists'), get_string('config_timePeriodMapping_ex', 'block_aspirelists') ));

    $settings->add(new admin_setting_configcheckbox('aspirelists/openNewWindow', get_string('config_openNewWindow', 'block_aspirelists'), get_string('config_openNewWindow_desc', 'block_aspirelists'), 0));

    $settings->add(new admin_setting_configtext('aspirelists/blockTitle',get_string('config_AspireBlockTitle', 'block_aspirelists'),get_string('config_AspireBlockTitle_desc', 'block_aspirelists'), get_string('aspirelists', 'block_aspirelists') ));

    $settings->add(new admin_setting_configtext('aspirelists/noResourceListsMessage',get_string('config_noResourceListsMessage', 'block_aspirelists'),get_string('config_noResourceListsMessage_desc', 'block_aspirelists'), get_string('no_resource_lists_msg', 'block_aspirelists') ));

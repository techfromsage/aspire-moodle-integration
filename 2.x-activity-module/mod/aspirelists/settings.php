<?php
// Copyright (c) Talis Education Limited, 2013
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.


    $settings->add(new admin_setting_configtext('aspirelists/targetAspire',get_string('config_targetAspire', 'mod_aspirelists'),get_string('config_targetAspire_desc', 'mod_aspirelists'),get_string('config_targetAspire_ex', 'mod_aspirelists')));

    $options = array(
        'modules'=>get_string('modules', 'mod_aspirelists'),
        'courses'=>get_string('courses', 'mod_aspirelists'),
        'units'=>get_string('units', 'mod_aspirelists'),
        'programmes'=>get_string('programmes', 'mod_aspirelists'),
        'subjects'=>get_string('subjects', 'mod_aspirelists'));

    $settings->add(new admin_setting_configselect('aspirelists/targetKG', get_string('config_kg', 'mod_aspirelists'),
        get_string('config_kg_desc', 'mod_aspirelists'), 'modules', $options));

    $settings->add(new admin_setting_configselect('aspirelists/courseCodeField',
        get_string('course_code_field', 'mod_aspirelists'), get_string('course_code_field_desc', 'mod_aspirelists'),
        'idnumber', array('idnumber'=>'idnumber','shortname'=>'shortname','fullname'=>'fullname')));

    $settings->add(new admin_setting_configtext('aspirelists/moduleCodeRegex',get_string('config_moduleCodeRegex', 'mod_aspirelists'), get_string('config_moduleCodeRegex_desc', 'mod_aspirelists'), get_string('config_moduleCodeRegex_ex', 'mod_aspirelists') ));

    $settings->add(new admin_setting_configtext('aspirelists/timePeriodRegex',get_string('config_timePeriodRegex', 'mod_aspirelists'), get_string('config_timePeriodRegex_desc', 'mod_aspirelists'), get_string('config_timePeriodRegex_ex', 'mod_aspirelists') ));

    $settings->add(new admin_setting_configtext('aspirelists/timePeriodMapping',get_string('config_timePeriodMapping', 'mod_aspirelists'), get_string('config_timePeriodMapping_desc', 'mod_aspirelists'), get_string('config_timePeriodMapping_ex', 'mod_aspirelists') ));

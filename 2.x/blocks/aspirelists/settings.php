<?php
// Copyright (c) Talis Education Limited, 2011
// Released under the LGPL Licence - http://www.gnu.org/licenses/lgpl.html. Anyone is free to change or redistribute this code.


    $settings->add(new admin_setting_configtext('aspirelists/targetAspire',get_string('config_targetAspire', 'block_aspirelists'),get_string('config_targetAspire_desc', 'block_aspirelists'),get_string('config_targetAspire_ex', 'block_aspirelists')));

    $options = array(
        'modules'=>get_string('modules', 'block_aspirelists'), 
        'courses'=>get_string('courses', 'block_aspirelists'), 
        'units'=>get_string('units', 'block_aspirelists'), 
        'programmes'=>get_string('programmes', 'block_aspirelists'), 
        'subjects'=>get_string('subjects', 'block_aspirelists'));
    
    $settings->add(new admin_setting_configselect('aspirelists/targetKG', get_string('config_kg', 'block_aspirelists'),
                   get_string('config_kg_desc', 'block_aspirelists'), 'modules', $options));


//    $settings->add(new admin_setting_configtext('aspirelists/targetKG',get_string('config_kg', 'block_aspirelists'),get_string('config_kg_desc', 'block_aspirelists'),get_string('config_kg_ex', 'block_aspirelists')));
?>
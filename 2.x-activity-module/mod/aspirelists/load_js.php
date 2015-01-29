<?php

global $PAGE;

$PAGE->requires->yui_module(
    'moodle-mod_aspirelists-inline_display',
    'M.mod_aspirelists.inline_display.init_view',
    array(
        get_string('accordion_open', 'aspirelists'),
        get_string('accordion_closed', 'aspirelists')
    )
);
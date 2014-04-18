<?php


class mod_aspirelists_renderer extends plugin_renderer_base {
    function display_aspirelists(stdClass $aspirelist){
        $output = '<p><a href="#aspire_inline_readings_container_' . $aspirelist->id . '" onclick="M.mod_aspirelists.show(\'#aspirelists_inline_readings_container_' . $aspirelist->id .'\');" id="aspirelists_inline_readings_' . $aspirelist->id . '">Show readings</a></p>';

        $output .= '<div id="aspire_inline_readings_container_' . $aspirelist->id .'" style="display: none;"><object id="aspirelists_inline_readings_' . $aspirelist->id . '" height="600px" width="1000px" type="text/html" data="/mod/aspirelists/launch.php?id='.$aspirelist->cmid.'"></object></div>';
        return $output;
    }
}
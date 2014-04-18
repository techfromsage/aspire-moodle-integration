<?php


class mod_aspirelists_renderer extends plugin_renderer_base {
    function display_aspirelists(stdClass $aspirelist){
        $output = '';
        if($aspirelist->showdescription)
        {
            $output .= format_module_intro('aspirelists', $aspirelist, $aspirelist->cmid, false);
        }
        if(isset($aspirelist->display) && $aspirelist->display === 1)
        {
            if(isset($aspirelist->showexpanded) && $aspirelist->showexpanded == '1')
            {
                $style = "";
            } else {
                $style = "display: none;";
            }
            $output .= '<div class="aspirelists_inline_reading">';
            $output .= '<div id="aspire_inline_readings_container_' . $aspirelist->id .'" class="aspirelists_inline_readings_container" style="' . $style . '" data-aspirelists-id="' . $aspirelist->id .'">';
            $output .= '<object id="aspirelists_inline_readings_' . $aspirelist->id . '" class="aspirelists_inline_list" height="600px" type="text/html" data="/mod/aspirelists/launch.php?id='.$aspirelist->cmid.'"></object></div></div>';
            return $output;
        }
    }

}
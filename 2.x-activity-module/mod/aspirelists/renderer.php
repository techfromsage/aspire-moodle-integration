<?php


class mod_aspirelists_renderer extends plugin_renderer_base {
    function display_aspirelists(stdClass $aspirelist){
        $pluginSettings = get_config('mod_aspirelists');
        if(!isset($pluginSettings->defaultInlineListHeight))
        {
            $pluginSettings->defaultInlineListHeight = "400px";
        }
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
                $srcType = 'src';
            } else {
                $style = "display: none;";
                $srcType = 'data-intended-src';
            }

            $output .= $this->output->container('<iframe id="aspirelists_inline_readings_' . $aspirelist->id . '" class="aspirelists_inline_list" width="100%" height="' . $pluginSettings->defaultInlineListHeight . '" '.$srcType.'="' . new moodle_url('/mod/aspirelists/launch.php?id='.$aspirelist->cmid) .'" style="' . $style . '"></iframe>');
            return $output;
        }
    }

}
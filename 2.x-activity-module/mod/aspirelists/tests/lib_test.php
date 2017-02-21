<?php

global $CFG;
require_once($CFG->dirroot . '/mod/aspirelists/lib.php');
class mod_aspirelists_lib_testcase extends advanced_testcase {
    public function test_constants()
    {
        $this->assertEquals(0, ASPIRELISTS_DISPLAY_PAGE);
        $this->assertEquals(1, ASPIRELISTS_DISPLAY_INLINE);
        $this->assertEquals('/lti/launch', ASPIRELISTS_LTI_LAUNCH_PATH);
    }

    public function test_add_lti_properties()
    {
        $this->resetAfterTest(true);
        $year = date('Y').(date('y')+1);
        $course = $this->getDataGenerator()->create_course(array('idnumber'=>'TEST01_'.$year));
        /** @var mod_aspirelists_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_aspirelists');
        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Course readings'));
        set_config('courseCodeField', 'idnumber', 'mod_aspirelists');
        set_config('targetAspire', 'https://test.rl.talisaspire.com', 'mod_aspirelists');
        set_config('targetKG', 'module', 'mod_aspirelists');
        set_config('moduleCodeRegex', '^([A-Za-z0-9]{6})_[0-9]{6}$', 'mod_aspirelists');
        set_config('timePeriodRegex', '^[A-Za-z0-9]{6}_([0-9]{6})$', 'mod_aspirelists');
        $mapping = array($year=>date('Y-').(date('Y')+1));
        set_config('timePeriodMapping', json_encode($mapping), 'mod_aspirelists');

        aspirelists_add_lti_properties($list);
        $this->assertEquals('https://test.rl.talisaspire.com/lti/launch', $list->toolurl);
        $this->assertFalse($list->instructorchoiceacceptgrades);
        $this->assertFalse($list->instructorchoicesendname);
        $this->assertFalse($list->instructorchoicesendemailaddr);
        $this->assertNotNull($list->servicesalt);

        $this->assertRegExp("/^launch_identifier=\w*\n/", $list->instructorcustomparameters);
        $this->assertContains("knowledge_grouping_code=TEST01\n", $list->instructorcustomparameters);
        $this->assertContains("time_period=" . $mapping[$year], $list->instructorcustomparameters);
        $this->assertFalse($list->debuglaunch);

        // Change configuration
        set_config('courseCodeField', 'shortname', 'mod_aspirelists');
        set_config('targetAspire', 'https://test.rl.talisaspire.com', 'mod_aspirelists');
        set_config('targetKG', 'course', 'mod_aspirelists');
        set_config('moduleCodeRegex', '', 'mod_aspirelists');
        set_config('timePeriodRegex', '', 'mod_aspirelists');
        set_config('timePeriodMapping', '', 'mod_aspirelists');
        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Course readings'));
        aspirelists_add_lti_properties($list);
        $this->assertEquals('https://test.rl.talisaspire.com/lti/launch', $list->toolurl);
        $this->assertRegExp("/^launch_identifier=\w*\n/", $list->instructorcustomparameters);
        $this->assertContains("knowledge_grouping_code=tc_1", $list->instructorcustomparameters);
        // This shouldn't have been added because there was no regex to match it
        $this->assertNotContains("time_period=", $list->instructorcustomparameters);
    }

    /**
     * @dataProvider add_lti_properties_includes_launch_env_info_Provider
     */
    public function test_add_lti_properties_includes_launch_env_info($displayVal, $showExpandedVal)
    {
        $this->resetAfterTest(true);
        $year = date('Y').(date('y')+1);
        $course = $this->getDataGenerator()->create_course(array('idnumber'=>'TEST01_'.$year));
        /** @var mod_aspirelists_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_aspirelists');
        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Course readings'));
        set_config('courseCodeField', 'idnumber', 'mod_aspirelists');
        set_config('targetAspire', 'https://test.rl.talisaspire.com', 'mod_aspirelists');
        set_config('targetKG', 'module', 'mod_aspirelists');
        set_config('moduleCodeRegex', '^([A-Za-z0-9]{6})_[0-9]{6}$', 'mod_aspirelists');
        set_config('timePeriodRegex', '^[A-Za-z0-9]{6}_([0-9]{6})$', 'mod_aspirelists');
        $mapping = array($year=>date('Y-').(date('Y')+1));
        set_config('timePeriodMapping', json_encode($mapping), 'mod_aspirelists');

        $list->display = $displayVal;
        $list->showexpanded = $showExpandedVal;

        aspirelists_add_lti_properties($list);

        $this->assertContains("display_inline=" . $displayVal, $list->instructorcustomparameters);
        $this->assertContains("display_inline_expanded=" . $showExpandedVal, $list->instructorcustomparameters);
    }

    public function add_lti_properties_includes_launch_env_info_Provider(){
        return array(
            array("1", "1"),
            array("0", "0"),
            array("1", "0"),
            array("0", "1")
        );
    }

}
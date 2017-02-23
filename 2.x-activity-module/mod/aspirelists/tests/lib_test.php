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
        $year = $this->getDefaultYear();
        $course = $this->createCourse($year);
        $list = $this->createList(array('course' => $course->id, 'name'=>'Course readings'));
        $timePeriodMapping = $this->getTimePeriodMapping($year);
        $this->setDefaultPluginConfig($timePeriodMapping);

        aspirelists_add_lti_properties($list);
        $this->assertEquals('https://test.rl.talisaspire.com/lti/launch', $list->toolurl);
        $this->assertFalse($list->instructorchoiceacceptgrades);
        $this->assertFalse($list->instructorchoicesendname);
        $this->assertFalse($list->instructorchoicesendemailaddr);
        $this->assertNotNull($list->servicesalt);

        $this->assertRegExp("/^launch_identifier=\w*\n/", $list->instructorcustomparameters);
        $this->assertContains("knowledge_grouping_code=TEST01\n", $list->instructorcustomparameters);
        $this->assertContains("time_period=" . $timePeriodMapping[$year], $list->instructorcustomparameters);
        $this->assertFalse($list->debuglaunch);

        // Change configuration
        $this->setPluginConfig(array(
            'courseCodeField' => 'shortname',
            'targetAspire' => 'https://test.rl.talisaspire.com',
            'targetKG' => 'course',
            'moduleCodeRegex' => '',
            'timePeriodRegex' => '',
            'timePeriodMapping' => ''
        ));
        $list = $this->createList(array('course' => $course->id, 'name'=>'Course readings'));
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
        $course = $this->createCourse($this->getDefaultYear());
        $list = $this->createList(array('course' => $course->id, 'name'=>'Add LTI Properties - Course readings'));
        $this->setDefaultPluginConfig();

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

    /**
     * Create a new course
     *
     * @param $year string the year this course is for
     * @return mixed
     */
    protected function createCourse($year){
        return $this->getDataGenerator()->create_course(array('idnumber'=>'TEST01_'.$year));
    }

    /**
     * Create a new list
     *
     * @param array $listAttrs
     * @return mixed
     */
    protected function createList(array $listAttrs){
        /** @var mod_aspirelists_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_aspirelists');
        return $generator->create_instance($listAttrs);
    }

    /**
     * Set the config for this plugin
     *
     * Expects an associative array of parameters
     * @param array $config
     */
    protected function setPluginConfig(array $config)
    {
        $pluginName = 'mod_aspirelists';
        foreach ($config as $key => $val) {
            set_config($key, $val, $pluginName);
        }
    }

    /**
     * Get a default year value for testing purposes
     *
     * @return string
     */
    protected function getDefaultYear(){
        return date('Y').(date('y')+1);
    }

    /**
     * Get date mapping for the timePeriodMapping
     * @param $year
     * @return array
     */
    protected function getTimePeriodMapping($year){
        return array($year => date('Y-') . (date('Y') + 1));
    }

    /**
     * Set a default plugin config
     *
     * Useful if there are no specific configs being tested by
     * this test
     *
     * @param array $timePeriodMapping
     */
    protected function setDefaultPluginConfig(array $timePeriodMapping = null){
        if($timePeriodMapping === null) {
            $timePeriodMapping = $this->getTimePeriodMapping($this->getDefaultYear());
        }
        $this->setPluginConfig(array(
            'courseCodeField' => 'idnumber',
            'targetAspire' => 'https://test.rl.talisaspire.com',
            'targetKG' => 'module', 'mod_aspirelists',
            'moduleCodeRegex' => '^([A-Za-z0-9]{6})_[0-9]{6}$',
            'timePeriodRegex' => '^[A-Za-z0-9]{6}_([0-9]{6})$',
            'timePeriodMapping' => json_encode($timePeriodMapping)
        ));
    }

}
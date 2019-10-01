
<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * PHPUnit data generator tests
 *
 * @package    mod_aspirelists
 * @category   phpunit
 * @copyright  Copyright (c) 2014 Talis Education Ltd. (http://www.talis.com)
 * @author     Ross Singer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * PHPUnit data generator testcase
 *
 * @package    mod_aspirelists
 * @category   phpunit
 * @copyright  Copyright (c) 2014 Talis Education Ltd. (http://www.talis.com)
 * @author     Ross Singer
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_aspirelists_generator_testcase extends advanced_testcase {
    public function test_generator() {
        global $DB;

        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('aspirelists'));

        $course = $this->getDataGenerator()->create_course();

        /** @var mod_aspirelists_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_aspirelists');
        $this->assertInstanceOf('mod_aspirelists_generator', $generator);
        $this->assertEquals('aspirelists', $generator->get_modulename());

        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Course readings'));
        $records = $DB->get_records('aspirelists', array('course' => $course->id));
        $this->assertEquals(1, count($records));
        $record = current($records);
        $this->assertEquals($list->id, $record->id);
        $this->assertEquals('Course readings', $record->name);
        $this->assertEquals(0, $record->display);
        $this->assertEquals(0, $record->showexpanded);
        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Section 1', 'display'=>true));
        $record = $DB->get_record('aspirelists', array('id'=>$list->id));
        $this->assertEquals('Section 1', $record->name);
        $this->assertEquals(1, $record->display);
        $this->assertEquals(0, $record->showexpanded);
        $list = $generator->create_instance(array('course' => $course->id, 'name'=>'Section 2', 'display'=>true, 'showexpanded'=>true));
        $this->assertEquals(3, $DB->count_records('aspirelists'));
        $record = $DB->get_record('aspirelists', array('id'=>$list->id));
        $this->assertEquals('Section 2', $record->name);
        $this->assertEquals(1, $record->display);
        $this->assertEquals(1, $record->showexpanded);

        $cm = get_coursemodule_from_instance('aspirelists', $list->id);
        $this->assertEquals($list->id, $cm->instance);
        $this->assertEquals('aspirelists', $cm->modname);
        $this->assertEquals($course->id, $cm->course);

        $context = context_module::instance($cm->id);
        $this->assertEquals($list->cmid, $context->instanceid);

    }
}

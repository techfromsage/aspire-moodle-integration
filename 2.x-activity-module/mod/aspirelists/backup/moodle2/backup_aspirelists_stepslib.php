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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2014 Talis Education Ltd.
 * @license   MIT
 */

class backup_aspirelists_activity_structure_step extends backup_activity_structure_step {
    protected function define_structure() {
        // Define each element separated
        $aspire = new backup_nested_element('aspirelists', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'timemodified', 'display', 'showexpanded'
        ));


        // Define sources
        $aspire->set_source_table('aspirelists', array('id' => backup::VAR_ACTIVITYID));


        // Return the root element (tadc), wrapped into standard activity structure
        return $this->prepare_activity_structure($aspire);
    }
}

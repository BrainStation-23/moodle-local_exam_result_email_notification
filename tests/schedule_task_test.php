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

namespace exam_result_email_notification\tests;
use basic_testcase;

/**
 * Test case for task scheduling test.
 *
 * @package     local_exam_result_email_notification
 * @copyright   2022 @Brain Station 23
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class schedule_task_test extends basic_testcase {

    public function test_schedule_task() {
        global $DB;
        // Assert that exam_result_email_notification entry exists and that its visible attribute is set to 0 (disabled).
        $this->assertTrue($DB->record_exists('task_scheduled', array('component' => 'local_exam_result_email_notification')));
    }
}

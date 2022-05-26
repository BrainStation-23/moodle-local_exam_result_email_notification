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

/**
 * Test case after local_exam_result_email_notification plugin installation.
 *
 * @copyright  2022 Brain Station 23
 * @author     Brainstation23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class installation_test extends \basic_testcase {
    /**
     * block Table check after Installation
     * @return void
     * @throws \dml_exception
     */
    public function test_after_install() {
        global $DB;
        // Assert that local_exam_result_email_notification entry exists and that its visible attribute is set to 0 (disabled).
        $this->assertTrue($DB->record_exists('config_plugins', array('plugin' => 'local_exam_result_email_notification')));
    }
}

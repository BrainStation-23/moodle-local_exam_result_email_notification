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

namespace local_exam_result_email_notification\tests;
use basic_testcase;
use local_exam_result_email_notification\task\cron_task;

/**
 * Test case for the lang of local_exam_result_email_notification plugin.
 *
 * @copyright  2022 Brain Station 23
 * @author     Brainstation23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class lang_test extends basic_testcase {

    /**
     * Initial setup before starting Test
     * @return void
     */
    public static function setUpBeforeClass(): void {
        require_once(__DIR__ . '/../classes/task/cron_task.php');
    }

    /**
     * Lang Test
     * @return void
     */
    public function test_en_lang() {
        $cron = new cron_task();
        $this->assertStringContainsString($cron->get_name(), get_string('pluginname', 'local_exam_result_email_notification'));
    }
}

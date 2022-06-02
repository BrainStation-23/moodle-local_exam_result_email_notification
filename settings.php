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
 * Add Navigation Link to Plugins/Local plugins menu.
 *
 * @package    local_exam_result_email_notification
 * @author     Brainstation23
 * @copyright  2022 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) { // Needs this condition or there is error on login page.
    $ADMIN->add('localplugins', new admin_externalpage('local_exam_result_email_notification',
        get_string('pluginname', 'local_exam_result_email_notification'),
        new moodle_url('/local/exam_result_email_notification/index.php')));
}


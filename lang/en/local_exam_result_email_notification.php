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
 * Language file for the plugin.
 *
 * @package    local_exam_result_email_notification
 * @author     Brainstation23
 * @copyright  2022 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Exam result email notification';
$string['pagetitle'] = 'Exam result email notification settings';
$string['pageheading'] = 'Exam Result Email Schedule Setup';
$string['scheduleform'] = 'Schedule form';
$string['enable'] = 'Enable';
$string['disable'] = 'Disable';
$string['no_result'] = 'There is no quiz. Please Make sure your QUIZ consists of Closing time & Minimum grade 1.';
$string['quiz_result_mail'] = 'Quiz Result Mail';
$string['exam_result_email_notification:admin'] = 'Permission';
$string['form:status'] = 'Status';
$string['form:status_help'] = 'Control of the Schedule Emailing cron job action for the quiz.';
$string['form:enable_eligible_mail'] = 'Enable Mail For Eligible Student';
$string['form:enable_eligible_mail_help'] = 'Email notification for Eligible student after completing a QUIZ test.';
$string['form:eligible_mail_subject'] = 'Mail Subject';
$string['form:eligible_mail_template'] = 'Mail Content';
$string['form:eligible_mail_template_help'] = 'Text/HTML format is acceptable. You may use {name} to mention participant name in the mail content.';
$string['form:enable_disqualified_mail'] = 'Enable Mail For Disqualified Student';
$string['form:enable_disqualified_mail_help'] = 'Email notification for Disqualified student after completing a QUIZ test.';
$string['form:disqualified_mail_subject'] = 'Mail Subject';
$string['form:disqualified_mail_template'] = 'Mail Content';
$string['form:disqualified_mail_template_help'] = 'Text/HTML format is acceptable. You may use {name} to mention participant name in the mail content.';
$string['form:report_receiver'] = 'Final Report Receiver';
$string['form:report_receiver_help'] = 'This User will get the Final result through a mail.';
$string['add_email_scheduling_success'] = 'Email Scheduling Has been added successfully.';
$string['update_email_scheduling_success'] = 'Email Scheduling Has been updated successfully.';
$string['form:mail_subject_is_required'] = 'Mail Subject is Required.';
$string['form:remove_email_scheduling'] = 'Email Scheduling Has been removed successfully.';
$string['form:cancel'] = 'Form Submission Cancelled.';
$string['permission_not_found'] = 'You do not have permission to view this page.';
$string['privacy:metadata'] = 'The Exam result email notification plugin does not store any personal data.';


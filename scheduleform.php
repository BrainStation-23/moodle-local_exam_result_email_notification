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
 * Exam result email scheduling form submission file.
 *
 * @package     local_exam_result_email_notification
 * @copyright   2022 @Brain Station 23
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $USER, $DB, $CFG;
require_once("lib.php");
require_once("forms/schedule.php");

$PAGE->set_url('/local/exam_result_email_notification/scheduleform.php');
$PAGE->set_context(context_system::instance());

require_login();

$pagetitle = get_string('pagetitle', 'local_exam_result_email_notification');
$pageheading = get_string('pageheading', 'local_exam_result_email_notification');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pageheading);

$quizid = optional_param('id', '', PARAM_INT);
$courseid = optional_param('course_id', '', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);
$context = context_course::instance($courseid);

if ($action == 'delete') {
    $DB->delete_records('local_email_notifications', ['quizid' => $quizid]);
    $DB->delete_records('local_exam_participants', ['quizid' => $quizid]);
    redirect("/local/exam_result_email_notification",
        get_string('form:remove_email_scheduling', 'local_exam_result_email_notification'), 10,
        \core\output\notification::NOTIFY_SUCCESS);
}

// If there is an id then we show the edit for with save.
$mform = new schedule_form("?id=$quizid&course_id=$courseid");

$toform = [];

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    redirect("/local/exam_result_email_notification", get_string('form:cancel', 'local_exam_result_email_notification'), 10,
        \core\output\notification::NOTIFY_WARNING);
} else if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.

    $edit = false;

    $quiz = $DB->get_record('local_email_notifications', ['quizid' => $quizid]);

    if ($quiz) {
        $edit = true;
    }
    $enablesuccessemail = optional_param('enablesuccessemail', '', PARAM_INT);
    $successemailsubject = optional_param('successemailsubject', '', PARAM_TEXT);
    $enablefailemail = optional_param('enablefailemail', '', PARAM_INT);
    $failemailsubject = optional_param('failemailsubject', '', PARAM_TEXT);

    $status = required_param('status', PARAM_INT);
    $reportuserid = required_param('reportuserid', PARAM_INT);

    if ($quiz) {
        // Edit schedule.
        $quiz->enablesuccessemail = $enablesuccessemail;
        $quiz->successemailsubject = $successemailsubject;
        $quiz->enablefailemail = $enablefailemail;
        $quiz->failemailsubject = $failemailsubject;
        $quiz->status = $status;
        $quiz->reportuserid = $reportuserid;
        $DB->update_record('local_email_notifications', $quiz);

    } else {
        // New schedule.
        $quiz = new stdClass();
        $quiz->quizid = $quizid;
        $quiz->enablesuccessemail = $enablesuccessemail;
        $quiz->successemailsubject = $successemailsubject;
        $quiz->successemailtemplate = "";
        $quiz->enablefailemail = $enablefailemail;
        $quiz->failemailsubject = $failemailsubject;
        $quiz->failemailtemplate = "";
        $quiz->status = $status;
        $quiz->reportuserid = $reportuserid;
        $quiz->id = $DB->insert_record('local_email_notifications', $quiz, true, false);

    }

    // Process the Editor Content for Insert/Update Eligible Participant Email Template.
    if (!empty($fromform->successemailtemplate_editor)) {
        $quiz->successemailtemplate_editor = $fromform->successemailtemplate_editor;
        $quiz = file_postupdate_standard_editor($quiz,
            'successemailtemplate', editor_options(),
            $context, 'local_exam_result_email_notification',
            'phase_successemailtemplate', $quiz->id);
        $DB->update_record('local_email_notifications', $quiz);
    }

    // Process the Editor Content for Insert/Update Disqualified Participant Email Template.
    if (!empty($fromform->failemailtemplate_editor)) {
        $quiz->failemailtemplate_editor = $fromform->failemailtemplate_editor;
        $quiz = file_postupdate_standard_editor($quiz,
            'failemailtemplate', editor_options(),
            $context, 'local_exam_result_email_notification',
            'phase_failemailtemplate', $quiz->id);
        $DB->update_record('local_email_notifications', $quiz);
    }

    // Notify for email schedule update.
    if ($edit) {
        redirect("/local/exam_result_email_notification/index.php",
            get_string('update_email_scheduling_success',
                'local_exam_result_email_notification'), 10,
            \core\output\notification::NOTIFY_SUCCESS);
    } else {
        redirect("/local/exam_result_email_notification/index.php",
            get_string('add_email_scheduling_success',
                'local_exam_result_email_notification'), 10,
            \core\output\notification::NOTIFY_SUCCESS);
    }

} else {
    // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // Or on the first display of the form.
    if ($quizid) {
        $obj = $DB->get_record('local_email_notifications', ['quizid' => $quizid]);
        // Set default data (if any).
        if ($obj) {
            // Process the Editor Content for Viewing Eligible Participant Email Template.
            $data = file_prepare_standard_editor($obj,
                'successemailtemplate', editor_options(),
                $context, 'local_exam_result_email_notification',
                'phase_successemailtemplate', $obj->id);
            // Process the Editor Content for Viewing Disqualified Participant Email Template.
            $formdata = file_prepare_standard_editor($data,
                'failemailtemplate', editor_options(),
                $context, 'local_exam_result_email_notification',
                'phase_failemailtemplate', $obj->id);
            $mform->set_data($formdata);
        }
    }
    echo $OUTPUT->header();
    $mform->display();
    $PAGE->requires->js_call_amd('local_exam_result_email_notification/exam_result_email_notification', 'init');
    echo $OUTPUT->footer();
}

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
 * Index page functions are defined here.
 *
 * @package    local_exam_result_email_notification
 * @author     Brainstation23
 * @copyright  2022 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
global $USER, $DB, $CFG;

$PAGE->set_url('/local/exam_result_email_notification/index.php');
$PAGE->set_context(context_system::instance());

require_login();

if (!has_capability('local/exam_result_email_notification:admin', context_system::instance())) {
    $permissionnotfound = get_string('permission_not_found' . 'local_exam_result_email_notification');
    echo $OUTPUT->header();
    echo "<h3>$permissionnotfound</h3>";
    echo $OUTPUT->footer();
    exit;
}

$results = new stdClass();

$pagetitle = get_string('pagetitle', 'local_exam_result_email_notification');
$pageheading = get_string('pageheading', 'local_exam_result_email_notification');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pageheading);

/*
 * Filter Quiz for exam result email scheduling task.
 */
$sql = "SELECT {quiz}.id,{quiz}.name,{quiz}.timeopen,{quiz}.timeclose, {quiz}.course,
       {local_email_notifications}.status, {local_email_notifications}.reportemailstatus
        FROM {grade_items}
        LEFT JOIN {local_email_notifications}
        ON
        {grade_items}.iteminstance = {local_email_notifications}.quizid
        LEFT JOIN {quiz}
        ON
        {quiz}.id = {grade_items}.iteminstance
        WHERE {quiz}.timeclose>0
        AND {grade_items}.itemtype = 'mod'
        AND {grade_items}.itemmodule = 'quiz'
        AND {grade_items}.gradepass>:gradepass
        AND {grade_items}.hidden=:hidden
        ORDER BY {quiz}.timecreated DESC;";

/*
 * $item->gradepass && $item->gradepass > 0.000009 && !$item->hidden
 * This is the requirements to pass a quiz.
 */

$quizdatalist = $DB->get_records_sql($sql, ['gradepass' => 0.000009, 'hidden' => 0]);
$noresultfound = get_string('no_result', 'local_exam_result_email_notification');

$data = [];

if ($quizdatalist) {

    $courseid = [];
    $quizid = [];

    foreach ($quizdatalist as $quiz) {
        $quizid[] = $quiz->id;
    }

    // Total Quiz Participants.
    list($inquizidsql, $inparams) = $DB->get_in_or_equal($quizid);

    $sql = "SELECT {quiz}.id as quizid, COUNT({quiz_attempts}.id) AS totaluser
            FROM {quiz_attempts}
            INNER JOIN {quiz} ON {quiz}.id = {quiz_attempts}.quiz
            WHERE {quiz_attempts}.quiz $inquizidsql
            GROUP BY {quiz_attempts}.quiz";

    $enrol = $DB->get_records_sql($sql, $inparams);

    $enroldata = [];

    if ($enrol) {
        foreach ($enrol as $singlecourse) {
            $enroldata[$singlecourse->quizid] = $singlecourse->totaluser;
        }
    }

    // Total Mail sent.
    $concatinationstring = $DB->sql_concat('lep.id', 'COALESCE(lep.userid, 0)', 'COALESCE(lep.quizid, 0)');

    $sql = "SELECT $concatinationstring as unid, 
            COUNT(lep.id) AS totalmail,
            lep.quizid  as quizid
            FROM {local_exam_participants} lep
            WHERE lep.mailstatus = '1'
            AND lep.quizid $inquizidsql
            GROUP BY lep.quizid";

    $totalmail = $DB->get_records_sql($sql, $inparams);

    $totalmaildata = [];

    if ($totalmail) {
        foreach ($totalmail as $singlemail) {
            $totalmaildata[$singlemail->quizid] = $singlemail->totalmail;
        }
    }

    foreach ($quizdatalist as $quiz) {
        $std = new stdClass();
        $std->id = $quiz->id;
        $std->name = $quiz->name;
        $std->timeopen = $quiz->timeopen;
        $std->timeclose = $quiz->timeclose;
        $std->status = $quiz->status;
        $std->course_id = $quiz->course;
        $std->total_participants = $enroldata[$quiz->id] ?? 0;
        $std->mail_sent = $totalmaildata[$quiz->id] ?? 0;
        $std->reportemailstatus = $quiz->reportemailstatus;
        $std->editurl = $CFG->wwwroot
            . "/local/exam_result_email_notification/scheduleform.php?id=$quiz->id&course_id=$quiz->course";
        $data[] = $std;
    }
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_exam_result_email_notification/searchresults',
    ['quizzes' => $data, 'noresultfound' => $noresultfound]
);
echo $OUTPUT->footer();

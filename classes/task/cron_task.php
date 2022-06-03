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
namespace local_exam_result_email_notification\task;
/**
 * Cron Manager.
 *
 * @package    local_exam_result_email_notification
 * @author     Brainstation23
 * @copyright  2022 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cron_task extends \core\task\scheduled_task {

    private $limit = 2;

    /*
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */

    public function get_name() {
        return get_string('pluginname', 'local_exam_result_email_notification');
    }

    /*
     * Execute the task.
     */
    public function execute() {
        // Mail for participants.
        $this->participantsmail();
        // Mail for final report.
        $this->reportmail();
    }

    /*
     * Generate Eligible participants table for report mail.
     */
    private function reportmail() {

        global $DB, $OUTPUT;

        mtrace("Processing Exam Reporting");

        $now = time();

        $sql = "SELECT {local_email_notifications}.id as email_notification_id,
        {quiz}.id as quizid,{quiz}.name as quizname,
        {local_email_notifications}.reportuserid
        FROM {local_email_notifications}
        INNER JOIN {quiz} ON {quiz}.id={local_email_notifications}.quizid
        WHERE {quiz}.timeclose<:now
        AND {local_email_notifications}.reportemailstatus=:reportemailstatus";

        $quizdatalist = $DB->get_records_sql($sql, ['now' => $now, 'reportemailstatus' => 0], 0, $this->limit);

        if ($quizdatalist) {
            foreach ($quizdatalist as $quiz) {
                $sql = "SELECT  {local_exam_participants}.id as pid,
                CONCAT( firstname, ' ', lastname) AS name,
                {local_exam_participants}.userid,
                {user}.email as email, {user}.phone1 as phone1, {user}.phone2  as phone2
                FROM {local_exam_participants}
                INNER JOIN {user} ON {user}.id = {local_exam_participants}.userid
                WHERE {local_exam_participants}.quizid = :quizid
                AND {local_exam_participants}.quizresultstatus = :quizresultstatus
                GROUP BY {user}.id";

                $participants = $DB->get_records_sql($sql, ['quizid' => $quiz->quizid, 'quizresultstatus' => 1]);

                $data['totalparticipants'] = 0;
                if ($participants) {
                    $i = 1;
                    foreach ($participants as $participant) {
                        $std = new \stdClass();
                        $std->sn = $i++;
                        $std->name = $participant->name;
                        $std->email = $participant->email;
                        $std->phone1 = $participant->phone1;
                        $std->phone2 = $participant->phone2;
                        $participantsdata[] = $std;
                    }
                    $data['totalparticipants'] = count($participantsdata);
                    $data['data'] = $participantsdata;
                }

                $messagehtml = $OUTPUT->render_from_template('local_exam_result_email_notification/report', $data);
                $messagetext = html_to_text($messagehtml);
                $user = \core_user::get_user($quiz->reportuserid);
                $noreplyuser = \core_user::get_noreply_user();
                $subject = $quiz->quizname . " Report.";
                $stdquiznotification = $DB->get_record('local_email_notifications', ['id' => $quiz->email_notification_id]);;
                $stdquiznotification->reportemailstatus = 1;
                $DB->update_record('local_email_notifications', $stdquiznotification);
                email_to_user($user, $noreplyuser, $subject, $messagetext, $messagehtml);

            }
            mtrace('done');
        } else {
            mtrace('No Data Found.');
        }

    }

    /*
     * Gather individual participant quiz result for mail.
     */
    private function participantsmail() {

        global $DB, $CFG;

        require_once($CFG->libdir . '/filelib.php');

        mtrace("Processing Exam Scheduling");

        $sql = "SELECT {local_email_notifications}.*,
        {grade_items}.gradepass,
        {grade_grades}.finalgrade as obtaininggrade,
        {grade_items}.courseid,
        {quiz_attempts}.userid
        FROM {quiz_attempts}
        INNER JOIN {local_email_notifications}
        ON
        ({local_email_notifications}.quizid={quiz_attempts}.quiz
        AND
        {local_email_notifications}.reportemailstatus=0)
        INNER JOIN {grade_items}
        ON
        ({grade_items}.iteminstance={quiz_attempts}.quiz
        AND {grade_items}.itemmodule = 'quiz')
        INNER JOIN {grade_grades} ON ({grade_grades}.itemid={grade_items}.id
        AND {grade_grades}.userid={quiz_attempts}.userid)
        LEFT JOIN {local_exam_participants}
        ON
        ({local_exam_participants}.userid={quiz_attempts}.userid
        AND
        {local_exam_participants}.mailstatus is null)
        WHERE {quiz_attempts}.state = 'finished'";

        $quizdatalist = $DB->get_records_sql($sql, null, 0, $this->limit);

        if ($quizdatalist) {

            foreach ($quizdatalist as $quiz) {

                $context = \context_course::instance($quiz->courseid);

                $subject = "BS23 Notification";
                $user = \core_user::get_user($quiz->userid);
                if ($quiz->gradepass > 0.000009 && $quiz->obtaininggrade >= $quiz->gradepass && $quiz->enablesuccessemail) {
                    $messagehtml = file_rewrite_pluginfile_urls($quiz->successemailtemplate,
                        'pluginfile.php',
                        $context->id,
                        'local_exam_result_email_notification',
                        'phase_successemailtemplate',
                        $quiz->id);
                    $messagetext = html_to_text($messagehtml);
                    $subject = $quiz->successemailsubject;
                    $quizresultstatus = 1;
                } else if ($quiz->enablefailemail) {
                    $messagehtml = file_rewrite_pluginfile_urls($quiz->failemailtemplate,
                        'pluginfile.php',
                        $context->id,
                        'local_exam_result_email_notification',
                        'phase_failemailtemplate',
                        $quiz->id);
                    $messagetext = html_to_text($messagehtml);
                    $subject = $quiz->failemailsubject;
                    $quizresultstatus = 0;
                }

                $messagehtml = str_replace("{name}", $user->firstname . ' ' . $user->lastname,
                    $messagehtml);

                $messagetext = str_replace("{name}", $user->firstname . ' ' . $user->lastname,
                    $messagetext);

                $stdparticipant = new \stdClass();
                $stdparticipant->userid = $quiz->userid;
                $stdparticipant->quizid = $quiz->quizid;
                $stdparticipant->mailstatus = 1;
                $stdparticipant->mailstring = $messagehtml;
                $stdparticipant->datetime = time();
                $stdparticipant->quizresultstatus = $quizresultstatus;
                $DB->insert_record('local_exam_participants', $stdparticipant);
                $noreplyuser = \core_user::get_noreply_user();
                email_to_user($user, $noreplyuser, $subject, $messagetext, $messagehtml);

            }
            mtrace('done');
        } else {
            mtrace('No Data Found.');
        }
    }
}

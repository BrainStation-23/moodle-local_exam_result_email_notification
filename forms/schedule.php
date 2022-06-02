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
 * Schedule moodle form generator.
 *
 * @package     local_exam_result_email_notification
 * @copyright   2022 @Brain Station 23
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/local/exam_result_email_notification/lib.php');

class schedule_form extends moodleform {

    // Add elements to form.
    public function definition() {
        global $DB, $CFG;
        $mform = $this->_form; // Don't forget the underscore!
        $html = '<div class="row">
        <div class="col-md">
        <strong class="pull-left">' . get_string('scheduleform', 'local_exam_result_email_notification') . '</strong>
        </div>
        <div class="col-md">
        <a class="btn btn-outline-primary pull-right" href="' . $CFG->wwwroot . '/local/exam_result_email_notification">Back</a>
        </div>
        </div>';

        $status = get_string('form:status', 'local_exam_result_email_notification');
        $enableeligiblemail = get_string('form:enable_eligible_mail', 'local_exam_result_email_notification');
        $eligiblemailsubject = get_string('form:eligible_mail_subject', 'local_exam_result_email_notification');
        $eligiblemailtemplate = get_string('form:eligible_mail_template', 'local_exam_result_email_notification');

        $enabledisqualifiedmail = get_string('form:enable_disqualified_mail', 'local_exam_result_email_notification');
        $disqualifiedmailsubject = get_string('form:disqualified_mail_subject', 'local_exam_result_email_notification');
        $disqualifiedmailtemplate = get_string('form:disqualified_mail_template', 'local_exam_result_email_notification');

        $reportreceiver = get_string('form:report_receiver', 'local_exam_result_email_notification');

        $options = array(
            '0' => 'Disable',
            '1' => 'Enable'
        );

        $editoroptions = editor_options();

        $mform->addElement('html', $html);

        $mform->addElement('select', 'status', $status, $options);
        $mform->setDefault('status', '1');
        $mform->addHelpButton('status', 'form:status', 'local_exam_result_email_notification');

        $mform->addElement('checkbox', 'enablesuccessemail', $enableeligiblemail, null, 'id=enablesuccessemail');
        $mform->addHelpButton('enablesuccessemail', 'form:enable_eligible_mail', 'local_exam_result_email_notification');

        $mform->addElement('text', 'successemailsubject', $eligiblemailsubject, 'id=successemailsubject size="100%"');
        $mform->setType('successemailsubject', PARAM_TEXT);
        $mform->setDefault('successemailsubject', '');

        $mform->addElement('editor', 'successemailtemplate_editor', $eligiblemailtemplate, 'id=successemailtext', $editoroptions);
        $mform->setType('successemailtemplate', PARAM_RAW);
        $mform->addHelpButton('successemailtemplate_editor', 'form:eligible_mail_template', 'local_exam_result_email_notification');

        $mform->addElement('checkbox', 'enablefailemail', $enabledisqualifiedmail, null, 'id=enablefailemail');
        $mform->addHelpButton('enablefailemail', 'form:enable_disqualified_mail', 'local_exam_result_email_notification');

        $mform->addElement('text', 'failemailsubject', $disqualifiedmailsubject, 'id=failemailsubject size="100%"');
        $mform->setType('failemailsubject', PARAM_TEXT);
        $mform->setDefault('failemailsubject', '');

        $mform->addElement('editor', 'failemailtemplate_editor', $disqualifiedmailtemplate, 'id=failemailtext', $editoroptions);
        $mform->setType('failemailtemplate', PARAM_CLEANHTML);
        $mform->addHelpButton('failemailtemplate_editor',
            'form:disqualified_mail_template',
            'local_exam_result_email_notification');

        $sql = "SELECT id, CONCAT( firstname, ' ', lastname) AS name
                FROM {user}
                WHERE {user}.deleted = 0;";
        $users = $DB->get_records_sql($sql);
        $useroptions = [];
        foreach ($users as $user) {
            $useroptions[$user->id] = $user->name;
        }

        $mform->addElement('select', 'reportuserid', $reportreceiver, $useroptions);
        $mform->addRule('reportuserid', get_string('required'), 'required');
        $mform->addHelpButton('reportuserid', 'form:report_receiver', 'local_exam_result_email_notification');

        $buttonarray = array();
        $buttonarray[] = $mform->createElement('submit', 'Submit', 'Save');
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addgroup($buttonarray, 'buttonar', '', ' ', false);

    }

    // Custom validation should be added here.
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if (isset($data['enablesuccessemail']) && empty($data['successemailsubject'])) {
            $errors['successemailsubject'] = get_string('form:mail_subject_is_required', 'local_exam_result_email_notification');
        }
        if (isset($data['enablefailemail']) && empty($data['failemailsubject'])) {
            $errors['failemailsubject'] = get_string('form:mail_subject_is_required', 'local_exam_result_email_notification');
        }
        return $errors;
    }

}

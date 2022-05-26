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

/*
 * Lib for the plugin.
 *
 * @package     local_exam_result_email_notification
 * @copyright   2022 @Brain Station 23
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function editor_options() {
    $courseid = required_param('course_id', PARAM_INT);
    $context = context_course::instance($courseid);
    $definitionoptions = array('subdirs' => false, 'maxfiles' => 5, 'maxbytes' => 0, 'trusttext' => true,
        'context' => $context);
    return $definitionoptions;
}

/*
 * Serve the files from the local_exam_result_email_notification file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function local_exam_result_email_notification_pluginfile($course,
                                                         $cm,
                                                         $context,
                                                         $filearea,
                                                         $args,
                                                         $forcedownload,
                                                         array $options = array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.

    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'phase_successemailtemplate' && $filearea !== 'phase_failemailtemplate') {
        return false;
    }

    // Make sure the user is logged in and has access to the
    // module (plugins that are not course modules should leave out the 'cm' part).
    // Disable login require_login($course, true, $cm);.

    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    // if (!has_capability('local/exam_result_email_notification:view', $context)) {
    // return false;
    // }.

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/';
    } else {
        $filepath = '/' . implode('/', $args) . '/';
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_exam_result_email_notification', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}



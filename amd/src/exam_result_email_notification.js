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
 * Schedule Setup Front End Activity Controller js.
 *
 * @author     BrainStation-23
 * @copyright  2022 Brain Station 23
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*eslint linebreak-style: ["error", "windows"]*/
define([
        'core/first',
        'jquery'
    ],
    function (core, $) {
        return {
            init: function () {
                $(document).ready(function () {

                    var enableSuccesseMail = $("#enablesuccessemail");
                    var enableFaileMail = $("#enablefailemail");

                    $('#fitem_successemailtext').hide();
                    $('#fitem_successemailsubject').hide();
                    $('#fitem_failemailtext').hide();
                    $('#fitem_failemailsubject').hide();

                    enableSuccesseMail.change(function () {
                        // this will contain a reference to the checkbox
                        if (this.checked) {
                            $('#fitem_successemailtext').show();
                            $('#fitem_successemailsubject').show();
                        } else {
                            $('#fitem_successemailtext').hide();
                            $('#fitem_successemailsubject').hide();
                        }
                    });

                    if (enableSuccesseMail.prop('checked') == true) {
                        $('#fitem_successemailtext').show();
                        $('#fitem_successemailsubject').show();
                    } else {
                        $('#fitem_successemailtext').hide();
                        $('#fitem_successemailsubject').hide();
                    }

                    enableFaileMail.change(function () {
                        // this will contain a reference to the checkbox
                        if (this.checked) {
                            $('#fitem_failemailtext').show();
                            $('#fitem_failemailsubject').show();
                        } else {
                            $('#fitem_failemailtext').hide();
                            $('#fitem_failemailsubject').hide();
                        }
                    });

                    if (enableFaileMail.prop('checked') == true) {
                        $('#fitem_failemailtext').show();
                        $('#fitem_failemailsubject').show();
                    } else {
                        $('#fitem_failemailtext').hide();
                        $('#fitem_failemailsubject').hide();
                    }
                });
            }
        };
    });
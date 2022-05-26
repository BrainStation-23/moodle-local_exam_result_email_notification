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
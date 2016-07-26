<!DOCTYPE HTML>
<!--
/*
 * jQuery File Upload Plugin Basic Demo 1.2.3
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2013, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
-->
<html lang="en">
    <head>
        <!-- Force latest IE rendering engine or ChromeFrame if installed -->
        <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
        <meta charset="utf-8">
        <title>jQuery File Upload Demo - Basic version</title>
        <meta name="description" content="File Upload widget with multiple file selection, drag&amp;drop support and progress bar for jQuery. Supports cross-domain, chunked and resumable file uploads. Works with any server-side platform (PHP, Python, Ruby on Rails, Java, Node.js, Go etc.) that supports standard HTML form file uploads.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap styles -->
        <link rel="stylesheet" href="<?php echo URL::base(TRUE); ?>static/css/jquery-ui.css">
        <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
        <link rel="stylesheet" href="<?php echo URL::base(TRUE); ?>static/css/jquery.fileupload-ui.css">
    </head>
    <body>
        <div class="container">
            <h1>jQuery File Upload Demo</h1>
            <h2 class="lead">Basic version</h2>

            <br>
            <label>
                <input type="text" name="img"/>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button" style="overflow: hidden;display: inline-block">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>上传</span>
                    <!-- The file input field used as target for the file upload widget -->
                    <input id="fileupload" rel="img" type="file" name="files[]" multiple>
                </span>
            </label>
            <br>
            <br>
            <!-- The global progress bar -->
            <div id="progress" class="progress">
                <div class="progress-bar progress-bar-success"></div>
            </div>
            <!-- The container for the uploaded files -->
            <div id="files" class="files"></div>
            <br>

        </div>
        <!--script src="http://code.jquery.com/jquery-1.10.1.min.js"></script-->
        <script src="<?php echo URL::base(TRUE); ?>static/js/jquery-1.6.2.min.js"></script>
        <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
        <script src="<?php echo URL::base(TRUE); ?>static/js/jquery-ui-1.8.16.custom.min.js"></script>
        <script src="<?php echo URL::base(TRUE); ?>static/js/jquery.ui.widget.js"></script>
        <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
        <script src="<?php echo URL::base(TRUE); ?>static/js/jquery.iframe-transport.js"></script>
        <!-- The basic File Upload plugin -->
        <script src="<?php echo URL::base(TRUE); ?>static/js/jquery.fileupload.js"></script>
        <script>
            /*jslint unparam: true */
            /*global window, $ */
            $(function() {
                'use strict';
                // Change this to the location of your server-side upload handler:
                var url = '<?php echo URL::site("bms/home/upload"); ?>';
                $('#fileupload').fileupload({
                    url: url,
                    dataType: 'json',
                    done: function(e, data) {
                        $.each(data.result.files, function(index, file) {
                            $(e.target).parents("label").find("input[name=img]").val(file.url);
                            $('<p/>').html("<img width=\"30\" src=\"" + file.url + "\"/>").appendTo('#files');
                        });
                    },
                    progressall: function(e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .progress-bar').css(
                                'width',
                                progress + '%'
                                );
                    }
                }).prop('disabled', !$.support.fileInput)
                        .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });
        </script>
    </body>
</html>

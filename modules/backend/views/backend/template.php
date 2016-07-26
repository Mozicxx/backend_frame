<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>后台管理</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="<?php echo URL::base(TRUE); ?>static/css/jquery.fileupload-ui.css"/>
        <link href="<?php echo URL::base(TRUE); ?>static/backend/css/common.css" rel="stylesheet" type="text/css" />
        <script src="<?php echo URL::base(TRUE); ?>static/mui/boot.js" type="text/javascript"></script>
        <script src="<?php echo URL::base(TRUE); ?>static/backend/backend.js" type="text/javascript"></script>
        <style type="text/css">
            html, body{
                margin:0;padding:0;border:0;width:100%;height:100%;overflow:hidden;
            }

            .logo
            {
                font-family:"微软雅黑",	"Helvetica Neue",​Helvetica,​Arial,​sans-serif;
                font-size:28px;
                font-weight:bold;
                cursor:default;
                position:absolute;top:25px;left:14px;
                line-height:28px;
                color:#444;
            }
            .topNav
            {
                position:absolute;right:8px;top:12px;
                font-size:12px;
                line-height:25px;
            }
            .topNav a
            {
                text-decoration:none;
                font-weight:normal;
                font-size:12px;
                line-height:25px;
                margin-left:3px;
                margin-right:3px;
                color:#333;
            }
            .topNav a:hover
            {
                text-decoration:underline;
            }
            .mini-layout-region-south img
            {
                vertical-align:top;
            }
        </style>
    </head>
    <body >   
        <?php echo isset($content) ? $content : null; ?>
        <script src="<?php echo URL::base(TRUE); ?>static/mui/tongji.js" type="text/javascript"></script>
    </body>
</html>
<form id="developerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>广告主信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">平台</td>
                    <td><input name="platform" class="mini-combobox" url="<?php echo URL::site("bms/data/platform", TRUE); ?>" required="true"/></td>
                    <td style="width:100px;">开发者</td>
                    <td><input name="developer_id" url="<?php echo URL::site("bms/data/developer", TRUE); ?>"  readonly="true" class="mini-combobox" pinyinField="pinyin"/></td>
                </tr>
                <tr>
                    <td>名称</td>
                    <td><input name="app_name" class="mini-textbox" required="true"/></td>
                    <td>分类</td>
                    <td><input name="tags" class="mini-combobox"  url="<?php echo URL::site("bms/data/app_tag", TRUE); ?>"/></td>
                </tr>
                <tr>
                    <td>宣传语</td>
                    <td colspan="3"><input name="slogan" class="mini-textbox" style="width: 83%"/></td>
                </tr>
                <tr>
                    <td>图标</td>
                    <td colspan="3" class="filed">
                        <input name="icon" class="mini-textbox" style="width: 70%"/>
                        <span class="btn btn-success fileinput-button">
                            <span>上传</span>
                            <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                        </span>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>应用上传</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">下载地址</td>
                    <td colspan="3" class="filed"><input name="download_url" class="mini-textbox" style="width: 70%"/>
                        <span class="btn btn-success fileinput-button">
                            <span>上传</span>
                            <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                        </span>
                    </td>                
                </tr>
                <tr>
                    <td>包大小</td>
                    <td width="70"><input name="package_size" class="mini-textbox" /></td>
                    <td width="100">应用评级</td>
                    <td><input name="grade" class="mini-spinner" required="true"/></td>
                </tr>
                <tr>
                    <td>包名</td>
                    <td><input name="package_name" class="mini-textbox" /></td>
                    <td>应用版本</td>
                    <td><input name="ver" class="mini-textbox" /></td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td colspan="3"><input name="status" class="mini-combobox" url="<?php echo URL::site("bms/data/online", TRUE); ?>" required="true"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>应用简介/配图</legend>
            <style>
                .appimg{float: left;width: 90px;height: 150px;margin: 0 5px;text-align: center;}
                .appimg img{width: 90px;height: 120px;background: #aaa;}
            </style>
            <table width="100%">
                <tr>
                    <td colspan="2" valign="top"><input name="app_description" class="mini-textarea" width="300" height="120"/></td>
                    <td colspan="2">
                        <div class="appimg filed"><img class="prev" src=""/>
                            <span style="display:block;" class="btn btn-success fileinput-button"><input name="app_image[]" type="hidden"/>
                                <span>上传</span>
                                <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                            </span>
                        </div>
                        <div class="appimg filed"><img class="prev" src=""/><input name="app_image[]" type="hidden"/>
                            <span style="display:block;" class="btn btn-success fileinput-button">
                                <span>上传</span>
                                <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                            </span>
                        </div>
                        <div class="appimg filed"><img class="prev" src=""/><input name="app_image[]" type="hidden"/>
                            <span style="display:block;" class="btn btn-success fileinput-button">
                                <span>上传</span>
                                <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div style="text-align:center;padding:10px;">
        <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;">确定</a>
        <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
    </div>
</form>

<script type="text/javascript">
    mini.parse();

    var form = new mini.Form("developerfrom");

    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;
        var img = {};
        $(".appimg > img.prev").each(function(index) {
            img[index] = $(this).attr("src");
        });
        o["app_image"] = mini.encode(img);
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/developer/app", TRUE) . "?method=save"; ?>",
            type: 'post',
            data: {data: json},
            cache: false,
            success: function(text) {
                CloseWindow("save");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
                CloseWindow();
            }
        });
    }

    ////////////////////
    //标准方法接口定义
    function SetData(data) {
        if (data.action === "edit") {
            //跨页面传递的数据对象，克隆后才可以安全使用
            data = mini.clone(data);

            $.ajax({
                url: "<?php echo URL::site("bms/developer/app", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
                    form.setChanged(false);
                    var img = mini.decode(o["app_image"]);
                    $(".appimg > img.prev").each(function(index) {
                        try {
                            $(this).attr("src", img[index]);
                        } catch (e) {
                        }
                    });
                }
            });
        }
    }

    function GetData() {
        var o = form.getData();
        return o;
    }
    function CloseWindow(action) {
        if (action === "close" && form.isChanged()) {
            if (confirm("数据被修改了，是否先保存？")) {
                return false;
            }
        }
        if (window.CloseOwnerWindow)
            return window.CloseOwnerWindow(action);
        else
            window.close();
    }
    function onOk(e) {
        SaveData();
    }
    function onCancel(e) {
        CloseWindow("cancel");
    }

</script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.ui.widget.js"></script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.iframe-transport.js"></script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.fileupload.js"></script>
<script>
    $(function() {
        $('.fileupload').each(function() {
            $(this).fileupload({
                dataType: 'json',
                done: function(e, data) {
                    $.each(data.result.files, function(index, file) {
                        var filed = $(e.target).parents(".filed").find("input[type=text]").attr("name");
                        var o = form.getData();
                        o[filed] = file.url;
                        form.setData(o);
                        //$(e.target).parents(".filed").find("input[type=text]").val(file.url);
                        $(e.target).parents(".filed").find("img.prev").attr("src", file.url);
                    });
                }
            });
        });
    });
</script>
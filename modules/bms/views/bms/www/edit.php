<link href="<?php echo URL::base(TRUE); ?>/static/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
<script type="text/javascript" charset="utf-8" src="<?php echo URL::base(TRUE); ?>/static/umeditor/umeditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo URL::base(TRUE); ?>/static/umeditor/umeditor.min.js"></script>
<script type="text/javascript" src="<?php echo URL::base(TRUE); ?>/static/umeditor/lang/zh-cn/zh-cn.js"></script>
<form id="articleform" method="post">
    <input name="id" type="hidden" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>编辑内容</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">标题</td>
                    <td><input name="title" class="mini-textbox" style="width: 90%" required="true"/></td>
                </tr>
                <tr>
                    <td valign="top" style="width:70px;">内容</td>
                    <td>
                        <input id="content" name="content" class="mini-textarea" style="width: 90%;height: 400px;"/>
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
    var editor = UM.getEditor('content');
    var form = new mini.Form("articleform");

    function SaveData() {
        var o = form.getData();
        o.content = editor.getContent();
        form.validate();
        if (form.isValid() === false)
            return;
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo Request::$current->url(TRUE) . "?method=save"; ?>",
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
                url: "<?php echo Request::$current->url(TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
                    editor.setContent(o.content);
                    form.setChanged(false);
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
<form id="developerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>反馈信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">主题</td>
                    <td colspan="5"><input style="width: 100%" name="subject" class="mini-textbox"  readonly="true"/></td>
                    <td style="width:50px;">反馈时间</td>
                    <td><input name="add_time" class="mini-textbox"  readonly="true"/></td>
                </tr>
                <tr>
                    <td>说明</td>
                    <td colspan="7"><input name="desc" class="mini-textarea" style="width: 86%" readonly="true"/></td>
                </tr>                
                <tr>
                    <td style="width:70px;">用户</td>
                    <td><input name="add_by" style="width:70px;" class="mini-textbox"  readonly="true"/></td>
                    <td style="width:50px;">手机号</td>
                    <td><input name="mobile"  class="mini-textbox"  readonly="true"/></td>
                    <td style="width:70px;">电邮</td>
                    <td><input name="email" style="width:70px;" class="mini-textbox"  readonly="true"/></td>
                    <td style="width:50px;">QQ</td>
                    <td><input name="qq" class="mini-textbox"  readonly="true"/></td>
                </tr>
                <tr>
                    <td>更新时间</td>
                    <td colspan="3"><input name="update_time" class="mini-textbox"  readonly="true"/></td>
                    <td>最后更新</td>
                    <td colspan="3"><input name="update_by" class="mini-textbox"  readonly="true"/></td>
                </tr>   
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>回复</legend>
            <input name="reply" class="mini-textarea" required="true" style="width: 98%"/>
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

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/developer/feedback", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/developer/feedback", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
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
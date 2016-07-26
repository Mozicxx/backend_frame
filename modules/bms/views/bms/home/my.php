<form id="advertiserfrom" method="post">
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>基本信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">姓名</td>
                    <td><input name="realname" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">性别</td>
                    <td><input name="gender" class="mini-combobox" data='[{id:"M",text:"男"},{id:"F",text:"女"}]'/></td>
                </tr>
                <tr>
                    <td>生日</td>
                    <td><input name="birthday" class="mini-datepicker"/></td>
                    <td>QQ</td>
                    <td><input name="qq" class="mini-textbox"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>联系方式</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">电子邮件</td>
                    <td><input name="email" class="mini-textbox" /></td>
                    <td style="width:100px;">手机号</td>
                    <td><input name="phone" class="mini-textbox" /></td>
                </tr>
                <tr>
                    <td>家庭住址</td>
                    <td colspan="3"><input name="address" class="mini-textbox" style="width: 68.8%" /></td>
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

    var form = new mini.Form("advertiserfrom");

    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/home/my", TRUE) . "?method=save"; ?>",
            type: 'post',
            data: {data: json},
            cache: false,
            success: function(text) {
                mini.alert("已保存");
                SetData();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
            }
        });
    }

    ////////////////////
    //标准方法接口定义
    function SetData() {

        $.ajax({
            url: "<?php echo URL::site("bms/home/my", TRUE) . "?method=get"; ?>",
            cache: false,
            success: function(text) {
                var o = mini.decode(text);
                form.setData(o);
                form.setChanged(false);
            }
        });
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

    SetData();
</script>
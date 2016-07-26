<form id="pwdform" method="post">
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>基本信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">旧密码</td>
                    <td><input name="oldpassword" class="mini-password" required="true"/></td>

                </tr>
                <tr>
                    <td>新密码</td>
                    <td><input name="password" class="mini-password" required="true"/></td>
                </tr>
                <tr>
                    <td>密码确认</td>
                    <td><input name="password2" class="mini-password" required="true"/></td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div style="text-align:left;padding:10px;">
        <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;">确定</a>
        <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
    </div>
</form>

<script type="text/javascript">
    mini.parse();

    var form = new mini.Form("pwdform");

    function SaveData() {
        var o = form.getData();
        if (o.password !== o.password2) {
            mini.alert("两次输入密码不一致");
            return false;
        }

        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("backend/home/retpwd", TRUE) . "?method=save"; ?>",
            type: 'post',
            data: {data: json},
            cache: false,
            success: function(text) {
                mini.alert(mini.decode(text));
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
            }
        });
    }


    function GetData() {
        var o = form.getData();
        return o;
    }
    function onOk(e) {
        SaveData();
    }
    function onCancel(e) {
        CloseWindow("cancel");
    }
</script>
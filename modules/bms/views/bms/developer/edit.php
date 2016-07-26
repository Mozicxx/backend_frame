<form id="developerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>开发者信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">登录名</td>
                    <td><input name="username" class="mini-textbox" readonly="true"/></td>
                    <td style="width:100px;">姓名</td>
                    <td><input name="name" class="mini-textbox" readonly="true"/></td>
                </tr>
                <tr>
                    <td>电子邮件</td>
                    <td><input name="email" class="mini-textbox" readonly="true"/></td>
                    <td>电话</td>
                    <td><input name="phone" class="mini-textbox"/></td>
                </tr>                
                <tr>
                    <td>QQ</td>
                    <td><input name="qq" class="mini-textbox"/></td>
                    <td>类型</td>
                    <td><input name="job" class="mini-combobox" data="[{id:'company',text:'公司'},{id:'personal',text:'个人开发者'}]" required="true"/></td>
                </tr>                        
                <tr>
                    <td>注册时间</td>
                    <td><input name="add" class="mini-textbox" readonly="readonly"/></td>
                    <td>最后登录</td>
                    <td><input name="last_login" class="mini-textbox" readonly="readonly"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>开发者审核</legend>
            <table width="100%">  
                <tr>
                    <td style="width:70px;">状态</td>
                    <td><input name="actived" class="mini-combobox" data="[{id:'actived',text:'已验证'},{id:'pendding',text:'未验证'}]" required="true"/></td>
                    <td style="width:100px;">客服代表</td>
                    <td><input name="r_manager" class="mini-combobox" url="<?php echo URL::site("bms/data/admin", TRUE); ?>" required="true"/></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td colspan="3"><input name="note" class="mini-textarea" style="width: 86%"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>重置密码</legend>
            <table width="100%">  
                <tr>
                    <td style="width:70px;">密码</td>
                    <td><input name="newpassword" class="mini-textbox"/></td>
                    <td>不修改密码可不填写此项。</td>
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

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/developer/manage", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/developer/manage", TRUE) . "?method=get&id="; ?>" + data.id,
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
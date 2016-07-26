<form id="channelmanagerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>基本信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">渠道</td>
                    <td colspan="3"><input name="channel_id" readonly="readonly" class="mini-combobox" url="<?php echo URL::site("backend/data/pubchannel", TRUE); ?>"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">登录名</td>
                    <td><input name="username" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">密码</td>
                    <td><input name="password" class="mini-textbox"/></td>
                </tr>
                <tr>
                    <td>姓名</td>
                    <td><input name="name" class="mini-textbox" required="true"/></td>
                    <td>手机号</td>
                    <td><input name="mobile" class="mini-textbox"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>联系方式</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">管理员</td>
                    <td><input name="owner" class="mini-combobox" required="true" data="[{id:'1',text:'是'},{id:'0',text:'否'}]" /></td>
                    <td style="width:100px;">状态</td>
                    <td><input name="status" class="mini-combobox" required="true" data="[{id:'actived',text:'活跃'},{id:'locked',text:'禁用'}]" /></td>
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

    var form = new mini.Form("channelmanagerfrom");

    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("backend/config/channelmanager", TRUE) . "?method=save"; ?>",
            type: 'post',
            data: {data: json},
            cache: false,
            success: function (text) {
                CloseWindow("save");
            },
            error: function (jqXHR, textStatus, errorThrown) {
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
                url: "<?php echo URL::site("backend/config/channelmanager", TRUE) . "?method=get"; ?>" + (data.id ? "&id=" + data.id : "") + (data.channel_id ? "&channel_id=" + data.channel_id : ""),
                cache: false,
                success: function (text) {
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
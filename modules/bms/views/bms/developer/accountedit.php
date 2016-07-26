<form id="developerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>媒介账户审核</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">帐户名</td>
                    <td><input name="account_name" class="mini-textbox"  readonly="true"/></td>
                    <td style="width:100px;">帐户类型</td>
                    <td><input name="account_type" class="mini-combobox" data="[{id:'personal',text:'个人账户'},{id:'company',text:'公司账户'}]"  readonly="true"/></td>
                </tr>
                <tr>
                    <td>银行</td>
                    <td><input name="bank" class="mini-textbox"  readonly="true"/></td>
                    <td>银行卡号</td>
                    <td><input name="bank_card" class="mini-textbox" readonly="true"/></td>
                </tr>                
                <tr>
                    <td>分行名称</td>
                    <td colspan="3"><input name="bank_name" class="mini-textbox" style="width: 86%" readonly="true"/></td>
                </tr>
                <tr>
                    <td>身份证</td>
                    <td colspan="3"><input name="idcard" class="mini-textbox" style="width: 86%" readonly="true"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <style>
                .idcardimg{height: 100px;}
            </style>
            <legend>身份证照片</legend>
            <table width="100%"> 
                <tr>
                    <td>正面</td>
                    <td>反面</td>
                </tr>
                <tr>
                    <td><img id="idcard1" class="idcardimg" src=""/></td>
                    <td><img id="idcard2" class="idcardimg" src=""/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>审核</legend>
            <table width="100%"> 
                <tr>
                    <td style="width:70px;">添加时间</td>
                    <td><input name="add_time" class="mini-textbox"  readonly="true"/></td>
                    <td style="width:100px;">审核时间</td>
                    <td><input name="pass_time" class="mini-textbox" readonly="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">状态</td>
                    <td><input name="status" class="mini-combobox" data="[{id:'pass',text:'已审核'},{id:'reject',text:'未通过'},{id:'pendding',text:'未审核'}]" required="true"/></td>
                    <td style="width:100px;">审核人</td>
                    <td><input name="pass_by" class="mini-combobox" url="<?php echo URL::site("bms/data/admin", TRUE); ?>"  readonly="true"/></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td colspan="3"><input name="note" class="mini-textarea" style="width: 86%"/></td>
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
            url: "<?php echo URL::site("bms/developer/account", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/developer/account", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
                    form.setChanged(false);
                    $("#idcard1").attr("src",o.idcard_pic1);
                    $("#idcard2").attr("src",o.idcard_pic2);
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
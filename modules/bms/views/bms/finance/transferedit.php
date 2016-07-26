<form id="form1" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>提现申请</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">账户名称</td>
                    <td><input name="account_name" class="mini-textbox" readonly="readonly"/></td>
                    <td style="width:100px;">账户类型</td>
                    <td><input name="account_type" readonly="readonly" class="mini-combobox" data="[{id:'personal',text:'个人账户'},{id:'company',text:'对公账户'}]" /></td>
                </tr>
                <tr>
                    <td>银行卡号</td>
                    <td><input name="bank_card" class="mini-textbox" readonly="readonly"/></td>
                    <td>银行</td>
                    <td><input name="bank" class="mini-textbox" readonly="readonly"/></td>
                </tr>
                <tr>
                    <td>申请结算金额</td>
                    <td><input name="apply_transfer" class="mini-textbox" readonly="readonly"/></td>
                    <td>申请时间</td>
                    <td><input name="apply_time" class="mini-datepicker" readonly="readonly"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>付款信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">扣税</td>
                    <td><input name="handle_fee" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">实际付款金额</td>
                    <td><input name="actual_transfer" required="true" class="mini-textbox" /></td>
                </tr>
                <tr>
                    <td>付款时间</td>
                    <td><input name="transfer_time" class="mini-datepicker" required="true"/></td>
                    <td>结算状态</td>
                    <td><input name="status" class="mini-combobox" data="[{id:'apply',text:'未处理'},{id:'processing',text:'正在处理'},{id:'success',text:'已汇款'}]" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">备注</td>
                    <td colspan="3"><input name="mark" class="mini-textarea" style="width: 86%"/></td>
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

    var form = new mini.Form("form1");

    function SaveData() {
        var o = form.getData();
        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/finance/transfer", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/finance/transfer", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o, true);
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
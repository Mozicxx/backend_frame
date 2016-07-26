<form id="form1" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>回款信息</legend>
            <table width="100%">
                <tr>
                    <td>客户名称</td>
                    <td colspan="3"><input name="advertiser_company" class="mini-textbox" style="width: 86%" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">回款金额</td>
                    <td><input name="payback_money" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">付款方式</td>
                    <td><input name="payback_type" required="true" class="mini-combobox" data="[{id:'电汇',text:'电汇'},{id:'支票',text:'支票'},{id:'现金',text:'现金'}]" /></td>
                </tr>
                <tr>
                    <td>回款日期</td>
                    <td><input name="payback_date" class="mini-datepicker" required="true"/></td>
                    <td>应收月份</td>
                    <td><input name="pay_month" class="mini-monthpicker" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">回款公司</td>
                    <td colspan="3"><input name="company" class="mini-textbox" required="true"/></td>
                </tr>
                <tr>
                    <td>发票号</td>
                    <td><input name="bill_no" class="mini-textbox" required="true"/></td>
                    <td>开票状态</td>
                    <td><input name="bill_status" class="mini-combobox" data="[{id:'Y',text:'已开票'},{id:'N',text:'未开票'}]"  required="true"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>备注</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">备注</td>
                    <td><input name="note" class="mini-textarea" style="width: 86%"/></td>
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
            url: "<?php echo URL::site("bms/finance/payback", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/finance/payback", TRUE) . "?method=get&id="; ?>" + data.id,
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
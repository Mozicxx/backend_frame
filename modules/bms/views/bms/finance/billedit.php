<form id="form1" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>发票信息</legend>
            <table width="100%">
                <tr>
                    <td>发票公司</td>
                    <td colspan="3"><input name="advertiser_company" class="mini-textbox" style="width: 86%" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">发票金额</td>
                    <td><input name="money" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">发票类型</td>
                    <td><input name="bill_type" required="true" class="mini-combobox" data="[{id:'国税',text:'国税'},{id:'地税',text:'地税'},{id:'增值税',text:'增值税'}]" /></td>
                </tr>
                <tr>
                    <td>发票内容</td>
                    <td colspan="3"><input name="description" class="mini-textbox" style="width: 86%" required="true"/></td>
                </tr>
                <tr>
                    <td>开票日期</td>
                    <td><input name="write_date" class="mini-datepicker" required="true"/></td>
                    <td>应收月份</td>
                    <td><input name="pay_month" class="mini-monthpicker" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">开票公司</td>
                    <td colspan="3"><input name="company" class="mini-textbox" required="true"/></td>
                </tr>
                <tr>
                    <td>发票号</td>
                    <td><input name="bill_no" class="mini-textbox" required="true"/></td>
                    <td>是否回款</td>
                    <td><input name="payback_status" class="mini-combobox" data="[{id:'Y',text:'已回款'},{id:'N',text:'未回款'}]"  required="true"/></td>
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
            url: "<?php echo URL::site("bms/finance/bill", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/finance/bill", TRUE) . "?method=get&id="; ?>" + data.id,
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
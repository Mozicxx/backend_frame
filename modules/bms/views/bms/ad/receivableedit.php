<form id="developerfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>广告信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">开始日期</td>
                    <td><input name="begin_date" class="mini-datepicker" required="true"/></td>
                    <td style="width:100px;">截至日期</td>
                    <td><input name="end_date" class="mini-datepicker"  required="true" /></td>
                </tr>
                <tr>
                    <td>广告</td>
                    <td colspan="2"><input name="ad_id" textName="ad_name" class="mini-buttonedit" onbuttonclick="onButtonEdit" style="width: 100%"  required="true"/></td>
                    <td><a class="mini-button" iconCls="icon-find" onclick="search()">查询收入</a>
                    </td>
                </tr>
                <tr>
                    <td style="width:70px;">广告主公司</td>
                    <td><input name="advertiser_company" class="mini-textbox" readonly="readonly"/>
                        <input type="hidden" name="advertiser_id" class="mini-hidden"/>
                    </td>
                    <td style="width:100px;">广告主类型</td>
                    <td><input name="advertiser_type" readonly="readonly" class="mini-combobox" url="<?php echo URL::site("bms/data/copertype", TRUE); ?>" /></td>
                </tr>
                <tr>
                    <td>广告单价</td>
                    <td><input name="price" class="mini-textbox" readonly="readonly"/></td>
                    <td>计费方式</td>
                    <td><input name="fee_type" class="mini-combobox" url="<?php echo URL::site("bms/data/feetype", TRUE); ?>" readonly="readonly"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>收入信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">效果数量</td>
                    <td><input name="amount" class="mini-textbox" readonly="readonly"/></td>
                    <td style="width:100px;">预计收入</td>
                    <td><input name="projected_income" class="mini-textbox" readonly="readonly" /></td>
                </tr>
                <tr>
                    <td>确认数量</td>
                    <td><input name="confirmed_amount" class="mini-textbox"/></td>
                    <td>媒体支出</td>
                    <td><input name="media_spending" class="mini-textbox" readonly="readonly"/></td>
                </tr>
                <tr>
                    <td>结算日期</td>
                    <td><input name="closing_date" class="mini-datepicker" required="true"/></td>
                    <td>确认收入</td>
                    <td><input name="confirmed_income" class="mini-textbox"/></td>
                </tr>
                <tr>
                    <td>确认时间</td>
                    <td><input name="confirm_date" class="mini-datepicker" /></td>
                    <td>回款状态</td>
                    <td><input name="payback_status" class="mini-combobox" data="[{id:'Y',text:'已回款'},{id:'N',text:'未回款'}]" required="true"/></td>
                </tr>
                <tr>
                    <td style="width:70px;">广告负责人</td>
                    <td><input name="ad_master" class="mini-combobox" url="<?php echo URL::site("bms/data/admin", TRUE); ?>" /></td>
                    <td style="width:100px;">广告归属人</td>
                    <td><input name="ad_owner" class="mini-combobox" url="<?php echo URL::site("bms/data/admin", TRUE); ?>" /></td>
                </tr>
                <tr>
                    <td>确认人</td>
                    <td colspan="3"><input name="confirm_by" class="mini-combobox" required="true" url="<?php echo URL::site("bms/data/admin", TRUE); ?>" /></td>
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

    var form = new mini.Form("developerfrom");

    function SaveData() {
        var o = form.getData();
        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/receivable", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/ad/receivable", TRUE) . "?method=get&id="; ?>" + data.id,
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

    function search() {
        var o = form.getData();
        var json = mini.encode(o);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/receivable", TRUE) . "?method=count"; ?>",
            cache: false,
            type: 'post',
            data: {data: json},
            success: function(text) {
                var o = mini.decode(text);
                form.setData(o, true);
                form.setChanged(false);
            }
        });
    }

    function onButtonEdit(e) {
        var btnEdit = this;
        mini.open({
            url: "<?php echo URL::site("bms/ad/select", TRUE); ?>?view=bills",
            title: "选择列表",
            width: 650,
            height: 380,
            ondestroy: function(action) {
                if (action === "ok") {
                    var iframe = this.getIFrameEl();
                    var data = iframe.contentWindow.GetData();
                    data = mini.clone(data);    //必须
                    if (data) {
                        btnEdit.setValue(data.id);
                        btnEdit.setText(data.name);
                    }
                }

            }
        });

    }

</script>
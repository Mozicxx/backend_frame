<form id="advertiserfrom" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>广告主信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">名称缩写</td>
                    <td><input name="s_name" class="mini-textbox" required="true"/></td>
                    <td style="width:100px;">广告主名称</td>
                    <td><input name="name" class="mini-textbox" required="true"/></td>
                </tr>
                <tr>
                    <td>公司名称</td>
                    <td><input name="company" class="mini-textbox" required="true"/></td>
                    <td>合作类型</td>
                    <td><input name="type" class="mini-combobox" url="<?php echo URL::site("bms/data/copertype", TRUE); ?>" required="true"/></td>
                </tr>                
                <tr>
                    <td>行业</td>
                    <td><input name="business" class="mini-textbox" required="true"/></td>
                    <td>所属分类</td>
                    <td><input name="tag" class="mini-textbox" required="true"/></td>
                </tr>
                <tr>
                    <td>付款周期</td>
                    <td><input name="pay_cycle" class="mini-textbox" required="true"/></td>
                    <td>付款日期</td>
                    <td><input name="pay_day" class="mini-textbox"/></td>
                </tr>
                <tr>
                    <td>关系等级</td>
                    <td><input name="friendly_level" class="mini-spinner" value="9" minValue="1" maxValue="9"/></td>
                    <td>信用等级</td>
                    <td><input name="credit_level" class="mini-spinner" value="9" minValue="1" maxValue="9"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>广告主联系方式</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">电话</td>
                    <td><input name="phone" class="mini-textbox" /></td>
                    <td style="width:100px;">传真</td>
                    <td><input name="fax" class="mini-textbox" /></td>
                </tr>
                <tr>
                    <td>邮编</td>
                    <td><input name="postcode" class="mini-textbox" /></td>
                    <td>市场接入</td>
                    <td><input name="add_by" class="mini-combobox" required="true" url="<?php echo URL::site("bms/data/admin", TRUE); ?>" /></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td colspan="3"><input name="note" class="mini-textarea" style="width: 98%" /></td>
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
            url: "<?php echo URL::site("bms/ad/advertiser", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/ad/advertiser", TRUE) . "?method=get&id="; ?>" + data.id,
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
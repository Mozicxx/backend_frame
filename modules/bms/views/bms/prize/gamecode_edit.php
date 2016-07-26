<form id="giftform" method="post">
    <input name="id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>游戏礼包</legend>
            <table width="100%">
                <tr>
                    <td>礼包名称</td>
                    <td colspan="3"><input name="pack_name" class="mini-textbox" style="width: 80%;" required="true"/></td>
                </tr>
                <tr>
                    <td>广告</td>
                    <td colspan="3"><input name="ad_id" textName="ad_name" class="mini-buttonedit" onbuttonclick="onButtonEdit" required="true"/></td>
                </tr>
                <tr>
                    <td>开始时间</td>
                    <td colspan="3"><input name="begin_date" class="mini-datepicker" required="true"/></td>
                </tr>
                <tr>
                    <td>截至时间</td>
                    <td colspan="3"><input name="end_date" class="mini-datepicker" required="true"/></td>
                </tr>
                <tr>
                    <td>礼包详情</td>
                    <td colspan="3"><input name="intro" class="mini-textarea" style="width: 80%"/></td>
                </tr>
                <tr>
                    <td>兑奖说明</td>
                    <td colspan="3"><input name="note" class="mini-textarea" style="width: 80%"/></td>
                </tr>
                <tr>
                    <td>状态</td>
                    <td colspan="3"><input name="status" class="mini-combobox"  data="[{id:'pendding',text:'未上架'},{id:'actived',text:'上架'}]"/></td>
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

    var form = new mini.Form("giftform");

    function SaveData() {
        var o = form.getData();
        form.validate();
        if (form.isValid() === false)
            return;
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/prize/gamecode", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/prize/gamecode", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
                    form.setChanged(false);
                    $("#icon").attr("src", o.icon);
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
    function onButtonEdit(e) {
        var btnEdit = this;
        mini.open({
            url: "<?php echo URL::site("bms/ad/select", TRUE); ?>?ad_type=offer",
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
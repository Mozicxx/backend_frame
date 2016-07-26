<form id="form1" method="post">
    <input type="hidden" name="id" class="mini-hidden" />
    <input type="hidden" name="begin_date" class="mini-hidden"/>
    <input type="hidden" name="end_date"  class="mini-hidden"/>
    <div style="padding:5px">
        <script type="text/javascript">
            var rule = [{id: "-", text: "不限"}, {id: "in", text: "指定"}, {id: "!", text: "排除"}];
            var _steps = [{id: "0", text: '尚未开始'}, {id: "1", text: '1统计用户中'}, {id: "2", text: '2统计用户完成'}, {id: "3", text: '3推送广告中'}, {id: "4", text: '4推送完成'}];
        </script>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >广告</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td>选择广告：</td>
                        <td><input name="ad_id" textName="name" class="mini-buttonedit" onbuttonclick="onButtonEdit" style="width: 100%"  required="true"/></td>
                        <td>广告价格：</td>
                        <td><input name="price" class="mini-textbox" readonly="readonly"/></td>
                        <td>计划投放：</td>
                        <td><input name="quota" class="mini-textbox" readonly="readonly"/></td>
                        <td>尚未完成：</td>
                        <td><input name="missing" class="mini-textbox" readonly="readonly"/></td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >选择用户</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50px"><input name="city_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td style="width: 70px">城市：</td>
                        <td>
                            <input name="city" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/city", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="carrier_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>运营商：</td>
                        <td>
                            <input name="carrier" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/carrier", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="net_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>接入网络：</td>
                        <td>
                            <input name="net" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/net", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="os_ver_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>系统版本：</td>
                        <td>
                            <input name="os_ver" class="mini-combobox" textField="text" valueField="id" url="<?php echo URL::site("bms/data/android_ver", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="term_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>机型：</td>
                        <td colspan="3">
                            <input name="term" class="mini-textarea" style="width:98%;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="scwh_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>分辨率：</td>
                        <td>
                            <input name="scwh" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/scwh", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="last_visit_" value="-" style="width: 50px;" class="mini-combobox" data='[{id: "-", text: "不限"}, {id: "in", text: "指定"}]'/></td>
                        <td>活跃时间：</td>
                        <td>
                            <input name="last_visit" class="mini-combobox" data="[{id:'1',text:'1天内活跃'},{id:'2',text:'2天内活跃'},{id:'3',text:'3天内活跃'},{id:'7',text:'7天内活跃'},{id:'30',text:'30天内活跃'}]" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="first_visit_" value="-" style="width: 50px;" class="mini-combobox" data='[{id: "-", text: "不限"}, {id: "in", text: "指定"}]'/></td>
                        <td>首次登录：</td>
                        <td>
                            <input name="first_visit" style="width:160px;" showOkButton="true" showTodayButton="false" class="mini-datepicker" format="yyyy-MM-dd H:mm:ss" showTime="true" />
                            之后
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="app_id_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>应用：</td>
                        <td>
                            <input name="app_id" class="mini-textarea" style="width:98%;" />
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >推送设置</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 70px">推送时间：</td>
                        <td style="width: 200px;">
                            <input name="push_time" style="width:160px;" showOkButton="true" showTodayButton="false" class="mini-datepicker" format="yyyy-MM-dd H:mm:ss" showTime="true" />
                        </td>
                        <td style="width: 70px">推送方式：</td>
                        <td style="width: 200px;">
                            <input name="control" class="mini-combobox" data="[{id:'mt',text:'手动'},{id:'at',text:'自动'}]"/>
                        </td>
                        <td style="width: 70px">当前步骤：</td>
                        <td>
                            <input name="step" class="mini-combobox" data="_steps"/>
                            <span class="grey">(谨慎修改)</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 70px">备注：</td>
                        <td>
                            <input style="width: 98%;" name="note" class="mini-textarea" />
                        </td>
                        <td style="width: 70px">测试openuuid：</td>
                        <td colspan="3">
                            <input style="width: 96%;" name="testid" class="mini-textarea" />
                        </td>
                    </tr>
                </table>
            </div>
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
    var filed = ["city", "carrier", "os_ver", "term", "scwh", "net","last_visit", "app_id"];
    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;
        for (var i = 0, l = filed.length; i < l; i++) {
            if (o[filed[i] + "_"] === "!") {
                o[filed[i]] = "!" + o[filed[i]];
            }
            if (o[filed[i] + "_"] === "-") {
                o[filed[i]] = "";
            }
        }
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=save"; ?>",
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

    function search() {
        var o = form.getData();
        var json = mini.encode(o);
        alert(json);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=queryad"; ?>",
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
    ////////////////////
    //标准方法接口定义
    function SetData(data) {
        if (data.action === "edit") {
            //跨页面传递的数据对象，克隆后才可以安全使用
            data = mini.clone(data);

            $.ajax({
                url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    for (var i = 0, l = filed.length; i < l; i++) {
                        if (o[filed[i]] === "" || o[filed[i]] === null) {
                            o[filed[i] + "_"] = "-";
                        } else if (o[filed[i]].indexOf("!") === 0) {
                            o[filed[i] + "_"] = "!";
                            o[filed[i]] = o[filed[i]].substr(1);
                        } else {
                            o[filed[i] + "_"] = "in";
                        }
                    }
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
    function onButtonEdit(e) {
        var btnEdit = this;
        mini.open({
            url: "<?php echo URL::site("bms/ad/select", TRUE); ?>?ad_type=push",
            title: "选择列表",
            width: 650,
            height: 380,
            ondestroy: function(action) {
                if (action == "ok") {
                    var iframe = this.getIFrameEl();
                    var data = iframe.contentWindow.GetData();
                    data = mini.clone(data);    //必须
                    if (data) {
                        btnEdit.setValue(data.id);
                        btnEdit.setText(data.name);
                        var o = form.getData();
                        o["price"] = data.price;
                        o["quota"] = data.quota;
                        o["missing"] = data.missing;
                        form.setData(o);
                        form.setChanged(false);
                    }
                }

            }
        });

    }
</script>
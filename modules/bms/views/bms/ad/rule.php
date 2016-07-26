<form id="form1" method="post">
    <input type="hidden" name="id" class="mini-hidden" />
    <input type="hidden" name="begin_date" class="mini-hidden"/>
    <input type="hidden" name="end_date"  class="mini-hidden"/>
    <div style="padding:5px">
        <script type="text/javascript">
            var rule = [{id: "-", text: "不限"}, {id: "in", text: "指定"}, {id: "!", text: "排除"}];
        </script>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >选择用户</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50px"><input name="city_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td style="width: 70px">城市：</td>
                        <td>
                            <input name="city" class="mini-checkboxlist" textField="text"  repeatItems="15" repeatLayout="table"valueField="id" url="<?php echo URL::site("bms/data/city", TRUE); ?>"/>
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
                            <input name="term" class="mini-textarea" style="width:98%;height: 3em;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="scwh_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>分辨率：</td>
                        <td>
                            <input name="scwh" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/scwh", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr style="display: none">
                        <td style="width: 50px"><input name="term_price_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>终端价格：</td>
                        <td>
                            <input name="term_price" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/term_price", TRUE); ?>"/>
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >选择应用</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50px"><input name="app_tag_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td style="width: 70px">应用类型：</td>
                        <td>
                            <input name="app_tag" class="mini-checkboxlist" textField="text" repeatItems="10" repeatLayout="table" valueField="id" url="<?php echo URL::site("bms/data/tagsgroup", TRUE); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="keywords_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td>关键字：</td>
                        <td>
                            <input name="keywords" style="width: 98%" class="mini-textbox"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 50px"><input name="app_grade_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td style="width: 70px">应用级别：</td>
                        <td>
                            <input name="app_grade" style="width: 40px;" class="mini-spinner" minValue="1" maxValue="9"/> 以上
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >其他条件</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50px"><input name="time_" value="-" style="width: 50px;" class="mini-combobox" data="rule"/></td>
                        <td style="width: 70px">时间段：</td>
                        <td>
                            <input name="time" class="mini-checkboxlist" textField="text" valueField="id" url="<?php echo URL::site("bms/data/hour", TRUE); ?>"/>
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
    var filed = ["app_grade", "city", "carrier", "os_ver", "term", "scwh", "term_price", "sc_size", "net", "app_tag", "keywords", "time"];
    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;
        for (var i = 0, l = filed.length; i < l; i++) {
            if (o[filed[i] + "_"] === "!") {
                o[filed[i]] = "!" + o[filed[i]];
            }
            if (o[filed[i]+"_"] === "-") {
                o[filed[i]] = "";
            }
        }
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/add", TRUE) . "?method=save"; ?>",
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
                url: "<?php echo URL::site("bms/ad/add", TRUE) . "?method=get&id="; ?>" + data.id,
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
</script>
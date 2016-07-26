<form id="developerfrom" method="post">
    <input name="role_id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>角色信息</legend>
            <table width="100%">
                <tr>
                    <td style="width:70px;">角色名称</td>
                    <td><input name="role_name" class="mini-textbox" style="width: 96%" required="true"/></td>
                </tr>
                <tr>
                    <td>角色描述</td>
                    <td><input name="role_desc" class="mini-textarea" style="width: 96%"/></td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>角色权限</legend>
            <div id="datagrid1" class="mini-datagrid" allowResize="true" style="width: 95%;height: auto;"
                 idField="id" showPager="false" multiSelect="true">
                <div property="columns">
                    <div field="text" width="60" headerAlign="center" allowSort="true">模块</div>
                    <div field="functions" renderer="onFuncRender" width="600" headerAlign="center" allowSort="role_desc">权限</div>
                </div>
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

    var form = new mini.Form("developerfrom");

    var grid = mini.get("datagrid1");
    grid.on("beforeload", function(e) {
        e.cancel = true;
    });

    function SaveData() {
        var o = form.getData();

        o.promission = grid.getData();

        form.validate();
        if (form.isValid() === false)
            return;

        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("backend/config/role", TRUE) . "?method=save"; ?>",
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
        //跨页面传递的数据对象，克隆后才可以安全使用
        data = mini.clone(data);
        $.ajax({
            url: "<?php echo URL::site("backend/config/role", TRUE) . "?method=get&id="; ?>" + data.id,
            cache: false,
            success: function(text) {
                var o = mini.decode(text);
                form.setData(o);
                form.setChanged(false);
                grid.setData(o.promission);
            }
        });
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

    function onFuncRender(e) {
        var grid = e.sender,
                record = e.record,
                id = record[grid.getIdField()];
        function createCheckboxs(funs) {
            if (!funs)
                return "";
            var html = "";
            if (true) {
                var value = record.checkAll !== false ? "全选" : "取消";

                var clickFn = 'checkAllFunc(\'' + id + '\', this)';
                html += '<input onclick="' + clickFn + '" type="button" value="' + value + '" style="border:solid 1px #aaa;"/>';
            }
            for (var i = 0, l = funs.length; i < l; i++) {
                var fn = funs[i];
                var clickFn = 'checkFunc(\'' + id + '\',\'' + fn.uri + '\', this.checked)';
                var checked = fn.checked ? 'checked' : '';
                html += '<label class="function-item"><input onclick="' + clickFn + '" ' + checked + ' type="checkbox" name="'
                        + fn.uri + '" hideFocus/>' + fn.name + '</label>';
            }
            return html;
        }
        return createCheckboxs(e.value);
    }

    function checkFunc(id, uri, checked) {
        var record = grid.getRecord(id);
        if (!record)
            return;
        var funs = record.functions;
        if (!funs)
            return;
        function getAction(uri) {
            for (var i = 0, l = funs.length; i < l; i++) {
                var o = funs[i];
                if (o.uri == uri)
                    return o;
            }
        }
        var obj = getAction(uri);
        if (!obj)
            return;
        obj.checked = checked;
    }
    function checkAllFunc(id, btn) {
        var record = grid.getRecord(id);
        if (!record)
            return;
        var funs = record.functions;
        if (!funs)
            return;


        var checked = record.checkAll !== false;

        for (var i = 0, l = funs.length; i < l; i++) {
            var o = funs[i];
            o.checked = checked;
        }

        record.checkAll = !checked;
        grid.updateRow(record);

    }


</script>
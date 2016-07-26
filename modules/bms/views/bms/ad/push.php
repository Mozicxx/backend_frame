<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="count()">统计用户</a>
                <a class="mini-button" iconCls="icon-upload" plain="true" onclick="push()">推送</a>
                <a class="mini-button" iconCls="icon-downgrade" plain="true" onclick="test()">测试</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入广告名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/ad/push", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="id" width="50" headerAlign="center" >ID</div>
            <div field="name" width="120" headerAlign="center" >广告名称</div>
            <div field="price" width="120" headerAlign="center" >单价</div>
            <div field="hit_users" width="120" headerAlign="center" allowSort="true">覆盖用户</div>
            <div field="finished" width="120" headerAlign="center" allowSort="true">完成用户</div>
            <div field="add_time" width="120" headerAlign="center" allowSort="true">添加日期</div>
            <div field="add_by" width="120" headerAlign="center" allowSort="true">发布人</div>
            <div field="push_time" width="120" headerAlign="center" allowSort="true">推送时间</div>
            <div field="step" width="120" headerAlign="center" renderer="onStepRender" allowSort="true">状态</div>
            <div field="note" width="120" headerAlign="center" allowSort="true">备注</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("add_time", "desc");

    function add() {

        mini.open({
            url: "<?php echo URL::site("bms/ad/push/edit", TRUE); ?>",
            title: "添加推送", width: 960, height: 660,
            onload: function() {
                var iframe = this.getIFrameEl();
                var data = {action: "new"};
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function(action) {
                grid.reload();
            }
        });
    }
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/ad/push/edit", TRUE); ?>",
                title: "编辑推送", width: 960, height: 660,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.id};
                    iframe.contentWindow.SetData(data);
                },
                ondestroy: function(action) {
                    grid.reload();
                }
            });
        } else {
            alert("请选中一条记录");
        }

    }
    function count() {
        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            mini.confirm("统计达到用户可能消耗较长时间，请耐心等待！", "确定？",
                    function(action) {
                        if (action == "ok") {
                            var ids = [];
                            for (var i = 0, l = rows.length; i < l; i++) {
                                var r = rows[i];
                                ids.push(r.id);
                            }
                            var id = ids.join(',');
                            grid.loading("操作中，请稍后......");
                            $.ajax({
                                url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=count&id="; ?>" + id,
                                success: function(text) {
                                    grid.reload();
                                    mini.alert("统计完成，共命中" + text + "个用户。");
                                },
                                error: function() {
                                }
                            });
                        }
                    });
        } else {
            alert("请选中一条记录");
        }
    }
    function push() {
        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            mini.confirm("点击确定，将推送消息到选定用户！", "确定？",
                    function(action) {
                        if (action == "ok") {
                            var ids = [];
                            for (var i = 0, l = rows.length; i < l; i++) {
                                var r = rows[i];
                                ids.push(r.id);
                            }
                            var id = ids.join(',');
                            grid.loading("操作中，请稍后......");
                            $.ajax({
                                url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=push&id="; ?>" + id,
                                success: function(text) {
                                    grid.reload();
                                },
                                error: function() {
                                }
                            });
                        }
                    }
            );
        } else {
            alert("请选中一条记录");
        }
    }
    function test() {
        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            var ids = [];
            for (var i = 0, l = rows.length; i < l; i++) {
                var r = rows[i];
                ids.push(r.id);
            }
            var id = ids.join(',');
            mini.mask({
                el: document.body,
                cls: 'mini-mask-loading',
                html: '测试发送中...'
            });
            $.ajax({
                url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=test&id="; ?>" + id,
                success: function(text) {
                    mini.unmask(document.body);
                    if (text === "null") {
                        mini.alert("没有设置测试用户openuuid！");
                    } else {
                        mini.alert("测试消息发送完成!已发送至：" + text + "。");
                    }
                },
                error: function() {
                }
            });
        } else {
            alert("请选中一条记录");
        }
    }
    function remove() {

        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/ad/push", TRUE) . "?method=delete&id="; ?>" + id,
                    success: function(text) {
                        grid.reload();
                    },
                    error: function() {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }

    var _Status = [{id: "0", text: '尚未开始'}, {id: "1", text: '统计用户中'}, {id: "2", text: '统计用户完成'}, {id: "3", text: '推送广告中'}, {id: "4", text: '推送完成'}];
    function onStepRender(e) {
        for (var i = 0, l = _Status.length; i < l; i++) {
            var g = _Status[i];
            if (g.id === e.value) {
                return g.text;
            }
        }
        return e.value;
    }
</script>
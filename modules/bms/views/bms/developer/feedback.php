<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">处理</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入主题" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/developer/feedback", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>            
            <div field="subject" width="120" headerAlign="center" allowSort="true">主题</div>            
            <div field="desc" width="320" headerAlign="center" allowSort="true">说明</div>
            <div field="add_time" width="80" headerAlign="center" allowSort="true">反馈时间</div>
            <div field="add_by" width="60" headerAlign="center" allowSort="true">用户</div>
            <div field="mobile" width="80" headerAlign="center" allowSort="true">手机号</div>
            <div field="email" width="80" headerAlign="center" allowSort="true">电邮</div>
            <div field="qq" width="60" headerAlign="center" allowSort="true">QQ</div>            
            <div field="status" width="60" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>
            <div field="update_by" width="80" headerAlign="center" allowSort="true">最后更新</div>
            <div field="update_time" width="80" headerAlign="center" allowSort="true">更新时间</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("add_time", "desc");
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/developer/feedback/edit", TRUE); ?>",
                title: "处理反馈", width: 720, height: 600,
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
                    url: "<?php echo URL::site("bms/developer/feedback", TRUE) . "?method=delete&id="; ?>" + id,
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
    /////////////////////////////////////////////////
    var Status = [{id: "processed", text: '已处理'}, {id: "actived", text: '未处理'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
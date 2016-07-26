<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入应用名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/developer/app", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="platform" width="80" headerAlign="center" allowSort="true">平台</div>
            <div field="icon" width="32" headerAlign="center" renderer="onImgRender" allowSort="true">图标</div>
            <div field="app_id" width="80" headerAlign="center" allowSort="true">APPID</div>
            <div field="app_name" width="120" headerAlign="center" allowSort="true">名称</div>
            <div field="package_name" width="120" headerAlign="center" allowSort="true">包名</div>            
            <div field="slogan" width="120" headerAlign="center" allowSort="true">宣传语</div>
            <div field="tags" width="120" headerAlign="center" allowSort="true">分类</div>            
            <div field="package_size" width="120" headerAlign="center" allowSort="true">包大小</div>
            <div field="add_time" width="120" headerAlign="center" allowSort="true">录入时间</div>
            <div field="total_user" width="120" headerAlign="center" allowSort="true">累计用户</div>
            <div field="grade" width="120" headerAlign="center" allowSort="true">应用评级</div>
            <div field="developer_id" width="120" headerAlign="center" allowSort="true">开发者</div>
            <div field="status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>
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
            url: "<?php echo URL::site("bms/developer/app/edit", TRUE); ?>",
            title: "录入广告", width: 640, height: 540,
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
                url: "<?php echo URL::site("bms/developer/app/edit", TRUE); ?>",
                title: "编辑广告", width: 640, height: 540,
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
                    url: "<?php echo URL::site("bms/developer/app", TRUE) . "?method=delete&id="; ?>" + id,
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
    var Status = [{id: "online", text: '已上线'}, {id: "offline", text: '未上线'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    function onImgRender(e){
        return "<img height=\"24\" onerror=\"this.src='http://oss.aliyuncs.com/zt-sandbox-hz/ic_launcher.png'\" src=\""+e.value+"\"/>";
    }
</script>
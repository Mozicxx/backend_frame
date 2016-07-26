<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("backend/config/channelmanager", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="channel_id" width="80" headerAlign="center" allowSort="true">渠道</div>
            <div field="name" width="120" headerAlign="center" allowSort="true">姓名</div>
            <div field="username" width="120" headerAlign="center" allowSort="true">登录名</div>
            <div field="mobile" width="120" headerAlign="center" allowSort="true">手机</div>
            <div field="status" width="60" headerAlign="center" allowSort="true">状态</div>
            <div field="owner" width="60" headerAlign="center" allowSort="true">管理员</div>
            <div field="add_time" width="120" dateFormat="yyyy-MM-dd HH:mm:ss" headerAlign="center" allowSort="true">添加时间</div>
            <div field="last_login" width="100" dateFormat="yyyy-MM-dd HH:mm:ss" headerAlign="center" allowSort="true">最后登陆</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("createtime", "desc");
    function add() {

        mini.open({
            url: "<?php echo URL::site("backend/config/channelmanager/edit", TRUE); ?>",
            title: "录入员工", width: 640, height: 400,
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
                url: "<?php echo URL::site("backend/config/channelmanager/edit", TRUE); ?>",
                title: "编辑员工", width: 640, height: 400,
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
                    url: "<?php echo URL::site("backend/config/channelmanager", TRUE) . "?method=delete&id="; ?>" + id,
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
    var Adtype = [{id: "proxy", text: '代理客户'}, {id: "direct", text: '直接客户'}];
    function onCopetypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Status = [{id: "pendding", text: '未生效'}, {id: "actived", text: '正在进行'}, {id: "finished", text: '已完结'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
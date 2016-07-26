<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入员工姓名" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/config/manager", TRUE) . "?method=list"; ?>"  idField="user_id" multiSelect="true"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="realname" width="120" headerAlign="center" allowSort="true">姓名</div>
            <div field="username" width="120" headerAlign="center" allowSort="true">登录名</div>
            <div field="roles" width="120" headerAlign="center" allowSort="true">角色</div>
            <div field="gender" width="120" headerAlign="center" allowSort="true">性别</div>
            <div field="birthday" width="120" headerAlign="center" allowSort="true">生日</div>
            <div field="email" width="120" headerAlign="center" allowSort="true">电子邮件</div>
            <div field="qq" width="120" headerAlign="center" allowSort="true">QQ</div>
            <div field="phone" width="120" headerAlign="center" allowSort="true">手机号</div>
            <div field="status" width="120" headerAlign="center" allowSort="true">状态</div>
            <div field="created" width="120" headerAlign="center" allowSort="true">添加时间</div>
            <div field="login_time" width="100" headerAlign="center" allowSort="true">最后登陆</div>
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
            url: "<?php echo URL::site("bms/config/manager/edit", TRUE); ?>",
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
                url: "<?php echo URL::site("bms/config/manager/edit", TRUE); ?>",
                title: "编辑员工", width: 640, height: 400,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.user_id};
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
                    ids.push(r.user_id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/config/manager", TRUE) . "?method=delete&id="; ?>" + id,
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
<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">审核</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入账户名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/developer/account", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="account_name" width="100" headerAlign="center" allowSort="true">帐户名</div>            
            <div field="account_type" width="80" renderer="onAccounttypeRender" headerAlign="center" allowSort="true">帐户类型</div>
            <div field="user_id" width="100" headerAlign="center" allowSort="true">开发者</div>
            <div field="bank_card" width="120" headerAlign="center" allowSort="true">银行卡号</div>
            <div field="bank" width="80" headerAlign="center" allowSort="true">银行</div>
            <div field="bank_name" width="120" headerAlign="center" allowSort="true">分行</div>
            <div field="idcard" width="120" headerAlign="center" allowSort="true">身份证号</div>
            <div field="status" width="60" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>            
            <div field="add_time" width="80" headerAlign="center" allowSort="true">添加时间</div>
            <div field="pass_time" width="80" headerAlign="center" allowSort="true">审核时间</div>
            <div field="pass_by" width="80" headerAlign="center" allowSort="true">审核人</div>
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
                url: "<?php echo URL::site("bms/developer/account/edit", TRUE); ?>",
                title: "编辑开发者", width: 600, height: 520,
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
    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }
    /////////////////////////////////////////////////    
    var Adtype = [{id: "company", text: '公司账户'}, {id: "personal", text: '个人账户'}];
    function onAccounttypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Status = [{id: "pendding", text: '未审核'},{id: "reject", text: '未通过'}, {id: "pass", text: '已审核'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
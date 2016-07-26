<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">处理</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="user_id" class="mini-textbox" emptyText="开发者" style="width:150px;" onenter="onKeyEnter"/>
                <input id="key" class="mini-textbox" emptyText="银行帐号" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/finance/transfer", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"  pageSize="20">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="apply_time" width="120" headerAlign="center" allowSort="true">申请时间</div>            
            <div field="user_id" width="120" headerAlign="center" allowSort="true">开发者</div>
            <div field="account_type" width="120" headerAlign="center" renderer="onTypeRender" allowSort="true">账户类型</div>
            <div field="bank_card" width="120" headerAlign="center" allowSort="true">银行卡号</div>
            <div field="bank" width="120" headerAlign="center" allowSort="true">银行</div>
            <div field="apply_transfer" width="120" headerAlign="center" allowSort="true">申请提现金额</div>            
            <div field="handle_fee" width="120" headerAlign="center" allowSort="true">扣税</div>
            <div field="actual_transfer" width="120" headerAlign="center" allowSort="true">实际转账金额</div>
            <div field="transfer_time" width="120" headerAlign="center" allowSort="true">汇款时间</div>
            <div field="status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>
            <div field="update_time" width="120" headerAlign="center" allowSort="true">更新时间</div>
            <div field="update_by" width="120" headerAlign="center" allowSort="true">经手人</div>
            <div field="mark" width="120" headerAlign="center" allowSort="true">备注</div>            
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("apply_time", "desc");
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/finance/transfer/edit", TRUE); ?>",
                title: "处理提现申请", width: 600, height: 400,
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
        var user_id = mini.get("user_id").getValue();
        grid.load({key: key,user_id:user_id});
    }
    function onKeyEnter(e) {
        search();
    }
    /////////////////////////////////////////////////
    var Status = [{id:'apply',text:'未处理'},{id:'processing',text:'正在处理'},{id:'success',text:'已汇款'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var typeRender = [{id:'personal',text:'个人账户'},{id:'company',text:'对公账户'}];
    function onTypeRender(e) {
        for (var i = 0, l = typeRender.length; i < l; i++) {
            var g = typeRender[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
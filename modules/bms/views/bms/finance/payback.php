<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
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
         url="<?php echo URL::site("bms/finance/payback", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="add_time" width="120" headerAlign="center" allowSort="true">登记日期</div>            
            <div field="advertiser_company" width="120" headerAlign="center" allowSort="true">客户名称</div>
            <div field="payback_money" width="120" headerAlign="center" allowSort="true">回款金额</div>
            <div field="payback_type" width="120" headerAlign="center" allowSort="true">付款方式</div>
            <div field="company" width="120" headerAlign="center" allowSort="true">回款公司</div>
            <div field="pay_month" width="120" headerAlign="center" allowSort="true">应收月份</div>
            <div field="add_by" width="120" headerAlign="center" allowSort="true">经手人</div>
            <div field="bill_status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">开票状态</div>
            <div field="bill_no" width="120" headerAlign="center" allowSort="true">发票号</div>
            <div field="payback_date" width="120" headerAlign="center" allowSort="true">回款日期</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("month", "desc");
    function add() {

        mini.open({
            url: "<?php echo URL::site("bms/finance/payback/edit", TRUE); ?>",
            title: "录入广告", width: 600, height: 400,
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
                url: "<?php echo URL::site("bms/finance/payback/edit", TRUE); ?>",
                title: "编辑广告", width: 600, height: 400,
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
                    url: "<?php echo URL::site("bms/finance/payback", TRUE) . "?method=delete&id="; ?>" + id,
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
    var Status = [{id: "Y", text: '已开票'}, {id: "N", text: '未开票'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
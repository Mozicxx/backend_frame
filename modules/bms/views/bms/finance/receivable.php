<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
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
         url="<?php echo URL::site("bms/finance/receivable", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="month" width="120" headerAlign="center" allowSort="true">应收月份</div>
            <div field="fee_type" width="120" headerAlign="center" renderer="onFeetypeRender" allowSort="true">类型</div>
            <div field="ad_master" width="120" headerAlign="center" allowSort="true">负责人</div>
            <div field="advertiser_company" width="120" headerAlign="center" allowSort="true">公司名称</div>
            <div field="ad_name" width="120" headerAlign="center" allowSort="true">广告名称</div>
            <div field="price" width="120" headerAlign="center" allowSort="true">单价</div>
            <div field="amount" width="120" headerAlign="center" allowSort="true">客户数据</div>
            <div field="projected_income" width="120" headerAlign="center" allowSort="true">预计收入</div>
            <div field="ad_owner" width="120" headerAlign="center" allowSort="true">归属人</div>
            <div field="confirmed_income" width="120" headerAlign="center" allowSort="true">确认收入</div>
            <div field="advertiser_type" width="120" headerAlign="center" renderer="onCopetypeRender" allowSort="true">类型</div>
            <div field="confirm_date" width="120" headerAlign="center" allowSort="true">确认日期</div>
            <div field="payback_status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">回款状态</div>
            <div field="note" width="120" headerAlign="center" allowSort="true">备注</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("month", "desc");
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/finance/receivable/edit", TRUE); ?>",
                title: "编辑广告", width: 600, height: 480,
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
    var Status = [{id: "Y", text: '已回款'}, {id: "N", text: '未回款'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Feetype = [{id: "CPA", text: 'CPA'}, {id: "CPC", text: 'CPC'}, {id: "CPS", text: 'CPS'}];
    function onFeetypeRender(e) {
        for (var i = 0, l = Feetype.length; i < l; i++) {
            var g = Feetype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Adtype = [{id: "proxy", text: '代理客户'}, {id: "direct", text: '直接客户'}];
    function onCopetypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
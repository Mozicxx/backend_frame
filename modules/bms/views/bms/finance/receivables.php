<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入广告名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/finance/receivables", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="month" width="120" headerAlign="center" allowSort="true">应收月份</div>
            <div field="ad_owner" width="120" headerAlign="center" allowSort="true">归属人</div>
            <div field="advertiser_company" width="120" headerAlign="center" allowSort="true">公司名称</div>
            <div field="media_spending" width="120" headerAlign="center" allowSort="true">媒介支出</div>
            <div field="amount" width="120" headerAlign="center" allowSort="true">客户数据</div>
            <div field="projected_income" width="120" headerAlign="center" allowSort="true">预计收入</div>
            <div field="confirmed_income" width="120" headerAlign="center" allowSort="true">确认收入</div>
            <div field="confirmed_rate" width="120" headerAlign="center" allowSort="true">确认率</div>
            <div field="bill_money" width="120" headerAlign="center" allowSort="true">已开票金额</div>
            <div field="payback_money" width="120" headerAlign="center" allowSort="true">已回款金额</div>
            <div field="back_rate" width="120" headerAlign="center" allowSort="true">回款率</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("month", "desc");

    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }
</script>
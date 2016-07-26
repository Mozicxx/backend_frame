<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="qdate" class="mini-datepicker" emptyText="日期" onenter="onKeyEnter"/>
                <input id="hour" class="mini-combobox" emptyText="小时" url="<?php echo URL::site("bms/data/hours", TRUE); ?>" onenter="onKeyEnter"/>
                <input id="ad_type" class="mini-combobox" emptyText="广告类型" onenter="onKeyEnter" url="<?php echo URL::site("bms/data/adtype", TRUE); ?>" />
                <input id="ad_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/ad", TRUE) . "?method=list"; ?>" emptyText="广告" style="width:150px;" onenter="onKeyEnter"/>
                <input id="app_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/app", TRUE) . "?method=list"; ?>" emptyText="应用" style="width:150px;" onenter="onKeyEnter"/>
                <input id="group" class="mini-combobox" emptyText="分组" data='[{id:"ad_id",text:"广告"},{id:"app_id",text:"应用"},{id:"ad_type",text:"广告类型"},{id:"hour",text:"小时"}]' onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/monitoring/data", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         showSummaryRow="true" pageSize="20"
         ondrawsummarycell="onDrawSummaryCell">
        <div property="columns">
            <div field="date" headerAlign="center" width="80" allowSort="true">日期</div>
            <div field="hour" headerAlign="center" width="50" allowSort="true">小时</div>
            <div field="app_id" headerAlign="center" width="80" allowSort="true">应用ID</div>
            <div field="app_name" headerAlign="center" width="80">应用</div>
            <div field="ad_id" headerAlign="center" width="60" allowSort="true">广告ID</div>
            <div field="ad_name" headerAlign="center" width="60" >广告</div>
            <div field="ad_type" headerAlign="center" width="80" allowSort="true">广告类型</div>
            <div field="ad_mprice" headerAlign="center" width="80" allowSort="true">媒体价格</div>
            <div field="ad_price" headerAlign="center" width="80" allowSort="true">接入价格</div>
            <div field="pv" headerAlign="center" width="100" allowSort="true">展示次数</div>
            <div field="epv" headerAlign="center" width="100" allowSort="true">有效展示</div>
            <div field="click" headerAlign="center" width="100" allowSort="true">点击次数</div>
            <div field="eclick" headerAlign="center" width="100" allowSort="true">有效点击</div>
            <div field="cpc_result" headerAlign="center" width="100" allowSort="true">CPC成果</div>
            <div field="real_cpc_result" headerAlign="center" width="100" allowSort="true">真实CPC</div>
            <div field="cpa_result" headerAlign="center" width="100" allowSort="true">CPA成果</div>
            <div field="real_cpa_result" headerAlign="center" width="100" allowSort="true">真实CPA</div>
            <div field="activate_result" headerAlign="center" width="100" allowSort="true">签到数</div>
            <div field="real_activate_result" headerAlign="center" width="100" allowSort="true">签到数</div>
            <div field="cpc_expense" headerAlign="center" width="100" allowSort="true">CPC支出</div>
            <div field="cpc_income" headerAlign="center" width="100" allowSort="true">CPC收入</div>
            <div field="cpa_expense" headerAlign="center" width="100" allowSort="true">CPA支出</div>
            <div field="cpa_income" headerAlign="center" width="100" allowSort="true">CPA收入</div>
            <div field="total_expense" headerAlign="center" width="100" allowSort="true">总支出</div>
            <div field="total_income" headerAlign="center" width="100" allowSort="true">总收入</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("hour", "desc");

    function search() {
        var date = mini.encode(mini.get("qdate").getValue());
        var hour = mini.get("hour").getValue();
        var ad_type = mini.get("ad_type").getValue();
        var ad_id = mini.get("ad_id").getValue();
        var app_id = mini.get("app_id").getValue();
        var group = mini.get("group").getValue();
        grid.load({date: date, hour: hour, ad_type: ad_type, ad_id: ad_id, app_id: app_id, groupFiled: group});
    }
    function onKeyEnter(e) {
        search();
    }
    function onDrawSummaryCell(e) {
        var result = e.result.sum;
        if (result[e.field + '_sum']) {
            e.cellHtml = "总：" + result[e.field + '_sum'] + "<br/>" +
                    "高：" + result[e.field + '_max'] + "<br/>"
                    + "低：" + result[e.field + '_min'] + "<br/>"
                    + "均：" + result[e.field + '_avg'] + "";
        }
        if (e.field === "date") {
            e.cellHtml = "共：" + e.result.total + "条";
        }
    }
    function reset(){
        window.location.href=window.location.href;
    }
</script>
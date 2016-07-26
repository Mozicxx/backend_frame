<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="begin_date" class="mini-datepicker" emptyText="开始日期" onenter="onKeyEnter"/>
                <input id="end_date" class="mini-datepicker" emptyText="截止日期" onenter="onKeyEnter"/>                
                <input id="ad_type" class="mini-combobox" emptyText="广告类型" onenter="onKeyEnter" url="<?php echo URL::site("bms/data/adtype", TRUE); ?>" />                
                <input id="app_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/app", TRUE) . "?method=list"; ?>" emptyText="应用" style="width:150px;" onenter="onKeyEnter"/>
                <input id="group" class="mini-combobox" emptyText="分组" data='[{id:"app_id",text:"应用"},{id:"ad_type",text:"广告类型"},{id:"date",text:"日期"}]' onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/monitoring/adtype", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         showSummaryRow="true" pageSize="20">
        <div property="columns">
            <div field="date" headerAlign="center" width="80" allowSort="true">日期</div>            
            <div field="app_id" headerAlign="center" width="80" allowSort="true">应用ID</div>
            <div field="app_name" headerAlign="center" width="80">应用</div>
            <div field="ad_type" headerAlign="center" width="80" allowSort="true">广告类型</div>
            <div field="au" headerAlign="center" width="100" allowSort="true">启动用户</div>
            <div field="push" headerAlign="center" width="100" allowSort="true">推送次数</div>
            <div field="pv" headerAlign="center" width="100" allowSort="true">展示次数</div>
            <div field="epv" headerAlign="center" width="100" allowSort="true">有效展示</div>
            <div field="click" headerAlign="center" width="100" allowSort="true">点击次数</div>
            <div field="eclick" headerAlign="center" width="100" allowSort="true">有效点击</div>
            <div field="download" headerAlign="center" width="100" allowSort="true">下载次数</div>
            <div field="edownload" headerAlign="center" width="100" allowSort="true">有效下载</div>
            <div field="cpc_result" headerAlign="center" width="100" allowSort="true">CPC成果</div>
            <div field="cpc_income" headerAlign="center" width="100" allowSort="true">CPC收入</div>
            <div field="cpa_result" headerAlign="center" width="100" allowSort="true">CPA成果</div>
            <div field="cpa_income" headerAlign="center" width="100" allowSort="true">CPA收入</div>            
            <div field="total_income" headerAlign="center" width="100" allowSort="true">总收入</div>
        </div>
    </div>
</div>
<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("date", "desc");
    function onKeyEnter(e) {
        search();
    }
    function search() {
        var begin_date = mini.get("begin_date").getFormValue();
        var end_date = mini.get("end_date").getFormValue();
        var ad_type = mini.get("ad_type").getValue();
        var app_id = mini.get("app_id").getValue();
        var groupFiled = mini.get("group").getValue();
        grid.load({begin_date: begin_date, end_date: end_date, ad_type: ad_type, app_id: app_id,groupFiled:groupFiled});
    }
    function reset() {
        window.location.href = window.location.href;
    }
</script>
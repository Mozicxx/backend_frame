<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="begin_date" class="mini-datepicker" emptyText="开始日期" onenter="onKeyEnter"/>
                <input id="end_date" class="mini-datepicker" emptyText="截至日期" onenter="onKeyEnter"/>
                <input id="developer" class="mini-autocomplete" url="<?php echo URL::site("bms/data/developer", TRUE) . "?method=list"; ?>" emptyText="开发者" style="width:150px;" onenter="onKeyEnter"/>
                <input id="app_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/app", TRUE) . "?method=list"; ?>" emptyText="应用" style="width:150px;" onenter="onKeyEnter"/>
                <input id="group" class="mini-combobox" emptyText="分组" data='[{id:"app_id",text:"应用"},{id:"date",text:"日期"}]' onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/report/app", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         showSummaryRow="true" pageSize="20"
         ondrawsummarycell="onDrawSummaryCell">
        <div property="columns">
            <div field="date" headerAlign="center" width="80" allowSort="true">日期</div>
            <div field="app_id" headerAlign="center" width="80" allowSort="true">应用ID</div>
            <div field="app_name" headerAlign="center" width="80">应用</div>
            <div field="nu" headerAlign="center" width="100" allowSort="true">新增用户</div>
            <div field="tu" headerAlign="center" width="100" allowSort="true">总用户</div>
            <div field="push" headerAlign="center" width="100" allowSort="true">推送广告</div>
            <div field="banner" headerAlign="center" width="100" allowSort="true">互动广告</div>
            <div field="pop" headerAlign="center" width="100" allowSort="true">插屏广告</div>
            <div field="offer" headerAlign="center" width="100" allowSort="true">积分墙</div>
            <div field="ext1" headerAlign="center" width="100" allowSort="true">橱窗广告</div>
            <div field="total" headerAlign="center" width="100" allowSort="true">总收入</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("total", "desc");

    function search() {
        var begin_date = mini.get("begin_date").getFormValue();
        var end_date = mini.get("end_date").getFormValue();
        var developer = mini.get("developer").getValue();
        var app_id = mini.get("app_id").getValue();
        var group = mini.get("group").getValue();
        grid.load({begin_date: begin_date, end_date: end_date, devloper: developer, app_id: app_id, groupFiled: group});
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
    function reset() {
        window.location.href = window.location.href;
    }
</script>
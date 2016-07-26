<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="begin_date" class="mini-datepicker" emptyText="开始日期" onenter="onKeyEnter"/>
                <input id="end_date" class="mini-datepicker" emptyText="截至日期" onenter="onKeyEnter"/>                
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/report/user", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"
         showSummaryRow="true" pageSize="20">
        <div property="columns">
            <div field="date" headerAlign="center" width="80" allowSort="true">日期</div>
            <div field="nu" headerAlign="center" width="100" allowSort="true">新增用户</div>
            <div field="tu" headerAlign="center" width="100" allowSort="true">总用户</div>
            <div field="dau" headerAlign="center" width="100" allowSort="true">活跃用户</div>
            <div field="wau" headerAlign="center" width="100" allowSort="true">7日活跃</div>
            <div field="mau" headerAlign="center" width="100" allowSort="true">30日活跃</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("date", "desc");

    function search() {
        var begin_date = mini.get("begin_date").getFormValue();
        var end_date = mini.get("end_date").getFormValue();
        grid.load({begin_date: begin_date, end_date: end_date});
    }
    function onKeyEnter(e) {
        search();
    }
    function reset() {
        window.location.href = window.location.href;
    }
</script>
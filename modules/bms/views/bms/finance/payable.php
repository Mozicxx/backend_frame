<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>            
            <td style="white-space:nowrap;">
                <input id="key" class="mini-monthpicker" emptyText="月份" style="width:150px;" onenter="onKeyEnter"/>
                <input id="key" class="mini-textbox" emptyText="开发者" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/finance/payable", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="month" width="120" headerAlign="center" allowSort="true">应付月份</div>
            <div field="email" width="120" headerAlign="center" allowSort="true">开发者</div>
            <div field="job" width="120" headerAlign="center" renderer="onJobRender" allowSort="true">类型</div>
            <div field="owner" width="120" headerAlign="center" allowSort="true">负责人</div>
            <div field="income" width="120" headerAlign="center" allowSort="true">期末收入</div>
            <div field="before" width="120" headerAlign="center" allowSort="true">起初余额</div>
            <div field="balance" width="120" headerAlign="center" allowSort="true">期末余额</div>
            <div field="draw" width="120" headerAlign="center" allowSort="true">提现</div>
            <div field="debt" width="120" headerAlign="center" allowSort="true">已提未汇</div>
            <div field="gross_margin" width="120" headerAlign="center" allowSort="true">毛利润率(估)</div>
            <div field="gross_profit" width="120" headerAlign="center" allowSort="true">毛利润(估)</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("income", "desc");
    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }
    var jobs = [{id: "company", text: '公司'}, {id: "personal", text: '个人开发者'}];
    function onJobRender(e) {
        for (var i = 0, l = jobs.length; i < l; i++) {
            var g = jobs[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    function reset(){
        window.location.href=window.location.href;
    }
</script>
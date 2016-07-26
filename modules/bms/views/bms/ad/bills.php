<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="begin_date" class="mini-datepicker" emptyText="开始日期" onenter="onKeyEnter"/>
                <input id="end_date" class="mini-datepicker" emptyText="截至日期" onenter="onKeyEnter"/>
                <input id="ad_name" class="mini-autocomplete" valueField="text" url="<?php echo URL::site("bms/data/ad", TRUE) . "?method=list"; ?>" emptyText="广告" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
            <td style="white-space:nowrap;text-align: right;">
                <a class="mini-button" iconCls="icon-save" plain="true" onclick="saveDate()">保存</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;"
         url="<?php echo URL::site("bms/ad/bills", TRUE) . "?method=list"; ?>"  idField="id"
         allowResize="true" pageSize="20"
        allowCellEdit="true" allowCellSelect="true" multiSelect="true"
        editNextOnEnterKey="true"  editNextRowCell="true">
        <div property="columns">
            <div type="indexcolumn"></div>
            <div field="date" headerAlign="center" width="80" allowSort="true">日期</div>
            <div field="ad_name" headerAlign="center" width="80" allowSort="true" >广告</div>
            <div field="advertiser_id" headerAlign="center" width="80" allowSort="true" >广告主</div>
            <div field="fee_type" headerAlign="center" width="80" allowSort="true">计费类型</div>
            <div field="price" headerAlign="center" width="80" allowSort="true">价格</div>            
            <div field="advertiser_cpc" headerAlign="center" width="100" allowSort="true">广告主CPC
                <input property="editor" class="mini-textbox" style="width:100%;" minWidth="100" />
            </div>            
            <div field="advertiser_cpa" headerAlign="center" width="100" allowSort="true">广告主CPA
                <input property="editor" class="mini-textbox" style="width:100%;" minWidth="100" />
            </div>
            <div field="cpc_result" headerAlign="center" width="80" allowSort="true">CPC成果</div>
            <div field="cpa_result" headerAlign="center" width="100" allowSort="true">CPA成果</div>
            <div field="check_time" headerAlign="center" width="120" allowSort="true">对账时间</div>
            <div field="spush" headerAlign="center" width="80" allowSort="true">推送次数</div>
            <div field="epush" headerAlign="center" width="80" allowSort="true">推送人数</div>
            <div field="pv" headerAlign="center" width="80" allowSort="true">展示次数</div>
            <div field="epv" headerAlign="center" width="80" allowSort="true">有效展示</div>
            <div field="click" headerAlign="center" width="80" allowSort="true">点击次数</div>
            <div field="eclick" headerAlign="center" width="80" allowSort="true">有效点击</div>
            <div field="download" headerAlign="center" width="80" allowSort="true">下载次数</div>
            <div field="edownload" headerAlign="center" width="80" allowSort="true">有效下载</div>
            <div field="add_by" headerAlign="center" width="80" allowSort="true">广告归属</div>            
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("ad_name", "desc");

    function search() {
        var begin_date = mini.get("begin_date").getFormValue();
        var end_date = mini.get("end_date").getFormValue();
        var ad_name = mini.get("ad_name").getFormValue();
        grid.load({begin_date: begin_date, end_date: end_date, ad_name: ad_name});
    }
    function saveDate() {
        var data = grid.getChanges();
        var json = mini.encode(data);
        grid.loading("保存中，请稍后......");
        $.ajax({
            url: "<?php echo URL::site("bms/ad/bills", TRUE) . "?method=save"; ?>",
            data: {data: json},
            type: "post",
            success: function(text) {
                grid.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
            }
        });
    }
    function onKeyEnter(e) {
        search();
    }
    function reset() {
        window.location.href = window.location.href;
    }
</script>
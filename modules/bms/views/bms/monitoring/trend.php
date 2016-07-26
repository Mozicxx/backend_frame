<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="white-space:nowrap;">
                <input id="begin_date" class="mini-datepicker" emptyText="开始日期" onenter="onKeyEnter"/>
                <input id="end_date" class="mini-datepicker" emptyText="截止日期" onenter="onKeyEnter"/>
                <input id="step" class="mini-combobox" emptyText="显示" data="[{id:'hour',text:'时'},{id:'day',text:'日'}]" onenter="onKeyEnter" />
                <input id="ad_type" class="mini-combobox" emptyText="广告类型" onenter="onKeyEnter" url="<?php echo URL::site("bms/data/adtype", TRUE); ?>" />
                <input id="ad_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/ad", TRUE) . "?method=list"; ?>" emptyText="广告" style="width:150px;" onenter="onKeyEnter"/>
                <input id="app_id" class="mini-autocomplete" url="<?php echo URL::site("bms/data/app", TRUE) . "?method=list"; ?>" emptyText="应用" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
                <a class="mini-button" iconCls="icon-reload" plain="true" onclick="reset()">重置</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="chart"></div>
</div>
<script type="text/javascript" src="<?php URL::base(TRUE); ?>/static/js/highcharts3.0.10.js"></script>
<script type="text/javascript">
                    function search() {
                        var begin_date = mini.get("begin_date").getFormValue();
                        var end_date = mini.get("end_date").getFormValue();
                        var ad_type = mini.get("ad_type").getValue();
                        var step = mini.get("step").getValue();
                        var ad_id = mini.get("ad_id").getValue();
                        var app_id = mini.get("app_id").getValue();
                        $.ajax({
                            cache: false,
                            method:"POST",
                            data: {method: 'post', begin_date: begin_date, end_date: end_date, ad_type: ad_type, ad_id: ad_id, app_id: app_id, step: step},
                            success: function(text) {
                                var o = mini.decode(text);
                                $('#chart').highcharts(o);
                            }
                        });
                    }
                    $(document).ready(function() {
                        $('#chart').highcharts(<?php echo json_encode($chart); ?>);
                    });
                    function reset() {
                        window.location.href = window.location.href;
                    }
</script>
<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/prize/send", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="app_id" width="80" headerAlign="center" allowSort="true">抽奖应用</div>            
            <div field="icon" width="32" headerAlign="center" renderer="onImgRender" allowSort="true">图标</div>
            <div field="gift_name" width="200" headerAlign="center" allowSort="true">奖品名称</div>
            <div field="amount" width="40" headerAlign="center" allowSort="true">数量</div>
            <div field="add_time" width="120" headerAlign="center" allowSort="true">中奖时间</div>
            <div field="openuuid" width="80" headerAlign="center" allowSort="true">用户标识</div>
            <div field="phone" width="80" headerAlign="center" allowSort="true">手机号</div>
            <div field="user_name" width="80" headerAlign="center" allowSort="true">用户名</div>
            <div field="note" width="200" headerAlign="center" allowSort="true">发奖备注</div>
            <div field="send_time" width="120" headerAlign="center" allowSort="true">发奖时间</div>
            <div field="send_user" width="60" headerAlign="center" allowSort="true">发奖人员</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("prize_id", "asc");
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/prize/send/send", TRUE); ?>",
                title: "编辑抽奖礼品", width: 640, height: 360,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.prize_id};
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
    function onImgRender(e){
        return "<img height=\"24\" onerror=\"this.src='http://oss.aliyuncs.com/zt-sandbox-hz/ic_launcher.png'\" src=\""+e.value+"\"/>";
    }
</script>
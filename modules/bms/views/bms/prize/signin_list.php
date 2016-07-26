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
         url="<?php echo URL::site("bms/prize/signgift", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="gift_id" width="32" headerAlign="center" allowSort="true">ID</div>
            <div field="icon" width="32" headerAlign="center" renderer="onImgRender" allowSort="true">图标</div>            
            <div field="gift_type" width="80" headerAlign="center" renderer="onTypeRender" allowSort="true">奖品类型</div>            
            <div field="gift_name" width="200" headerAlign="center" allowSort="true">奖品名称</div>            
            <div field="amount" width="40" headerAlign="center" allowSort="true">数量</div>
            <div field="extra" width="200" headerAlign="center" allowSort="true">扩展信息</div>
            <div field="add_time" width="60" headerAlign="center" allowSort="true">录入时间</div>
            <div field="add_by" width="60" headerAlign="center" allowSort="true">录入人员</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("gift_id", "asc");
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/prize/signgift/edit", TRUE); ?>",
                title: "编辑抽奖礼品", width: 640, height: 260,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.gift_id};
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
    var Status = [{id: "score", text: '积分'}, {id: "custom", text: '自定义'}];
    function onTypeRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    function onImgRender(e) {
        return "<img height=\"24\" onerror=\"this.src='http://oss.aliyuncs.com/zt-sandbox-hz/ic_launcher.png'\" src=\"" + e.value + "\"/>";
    }
</script>
<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入广告名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/ad/advertiser", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true"  pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="s_name" width="120" headerAlign="center" allowSort="true">名称缩写</div>
            <div field="name" width="120" headerAlign="center" allowSort="true">联系人</div>
            <div field="company" width="120" headerAlign="center" allowSort="true">公司</div>
            <div field="status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">合作阶段</div>
            <div field="type" width="120" headerAlign="center" renderer="onCopetypeRender" allowSort="true">合作类型</div>
            <div field="business" width="120" headerAlign="center" allowSort="true">行业</div>
            <div field="pay_cycle" width="120" headerAlign="center" allowSort="true">付款周期</div>
            <div field="friendly_level" width="120" headerAlign="center" allowSort="true">关系</div>
            <div field="credit_level" width="120" headerAlign="center" allowSort="true">信用</div>
            <div field="phone" width="120" headerAlign="center" allowSort="true">电话</div>
            <div field="add_by" width="120" headerAlign="center" allowSort="true">市场接入</div>
            <div field="total_ad" width="120" headerAlign="center" allowSort="true">广告数</div>
            <div field="add_time" width="100" headerAlign="center" allowSort="true">录入时间</div>
        </div>
    </div>
</div>

<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("add_time", "desc");
    function add() {

        mini.open({
            url: "<?php echo URL::site("bms/ad/advertiser/edit", TRUE); ?>",
            title: "录入广告", width: 640, height: 400,
            onload: function() {
                var iframe = this.getIFrameEl();
                var data = {action: "new"};
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function(action) {
                grid.reload();
            }
        });
    }
    function edit() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/ad/advertiser/edit", TRUE); ?>",
                title: "编辑广告", width: 640, height: 400,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "edit", id: row.id};
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
    function remove() {

        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定删除选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/ad/advertiser", TRUE) . "?method=delete&id="; ?>" + id,
                    success: function(text) {
                        grid.reload();
                    },
                    error: function() {
                    }
                });
            }
        } else {
            alert("请选中一条记录");
        }
    }
    function search() {
        var key = mini.get("key").getValue();
        grid.load({key: key});
    }
    function onKeyEnter(e) {
        search();
    }
    /////////////////////////////////////////////////    
    var Adtype = [{id: "proxy", text: '代理客户'}, {id: "direct", text: '直接客户'}];
    function onCopetypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Status = [{id: "pendding", text: '未生效'}, {id: "actived", text: '正在进行'}, {id: "finished", text: '已完结'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
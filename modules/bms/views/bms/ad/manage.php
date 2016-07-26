<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-new" plain="true" onclick="copy()">复制</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-lock" plain="true" onclick="rule()">定投</a>
                <a class="mini-button" iconCls="icon-upload" plain="true" onclick="op('actived')">上线</a>
                <a class="mini-button" iconCls="icon-download" plain="true" onclick="op('finished')">下线</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="remove()">删除</a>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-autocomplete" valueField="text" url="<?php echo URL::site("bms/data/ad", TRUE) . "?method=list"; ?>" emptyText="请输入广告名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/ad/manage", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20">
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="id" width="60" headerAlign="center" allowSort="true">广告ID</div>
            <div field="name" width="120" headerAlign="center" allowSort="true">广告名称</div>
            <div field="platform" width="60" headerAlign="center" renderer="onPlatformRender" allowSort="true">广告</div>
            <div field="fee_type" width="60" headerAlign="center" renderer="onFeetypeRender" allowSort="true">计费</div>
            <div field="ad_type" width="60" headerAlign="center" renderer="onAdtypeRender" allowSort="true">展现</div>
            <div field="status" width="70" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>
            <div field="price" width="60" headerAlign="center" allowSort="true">接入价</div>
            <div field="m_price" width="60" headerAlign="center" allowSort="true">媒体价</div>
            <div field="active_rate" width="40" headerAlign="center" allowSort="true">权重</div>
            <div field="quota" width="70" headerAlign="center" allowSort="true">计划投放</div>
            <div field="finished" width="70" headerAlign="center" allowSort="true">已完成</div>
            <div field="missing" width="60" headerAlign="center" allowSort="true">剩余</div>
            <div field="day_quota" width="70" headerAlign="center" allowSort="true">市场日投</div>
            <div field="day_quota_op" width="70" headerAlign="center" allowSort="true">运营日投</div>
            <div field="day_finished" width="70" headerAlign="center" allowSort="true">今日完成</div>
            <div field="day_missing" width="70" headerAlign="center" allowSort="true">今日剩余</div>
            <div field="add_time" width="80" headerAlign="center" allowSort="true">录入时间</div>
            <div field="passed_time" width="80" headerAlign="center" allowSort="true">审核时间</div>
            <div field="begin_date" width="80" headerAlign="center" allowSort="true">开始时间</div>
            <div field="end_date" width="80" headerAlign="center" allowSort="true">结束时间</div>
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
            url: "<?php echo URL::site("bms/ad/manage/edit", TRUE); ?>",
            title: "录入广告", width: 720, height: 690,
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
                url: "<?php echo URL::site("bms/ad/manage/edit", TRUE); ?>",
                title: "编辑广告", width: 740, height: 690,
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
    function copy() {

        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/ad/manage/edit", TRUE); ?>",
                title: "编辑广告", width: 740, height: 690,
                onload: function() {
                    var iframe = this.getIFrameEl();
                    var data = {action: "copy", id: row.id};
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
    function rule() {
        var row = grid.getSelected();
        if (row) {
            mini.open({
                url: "<?php echo URL::site("bms/ad/add/rule", TRUE); ?>",
                title: "广告定投"+row.name, width:960, height: 660,
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
                    url: "<?php echo URL::site("bms/ad/manage", TRUE) . "?method=delete&id="; ?>" + id,
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
    
    function op(status) {

        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定"+(status==="actived"?"上线":"下线")+"选中广告？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/ad/manage", TRUE) . "?method=op&id="; ?>" + id+"&status="+status,
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

    var Platform = [{id: "Android", text: 'Android'}, {id: "IOS", text: 'IOS'}];
    function onPlatformRender(e) {
        for (var i = 0, l = Platform.length; i < l; i++) {
            var g = Platform[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Feetype = [{id: "CPA", text: 'CPA'}, {id: "CPC", text: 'CPC'}, {id: "CPS", text: 'CPS'}];
    function onFeetypeRender(e) {
        for (var i = 0, l = Feetype.length; i < l; i++) {
            var g = Feetype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Adtype = [{id: "banner", text: '互动广告'}, {id: "offer", text: '推荐列表'}, {id: "push", text: '推送广告'}, {id: "pop", text: '插屏广告'}, {id: "ext1", text: '橱窗广告'}];
    function onAdtypeRender(e) {
        for (var i = 0, l = Adtype.length; i < l; i++) {
            var g = Adtype[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
    var Status = [{id: "pendding", text: '未上线'}, {id: "actived", text: '正在进行'}, {id: "finished", text: '已完结'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }
</script>
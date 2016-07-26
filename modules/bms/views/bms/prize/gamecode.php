<div class="mini-toolbar" style="padding:2px;border-bottom:0;">
    <table style="width:100%;">
        <tr>
            <td style="width:100%;">
                <a class="mini-button" iconCls="icon-add" plain="true" onclick="add()">增加</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="edit()">编辑</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="code()">导码</a>
                <a class="mini-button" iconCls="icon-edit" plain="true" onclick="status('actived')">上架</a>
                <a class="mini-button" iconCls="icon-remove" plain="true" onclick="status('deleted')">删除</a>
                <span style="float: right;margin-right: 2em;color: red;">游戏码导入使用csv格式文件，每行一个游戏码，重复游戏码不再导入。</span>
            </td>
            <td style="white-space:nowrap;">
                <input id="key" class="mini-textbox" emptyText="请输入礼包名称" style="width:150px;" onenter="onKeyEnter"/>
                <a class="mini-button" iconCls="icon-find" plain="true" onclick="search()">查询</a>
            </td>
        </tr>
    </table>
</div>
<div class="mini-fit">
    <div id="datagrid1" class="mini-datagrid" style="width:100%;height:100%;" allowResize="true"
         url="<?php echo URL::site("bms/prize/gamecode", TRUE) . "?method=list"; ?>"  idField="id" multiSelect="true" pageSize="20"
         >
        <div property="columns">
            <div type="checkcolumn" ></div>
            <div field="id" width="60" headerAlign="center">礼包ID</div>
            <div field="pack_name" width="120" headerAlign="center" allowSort="true">礼包名称</div>
            <div field="ad_name" width="80" headerAlign="center">广告</div>
            <div field="ad_id" width="60" headerAlign="center" allowSort="true">广告ID</div>            
            <div field="intro" width="120" headerAlign="center" allowSort="true">礼包内容</div>
            <div field="note" width="120" headerAlign="center" allowSort="true">兑换说明</div>
            <div field="begin_date" width="80" headerAlign="center" dateFormat="yyyy-MM-dd" allowSort="true">开始</div>
            <div field="end_date" width="80" headerAlign="center" dateFormat="yyyy-MM-dd" allowSort="true">截至</div>
            <div field="id" width="60" headerAlign="center" align="center" renderer="onCodeRender" allowSort="true">游戏码</div>
            <div field="total" width="60" headerAlign="center">码总量</div>
            <div field="new" width="60" headerAlign="center">码余量</div>
            <div field="status" width="60" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>
            <div field="add_time" width="120" headerAlign="center" dateFormat="yyyy-MM-dd HH:ss:mm" allowSort="true">添加时间</div>
            <div field="add_by" width="60" headerAlign="center" allowSort="true">录入</div>
        </div>
    </div>
</div>
<script src="<?php echo URL::base(TRUE); ?>static/js/upload.js"></script>
<script type="text/javascript">
    mini.parse();
    var grid = mini.get("datagrid1");
    grid.load();
    grid.sortBy("add_time", "desc");
    function add() {
        mini.open({
            url: "<?php echo URL::site("bms/prize/gamecode/edit", TRUE); ?>",
            title: "录入游戏激活码", width: 480, height: 360,
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
                url: "<?php echo URL::site("bms/prize/gamecode/edit", TRUE); ?>",
                title: "编辑游戏激活码", width: 480, height: 360,
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
    function code(pack_id) {
        if(!pack_id){
            var row=grid.getSelected();
            if(row){
                pack_id = row.id;
            }else{
                alert("请选中一条记录");
                return;
            }
        }
        $.upload({
            // 上传地址
            url: '<?php echo URL::site("bms/prize/gamecode", TRUE); ?>?method=load',
            // 文件域名字
            fileName: 'filedata',
            // 其他表单数据
            params: {pack_id:pack_id},
            // 上传完成后, 返回json, text
            dataType: 'json',
            // 上传之前回调,return true表示可继续上传
            onSend: function() {                
                return true;
            },
            beforeSubmit:function(){
                grid.loading("正在导入，请稍后......");
            },
            // 上传之后回调
            onComplate: function(data) {                
                alert("成功导入["+data+"]个游戏码。");
                grid.reload();
            }
        });
    }
    function status(status) {
        var rows = grid.getSelecteds();
        if (rows.length > 0) {
            if (confirm("确定" + (status === "actived" ? "上架" : "删除") + "选中记录？")) {
                var ids = [];
                for (var i = 0, l = rows.length; i < l; i++) {
                    var r = rows[i];
                    ids.push(r.id);
                }
                var id = ids.join(',');
                grid.loading("操作中，请稍后......");
                $.ajax({
                    url: "<?php echo URL::site("bms/prize/gamecode", TRUE) . "?method=status&id="; ?>" + id + "&status=" + status,
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
    var Status = [{id: "actived", text: '已上线'}, {id: "pendding", text: '未上线'}];
    function onStatusRender(e) {
        for (var i = 0, l = Status.length; i < l; i++) {
            var g = Status[i];
            if (g.id === e.value)
                return g.text;
        }
        return "";
    }

    function onCodeRender(e) {
        return "<a href=\"javascript:code('" + e.value + "')\">导入</a>";
    }
</script>
<div class="mini-toolbar" style="text-align:center;line-height:30px;" borderStyle="border:0;">
    <label >名称：</label>
    <input id="key" class="mini-textbox" style="width:150px;" onenter="onKeyEnter"/>
    <a class="mini-button" style="width:60px;" onclick="search()">查询</a>
</div>
<div class="mini-fit">
    <div id="grid1" class="mini-datagrid" style="width:100%;height:100%;" 
         idField="id" allowResize="true"
         borderStyle="border-left:0;border-right:0;" onrowdblclick="onRowDblClick"
         url="<?php echo URL::site("bms/ad/select", TRUE) . "?method=list&distict=true"; ?>"
         >
        <div property="columns">
            <div field="name" width="120" headerAlign="center" allowSort="true">广告名称</div>
            <div field="platform" width="120" headerAlign="center" renderer="onPlatformRender" allowSort="true">广告平台</div>
            <div field="fee_type" width="120" headerAlign="center" renderer="onFeetypeRender" allowSort="true">计费类型</div>
            <div field="ad_type" width="120" headerAlign="center" renderer="onAdtypeRender" allowSort="true">展现方式</div>
            <div field="status" width="120" headerAlign="center" renderer="onStatusRender" allowSort="true">状态</div>     
        </div>
    </div>
</div>                
<div class="mini-toolbar" style="text-align:center;padding-top:8px;padding-bottom:8px;" borderStyle="border:0;">
    <a class="mini-button" style="width:60px;" onclick="onOk()">确定</a>
    <span style="display:inline-block;width:25px;"></span>
    <a class="mini-button" style="width:60px;" onclick="onCancel()">取消</a>
</div>

<script type="text/javascript">
    mini.parse();

    var grid = mini.get("grid1");
    grid.sortBy("add_time", "desc");
    <?php if(isset($_GET["ad_type"])){?>
    grid.load({ad_type:'<?php echo $_GET["ad_type"];?>'});
    <?php }else{?>
    grid.load();
    <?php }?>

    function GetData() {
        var row = grid.getSelected();
        return row;
    }
    function search() {
        var key = mini.get("key").getValue();
        <?php if(isset($_GET["ad_type"])){?>
        grid.load({key: key,ad_type:'<?php echo $_GET["ad_type"];?>'});
        <?php }else{?>        
        grid.load({key: key});
        <?php }?>
    }
    function onKeyEnter(e) {
        search();
    }
    function onRowDblClick(e) {
        onOk();
    }
    //////////////////////////////////
    function CloseWindow(action) {
        if (window.CloseOwnerWindow)
            return window.CloseOwnerWindow(action);
        else
            window.close();
    }

    function onOk() {
        CloseWindow("ok");
    }
    function onCancel() {
        CloseWindow("cancel");
    }
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
    var Adtype = [{id: "banner", text: '互动广告'}, {id: "offer", text: '推荐列表'}, {id: "push", text: '推送广告'}, {id: "pop", text: '插屏广告'}];
    function onAdtypeRender(e) {
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
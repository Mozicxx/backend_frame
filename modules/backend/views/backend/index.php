<div class="mini-layout" style="width:100%;height:100%;">
    <div title="north" region="north" class="header" bodyStyle="overflow:hidden;" height="80" showHeader="false" showSplit="false">
        <div class="logo">管理后台</div>

        <div class="topNav">    
            <a id="homeindex" title="今日数据" onclick="opframe(this);return false;" href="<?php echo URL::site("backend/home/dashboard", TRUE); ?>">首页</a> |
            <a href="<?php echo URL::site("backend/book/", TRUE); ?>">系统手册</a> |
            <a id="homeuser" title="个人资料" onclick="opframe(this);return false;" href="<?php echo URL::site("backend/home/my", TRUE); ?>"><?php echo $username; ?></a> |            
            <a id="password" title="修改密码" onclick="opframe(this);return false;" href="<?php echo URL::site("backend/home/retpwd", TRUE); ?>">修改密码</a> |
            <a href="<?php echo URL::site("backend/auth/logout", TRUE); ?>">退出登录</a>
        </div>
        <div style="position:absolute;right:12px;bottom:5px;font-size:12px;line-height:25px;font-weight:normal;">
            皮肤:
            <select id="selectSkin" onchange="onSkinChange(this.value)" style="width:100px;margin-right:10px;">
                <option value="">Default</option>
                <option value="blue">Blue</option>
                <option value="pure">Pure</option>
                <option value="gray">Gray</option>
                <option value="olive2003">Olive2003</option>
                <option value="blue2003">Blue2003</option>
                <option value="blue2010">Blue2010</option>
                <option value="bootstrap">Bootstrap</option>
                <option value="metro">metro</option>
                <option value="metro-green">metro-green</option>
                <option value="metro-orange">metro-orange</option>
                <option value="jqueryui-cupertino">jqueryui-cupertino</option>
                <option value="jqueryui-smoothness">jqueryui-smoothness</option>
            </select>
            尺寸:
            <select id="selectMode" onchange="onModeChange(this.value)" style="width:100px;">
                <option value="">Default</option>
                <option value="medium">Medium</option>
                <option value="large">Large</option>
            </select>
        </div>

    </div>
    <div showHeader="false" region="south" style="border:0;text-align:center;" height="25" showSplit="false">
        Copyright © mozic
    </div>
    <div region="west" title="在线演示" showHeader="false" bodyStyle="padding-left:1px;" showSplitIcon="true" width="230" minWidth="100" maxWidth="350">
        <div id="leftTree" class="mini-outlookmenu" url="<?php echo URL::site("backend/home/menu"); ?>" onitemselect="onItemSelect"
             idField="id" parentField="pid" textField="text" borderStyle="border:0">
        </div>
    </div>
    <div title="center" region="center" style="border:0;">
        <div id="mainTabs" class="mini-tabs" activeIndex="0" style="width:100%;height:100%;">
            <div title="今日数据">
                <iframe src="<?php echo URL::site("backend/home/dashboard", TRUE); ?>" id="mainframe" frameborder="0" name="main" style="width:100%;height:100%;" border="0"></iframe>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    mini.parse();
    var tabs = mini.get("mainTabs");
    function onItemSelect(e) {
        var item = e.item;
        var tab = tabs.getTab("tab$" + item.id);
        if (!tab) {
            tab = {_nodeid: item.id, name: "tab$" + item.id, title: item.text, url: item.url, showCloseButton: true};
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
    }
    
    function opframe(item){
        var tab = tabs.getTab("tab$"+item.id);
        if (!tab) {
            tab = {_nodeid: item.id, name: "tab$"+item.id, title: item.title, url: item.href, showCloseButton: true};
            tabs.addTab(tab);
        }
        tabs.activeTab(tab);
        return false;
    }

    function onSkinChange(skin) {
        mini.Cookie.set('miniuiSkin', skin);
        window.location.reload()
    }
    function onModeChange(skin) {
        mini.Cookie.set('miniuiMode', skin);
        window.location.reload()
    }
    function AddCSSLink(id, url, doc) {
        doc = doc || document;
        var link = doc.createElement("link");
        link.id = id;
        link.setAttribute("rel", "stylesheet");
        link.setAttribute("type", "text/css");
        link.setAttribute("href", url);

        var heads = doc.getElementsByTagName("head");
        if (heads.length)
            heads[0].appendChild(link);
        else
            doc.documentElement.appendChild(link);
    }
    var CanSet = false;
    window.onload = function() {
        var skin = mini.Cookie.get("miniuiSkin");
        if (skin) {
            var selectSkin = document.getElementById("selectSkin");
            selectSkin.value = skin;
        }
        CanSet = true;
    };
</script>

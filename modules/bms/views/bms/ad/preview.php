<style type="text/css">
    body{background: #666;}
    .hidden{display: none;}
    .banner{height: 50px;overflow: hidden;position: absolute;bottom: 0;}
    .banner img{width: 100%;}
    .push .info{height: 48px;overflow: hidden;padding: 6px;background-color: #fff;}
    .push .notify{background: #000;color: #fff;padding: 2px 5px;}
    .push #push_icon{float: left;margin:0 5px;height: 48px;}
    .push p{line-height: 24px;padding: 0px;margin: 0px;font-size: 14px;}
    .gray{color: #666;}
    .pop{margin: 0 auto;padding: 0px;text-align: center;vertical-align: middle;}
    .php #pop_img{max-width: 288px;}
    .offer_item{padding: 6px;border-bottom: 1px solid #ccc;background: #efefef;}
    .offernav{line-height: 20px;background: #009900;color: #fff;padding: 5px;}
    .offernav .balance{float: right;}
    .offernav .back{padding: 6px 10px;background: #0a0}
    .offer #offer_icon{float: left;margin: 0 5px;width: 48px;}
    .offer .text{}
    .offer h2{font-size: 14px;line-height: 24px;margin: 0px;padding: 0px;}
    .offer #offer_title{font-size: 14px;color: #666;line-height: 24px;}
</style>
<div class="banner hidden"><img id="banner_img"/></div>
<div class="pop hidden"><table><tr><td height="430" width="320" align="center" valign="center"><img width="288" id="pop_img"/></td></tr></table></div>
<div class="push hidden">
    <div class="notify">通知</div>
    <div class="info">
    <img id="push_icon"/>
    <p id="push_name"></p>
    <p class="gray" id="push_title"></p>
    </div>
</div>
<div class="offer hidden">
    <div class="offernav"><span class="back">返回</span><span class="balance">50金币</span></div>
    <div class="offer_item">
    <em class="ico"><img id="offer_icon"/></em>
    <div id="offer_action"></div>
    <div class="txt">
        <h2 id="offer_name"></h2>
        <span id="offer_title"></span>
    </div>
    </div>
</div>
<script type="text/javascript">
    mini.parse();
    function SetData(data) {
        if (data.image)
            $("#" + data.ad_type + "_img").attr("src", data.image);
        if (data.icon)
            $("#" + data.ad_type + "_icon").attr("src", data.icon);
        $("#" + data.ad_type + "_name").html(data.name);
        $("#" + data.ad_type + "_title").html(data.title);
        if (data.action)
            $("#" + data.ad_type + "_action").html(data.action);

        $("." + data.ad_type).show();
    }
</script>
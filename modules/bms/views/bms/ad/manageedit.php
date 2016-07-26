<form id="form1" method="post">
    <input name="id" type="hidden" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >广告信息</legend>
            <table style="width: 100%;">
                <tr>
                    <td>选择平台：</td>
                    <td>
                        <input name="platform" class="mini-combobox" url="<?php echo URL::site("bms/data/platform", TRUE); ?>" required="true"/>
                    </td>
                    <td>展现方式：</td>
                    <td>
                        <input name="ad_type" class="mini-combobox" valueField="id" url="<?php echo URL::site("bms/data/adtype", TRUE); ?>"  required="true"/>
                    </td>
                    <td>计费类型：</td>
                    <td>
                        <input name="fee_type" class="mini-combobox" url="<?php echo URL::site("bms/data/feetype", TRUE); ?>" required="true"/>
                    </td>
                </tr>
                <tr>                    
                    <td>广告主：</td>
                    <td>
                        <input id="advertiser_id" name="advertiser_id" url="<?php echo URL::site("bms/data/advertiser", TRUE); ?>"  required="true" class="mini-combobox" pinyinField="pinyin"/>                        
                    </td>
                    <td>广告类型：</td>
                    <td>
                        <input name="tag" class="mini-combobox" url="<?php echo URL::site("bms/data/tags", TRUE); ?>" textField="text"/>
                    </td>
                    <td>激活提示：</td>
                    <td>
                        <input name="action_name" class="mini-textbox"/>
                    </td>
                </tr>
                <tr>
                    <td>广告名称：</td>
                    <td colspan="3">
                        <input name="name" class="mini-textbox" style="width: 93.3%;" required="true"/>
                    </td>
                    <td>展示权重：</td>
                    <td>
                        <input name="active_rate" class="mini-spinner" value="9" minValue="1" maxValue="9" required="true"/>
                    </td>

                </tr>
                <tr>
                    <td>广告语：</td>
                    <td colspan="5">
                        <input name="title" style="width: 80%;" class="mini-textbox" required="true"/>
                    </td>
                </tr>
                <tr>
                    <td>广告图标：</td>
                    <td colspan="5" class="filed">
                        <input name="icon" style="width: 50%;" class="mini-textbox" valueField="id" textField="name"/>
                        <span class="btn btn-success fileinput-button">
                            <span>上传</span>
                            <input style="height:20px;overflow: hidden;"  class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                        </span>
                        <span style="color:#999;">(积分墙和推送广告必填:64x64)</span>
                    </td>
                </tr>
                <tr>
                    <td>广告图片：</td>
                    <td colspan="5" class="filed">
                        <input name="image" style="width: 50%;" class="mini-textbox"/>
                        <span class="btn btn-success fileinput-button">
                            <span>上传<i class="processing"></i></span>
                            <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                        </span>
                        <span style="color:#999;">(插屏:600x500/320x250,互动:640x100/320x50)</span>
                    </td>
                </tr>
                <tr>
                    <td>跳转地址：</td>
                    <td colspan="5">
                        <input name="click_url" class="mini-textbox" style="width:80%;" valueField="id" textField="name"/>
                    </td>
                </tr>
                <tr>
                    <td >广告描述：</td>
                    <td colspan="5">
                        <input name="description" class="mini-textarea" style="width:98%;" />
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >广告计划</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width:70px;">广告单价：</td>
                        <td style="width:150px;">
                            <input name="price" class="mini-textbox" required="true"/>
                        </td>
                        <td style="width:70px;">媒体价格：</td>
                        <td >
                            <input name="m_price" class="mini-textbox" required="true"/>
                        </td>
                        <td>奖励积分：</td>
                        <td >
                            <input name="award_score" class="mini-textbox" required="true"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:70px;">投放总量：</td>
                        <td style="width:150px;">
                            <input name="quota" class="mini-textbox" required="true"/>
                        </td>
                        <td style="width:70px;">市场日投：</td>
                        <td >
                            <input name="day_quota" class="mini-textbox" required="true"/>
                        </td>
                        <td >运营日投：</td>
                        <td >
                            <input name="day_quota_op" class="mini-textbox" required="true"/>
                        </td>
                    </tr>
                    <tr>
                        <td >开始时间：</td>
                        <td >
                            <input name="begin_date" class="mini-datepicker" required="true" emptyText="请选择日期"/>
                        </td>
                        <td style="width:70px;">结束时间：</td>
                        <td>
                            <input name="end_date" class="mini-datepicker" required="true" emptyText="请选择日期"/>
                        </td>
                        <td style="width:70px;">广告状态：</td>
                        <td>
                            <input name="status" class="mini-combobox" data="[{id:'pendding',text:'未上线'},{id:'actived',text:'正在进行'},{id:'finished',text:'已完结'}]" required="true" />
                        </td>
                    </tr>                    
                </table>
            </div>
        </fieldset>
        <style>
            .appimg{float: left;width: 60px;height: 100px;margin: 0 5px;text-align: center;overflow: hidden;}
            .appimg img{width: 60px;height: 80px;background: #aaa;}
        </style>
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend >应用信息</legend>
            <div style="padding:5px;">
                <table style="width: 100%;">
                    <tr>
                        <td >下载地址：</td>
                        <td colspan="3" class="filed">
                            <input name="download_url" class="mini-textbox" style="width: 80%"/>
                            <span class="btn btn-success fileinput-button">
                                <span>上传<i class="processing"></i></span>
                                <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                            </span>
                        </td>                
                        <td colspan="2" rowspan="4" style="margin:0px;padding: 0px;vertical-align: top;">
                            <div style="float:left;">配图：</div>
                            <div class="appimg filed"><img class="prev" src=""/>
                                <span style="display:block;" class="btn btn-success fileinput-button"><input name="app_image[]" type="hidden"/>
                                    <span>上传</span>
                                    <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                                </span>
                            </div>
                            <div class="appimg filed"><img class="prev" src=""/><input name="app_image[]" type="hidden"/>
                                <span style="display:block;" class="btn btn-success fileinput-button">
                                    <span>上传</span>
                                    <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                                </span>
                            </div>
                            <div class="appimg filed"><img class="prev" src=""/><input name="app_image[]" type="hidden"/>
                                <span style="display:block;" class="btn btn-success fileinput-button">
                                    <span>上传</span>
                                    <input style="height:20px;overflow: hidden;" class="fileupload" type="file" name="files[]" data-url="<?php echo URL::site("bms/home/upload"); ?>">
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>                        
                        <td style="width:70px;">包名：</td>
                        <td >
                            <input name="package_name" class="mini-textbox"/>
                        </td>
                        <td style="width:70px;">包大小：</td>
                        <td style="width:150px;">
                            <input name="package_size" class="mini-textbox"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:70px;">应用厂商：</td>
                        <td >
                            <input name="app_owner" class="mini-textbox"/>
                        </td>
                        <td >应用版本：</td>
                        <td >
                            <input name="app_ver" class="mini-textbox"/>
                        </td>
                    </tr>
                    <tr>                        
                        <td >应用评级：</td>
                        <td>
                            <input name="app_star" class="mini-spinner" value="9" minValue="1" maxValue="9" />
                        </td>
                        <td >访问量：</td>
                        <td>
                            <input name="app_hits" class="mini-textbox"/>
                        </td>
                    </tr>
                    <tr>
                        <td >应用描述：</td>
                        <td colspan="5">
                            <input name="extra" class="mini-textarea" style="width:98%;" />
                        </td>
                    </tr>
                </table>
            </div>
        </fieldset>
    </div>

    <div style="text-align:center;padding:10px;">
        <a class="mini-button" onclick="preview" style="width:60px;margin-right:20px;">预览</a>
        <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;">确定</a>
        <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
    </div>
</form>

<script type="text/javascript">
    mini.parse();

    var form = new mini.Form("form1");

    function SaveData() {
        var o = form.getData();

        form.validate();
        if (form.isValid() === false)
            return;
        var img = {};
        $(".appimg > img.prev").each(function(index) {
            img[index] = $(this).attr("src");
        });
        o["app_image"] = mini.encode(img);
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/ad/manage", TRUE) . "?method=save"; ?>",
            type: 'post',
            data: {data: json},
            cache: false,
            success: function(text) {
                CloseWindow("save");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText);
                CloseWindow();
            }
        });
    }

    ////////////////////
    //标准方法接口定义
    function SetData(data) {
        if (data.action) {
            //跨页面传递的数据对象，克隆后才可以安全使用
            data = mini.clone(data);

            $.ajax({
                url: "<?php echo URL::site("bms/ad/manage", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    if (data.action === "copy") {
                        o.id = null;
                    }
                    form.setData(o);
                    form.setChanged(false);

                    var img = mini.decode(o["app_image"]);
                    $(".appimg > img.prev").each(function(index) {
                        try {
                            $(this).attr("src", img[index]);
                        } catch (e) {
                        }
                    });
                }
            });
        }
    }

    function GetData() {
        var o = form.getData();
        return o;
    }
    function CloseWindow(action) {
        if (action === "close" && form.isChanged()) {
            if (confirm("数据被修改了，是否先保存？")) {
                return false;
            }
        }
        if (window.CloseOwnerWindow)
            return window.CloseOwnerWindow(action);
        else
            window.close();
    }
    function onOk(e) {
        SaveData();
    }
    function onCancel(e) {
        CloseWindow("cancel");
    }
    function preview(e) {
        mini.open({
            url: "<?php echo URL::site("bms/ad/add/preview", TRUE); ?>",
            title: "预览广告", width: 320, height: 480,
            onload: function() {
                var iframe = this.getIFrameEl();
                var data = form.getData();
                iframe.contentWindow.SetData(data);
            },
            ondestroy: function(action) {
            }
        });
    }
</script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.ui.widget.js"></script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.iframe-transport.js"></script>
<script src="<?php echo URL::base(TRUE); ?>static/js/jquery.fileupload.js"></script>
<script>
    $(function() {
        $('.fileupload').each(function() {
            $(this).fileupload({
                dataType: 'json',
                done: function(e, data) {
                    $.each(data.result.files, function(index, file) {
                        var filed = $(e.target).parents(".filed").find("input[type=text]").attr("name");
                        var o = form.getData();
                        o[filed] = file.url;
                        form.setData(o);
                        //$(e.target).parents(".filed").find("input[type=text]").val(file.url);
                        $(e.target).parents(".filed").find("img.prev").attr("src", file.url);
                        alert(file.name + "上传成功！");
                    });
                },
                progressall: function(e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $(e.target).parents(".fileinput-button").find(".processing").html(progress + '%');
                }
            });
        });
    });
</script>
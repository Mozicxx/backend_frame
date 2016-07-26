<form id="giftform" method="post">
    <input name="prize_id" class="mini-hidden" />
    <div style="padding:5px">
        <fieldset style="border:solid 1px #aaa;padding:3px;">
            <legend>奖品信息</legend>
            <table width="100%">
                <tr>
                    <td>奖品名称</td>
                    <td colspan="3"><input name="gift_name" readonly="true" class="mini-textbox" style="width: 80%;" required="true"/></td>                    
                </tr>
                <tr>
                    <td>奖品数量</td>
                    <td colspan="3"><input name="amount" readonly="true" class="mini-textbox" width="80" required="true"/></td>
                </tr>
                <tr class="filed">
                    <td>图标</td>
                    <td colspan="3">
                        <img id="icon" class="prev" width="24" height="24"/>
                    </td>
                </tr>
                <tr>
                    <td>手机号</td>
                    <td colspan="3"><input name="phone" readonly="true" class="mini-textbox" style="width: 80%"/></td>
                </tr>
                <tr>
                    <td>姓名</td>
                    <td colspan="3"><input name="user_name" readonly="true" class="mini-textbox" style="width: 80%"/></td>
                </tr>
                <tr>
                    <td>地址</td>
                    <td colspan="3"><input name="address" readonly="true" class="mini-textbox" style="width: 80%"/></td>
                </tr>
                <tr>
                    <td>备注</td>
                    <td colspan="3"><input name="note" class="mini-textarea" style="width: 80%"/></td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div style="text-align:center;padding:10px;">
        <a class="mini-button" onclick="onOk" style="width:60px;margin-right:20px;">确定</a>
        <a class="mini-button" onclick="onCancel" style="width:60px;">取消</a>
    </div>
</form>

<script type="text/javascript">
    mini.parse();

    var form = new mini.Form("giftform");

    function SaveData() {
        var o = form.getData();
        form.validate();
        if (form.isValid() === false)
            return;
        var json = mini.encode([o]);
        $.ajax({
            url: "<?php echo URL::site("bms/prize/send", TRUE) . "?method=save"; ?>",
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
        if (data.action === "edit") {
            //跨页面传递的数据对象，克隆后才可以安全使用
            data = mini.clone(data);

            $.ajax({
                url: "<?php echo URL::site("bms/prize/send", TRUE) . "?method=get&id="; ?>" + data.id,
                cache: false,
                success: function(text) {
                    var o = mini.decode(text);
                    form.setData(o);
                    form.setChanged(false);
                    $("#icon").attr("src", o.icon);
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
                        $(e.target).parents(".filed").find("img.prev").attr("src", file.url);
                    });
                }
            });
        });
    });
</script>
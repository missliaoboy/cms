<?php

defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>hotkeys.js"></script>
<form action="/index.php?m=content&c=cache_cdn&a=show" method="post" onsubmit="return ajaxtext()">
    <table align="center" width="50%" cellspacing="10">
        <center style="color:red;font-size: 24px;font-weight: bold;">注意：页面url和目录都不能带有http://,多个用;号分隔</center>
        <tr>
            <th width='200'>请输入要更新的页面url:</th>
            <td>
                <textarea name="murl" id="murl" cols="50" onchange="textchange(this)"></textarea>
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th width='200'>请输入要更新的目录:</th>
            <td>
                <textarea name="mdir" id="mdir" cols="50" onchange="textchange(this)"></textarea>
            </td>
        </tr>
        <tr align="center">
            <td colspan="2"><button>提交</button></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    function ajaxtext()
    {
        var murl    = $("#murl").val();
        var mdir    = $("#mdir").val();
        if(murl.length > 0 || mdir.length > 0){
            return true;
        } else {
            return false;
        }
    }
    function textchange(e)
    {
        var url = $(e).val();
        url = url.replace(/[\n]/g, ";");
        url = url.replace(/http:\/\//g, "");
        url = url.replace(/\s+/g, "");
        $(e).val(url);
    }
</script>
</body>
</html>

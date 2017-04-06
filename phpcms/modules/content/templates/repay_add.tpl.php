<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
  $(function(){
    $.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
    $("#name").formValidator({onshow:"<?php echo L('type_name_tips')?>",onfocus:"<?php echo L("input").L('type_name')?>",oncorrect:"<?php echo L('input_right');?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('type_name')?>"});
  })
</script>
<form action="?m=content&c=repay&a=add_deam" method="post" id="myform">
<div style="padding:6px 3px">
    <div class="col-2 col-left mr6" style="width:540px">
      <h6><img src="<?php echo IMG_PATH;?>icon/sitemap-application-blue.png" width="16" height="16" /> 新增条目 </h6>
<table width="100%"  class="table_form">
  <tr>
    <th width="80">标题：</th>
    <td class="y-bg"><input style="width:300px;" type="text" name="info[title]"></td>
  </tr>
  <tr>
        <th>图片：</th>
        <td><?php echo form::images('info[thumb]', 'image', $image, 'content');?></td>
      </tr>
  <tr>

  <tr>
    <th>网站url：</th>
    <td class="y-bg">
        <input type="text" style="width:300px;" name="info[url]">
    </td>
  </tr>
  <?php if($level > 0) { ?>
    <tr>
        <th>网站跳转url：</th>
        <td class="y-bg">
            <input type="text" style="width:300px;" name="info[lurl]">
        </td>
    </tr>
  <?php } ?>
  <tr>
    <th>描述：</th>
    <td class="y-bg">
        <textarea rows="2" cols="20" name="info[description]" class="inputtext" style="height:80px;width:300px;"></textarea>
    </td>
  </tr>
</table>

<div class="bk15"></div>
    <input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit');?>" />
    <?php if($id > 0) { ?>
    <input type="hidden" name="info[parentid]" value="<?php echo $id; ?>">
    <?php } ?>
    </div>
</div>
<input type="hidden" name="info[level]" value="<?php echo $level; ?>">
</form>
</body>
</html>
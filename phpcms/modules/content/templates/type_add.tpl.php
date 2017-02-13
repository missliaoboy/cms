<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
<!--
	$(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		$("#name").formValidator({onshow:"<?php echo L('type_name_tips')?>",onfocus:"<?php echo L("input").L('type_name')?>",oncorrect:"<?php echo L('input_right');?>"}).inputValidator({min:1,onerror:"<?php echo L("input").L('type_name')?>"});
	})
//-->
</script>
<form action="?m=content&c=type_manage&a=add" method="post" id="myform">
<div style="padding:6px 3px">
    <div class="col-2 col-left mr6" style="width:440px">
      <h6><img src="<?php echo IMG_PATH;?>icon/sitemap-application-blue.png" width="16" height="16" /> <?php echo L('add_type');?></h6>
<table width="100%"  class="table_form">
  <tr>
    <th width="80"><?php echo L('type_name')?>：</th>
    <td class="y-bg"><textarea name="info[name]" rows="2" cols="20" id="name" class="inputtext" style="height:80px;width:300px;"></textarea></td>
  </tr>
    <tr>
    <th><?php echo L('description')?>：</th>
    <td class="y-bg"><textarea name="info[description]" style="width:300px;height:60px;"></textarea></td>
  </tr>
  <tr>
        <th>图片：</th>
        <td><?php echo form::images('info[image]', 'image', $image, 'content');?></td>
      </tr>
  <tr>
  </tr>
    <tr>
    <th>所属分组：</th>
    <td class="y-bg">
          <select name="info[type_deam_id]">
                <option>请选择分组</option>
                <?php 
                      foreach ($type_deam as $key => $value) {
                            echo "<option value='".$value['id']."'>".$value['name']."</option>";
                      }
                ?>
          </select>
    </td>
  </tr>
  <tr>
    <th>url：</th>
    <td class="y-bg">
        <input type="text" name="info[prefix_url]">
    </td>
  </tr>
  <tr>
    <th>模板：</th>
    <td class="y-bg">
        <input type="text" name="info[template]">
    </td>
  </tr>
  <tr>
    <th>SEO标题：</th>
    <td class="y-bg">
        <input type="text" name="info[seotitle]">
    </td>
  </tr>
  <tr>
    <th>SEO关键词：</th>
    <td class="y-bg">
        <textarea rows="2" cols="20" name="info[seokeywords]" class="inputtext" style="height:80px;width:300px;"></textarea>
    </td>
  </tr>
  <tr>
    <th>SEO简介：</th>
    <td class="y-bg">
        <textarea rows="2" cols="20" name="info[seodescription]" class="inputtext" style="height:80px;width:300px;"></textarea>
    </td>
  </tr>
</table>

<div class="bk15"></div>
    <input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit');?>" />

    </div>
</div>
</form>
</body>
</html>
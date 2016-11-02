<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_header = $show_validator = 1;
include $this->admin_tpl('header', 'admin');
?>
<script type="text/javascript">
<!--
	$(document).ready(function(){
		$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, function(){this.close();$(obj).focus();})}});
		<?php if (is_array($html) && $html['validator']){ echo $html['validator']; unset($html['validator']); }?>

	})
    function checkclick(e){
        var type = $(e).parent().find('input').prop('checked');
        if( type == false) {
            $(e).addClass('check_color');
        } else {
            $(e).removeClass('check_color');
        }
        return false;
    }
//-->
</script>
<style type="text/css">
    .typeid li {
        float: left;
        width: 90px;
    }
    .check_color{
       color: red;
       font-weight: bold;
    }
</style>
<div class="pad-10">
<div class="col-tab">
<div class='content' style="height:auto;">
<form action="?m=content&c=type&a=type_list&module=<?php echo $_GET['module']?>&action=<?php echo $_GET['action']?>" method="post" name="myform" id="myform"><input type="hidden" name="modelid" value="<?php echo $_GET['modelid']?>"><input type="hidden" name="catid" value="<?php echo $_GET['catid']?>">
<input type='hidden' name="id" value='<?php echo $_GET['id']?>'>
<table width="100%"  class="table_form">
  <?php 
    foreach ($type_deam as $key => $value) {
      if(!in_array($key, $usable_deam_type))continue;
  ?>
  <tr>
      <th width="80"><?php echo $value['name']?>：</th>
      <td>
          <ul class="typeid">
          <?php 
              foreach ($type as $k => $val) {
                  if($val['type_deam_id'] == $key){
          ?>
            <li>
                <input type="checkbox" name="typeid[]" id="id<?php echo $k; ?>" <?php if(in_array($k, $arr_type)){ echo " checked "; }?> value="<?php echo $k; ?>">
                <label class="ib <?php if(in_array($k, $arr_type)){ echo ' check_color '; } ?> " for="id<?php echo $k; ?>" onclick="checkclick(this)"  >
                    <?php echo $val['name']; ?>
                </label>
            </li>
          <?php        }
              }
          ?>
          </ul>
      </td>
  </tr>
  <?php  }
  ?>
  </table>
<div class="bk15"></div>

<input type="hidden" name="return" value="<?php echo $return?>" />
<input type="submit" class="dialog" id="dosubmit" name="dosubmit" value="<?php echo L('submit')?>" />
</form>
</div>
</div>
</div>
</body>
</html>
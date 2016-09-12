<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<div id="closeParentTime" style="display:none"></div>
<div class="pad-10">
<div id="searchid" style="display:<?php if(!isset($_GET['search'])) echo 'none';?>">
<form name="searchform" action="" method="get" id="form1">
<input type="hidden" value="content" name="m">
<input type="hidden" value="content" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="<?php echo $catid;?>" name="catid">
<input type="hidden" value="<?php echo $steps;?>" name="steps">
<input type="hidden" value="1" name="search">
<input type="hidden" value="<?php echo $type_sort;?>" name="type_sort">
<input type="hidden" value="<?php echo $type_name;?>" name="type_name">
<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td>
			<div class="explain-col">
				<?php echo L('addtime');?>：
				<?php echo form::date('start_time',$_GET['start_time'],0,0,'false');?>- &nbsp;<?php echo form::date('end_time',$_GET['end_time'],0,0,'false');?>
				<select name="posids"><option value='' <?php if($_GET['posids']=='') echo 'selected';?>><?php echo L('all');?></option>
				<option value="1" <?php if($_GET['posids']==1) echo 'selected';?>><?php echo L('elite');?></option>
				<option value="2" <?php if($_GET['posids']==2) echo 'selected';?>><?php echo L('no_elite');?></option>
				</select>				
				<select name="searchtype">
					<option value='0' <?php if($_GET['searchtype']==0) echo 'selected';?>><?php echo L('title');?></option>
					<option value='1' <?php if($_GET['searchtype']==1) echo 'selected';?>><?php echo L('intro');?></option>
					<option value='2' <?php if($_GET['searchtype']==2) echo 'selected';?>><?php echo L('username');?></option>
					<option value='3' <?php if($_GET['searchtype']==3) echo 'selected';?>>ID</option>
				</select>
				<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
				<select name="typeid">
					<option></option>
					<?php 
						if(!empty($type) && is_array($type)){
							foreach ($type as $key => $value) {
								echo "<option value='".$value['typeid']."'>".$value['name']."</option>";
							}
						}
					?>
				</select>
				<input type="submit" name="search" class="button" value="<?php echo L('search');?>" />
			</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
</div>
<form name="myform" id="myform" action="" method="post" >
<div class="table-list">
    <table width="100%">
        <thead>
            <tr>
			 <th width="16"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
            <!-- <th width="37"><?php echo L('listorder');?></th> -->
            <th width="40" onclick="type_sort(this)" alt='id'><a href="javascript:;" style="font-weight:blod;color:red;">ID</a></th>
            <th width="140" onclick="type_sort(this)" alt='typeid'><a href="javascript:;" style="font-weight:blod;color:red;">朝代</a></th>
			<th width="40">影响id
			</th>
            <th width="40">操作</th>
            <th width="70" >操作人员</th>
            <th width="118" onclick="type_sort(this)" alt="updatetime"><a href="javascript:;" style="font-weight:blod;color:red;">操作时间</a></th>
            </tr>
        </thead>
		<tbody>
			<?php foreach($datas as $k=>$v){ ?>
				<tr>
					<td align="center"><input class="inputcheckbox " name="ids[]" value="<?php echo $v['id'];?>" type="checkbox"></td>
					<!-- <td align="center"></td> -->
					<td align="center"><?php echo $v['id']; ?></td>
					<td align="center"><?php echo $model[$v['catid']]['catname'];?></td>
					<td align="center"><?php echo $v['lid']; ?></td>
					<td align="center"><?php echo $v['type'];?></td>
					<td align="center"><?php echo $user_arr[$v['user_id']]['username'];?></td>
					<td align="center"><?php echo date('Y-m-d H:i:s',$v['add_time']);?></td>
				</tr>
			<?php } ?>
		</tbody>
     </table>
    <div id="pages"><?php echo $pages;?></div>
</div>
</form>
</div>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>cookie.js"></script>
<script type="text/javascript"> 
<!--
function push() {
	var str = 0;
	var id = tag = '';
	$("input[name='ids[]']").each(function() {
		if($(this).attr('checked')=='checked') {
			str = 1;
			id += tag+$(this).val();
			tag = '|';
		}
	});
	if(str==0) {
		alert('<?php echo L('you_do_not_check');?>');
		return false;
	}
	window.top.art.dialog({id:'push'}).close();
	window.top.art.dialog({title:'<?php echo L('push');?>：',id:'push',iframe:'?m=content&c=push&action=position_list&catid=<?php echo $catid?>&modelid=<?php echo $modelid?>&id='+id,width:'800',height:'500'}, function(){var d = window.top.art.dialog({id:'push'}).data.iframe;// 使用内置接口获取iframe对象
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'push'}).close()});
}
function confirm_delete(){
	if(confirm('<?php echo L('confirm_delete', array('message' => L('selected')));?>')) $('#myform').submit();
}
function view_comment(id, name) {
	window.top.art.dialog({id:'view_comment'}).close();
	window.top.art.dialog({yesText:'<?php echo L('dialog_close');?>',title:'<?php echo L('view_comment');?>：'+name,id:'view_comment',iframe:'index.php?m=comment&c=comment_admin&a=lists&show_center_id=1&commentid='+id,width:'800',height:'500'}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function reject_check(type) {
	if(type==1) {
		var str = 0;
		$("input[name='ids[]']").each(function() {
			if($(this).attr('checked')=='checked') {
				str = 1;
			}
		});
		if(str==0) {
			alert('<?php echo L('you_do_not_check');?>');
			return false;
		}
		document.getElementById('myform').action='?m=content&c=content&a=pass&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>&reject=1';
		document.getElementById('myform').submit();
	} else {
		$('#reject_content').css('display','');
		return false;
	}	
}
setcookie('refersh_time', 0);
function refersh_window() {
	var refersh_time = getcookie('refersh_time');
	if(refersh_time==1) {
		window.location.reload();
	}
}
setInterval("refersh_window()", 3000);
function type_sort(e)
{
	$("input[name='type_name']").val($(e).attr('alt'));
	var type = $("input[name='type_sort']").val();
		type = type == 1 ? 0 :1;
	$("input[name='type_sort']").val(type);
	$('#form1').submit();
	// window.location.href = "http://www.baidu.com";type_sort
}
//-->
</script>
</body>
</html>
<?php

defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<div id="closeParentTime" style="display:none"></div>
<SCRIPT LANGUAGE="JavaScript">
<!--
	if(window.top.$("#current_pos").data('clicknum')==1 || window.top.$("#current_pos").data('clicknum')==null) {
	parent.document.getElementById('display_center_id').style.display='';
	var siteid = <?php echo $this->siteid;?>;
	if(siteid == 1){
		parent.document.getElementById('center_frame').src = '?m=content&c=content&a=public_categorys2&type=add&menuid=<?php echo $_GET['menuid'];?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>';
	} else {
		parent.document.getElementById('center_frame').src = '?m=content&c=content&a=public_categorys&type=add&menuid=<?php echo $_GET['menuid'];?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>';
	}
	window.top.$("#current_pos").data('clicknum',0);


}
//-->
</SCRIPT>
<div class="pad-10">
<div class="content-menu ib-a blue line-x">
<a class="add fb" href="javascript:;" onclick=javascript:openwinx('?m=content&c=content&a=add&menuid=&catid=<?php echo $catid;?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')><em><?php echo L('add_content');?></em></a>　
<a href="?m=content&c=content&a=init&catid=<?php echo $catid;?>&pc_hash=<?php echo $pc_hash;?>" <?php if($steps==0 && !isset($_GET['reject'])) echo 'class=on';?>><em><?php echo L('check_passed');?></em></a><span>|</span>
<?php echo $workflow_menu;?> <a href="javascript:;" onclick="javascript:$('#searchid').css('display','');"><em><?php echo L('search');?></em></a> 
<?php if($category['ishtml']) {?>
<span>|</span><a href="?m=content&c=create_html&a=category&pagesize=30&dosubmit=1&modelid=0&catids[0]=<?php echo $catid;?>&pc_hash=<?php echo $pc_hash;?>&referer=<?php echo urlencode($_SERVER['QUERY_STRING']);?>"><em><?php echo L('update_htmls',array('catname'=>$category['catname']));?></em></a>
<?php }?>
</div>
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
<?php if(isset($_GET['reject'])){ ?>
<input type="hidden" value="<?php echo $_GET['reject'];?>" name="reject">
<?php
}
?>
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
					<option value="4" <?php if($_GET['searchtype']==4) echo 'selected';?>>修改人员</option>
				</select>
				<input name="keyword" type="text" value="<?php if(isset($keyword)) echo $keyword;?>" class="input-text" />
				<select name="typeid">
					<option></option>
					<?php 
						if(!empty($type) && is_array($type)){
							foreach ($type as $key => $value) {
								$selected = '';
								if($typeid == $value['typeid'])$selected = " selected='selected' ";
								echo "<option value='".$value['typeid']."' ".$selected." >".$value['name']."</option>";
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
            <th width="37"><?php echo L('listorder');?></th>
            <th width="40" onclick="type_sort(this)" alt='id'><a href="javascript:;" style="font-weight:blod;color:red;">ID</a></th>
            <th width="50" onclick="type_sort(this)" alt='typeid'><a href="javascript:;" style="font-weight:blod;color:red;">朝代</a></th>
			<th><?php echo L('title');?></th>
			<th>关键词</th>
			<th>tag关键词</th>
			<?php  if($_SESSION['roleid'] == 1){ ?>
				<th width="200">审核人员</th>
				<th width="70">修改人员</th>			
			<?php	} ?>
			<th width="40">最近修改人员</th>
            <th width="40" onclick="type_sort(this)" alt="views"><a href="javascript:;" style="font-weight:blod;color:red;"><?php echo L('hits');?></a></th>
            <th width="70" ><?php echo L('publish_user');?></th>
            <th width="118" onclick="type_sort(this)" alt="updatetime"><a href="javascript:;" style="font-weight:blod;color:red;"><?php echo L('updatetime');?></a></th>
			<th width="72"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
<tbody>
    <?php
	if(is_array($datas)) {
		//print_r($datas);
		$sitelist = getcache('sitelist','commons');
		$release_siteurl = $sitelist[$category['siteid']]['url'];
		$path_len = -strlen(WEB_PATH);
		$release_siteurl = substr($release_siteurl,0,$path_len);
		$this->hits_db = pc_base::load_model('hits_model');
		
		foreach ($datas as $r) {

			$hits_r = $this->hits_db->get_one(array('hitsid'=>'c-'.$modelid.'-'.$r['id']));
	?>
        <tr>
		<td align="center"><input class="inputcheckbox " name="ids[]" value="<?php echo $r['id'];?>" type="checkbox"></td>
        <td align='center'><input name='listorders[<?php echo $r['id'];?>]' type='text' size='3' value='<?php echo $r['listorder'];?>' class='input-text-c'></td>
		<td align='center' ><?php echo $r['id'];?></td>
		<td align='center'>
			<?php 
				if($r['typeid']){
					$arr2 = explode(',', $r['typeid']);
					foreach ($arr2 as $k2 => $val2) {
						echo $type[$val2]['name']."<br>";
					}
				}
			?>
		</td>
		<td  ondblclick="dblclickInfo(this,'<?php echo $r['id'];?>','<?php echo $r['catid'];?>','title')">
		<?php
		if($status==99) {
			if($r['islink']) {
				echo '<a href="'.$r['url'].'" target="_blank">';
			} elseif(strpos($r['url'],'http://')!==false) {
				echo '<a href="'.$r['url'].'" target="_blank">';
			} else {
				echo '<a href="'.$release_siteurl.$r['url'].'" target="_blank">';
			}
		} else {
			echo '<a href="javascript:;" onclick=\'window.open("?m=content&c=content&a=public_preview&steps='.$steps.'&catid='.$catid.'&id='.$r['id'].'","manage")\'>';
		}?><span<?php echo title_style($r['style'])?>><?php echo $r['title'];?></span></a> <?php if($r['thumb']!='') {echo '<img src="'.IMG_PATH.'icon/small_img.gif" title="'.L('thumb').'">'; } if($r['posids']) {echo '<img src="'.IMG_PATH.'icon/small_elite.gif" title="'.L('elite').'">';} if($r['islink']) {echo ' <img src="'.IMG_PATH.'icon/link.png" title="'.L('islink_url').'">';}?></td>
		<td ondblclick="dblclickInfo(this,'<?php echo $r['id'];?>','<?php echo $r['catid'];?>','seokeywords')"><span><?php echo $r['seokeywords']; ?></span></td>
		<td ondblclick="dblclickInfo(this,'<?php echo $r['id'];?>','<?php echo $r['catid'];?>','keywords')"><span><?php echo $r['keywords']; ?></span></td>
		<?php  if($_SESSION['roleid'] == 1){ ?>
			<td align="center">
				<?php 
					if(!empty($r['user_check'])){
						$user_check = string2array($r['user_check']);
						$msg 	= '';
						foreach ($user_check as $key => $value) {
							$msg .= $key."审<b style='color:red;'>".$admin_user[$value]['username']."</b>,";
						}
						echo trim($msg,',');
					}
				?>
			</td>
			<td style="word-break: break-all;word-wrap:break-word; width:70px;" align="center" class="wirtes" onclick="type_sort(this)" alt="wirtes">
				<?php 
					if(!empty($r['wirtes'])){
						$user = explode(',', $r['wirtes']);
						$adminUser = '';
						foreach ($user as $key => $value) {
							$adminUser .= $admin_user[$value]['username'].',';
						}
						echo trim($adminUser,',');
					}
				?>
			</td>		
		<?php	} ?>
		<td align='center'>
		<?php 
			if(!empty($r['wirtes'])){
				$user = explode(',', $r['wirtes']);
				$user_count = count($user) - 1;
				echo $admin_user[$user[$user_count]]['username'];
			}
		?>
		</td>
		<td align='center' title="<?php echo L('today_hits');?>：<?php echo $hits_r['dayviews'];?>&#10;<?php echo L('yestoday_hits');?>：<?php echo $hits_r['yesterdayviews'];?>&#10;<?php echo L('week_hits');?>：<?php echo $hits_r['weekviews'];?>&#10;<?php echo L('month_hits');?>：<?php echo $hits_r['monthviews'];?>"><?php echo $hits_r['views'];?></td>
		<td align='center'>
		<?php
		if($r['sysadd']==0) {
			echo "<a href='?m=member&c=member&a=memberinfo&username=".urlencode($r['username'])."&pc_hash=".$_SESSION['pc_hash']."' >".$r['username']."</a>"; 
			echo '<img src="'.IMG_PATH.'icon/contribute.png" title="'.L('member_contribute').'">';
		} else {
			echo $r['username'];
		}
		?></td>
		<td align='center'><?php echo format::date($r['updatetime'],1);?></td>
		<td align='center'><a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $catid;?>&id=<?php echo $r['id']?>','')"><?php echo L('edit');?></a> | <a href="javascript:view_comment('<?php echo id_encode('content_'.$catid,$r['id'],$this->siteid);?>','<?php echo safe_replace($r['title']);?>')"><?php echo L('comment');?></a></td>
	</tr>
     <?php }
	}
	?>
</tbody>
     </table>
    <div class="btn"><label for="check_box"><?php echo L('selected_all');?>/<?php echo L('cancel');?></label>
		<input type="hidden" value="<?php echo $pc_hash;?>" name="pc_hash">
    	<input type="button" class="button" value="<?php echo L('listorder');?>" onclick="myform.action='?m=content&c=content&a=listorder&dosubmit=1&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>';myform.submit();"/>
		<?php if($category['content_ishtml']) {?>
		<input type="button" class="button" value="<?php echo L('createhtml');?>" onclick="myform.action='?m=content&c=create_html&a=batch_show&dosubmit=1&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>';myform.submit();"/>
		<?php }
		if($status!=99) {?>
		<input type="button" class="button" value="<?php echo L('passed_checked');?>" onclick="myform.action='?m=content&c=content&a=pass&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>';myform.submit();"/>
		<?php }?>
		<input type="button" class="button" value="<?php echo L('delete');?>" onclick="myform.action='?m=content&c=content&a=delete&dosubmit=1&catid=<?php echo $catid;?>&steps=<?php echo $steps;?>';return confirm_delete()"/>
		<?php if(!isset($_GET['reject'])) { ?>
		<input type="button" class="button" value="<?php echo L('push');?>" onclick="push();"/>
		<?php if($workflow_menu) { ?><input type="button" class="button" value="<?php echo L('reject');?>" onclick="reject_check()"/>
		<div id='reject_content' style='background-color: #fff;border:#006699 solid 1px;position:absolute;z-index:10;padding:1px;display:none;'>
		<table cellpadding='0' cellspacing='1' border='0'><tr><tr><td colspan='2'><textarea name='reject_c' id='reject_c' style='width:300px;height:46px;'  onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;"><?php echo L('reject_msg');?></textarea></td><td><input type='button' value=' <?php echo L('submit');?> ' class="button" onclick="reject_check(1)"></td></tr>
		</table>
		</div>
		<?php }}?>
		<input type="button" class="button" value="类别" onclick="type_push();"/>
		<input type="button" class="button" value="<?php echo L('remove');?>" onclick="myform.action='?m=content&c=content&a=remove&catid=<?php echo $catid;?>';myform.submit();"/>
		<?php echo runhook('admin_content_init')?>
	</div>
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
function type_push()
{
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
	window.top.art.dialog({id:'type_push'}).close();
	window.top.art.dialog({title:'类别：',id:'type_push',iframe:'?m=content&c=type&a=type_list&catid=<?php echo $catid?>&modelid=<?php echo $modelid?>&id='+id,width:'800',height:'500'}, function(){var d = window.top.art.dialog({id:'type_push'}).data.iframe;// 使用内置接口获取iframe对象
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'type_push'}).close()});
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
	if($(e).attr('alt') == 'wirtes'){
		
		$("input[name='keyword']").val($.trim($(e).html()));
		$("select[name='searchtype']").val('4');

		//return;
	}
	var type = $("input[name='type_sort']").val();
		type = type == 1 ? 0 :1;
	
	if($(e).attr('alt') == 'views'){
		type = 0;
	}
	$("input[name='type_sort']").val(type);	
	$('#form1').submit();
	// window.location.href = "http://www.baidu.com";type_sort
}

function dblclickInfo($this,$id,$catid,$remark){

	if($remark == 'title'){
		
		var $val = $this.getElementsByTagName('span').item(0).innerHTML;

		$this.getElementsByTagName('span').item(0).innerHTML = '<input type="text" id="'+$id+'_<?php echo $modelid;?>_'+$catid+'" style="width:300px;" onblur="updateInfo(this,\'title\')" value="'+$val+'">';
		var url = $this.getElementsByTagName('a').item(0).getAttribute('href');
		$this.getElementsByTagName('a').item(0).removeAttribute('href');
		$this.getElementsByTagName('a').item(0).setAttribute('alt',url);

	}else{
		var $val = $this.getElementsByTagName('span').item(0).innerHTML;
		$this.getElementsByTagName('span').item(0).innerHTML = '<input type="text" id="'+$id+'_<?php echo $modelid;?>_'+$catid+'" style="width:300px;" onblur="updateInfo(this,\''+$remark+'\')" value="'+$val+'">';
	}

	//console.debug(url);
}
function updateInfo($this,$remark){

	//console.debug($this.parentNode.parentNode);
	if($remark == 'title'){
		
		var url = $this.parentNode.parentNode.getAttribute('alt');
		$this.parentNode.parentNode.setAttribute('href',url);
		$this.parentNode.innerHTML = $this.value;
				
	}else{
		$this.parentNode.innerHTML = $this.value;
	}
	var idInfo = $this.getAttribute('id');
	var idList = idInfo.split('_');
	//console.debug(idList);return;
	$.post('?m=content&c=content&a=updateinfos&pc_hash=<?php echo $pc_hash; ?>',{'val':$this.value,'remark':$remark,'id':idList[0],'modelid':idList[1],'catid':idList[2]},function(data){
		//console.debug(data);
		if(!data){
			alert('修改'+$remark+'失败!');
		}else{
			var searchStyle =   $('#searchid').attr('style');

			window.location.href = window.location.href;
			/*
			if(typeof searchStyle != 'undefined' && searchStyle == 'display:'){

				window.location.href = '?m=content&c=content&a=init&catid=<?php echo $_GET['catid'];?>&steps=<?php echo $_GET['steps'];?>&search=1&type_sort=<?php echo $_GET['type_sort'];?>&type_name=<?php echo $_GET['type_name'];?>&pc_hash=<?echo $_GET['pc_hash'];?>&start_time=<?php echo $_GET['start_time'];?>&end_time=<?php echo $_GET['end_time'];?>&posids=<?php echo $_GET['posids'];?>&searchtype=<?php echo $_GET['searchtype'];?>&keyword=<?php echo $_GET['keyword'];?>&typeid=<?php echo $_GET['typeid'];?>&search=搜索&page=<?php echo $_GET['page'];?>';
			}else{

				window.location.href = '?m=content&c=content&a=init&menuid=<?php echo $_GET['menuid'];?>&catid=<?php echo $_GET['catid'];?>&pc_hash=<?php echo $_GET['pc_hash'];?>&page=<?php echo $_GET['page'];?>';
			}*/
		}
	});
}
function getHitsOrder(){


}
//-->
</script>
</body>
</html>
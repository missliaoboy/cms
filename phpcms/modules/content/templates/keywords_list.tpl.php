<?php
defined('IN_ADMIN') or exit('No permission resources.');
?>
<div class="pad-10">
</div>

<form name="myform" id="myform" action="" method="post" >
<div class="table-list">
    <table width="100%" border="1" cellspacing="0">
        <thead>
            <tr>
            <th width="100">ID</th>
			<th>标题</th>
            <th width="140">状态</th>
            <th width="270">新增时间</th>
            <th width="278">发放时间</th>
            </tr>
        </thead>
		<tbody>
			<?php foreach ($datas as $key => $v) { ?>
		     <tr>
				 <td align='center' ><?php echo $v['id']; ?></td>
				<td align='center'>
				<?php if($v['sid'] > 0){ ?>
					<a href="index.php?m=content&c=index&a=show&catid=&id=<?php echo $v['sid']; ?>" target="_blank">
						<span><?php echo $v['title']; ?></span>
					</a> 
				<?php }else{ ?>
					<span><?php echo $v['title']; ?></span>
				<?php } ?>
				</td>
				<td align='center'>
					<?php if($v['type'] == 0){ ?>
						<b style="color:green;">未使用</b>
					<?php }else{ ?>
						<b style="color:red;">已使用</b>
					<?php } ?>
				</td>
				<td align='center'>
					<?php echo date("Y-m-d H:i:s",$v['add_time']); ?>
				</td>
				<td align='center'>
					<?php if($v['updatetime'] > 0){ echo date("Y-m-d H:i:s",$v['updatetime']); } ?>
				</td>
			</tr>
			<?php } ?>
     	</tbody>
     </table>
<!--     <div class="btn"><label for="check_box">全选/取消</label>
		<input type="hidden" value="EYHTnU" name="pc_hash">
    	<input type="button" class="button" value="排序" onclick="myform.action='?m=content&c=content&a=listorder&dosubmit=1&catid=7&steps=0';myform.submit();"/>
				<input type="button" class="button" value="删除" onclick="myform.action='?m=content&c=content&a=delete&dosubmit=1&catid=7&steps=0';return confirm_delete()"/>
		<div id='reject_content' style='background-color: #fff;border:#006699 solid 1px;position:absolute;z-index:10;padding:1px;display:none;'>
		</div>
			</div> -->
    <div id="pages"><?php echo $pages;?></div>
</div>
</form>
</div>
<script language="javascript" type="text/javascript" src="/statics/js/cookie.js"></script>
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
		alert('您没有勾选信息');
		return false;
	}
	window.top.art.dialog({id:'push'}).close();
	window.top.art.dialog({title:'推送：',id:'push',iframe:'?m=content&c=push&action=position_list&catid=7&modelid=1&id='+id,width:'800',height:'500'}, function(){var d = window.top.art.dialog({id:'push'}).data.iframe;// 使用内置接口获取iframe对象
	var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'push'}).close()});
}
function confirm_delete(){
	if(confirm('确认删除吗？')) $('#myform').submit();
}
function view_comment(id, name) {
	window.top.art.dialog({id:'view_comment'}).close();
	window.top.art.dialog({yesText:'关闭',title:'查看评论：'+name,id:'view_comment',iframe:'index.php?m=comment&c=comment_admin&a=lists&show_center_id=1&commentid='+id,width:'800',height:'500'}, function(){window.top.art.dialog({id:'edit'}).close()});
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
			alert('您没有勾选信息');
			return false;
		}
		document.getElementById('myform').action='?m=content&c=content&a=pass&catid=7&steps=0&reject=1';
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
//-->
</script>
</body>
</html>
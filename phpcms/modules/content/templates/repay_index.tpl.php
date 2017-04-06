<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<form name="myform" action="?m=content&c=type_manage&a=listorder" method="post">
<div class="pad_10">
<div class="table-list">
    <table width="100%" cellspacing="0" >
        <thead>
	<tr>
	<th width="5%"><?php echo L('listorder');?></td>
	<th width="5%">ID</th>
	<th width="20%"><?php echo L('type_name');?></th>
	<th width="20%">url</th>
	<th width="*"><?php echo L('description');?></th>
	<th width="30%"><?php echo L('operations_manage');?></th>
	</tr>
        </thead>
    <tbody>
    

<?php
foreach($datas as $r) {
?>
<tr>
<td align="center"><input type="text" name="listorders[<?php echo $r['typeid']?>]" value="<?php echo $r['listorder']?>" size="3" class='input-text-c'></td>
<td align="center"><?php echo $r['id']?></td>
<td align="center"><?php echo $r['title']?></td>
<td align="center"><?php echo $r['url']?></td>
<td ><?php echo mb_substr($r['description'],0,100); ?></td>
<td align="center">
<a href="javascript:window.top.art.dialog({id:'add_deam',iframe:'?m=content&c=repay&a=add_deam&level=1&id=<?php echo $r['id']; ?>', title:'新增条目', width:'780', height:'500', lock:true}, function(){var d = window.top.art.dialog({id:'add_deam'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;}, function(){window.top.art.dialog({id:'add_deam'}).close()});void(0);">新增</a> | 
<a href="?m=content&c=repay&a=list_show&id=<?php echo $r['id']; ?>">列表</a> | 

<a href="javascript:edit('<?php echo $r['id']?>','<?php echo trim(new_addslashes($r['title']))?>')"><?php echo L('edit');?></a> | 
<a href="javascript:;" onclick="data_delete(this,'<?php echo $r['id']?>','<?php echo trim(new_addslashes($r['title']));?>')"><?php echo L('delete')?></a>
</td>
</tr>
<?php } ?>
	</tbody>
    </table>

    <div class="btn"><input type="submit" class="button" name="dosubmit" value="<?php echo L('listorder')?>" /></div>  </div>
	<div id="pages"><?php echo $pages;?></div>
</div>

</div>
</form>

<script type="text/javascript"> 
<!--
window.top.$('#display_center_id').css('display','none');
function edit(id, name) {
	window.top.art.dialog({id:'edit'}).close();
	window.top.art.dialog({title:'<?php echo L('edit_type');?>《'+name+'》',id:'edit',iframe:'?m=content&c=repay&a=edit&id='+id,width:'780',height:'500'}, function(){var d = window.top.art.dialog({id:'edit'}).data.iframe;d.document.getElementById('dosubmit').click();return false;}, function(){window.top.art.dialog({id:'edit'}).close()});
}
function data_delete(obj,id,name){
	window.top.art.dialog({content:'删除 '+name+' ?', fixed:true, style:'confirm', id:'data_delete'}, 
	function(){
	$.get('?m=content&c=repay&a=delete&id='+id+'&pc_hash='+pc_hash,function(data){
				if(data) {
					$(obj).parent().parent().fadeOut("slow");
				}
			}) 	
		 }, 
	function(){});
};
//-->
</script>
</body>
</html>

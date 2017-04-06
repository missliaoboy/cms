<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
$type = false;
?>
<table width="900" border="2" align="center">
	<tr>
		<th width="50" height="50">第一级</th>
		<td align="center" colspan="<?php echo $count_num; ?>">
			<span style="font-weight: bold;font-size:20px;">
			<a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $arr['catid']; ?>&id=<?php echo $arr['id']; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')">
				<?php echo $arr['title']; ?>
			</a>
			</span>
			<span style="color: red;font-weight: bold;" onclick="javascript:openwinx('?m=content&c=content&a=add&menuid=&modelid=16&parent_id=<?php echo $arr['id']; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')">
				+
			</span>
			<span style="font-weight: bold;color:red;" onclick="confirm_delete('<?php echo $arr['catid']; ?>','<?php echo $arr['id']; ?>')">X</span>
		</td>
	</tr>
	<?php if($count_num > 0){ ?>
	<tr>
		<th width="50" height="50">第二级</th>
		<?php foreach($arr2 as $v){ if(is_array($v['value'])){ $type = true;}?>
			<td align="center" colspan="<?php echo $v['value_count']; ?>">
				<span style="font-weight: bold;font-size:20px;">
					<a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $v['catid']; ?>&id=<?php echo $v['id']; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')">
						<?php echo $v['title']; ?>
					</a>
				</span>
				<span style="color: red;font-weight: bold;" onclick="javascript:openwinx('?m=content&c=content&a=add&menuid=&modelid=16&parent_id=<?php echo $v['id']; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')">
					+
				</span>
				<span style="font-weight: bold;color:red;" onclick="confirm_delete('<?php echo $v['catid']; ?>','<?php echo $v['id']; ?>')">X</span>
			</td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php if($type){ ?>
	<tr>
		<th width="50" height="150" >第三级</th>
		<?php foreach($arr2 as $v){ ?>
			<td align="center" colspan="<?php echo $v['value_count']; ?>">
				<?php foreach($v['value'] as $val){ ?>
					<span>
						<a href="javascript:;"  onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $val['catid']; ?>&id=<?php echo $val['id']; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>','')">
						<?php echo $val['title'];?>
						</a>
					</span><span style="font-weight: bold;color:red;" onclick="confirm_delete('<?php echo $val['catid']; ?>','<?php echo $val['id']; ?>')">X</span><br/>

				<?php } ?>
			</td>
		<?php } ?>
	</tr>
	<?php } ?>
	</table>
	<script type="text/javascript">
		function confirm_delete(catid,id){
			var arr = [];
			arr.push(id);
			if(confirm('确认删除吗？')){
				$.ajax({
					type:'post',
					url:'http://www.jianglishi.cn/index.php?m=content&c=content&a=delete&dosubmit=1&catid='+catid+'&steps=&pc_hash=<?php echo $_SESSION[pc_hash];?>',
					data:{ids:arr},
					dateType:'json',
					success: function(){
						window.location.reload();
					}
				});
			};
		}
	</script>
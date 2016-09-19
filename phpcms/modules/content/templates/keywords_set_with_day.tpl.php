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
	            <th width="100">序号</th>
				<th width="140">分类id</th>
	            <th>分类名</th>
	            <th width="270">每日投放数量</th>
            </tr>
        </thead>
		<tbody>
			<?php 
				$i = 0;
				foreach ($category as $key => $v) { 
				if( $v['arrchildid'] != $v['catid'] )continue;
			?>
		     <tr>
				<td align='center' >
					<?php echo ++$i; ?>
					<input name="modelid[]" type="hidden" value="<?php echo $v['modelid']; ?>" >
				</td>
				<td align='center'>
					<?php echo $v['catid'];?>
				</td>
				<td align='center'>
					<input type="hidden" name="catid[]" value="<?php echo $v['catid']; ?>">
					<?php echo $v['catname'];?>
				</td>
				<td align='center'>
					<input type="text" name="num[]" value="<?php echo isset($arr[$v['catid']]['num']) ? $arr[$v['catid']]['num']:0; ?>">
				</td>
			</tr>
			<?php } ?>
     	</tbody>
     </table>
     <button>保存配置</button>
     <input name='now' type="submit" value="立即投放">
</div>
</form>
</div>
</body>
</html>
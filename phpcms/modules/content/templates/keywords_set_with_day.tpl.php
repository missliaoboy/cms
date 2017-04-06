<?php
defined('IN_ADMIN') or exit('No permission resources.');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.min.js"></script>
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
     <a href="javascript:;" onclick="choose(this)">开启选择</a>
</div>
</form>
</div>
<script type="text/javascript">
	var show_type = 0;
	$(function(){
		$('#myform tbody').delegate('tr', 'click', function() {
			if( show_type ){
				if($(this).find("input").hasClass('choose')){
					$(this).find("input").removeClass('choose').removeAttr('disabled');
					$(this).css({"background":"#f6a190"});
				} else {
					$(this).find("input").addClass('choose').attr('disabled','disabled');
					$(this).css({"background":""});
				}				
			}
		});
		$('#myform tbody tr').delegate('.num', 'click', function(e) {
			e.stopPropagation();
        	e.preventDefault();
		});
	})
	function choose(e)
	{
		var type 	= $("#myform .num>input").hasClass('choose');
		if(type){
			show_type = 0;
			$("#myform tr input").removeClass('choose').removeAttr('disabled');
			$("#myform tbody tr").css({"background":""});
			$(e).html("开启选择");
		} else {
			show_type = 1;
			$("#myform tr input").addClass('choose').attr('disabled','disabled');
			$(e).html("取消选择");
		}
	}
</script>
</body>
</html>

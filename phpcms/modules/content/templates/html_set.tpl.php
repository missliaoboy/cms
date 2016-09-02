<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');?>
<h1><center>准备生成</center></h1>
<span id="html_set">
</span>
<script language="JavaScript">
	var ids2 = [];
	var j = 0;
	<?php 	foreach ($arr_ids as $key => $value) {	if(empty($value))continue; ?>
		ids2.push("<?php echo $value; ?>");
		++j;
	<?php	} ?>
	if(ids2 != ''){
		change_model(0);
	}
	function change_model(i) {
		var arr = [];
		arr.push(ids2[i]);
		if(i < j){
			$.ajax({
				url:"/index.php?m=content&c=content&a=pass&catid=<?php echo $catid; ?>&steps=<?php echo $steps; ?>&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
				type:'post',
				data:{catid:"<?php echo $catid?>",ids:arr,'token':'<?php echo TOKEN_AJAX_TEMPLATE; ?>','site_type':1},
				dataType:'json',
				success:function(e)
				{
					var content = '';
					if(e.title != ''){
						content = "<a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').append("<h2>第"+ (i+1) +"条生成成功:<span>"+content+"</span></h2><br/>");
					++i;
					change_model(i);
				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
					alert(XMLHttpRequest.status);
					alert(XMLHttpRequest.readyState);
					alert(textStatus);
			    }
			})			
		} else {
			window.location.href = document.referrer;
		}
	}
</script>
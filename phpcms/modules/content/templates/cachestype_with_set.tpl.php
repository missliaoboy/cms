<?php
	defined('IN_ADMIN') or exit('No permission resources.');
	pc_base::load_app_class('admin','admin',0); 
?>
<style type="text/css">
	.isuccess{
		color:red;
	}
	.ierror{
		color: green;
	}
	#html_msg{
		width:600px;
		height:300px;
		overflow:auto;
		font-weight: bold;
		font-size: 20px;
		margin-left:30px; 
		float:left;
	}
</style>
<h1><center>准备生成</center></h1>
<div>
	<div id="html_set" style="width:600px;height:300px;overflow:auto;float:left;"></div>
	<div id="html_msg" style="">
		<span class="isuccess">成功生成 &nbsp;&nbsp;<i>0</i>&nbsp;&nbsp; 篇</span><br/>
		<span class="ierror">生成失败&nbsp;&nbsp;<i>0</i>&nbsp;&nbsp; 篇</span><br/>
		<span class="iall">总数&nbsp;&nbsp;<i>0</i>&nbsp;&nbsp; 篇</span><br/>
	</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.min.js"></script>
<script language="JavaScript">
	var ids2 = [];
	var id 	 = []; 
	var arr  = [];
	var ids;
	var tongji 		= 0;  //序号
	var isuccess 	= 0; //成功
	var ierror 		= 0; //失败
	var j = 0;
	<?php 	foreach ($deam as $key => $value) {	if(empty($value))continue; ?>
		ids2.push("<?php echo $value; ?>");
		++j;
	<?php	} ?>
	
	function get_html_set()
	{
		if(ids2[0] != undefined ){
			var new_arr 	= ids2[0];
			++tongji;
			$.ajax({
				url:"/index.php?m=content&c=cachestype&a=type_with",
				type:'post',
				// async 	: false,
				data:{typeid:new_arr},
				dataType:'json',
				success:function(e)
				{
					++isuccess;
					ids2.shift();
					if(e.title != ''){
						content = "<a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').prepend("<span>生成成功:<span>"+content+"</span></span>     耗时：" + e.time + "秒   序号" + tongji + "<br/>");
					set_html();
					console.log(e.idall);
					get_html_child_set(e.idall);

				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
			    	data.shift();
					++ierror;
					$('#html_set').prepend("<span style='color:green;font-weight: bold;'>生成失败<span></span></span>   序号" + tongji + "<br/>");
					set_html();
					get_html_set();
			    }
			});
		} else {
			$('#html_set').prepend("<span>生成完成<span></span></span><br/>");
		}
	}

	function get_html_child_set(data2)
	{
		console.log(data2);
		if(data2[0] != undefined ){
			var new_arr 	= data2[0];
			++tongji;
			$.ajax({
				url:"/index.php?m=content&c=cachestype&a=type_child_with",
				type:'post',
				data:{typeid:new_arr},
				dataType:'json',
				success:function(e)
				{
					++isuccess;
					data2.shift();
					if(e.title != ''){
						content = "<a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').prepend("<span>生成成功:<span>"+content+"</span></span>     耗时：" + e.time + "秒   序号" + tongji + "<br/>");
					set_html();
					get_html_child_set(data2);

				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
			    	data2.shift();
					++ierror;
					$('#html_set').prepend("<span style='color:green;font-weight: bold;'>生成失败<span></span></span>   序号" + tongji + "<br/>");
					set_html();
					get_html_child_set(data2);
			    }
			});
		} else {
			if(ids2[0] != undefined ){
				get_html_set();
			} else {
				$('#html_set').prepend("<span>生成完成<span></span></span><br/>");
			}
		}
	}


	function set_html()
	{
		$('#html_msg .isuccess i').html(isuccess);
		$('#html_msg .ierror i').html(ierror);
		$('#html_msg .iall i').html(tongji);	
	}

	window.onload = function()
	{
		get_html_set(ids2);
	}
</script>
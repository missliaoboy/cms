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
	var sid  = "<?php echo $session_id; ?>";
	var ids2 = [];
	var id 	 = []; 
	var arr  = [];
	var category = [227,228,229,230,231,232];
	var ids;
	var tongji 		= 0;  //序号
	var isuccess 	= 0; //成功
	var ierror 		= 0; //失败
	var j = 0;
	<?php 	foreach ($new_arr as $key => $value) {	if(empty($value))continue; ?>
		ids = [];
		ids.id 	= "<?php echo $value['id']; ?>";
		ids.time 	= "<?php echo $time; ?>";
		ids.key 	= "<?php echo $value['key']; ?>";
		ids2[<?php echo $key; ?>] = ids;
		arr[<?php echo $key; ?>] = ids;
		++j;
	<?php	} ?>
	
	function get_html_set(data)
	{
		if(ids2[0] != undefined ){
			var new_arr 	= ids2[0];
			var e_arr2	= new Array();
			if( !new_arr ){
				return false;
			}
			++tongji;
			$.ajax({
				url:"/index.php?m=content&c=with_set&a=type_list_deam&sid="+sid,
				type:'post',
				// async 	: false,
				data:{id:new_arr.id,time:new_arr.time,key:new_arr.key,level:1},
				dataType:'json',
				success:function(e)
				{
					var return_data = $.parseJSON(e);
					++isuccess;
					data.shift();
					var content = '';
					if(e.title != ''){
						content = "<a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').prepend("<span>生成成功:<span>"+content+"</span></span>     耗时：" + e.time + "秒   序号" + tongji + "<br/>");
					set_html();
					category_with(data,new_arr,e.childid);
				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
			    	data.shift();
					++ierror;
					$('#html_set').prepend("<span style='color:green;font-weight: bold;'>生成失败<span></span></span>   序号" + tongji + "<br/>");
					set_html();
					get_html_set(data);
			    }
			});
		} else {
			$('#html_set').prepend("<span>生成完成<span></span></span><br/>");
		}
	}

	function category_with(data,data2,childid)
	{
		if( childid[0] !== undefined ){
			var now = childid[0];
			++tongji;
			$.ajax({
				url:"/index.php?m=content&c=with_set&a=type_list_html&sid="+sid,
				type:'post',
				// async 	: false,
				data:{id:now,level:2},
				dataType:'json',
				success:function(e)
				{
					childid.shift();
					++isuccess;
					var content = '';
					if(e.title != ''){
						content = "<a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').prepend("<span>生成成功:<span>"+content+"</span></span>     耗时：" + e.time + "秒   序号" + tongji + "<br/>");
					set_html();
					if( childid[0] == undefined ){
						get_html_set(data);
					} else{
						category_with(data,data2,childid);
					}
				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
					++ierror;
					childid.shift();
					set_html();
					$('#html_set').prepend("<span style='color:green;font-weight: bold;'>生成失败<span></span></span>   序号" + tongji + "<br/>");
					if(childid[0] == undefined){
						get_html_set(data);
					} else{
						category_with(data,data2,childid);
					}
			    }
			});
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
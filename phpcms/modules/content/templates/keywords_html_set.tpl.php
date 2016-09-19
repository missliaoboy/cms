<?php
	defined('IN_ADMIN') or exit('No permission resources.');
	pc_base::load_app_class('admin','admin',0); 
	$new_system = pc_base::load_config('system'); //引入配置文件
	$time 		= time();
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
	<?php 	foreach ($arr4 as $key => $value) {	if(empty($value))continue; ?>
		ids = [];
		ids.num 	= "<?php echo $value['num']; ?>";
		ids.catid 	= "<?php echo $value['catid']; ?>";
		ids.modelid = "<?php echo $value['modelid']; ?>";
		ids2[<?php echo $key; ?>] = ids;
		++j;
	<?php	} ?>
	// 获取要生成分类的id  ajax请求防止超时
	function get_catid_id()
	{
		var time 	= "<?php echo $time; ?>";
		var key 	= "<?php echo md5($new_system['with_set_key'].$time); ?>";
		for( var i in ids2){
			$.ajax({
				url		: "/index.php?m=content&c=with_set&a=get_catid_id",
				type 	: "post",
				async 	: false,
				data 	: {catid:ids2[i].catid,num:ids2[i].num,time:time,key:key,modelid:ids2[i].modelid},
				dataType: 'json',
				success	: function(data){
					--j;
					var e_id = '';
					if( data != '' && data ){
						for(var i2 in data){
							if(data[i2].id > 0){
								var e_arr = [];
								e_arr['modelid'] = ids2[i].modelid;
								e_arr['catid'] 	 = ids2[i].catid;
								e_arr['id'] 	 = data[i2].id;
								arr.push(e_arr); 
							}
						}						
					}
					if(j == 0){
						data = '';
						get_html_set(arr);
					}
				}
			});
		}
	}
	
	function get_html_set(data)
	{
		if(j == 0 && data[0] != undefined ){
			var new_arr 	= data[0];
			var e_arr2	= new Array();
			if( !new_arr ){
				return false;
			}
			++tongji;
			e_arr2.id 		= new Array();
			e_arr2.catid 	= new_arr.catid;
			e_arr2.modelid 	= new_arr.modelid;
			e_arr2.id.push(data[0].id);
			data.shift();
			$.ajax({
				url:"/index.php?m=content&c=content&a=pass&catid="+e_arr2.catid+"&steps=99&pc_hash=<?php echo $_SESSION['pc_hash'];?>",
				type:'post',
				async 	: false,
				data:{catid:e_arr2.catid,modelid:e_arr2.modelid,ids:e_arr2.id,'token':'www.jianglishi.cn','site_type':1},
				dataType:'json',
				success:function(e)
				{
					++isuccess;
					var content = '';
					if(e.title != ''){
						content = "<b>【" + e.catname + "】</b><a href='" + e.url + "' target='_blank'  style='color:red;font-weight: bold;'>" + e.title + "</a>"; 
					}
					$('#html_set').prepend("<span>生成成功:<span>"+content+"</span></span>     耗时：" + e.time + "秒   序号" + tongji + "<br/>");
					set_html();
					get_html_set(data);

				},
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
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

	function set_html()
	{
		$('#html_msg .isuccess i').html(isuccess);
		$('#html_msg .ierror i').html(ierror);
		$('#html_msg .iall i').html(tongji);	
	}

	window.onload = function()
	{
		get_catid_id();
	}
</script>
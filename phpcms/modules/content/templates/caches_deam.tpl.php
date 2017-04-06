<?php ?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
	</head>
	<body>
		<div style="margin:100px auto;width:600px;height:400px;">
			<form action="#" method="post">
				<center><h1>请选择要生成的类别组</h1></center>
				<?php
					foreach ($cache_deam as $key => $value) {
				?>
					<div style="width: 170px;float: left;">
						<label><input type="checkbox" name="id[]" value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
					</div>
				<?php
					}
				?>
				<div style="text-align:center;clear:both;height:200px;line-height: 200px;">
					<a href="javascript:;" onclick="all_with()" >全选/反选</a>
					<input type="submit" name="with_set" value="开始生成">
				</div>
			</form>		
		</div>
	</body>
	<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>jquery.min.js"></script>
	<script type="text/javascript">
		//实现全选
		function all_with(){
			$("input[name='id\[\]']").each(function(){
	            if($(this).prop("checked") == true){
	                $(this).prop("checked",false);
	            }else{
	                $(this).prop("checked",true);
	            }
	        })  
		}
	</script>	
</html>

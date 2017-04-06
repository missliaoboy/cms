<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<div class="pad-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="content" name="m">
<input type="hidden" value="content" name="c">
<input type="hidden" value="public_relationlist" name="a">
<input type="hidden" value="<?php echo $modelid;?>" name="modelid">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td align="center">
		<div class="explain-col">
				<select name="field">
					<option value='title' <?php if($_GET['field']=='title') echo 'selected';?>><?php echo L('title');?></option>
					<option value='keywords' <?php if($_GET['field']=='keywords') echo 'selected';?> ><?php echo L('keywords');?></option>
					<option value='description' <?php if($_GET['field']=='description') echo 'selected';?>><?php echo L('description');?></option>
					<option value='id' <?php if($_GET['field']=='id') echo 'selected';?>>ID</option>
				</select>
				<?php
					echo form::select_model('model','name="model"',L('model_name'),$modelid);
				?>				
				<?php echo form::select_category('',$catid,'name="catid"',L('please_select_category'),$modelid,0,1);?>
				<input name="keywords" type="text" value="<?php echo stripslashes($_GET['keywords'])?>" style="width:330px;" class="input-text" />
				<input type="submit" name="dosubmit" class="button" value="<?php echo L('search');?>" />
	</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<div class="table-list">
    <table width="100%" cellspacing="0" >
        <thead>
            <tr>
            <th ><?php echo L('title');?></th>
			<th width="100"><?php echo L('belong_category');?></th>
            <th width="100"><?php echo L('addtime');?></th>
            </tr>
        </thead>
    <tbody>
	<?php foreach($infos as $r) { ?>
	<tr onclick="select_list(this,'<?php echo safe_replace($r['title']);?>',<?php echo $r['id'];?>,<?php echo $modelid;?>)" class="cu" title="<?php echo L('click_to_select');?>">
		<td align='left' ><?php echo $r['title'];?></td>
		<td align='center'><?php echo $this->categorys[$r['catid']]['catname'];?></td>
		<td align='center'><?php echo format::date($r['inputtime']);?></td>
	</tr>
	 <?php }?>
	    </tbody>
    </table>
   <div id="pages"><?php echo $pages;?></div>
</div>
</div>
<style type="text/css">
 .line_ff9966,.line_ff9966:hover td{
	background-color:#FF9966;
}
 .line_fbffe4,.line_fbffe4:hover td {
	background-color:#fbffe4;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function select_list(obj,title,id,modelid) {
		var relation_ids = window.top.$('#relation').val();
		var sid = 'v'+modelid+id;
		if($(obj).attr('class')=='line_ff9966' || $(obj).attr('class')==null) {

			$(obj).attr('class','line_fbffe4');
			window.top.$('#'+sid).remove();

			if(relation_ids !='' ) {
				
				var r_arr = relation_ids.split(',');
				var newrelation_ids = '';
				for(var i=0;i<r_arr.length;i++){

					var n_n = r_arr[i].split('_');
					if(n_n[0] == modelid && n_n[1] == id) {
						r_arr.splice(i,1);
					}
				}

				newrelation_ids = r_arr.join(',');
				window.top.$('#relation').val(newrelation_ids);
			}
		} else {

			if(relation_ids != ''){

				var r_arr = relation_ids.split(",");
				var state = false;
				$.each(r_arr,function(m,d){
					var st = modelid+'_'+id;
					if(st == d){
						alert('已存在该信息！');
						state = true;
					}
				});
				if(state == true)
					return false;
			}

			$(obj).attr('class','line_ff9966');
			var str = "<li id='"+sid+"'>·<span>"+title+"</span><a href='javascript:;' class='close' onclick=\"remove_relation_n('"+sid+"',"+id+","+modelid+")\"></a></li>";
			window.top.$('#relation_text').append(str);
			if(relation_ids =='' ) {
				window.top.$('#relation').val(modelid+'_'+id);
			} else {
				relation_ids = relation_ids+','+modelid+'_'+id;
				window.top.$('#relation').val(relation_ids);
			}
		}
	}

	function modelChange($this){
		var modelId = $this.value;
		if(modelId != 0){			
			$("form > input[name=modelid]").eq(0).val(modelId);
			$.post('?m=content&c=content&a=public_columnList&pc_hash=<?php echo $_GET['pc_hash'];?>',{modelid:modelId},function(ax){

				var catidS = $(".explain-col > select[name=catid] > option:selected").val();
				if(typeof catidS != 'undefined'){
					$(".explain-col > select[name=catid]").eq(0).remove();					
				}
				$(".explain-col > input[name=keywords]").eq(0).before(ax);

			});

		}
	}	
//-->
</SCRIPT>
</body>
</html>
<?php

defined('IN_ADMIN') or exit('No permission resources.');$addbg=1;
include $this->admin_tpl('header','admin');
//原创文章
if($catid == 65){
    $original   = 1;
    $original_s = 0;
}else if($catid == 66){
    $original   = 0;
    $original_s = 1;
}else{
    $original   = 0;
    $original_s = 0;
}
?>
<script type="text/javascript">
<!--
	var charset = '<?php echo CHARSET;?>';
	var uploadurl = '<?php echo pc_base::load_config('system','upload_url')?>';
//-->
    var collect_type = 0;
</script>


<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>colorpicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>hotkeys.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content/content_new.js"></script>
<script type="text/javascript">var catid=<?php echo $catid;?></script>
<style type="text/css" media="screen">
    #selectS a,#renwuContentDiv a{
        text-decoration:none;
    }
    #selectS a div,#renwuContentDiv a div{
        border: 1px solid #D3D3D3;
        line-height: 26px;
        font-size: 14px;
        width: 80px;
        margin-left:8px;
        margin-top:6px;        
        text-align: center;
        display:inline-block;
    }
    .onMouseOverDiv{
        background-color: #D3D3D3;
    }

    #renwuDiv{    
        line-height:30px;
        height:30px;
    }
    #searchRenwu{       
        border:1px double #BCBCBC;
        display:inline-block;
        background-color:#F7F5F4;
        padding-bottom: 2px;
        text-align: center;
        width:60px;
    }
    #searchRenwu:hover{
        background-color:#BDC3FF;
    }
    #renwuDiv input{
        width:200px;
    }
    #renwuDiv input,#searchRenwu{
        line-height:24px;
        margin-left:8px;
    }
    
    #renwuContentDiv{
        border:1px solid #D3D3D3;
        line-height:30px;
        margin-left:6px;
        padding-bottom:6px;
        max-height:200px;
        overflow:auto;        
    }
    .displayNone{
        display:none;
    }
</style>
<form name="myform" id="myform" action="?m=content&c=content&a=add" method="post" enctype="multipart/form-data">
<div class="addContent">
<div class="crumbs"><?php echo L('add_content_position');?></div>
<div class="col-right">
    	<div class="col-1">
        	<div class="content pad-6">
<?php
if(is_array($forminfos['senior'])) {
 foreach($forminfos['senior'] as $field=>$info) {
	if($info['isomnipotent']) continue;
	if($info['formtype']=='omnipotent') {
		foreach($forminfos['base'] as $_fm=>$_fm_value) {
			if($_fm_value['isomnipotent']) {
				$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
			}
		}
		foreach($forminfos['senior'] as $_fm=>$_fm_value) {
			if($_fm_value['isomnipotent']) {
				$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
			}
		}
	}
 ?>
	<h6><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $info['name']?></h6>
	 <?php echo $info['form']?><?php echo $info['tips']?> 
<?php
} }
?>
<?php if($_SESSION['roleid']==1 || $priv_status) {?>
<h6><?php echo L('c_status');?></h6>
<span class="ib" style="width:90px"><label><input type="radio" name="status" value="99" checked/> <?php echo L('c_publish');?> </label></span>
<?php if($workflowid) { ?><label><input type="radio" name="status" value="1" > <?php echo L('c_check');?> </label><?php }?>
<?php }?>

         <?php
            if($catid == 38 || $catid==39){
        ?>
                <h6>背景样式</h6>
                <select name="info[style_value]">
                    <option value="0" <?php if(!$data['style_value'])echo 'selected';?>>选择背景样式</option>

        <?php
                foreach($styleList as $styleKey=>$styleInfo){
        ?>
                    <option value="<?php echo $styleInfo['style_id'];?>" <?php if($styleInfo['style_id'] == $data['style_value']){echo 'selected';}?>><?php echo $styleInfo['style_name']?></option>
                    
        <?php            
                }
         ?>
                </select>
         <?php       
            }
         ?>
          </div>
        </div>
    </div>
    <a title="展开与关闭" class="r-close" hidefocus="hidefocus" style="outline-style: none; outline-width: medium;" id="RopenClose" href="javascript:;"><span class="hidden">展开</span></a>
    <div class="col-auto">
    	<div class="col-1">
        	<div class="content pad-6">
<table width="100%" cellspacing="0" class="table_form">
	<tbody>	
<?php
if(is_array($forminfos['base'])) {
 foreach($forminfos['base'] as $field=>$info) {
	 if($info['isomnipotent']) continue;
	 if($info['formtype']=='omnipotent') {
		foreach($forminfos['base'] as $_fm=>$_fm_value) {

			if($_fm_value['isomnipotent']) {
				$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
			}
		}
		foreach($forminfos['senior'] as $_fm=>$_fm_value) {
			if($_fm_value['isomnipotent']) {
				$info['form'] = str_replace('{'.$_fm.'}',$_fm_value['form'],$info['form']);
			}
		}
	}
    if(is_array($info['form'])){
        foreach ($info['form'] as $k => $v) {
    ?>
    <tr>
      <th width="80" style="width:80px;"><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $v['name'] ?>
      </th>
      <td><?php echo $v['value']?>  <?php echo $info['tips']?></td>
    </tr>
    <?php } }else{ ?>
        <tr>
          <th width="80" style="width:80px;"><?php if($info['star']){ ?> <font color="red">*</font><?php } ?> <?php echo $info['name'] ?>
          </th>
          <td><?php echo $info['form']?>  <?php echo $info['tips']?></td>
        </tr>
    <?php }
 ?>
    <?php if($field == 'add_info'){ ?>
        <script type="text/javascript">
            collect_type = 1;
        </script>
    <?php }?>
<?php
} }
?>
    <tr>
        <th width="80">朝代选择</th>
        <td id="selectS">
        </td>
    </tr>
    <tr class="searchTr">
        <th width="80">人物搜索</th>
        <td id="renwuTd">
            <div id="renwuDiv">
                <input type="txt" name="renwuName" id="renwuName"  value="请输入人物名" /><a href="javascript:;"><div id="searchRenwu">人名搜索</div></a>
            </div>
        </td>
    </tr>
    
    <tr  class="renwuTr displayNone">
        <th width="80">人物选择</th>
        <td id="renwuTd">
            <div id="renwuContentDiv">
            </div>
        </td>
    </tr>
    <?php if(!empty($parent_arr)) { ?>
        <input type="hidden" name="info[level]" value="<?php echo ++$parent_arr['level'];?>">
        <input type="hidden" name="info[parent_id]" value="<?php echo $parent_arr['id'];?>">
        <input type="hidden" name="info[parent_id_all]" value="<?php echo $parent_arr['parent_id_all'].';'.$parent_arr['id'];?>">
    <?php } ?>
    </tbody></table>
                </div>
        	</div>
        </div>
        
    </div>
</div>

<div class="fixed-bottom">
	<div class="fixed-but text-c">
    <div class="button"><input value="<?php echo L('save_close');?>" type="submit" name="dosubmit" class="cu" style="width:145px;" onclick="refersh_window()"></div>
    <div class="button"><input value="<?php echo L('save_continue');?>" type="submit" name="dosubmit_continue" class="cu" style="width:130px;" title="Alt+X" onclick="refersh_window()"></div>
    <div class="button"><input value="<?php echo L('c_close');?>" type="button" name="close" onclick="refersh_window();close_window();" class="cu" style="width:70px;"></div>
      </div>
</div>
<input type="hidden" id="chaodaiId" name="info[chaodai_id]" value="" />
<input type="hidden" id="renwu_id" name="info[renwu_id]" value="" />
<input type="hidden" id="original" name="info[original]" value="<?php echo $original; ?>" />
<input type="hidden" id="original_s" name="info[original_s]" value="<?php echo $original_s; ?>" />
</form>

</body>
</html>

<script type="text/javascript">
$(function(){
    
    //页面朝代获取jquery
    $.get('/api.php?op=easyui&state=chaodai&pc_hash=<?php echo $_SESSION['pc_hash'];?>',function(data){

        $("#selectS").append(data);
        $("#selectS div").click(function(){

            var divClass   = $(this).attr("class");
            var chaodai_id = $("#chaodaiId").val();
            if(chaodai_id == ''){
                chaodai_id = new Array();
            }else{
                chaodai_id = chaodai_id.split(",");
            }
            var id = $(this).attr("id");
            var chaodaiId = id.split("_");
            if( typeof divClass == 'undefined' || divClass == ''){

                $(this).addClass("onMouseOverDiv");
                chaodai_id.push(chaodaiId[1]);
                var chaodai_id= chaodai_id.join(",");
                $("#chaodaiId").val(chaodai_id);
    
            }else{
                $(this).removeClass("onMouseOverDiv");
                var chaodaiIds = $("#chaodaiId").val();
                var chaodaiIds_list = chaodaiIds.split(",");
                for(var $i=0;$i<chaodaiIds_list.length;$i++){
                    if(chaodaiIds_list[$i] == chaodaiId[1]){    
                        chaodaiIds_list.splice($i,1);
                    }
                }
                var chaodai_id = chaodaiIds_list.join(",");
                $("#chaodaiId").val(chaodai_id);
            }
 
        });
    });
    
    $("#searchRenwu").click(function(){

        var chaodai_id = $("#chaodaiId").val();
        var renwuName  = $("#renwuName").val();
        var renwu_id   = $("#renwu_id").val();
        if(renwuName != '请输入人物名' && renwuName != '' ){

            var boxName = document.getElementsByName("info[chaodai_select]");
            for(var b=0;b<boxName.length;b++){

                if(boxName.item(b).checked){
                    var cState = b;
                }
            }            
            $.post('/api.php?op=easyui&chaodai_id='+chaodai_id+'&state=renwu&pc_hash=<?php echo $_SESSION['pc_hash'];?>',{"renwuId":renwu_id,"renwuName":renwuName,"cState":cState},function(dataRenwu){

                $(".renwuTr").removeClass("displayNone");
                /*
                if(dataRenwu == ''){
                    $("#renwuContentDiv").html("<a href='javascript:;'><div style='width:100px;'>没有找到哦！</div></a>");
                    return false;
                }else{
                    $("#renwuContentDiv").html(dataRenwu);
                }
                */

                if(dataRenwu){
                    var dataInfo = eval("("+dataRenwu+")");
                    //console.debug(dataRenwu);
                    var $renwuHtml = '';
                    for(var d=0;d<dataInfo.length;d++){
                        //console.debug(dataInfo[d].id+'----'+dataInfo[d].title);
                        if($("#contentDiv_"+dataInfo[d].id).length > 0)
                            continue;
                        $renwuHtml += '<a href="javascript:;"><div id="contentDiv_'+dataInfo[d].id+'">'+dataInfo[d].title+'('+dataInfo[d].name+')</div></a>';
                    }
                    $("#renwuContentDiv").append($renwuHtml);
                    if($("#contentDiv_null").length > 0){
                        //$("#contentDiv_null").remove();
                        $("#contentDiv_null").parent('a').remove();
                    }                    
                }else{
                    if($("#contentDiv_null").length > 0){
                        //$("#contentDiv_null").remove();
                        $("#contentDiv_null").parent('a').remove();
                    }
                    $("#renwuContentDiv").append("<a href='javascript:;'><div id='contentDiv_null' style='width:100px;'>没有找到哦！</div></a>");
                    return false;
                }
            }); 
            document.getElementsByTagName('BODY')[0].scrollTop=document.getElementsByTagName('BODY')[0].scrollHeight;
        }
    });
    
    $("#renwuName").focus(function(){
        if($(this).val() == '请输入人物名'){
            $(this).val('');
        }
    });
     $("#renwuName").blur(function(){
        if($(this).val() == ''){
            $(this).val('请输入人物名');
        }
     });

    //人物选择
    $("#renwuContentDiv a div").live('click',function(){

        var renwuDivClass = $(this).attr("class");
        var thisDivId  = $(this).attr("id").split('_');
        var renwu_id   = $("#renwu_id").val();
        if(renwu_id == ''){
            renwu_id = new Array();
        }else{
            renwu_id = renwu_id.split(",");
        }
        var thisId = $(this).attr('id');
        if((typeof renwuDivClass == 'undefined' || renwuDivClass =='') && thisId != 'contentDiv_null'){

            $(this).attr("class",'onMouseOverDiv');
            renwu_id.push(thisDivId[1]);
            var renwu_id = renwu_id.join(",");
            $("#renwu_id").val(renwu_id);                        
        }else{
            $(this).removeClass('onMouseOverDiv');
            
            for(var $j=0;$j<renwu_id.length;$j++){
                if(renwu_id[$j] == thisDivId[1]){    
                    renwu_id.splice($j,1);
                }
            }
            var renwu_id = renwu_id.join(",");
            $("#renwu_id").val(renwu_id);                       
        }
    });     
})
function linkage(val){
    $.post("?m=content&c=content&a=linkageQuery&pc_hash=<?php echo $_SESSION['pc_hash'];?>",{catid:val},function(data){
        $("#renw_id").html(data);
    });
}
<!--
//只能放到最下面
var openClose = $("#RopenClose"), rh = $(".addContent .col-auto").height(),colRight = $(".addContent .col-right"),valClose = getcookie('openClose');
$(function(){

	if(valClose==1){
		colRight.hide();
		openClose.addClass("r-open");
		openClose.removeClass("r-close");
	}else{
		colRight.show();
	}
	openClose.height(rh);
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({id:'check_content_id',content:msg,lock:true,width:'200',height:'50'}, 	function(){$(obj).focus();
	boxid = $(obj).attr('id');
	if($('#'+boxid).attr('boxid')!=undefined) {
		check_content(boxid);
	}
	})}});
	<?php echo $formValidator;?>

    //数据采集 @user xiong
    // $("#title").blur(function(){
    //     if(collect_type == 0){ return false; }
    //     var title = $(this).val();
    //     if(title != ''){
    //         $.post("?m=content&c=content&a=characteradd&pc_hash=<?php echo $_SESSION['pc_hash'];?>",{title:title},function(data){
    //             var msg =  $.parseJSON(data);
    //             var content = '';
    //             if($('.new_where').length > 0){
    //                 $('.new_where').remove();
    //             }
    //             $.each(msg.info,function(e,item){
    //                 content += '<tr class="new_where">';
    //                 content += '    <th width="80">  </th>';
    //                 content += '    <td>';
    //                 content += '        <input class="input-text" type="text" style="width:100px;" value="' + item.name + '" name="info[add_title][]">';
    //                 content += '        <input class="input-text" type="text" style="width:350px;" value="' + item.value + '" name="info[add_content][]">';
    //                 content += '        <span onclick="del_info(this)" style="font-weight:bold;color:red;">删除</span>';
    //                 content += '    </td>';
    //                 content += '</tr>';
    //             });
    //             if(content != ''){
    //                 $('#title').parent().parent().before(content);
    //             }
    //             CKEDITOR.instances.rw_jianjie.setData(msg.rw_jianjie); //赋值
    //             CKEDITOR.instances.content.setData(msg.content);
    //         });
    //     }
    // });

    //数据采集2 链接采集 @user xiong
    $('#click_collect_url').click(function(){
        if(collect_type == 0){ return false; }
        var url = $("#collect_url").val();
        if(url.length > 0){
            $.post("?m=content&c=content&a=characterurl&pc_hash=<?php echo $_SESSION['pc_hash'];?>",{url:url},function(data){
                var msg =  $.parseJSON(data);
                var content = '';
                if($('.new_where').length > 0){
                    $('.new_where').remove();
                }
                $.each(msg.info,function(e,item){
                    content += '<tr class="new_where">';
                    content += '    <th width="80">  </th>';
                    content += '    <td>';
                    content += '        <input class="input-text" type="text" style="width:100px;" value="' + item.name + '" name="info[add_title][]">';
                    content += '        <input class="input-text" type="text" style="width:350px;" value="' + item.value + '" name="info[add_content][]">';
                    content += '        <span onclick="del_info(this)" style="font-weight:bold;color:red;">删除</span>';
                    content += '    </td>';
                    content += '</tr>';
                });
                if(content != ''){
                    $('#title').parent().parent().before(content);
                }
                CKEDITOR.instances.rw_jianjie.setData(msg.rw_jianjie); //赋值
                CKEDITOR.instances.content.setData(msg.content);
            });
        }
    })

	
/*
 * 加载禁用外边链接
 */

	$('#linkurl').attr('disabled',true);
	$('#islink').attr('checked',false);
	$('.edit_content').hide();
	jQuery(document).bind('keydown', 'Alt+x', function (){close_window();});
})
document.title='<?php echo L('add_content');?>';
self.moveTo(-4, -4);
function refersh_window() {
	setcookie('refersh_time', 1);
}
openClose.click(
	  function (){
		if(colRight.css("display")=="none"){
			setcookie('openClose',0,1);
			openClose.addClass("r-close");
			openClose.removeClass("r-open");
			colRight.show();
		}else{
			openClose.addClass("r-open");
			openClose.removeClass("r-close");
			colRight.hide();
			setcookie('openClose',1,1);
		}
	}
)
    //链接采集数据
    function collect_url_content()
    {
        if(collect_type == 0){ return false; }
        var url = $("#collect_url").val();
        if(url.length > 0){
            $.post("?m=content&c=content&a=characterurl&pc_hash=<?php echo $_SESSION['pc_hash'];?>",{url:url},function(data){
                if(msg != ''){
                    var msg =  $.parseJSON(data);
                    var content = '';
                    if($('.new_where').length > 0){
                        $('.new_where').remove();
                    }
                    $.each(msg.info,function(e,item){
                        content += '<tr class="new_where">';
                        content += '    <th width="80">  </th>';
                        content += '    <td>';
                        content += '        <input class="input-text" type="text" style="width:100px;" value="' + item.name + '" name="info[add_title][]">';
                        content += '        <input class="input-text" type="text" style="width:350px;" value="' + item.value + '" name="info[add_content][]">';
                        content += '        <span onclick="del_info(this)" style="font-weight:bold;color:red;">删除</span>';
                        content += '    </td>';
                        content += '</tr>';
                    });
                    if(content != ''){
                        
                        if($("#add_where").length > 0){
                            $('#add_where').parent().parent().after(content);
                        }else{
                            if($("#title").length > 0){
                                $('#title').parent().parent().before(content);
                            }else if( ('#jltitle').length > 0){
                                $('#title').parent().parent().after(content);
                            }
                        }
                    }
                    if($('#rw_jianjie').length > 0){
                        UE.getEditor('rw_jianjie').setContent(msg.rw_jianjie);
                    }
                    if($('#zy_jianjie').length > 0){
                        UE.getEditor('zy_jianjie').setContent(msg.rw_jianjie);
                    }
                    UE.getEditor('content').setContent(msg.content);
                    // CKEDITOR.instances.rw_jianjie.setData(msg.rw_jianjie); //赋值
                    // CKEDITOR.instances.content.setData(msg.content);                    
                } else {
                    alert('采集失败');
                }
            });
        }
        return false;
    }
//-->
</script>

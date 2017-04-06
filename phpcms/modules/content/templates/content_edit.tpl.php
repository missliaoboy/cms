<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
//原创文章

if(!isset($data['renwu_id'])){
    $renwu_id = '';
}else
     $renwu_id = rtrim($data['renwu_id'],',');

if(isset($data['chaodai_id'])){
    if($data['chaodai_id'] == 0)
        $data['chaodai_id'] = '';
}else
    $data['chaodai_id'] = '';
$chaodai_id = rtrim($data['chaodai_id'],',');

?>

<style type="text/css">
    html,body{ background:#e2e9ea}
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
@charset "utf-8";
/* CSS Document */
body,ul,ol,h1,h2,h3,h4,h5,dl,dd,form,input,textarea,select,p{padding: 0; margin: 0;font-family: Arial,"Microsoft Yahei",sans-serif;font-size: 14px;}
input,textarea{margin:0;padding:0;border:none; outline:none;}
textarea{ resize:none; overflow:auto;}
em{ font-style:normal;}
ul,li { list-style: none;}
h1,h2,h3,h4,h5{margin:0; font-weight:normal;}
a,input{-webkit-tap-highlight-color:rgba(255,0,0,0);text-decoration:blink;}
img{ border:none; vertical-align:top;}

/*清浮动*/
.clearfix{*zoom:1;}
.clearfix:after{content: "";display: block;clear: both;}

.content_list_image{
    width: 900px;
    /*height: 600px;*/
    margin: 50px auto;
}
.content_list_image ul li{
    width: 876px;
    padding: 10px 12px;
    height: 250px;
    float: left;
}
.content_list_image ul li:nth-child(odd){
    background: #f8f8f8;
}
.content_list_image ul li:nth-child(even){
    background: #fff;
}
.picture{
    width: 234px;
    margin-right: 30px;
    float: left;
}
.picture em{
    width: 234px;
    height: 140px;
    float: left;
}
.picture em img{
    width: 100%;
    height: 100%;
}
.picture input{
    width: 232px;
    height: 30px;
    border: 1px solid #d2d2d2;
    float: left;
    margin-top: 4px;
    text-indent: 8px;
    color: #777;
}
.group{
    width: 612px;
    height: 32px;
    line-height: 32px;
    margin-bottom: 10px;
    float: left;
}
.info{
    width: 100px;
    color: #777;
    float: left;
}
.select input{
    width: 455px;
    height: 30px;
    float: left;
    text-indent: 6px;
    border: 1px solid #d0d0d0;
}
.group textarea{
    width: 555px;
    height: 90px;
    line-height: 20px;
    float: left;
    text-indent: 6px;
    border: 1px solid #d0d0d0;
}
</style>
<script type="text/javascript">
<!--
	var charset = '<?php echo CHARSET;?>';
	var uploadurl = '<?php echo pc_base::load_config('system','upload_url')?>';
//-->
</script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content_addtop.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>colorpicker.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>hotkeys.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>content/content_new.js"></script>

<script type="text/javascript">var catid=<?php echo $catid;?></script>
<form name="myform" id="myform" action="?m=content&c=content&a=edit" method="post" enctype="multipart/form-data">
<div class="addContent">
<div class="crumbs"><?php echo L('edit_content_position');?></div>
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
    <?php 
        if($field == 'add_info'){
            $json = !empty($forminfos['base']['info']) ? $forminfos['base']['info'] : '';
            if(is_array($json)){
                foreach ($json as $key => $value) {
    ?>
    <tr class="add_info">
        <th width="80"></th>
        <td>
            <input class="input-text" type="text" style="width:80px;" name="info[add_title][]" value="<?php echo $value->name;?>"> 
            <?php if($value->name == '主要成就'){ ?>
                <textarea name="info[add_content][]" style="width:350px;"><?php echo $value->value;?></textarea>
            <!-- <input class="input-text"  style="width:350px;" name="info[add_content][]" value="<?php echo $value->value;?>">   -->
            <?php }else{ ?>
                <input class="input-text"  style="width:350px;" name="info[add_content][]" value="<?php echo $value->value;?>"> 
            <?php } ?>
            <span onclick="del_info(this)" style="font-weight:bold;color:red;">删除</span>
        </td>
    </tr>
    <?php
                }
            }
        }
    ?>
    <?php 
        if($field == 'add_content'){
            $json = !empty($forminfos['base']['new_content']) ? $forminfos['base']['new_content'] : '';
            if(is_array($json)){
                foreach ($json as $key => $value) {
    ?>
    <tr class="add_content">
        <th width="80"></th>
        <td>
            <input class="input-text" type="text" style="width:100px;" name="info[add_title2][]" value="<?php echo $value->name;?>">
            <textarea name="info[add_content2][]" id="add_content<?php echo $key;?>" rows="6" cols="80" ><?php echo $value->value;?></textarea>
            <span onclick="del_info(this)" style="font-weight:bold;color:red;">删除</span>
        </td>
    </tr>
    <?php
                }
            }
        }
    ?>
<?php
} }
?>
<script type="text/javascript" style="height:300px;width:75%;">

<?php 
    $json = !empty($forminfos['base']['new_content']) ? $forminfos['base']['new_content'] : '';
    if(is_array($json)){
        foreach ($json as $key => $value) {
?>
        var add_content<?php echo $key;?> = UE.getEditor("add_content<?php echo $key;?>",{
        initialFrameWidth : 600,
        initialFrameHeight: 600
    });
<?php
        }
    }
?>
</script>

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
    <div class="button">
	<input value="<?php if($r['upgrade']) echo $r['url'];?>" type="hidden" name="upgrade">
	<input value="<?php echo $id;?>" type="hidden" name="id"><input value="<?php echo L('save_close');?>" type="submit" name="dosubmit" class="cu" onclick="refersh_window()"></div>
    <div class="button"><input value="<?php echo L('save_continue');?>" type="submit" name="dosubmit_continue" class="cu" onclick="refersh_window()"></div>
    <div class="button"><input value="<?php echo L('c_close');?>" type="button" name="close" onclick="refersh_window();close_window();" class="cu" title="Alt+X"></div>
      </div>
</div>
<input type="hidden" name="info[chaodai_id]" id="chaodaiId" value = "<?php echo $chaodai_id;?>" />
<input type="hidden" name="info[renwu_id]" id="renwu_id" value = "<?php echo $renwu_id;?>" />
<input type="hidden" id="original" name="info[original]" value="<?php echo $data['original']; ?>" />
<input type="hidden" id="original_s" name="info[original_s]" value="<?php echo $data['original_s']; ?>" />
</form>

</body>
</html>
<script type="text/javascript">

$(document).ready(function(){

    var chaodai_id = $("#chaodaiId").val();
    var renwu_id   = $("#renwu_id").val(); 
    $.post('/api.php?op=easyui&state=chaodai&pc_hash=<?php echo $_SESSION['pc_hash'];?>',function(dataList){
        
        $("#selectS").append(dataList);
        var chaodai_list = chaodai_id.split(",");
        for(var $z=0;$z<chaodai_list.length;$z++){

            $("#category_"+chaodai_list[$z]).addClass('onMouseOverDiv');
        }
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
    
    //刷开页面人物$.post访问
    var renwuName = '';
    toDealWith(chaodai_id,renwu_id,renwuName);
    //点击人物搜索
    $("#searchRenwu").click(function(){
        var chaodai_id = $("#chaodaiId").val();
        var renwu_id   = $("#renwu_id").val();
        var renwuName  = $("#renwuName").val();
        if(renwuName !='' && renwuName != '请输入人物名'){
            toDealWith(chaodai_id,renwu_id,renwuName);
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

    $("#renwuContentDiv div").live("click",function(){
        
        var id = $(this).attr("id");
        if(typeof id == 'undefined' || id==''){
            return false;
        }
        var renwuDivClass = $(this).attr("class");

        var thisDivId  = id.split('_');
        var renwu_id   = $("#renwu_id").val();
        if(renwu_id == ''){
            renwu_id = new Array();
        }else{
            renwu_id = renwu_id.split(",");
        }
        var thisId = $(this).attr('id');
        if((typeof renwuDivClass == 'undefined' || renwuDivClass =='') && thisId != 'contentDiv_null'){
        //if(typeof renwuDivClass == 'undefined' || renwuDivClass ==''){

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
});
var state = true;
function toDealWith(chaodai_id,renwu_id,renwuName){
    
    var boxName = document.getElementsByName("info[chaodai_select]");
    for(var b=0;b<boxName.length;b++){

        if(boxName.item(b).checked){

            var cState = b;
        }
    }
    //var cState = 
    //return false;
    $.post('/api.php?op=easyui&chaodai_id='+chaodai_id+'&state=renwu&pc_hash=<?php echo $_SESSION['pc_hash'];?>',{"renwuId":renwu_id,"renwuName":renwuName,"cState":cState},function(dataRenwu){

        //console.debug(dataRenwu);
        
        $(".renwuTr").removeClass("displayNone");
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
                $("#contentDiv_null").parent('a').remove();
            }            
        }else{
            //console.debug($("#contentDiv_null").length);
            if($("#contentDiv_null").length > 0){
                $("#contentDiv_null").parent('a').remove();
            }
            $("#renwuContentDiv").append("<a href='javascript:;'><div id='contentDiv_null' style='width:100px;'>没有找到哦！</div></a>");
        }

        var renwu_list = renwu_id.split(',');
        for(var $r=0;$r<renwu_list.length;$r++){
            $("#contentDiv_"+renwu_list[$r]).addClass("onMouseOverDiv");
        }

    })
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
	$.formValidator.initConfig({formid:"myform",autotip:true,onerror:function(msg,obj){window.top.art.dialog({content:msg,lock:true,width:'200',height:'50'}, 	function(){$(obj).focus();
	boxid = $(obj).attr('id');
	if($('#'+boxid).attr('boxid')!=undefined) {
		check_content(boxid);
	}
	})}});
	<?php echo $formValidator;?>
	
/*
 * 加载禁用外边链接
 */
	jQuery(document).bind('keydown', 'Alt+x', function (){close_window();});
})
document.title='<?php echo L('edit_content').addslashes($data['title']);?>';
self.moveTo(0, 0);
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
        var url = $("#collect_url").val();
        if(url.length > 0){
            $.post("?m=content&c=content&a=characterurl&pc_hash=<?php echo $_SESSION['pc_hash'];?>",{url:url},function(data){
                if(msg != ''){
                    var msg =  $.parseJSON(data);
                    var content = '';
                    $('.add_info').remove();
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

	function images($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$list_str = '';
		if($value) {
			$value = string2array(new_html_entity_decode($value));
			if(is_array($value)) {
				foreach($value as $_k=>$_v) {
				$list_str .= "
				<li id='image_{$field}_{$_k}'>
					<input type='hidden' name='{$field}_thumb[]' value='{$_v[thumb]}' >
					<div class='picture'>
						<em><img src='{$_v[url]}'></em>
						<input type='text' name='{$field}_url[]' value='{$_v[url]}' ondblclick='image_priview(this.value);'>
					</div>
					<!-- 标题 -->
					<div class='group'>
						<div class='info'><span>标题：</span></div>
						<div class='select'>
							<input type='text' name='{$field}_title[]' value='{$_v[title]}' placeholder='标题'> 
						</div>
					</div>
					<!-- ALT -->
					<div class='group'>
						<div class='info'><span>ALT：</span></div>
						<div class='select'>
							<input type='text' name='{$field}_alt[]' value='{$_v[alt]}' placeholder='ALT'> 
						</div>
					</div>
					<!-- URL -->
					<div class='group'>
						<div class='info'><span>跳转URL：</span></div>
						<div class='select'>
							<input type='text' name='{$field}_lurl[]' value='{$_v[lurl]}' placeholder='跳转URL'> 
						</div>
					</div>
					<!-- 内容 -->
					<div class='group'>
						<div class='info'><span>内容：</span></div>
						<textarea name='{$field}_intro[]'  value='{$_v[intro]}' style='width: 462px;' placeholder='内容...'>{$_v[intro]}</textarea>
					</div>
					<a href=\"javascript:remove_div('image_{$field}_{$_k}')\">".L('remove_out', '', 'content')."</a>
				</li>";
				}
			}
		} else {
			$list_str .= "<center><div class='onShow' id='nameTip'>".L('upload_pic_max', '', 'content')." <font color='red'>{$upload_number}</font> ".L('tips_pics', '', 'content')."</div></center>";
		}
		$string = '<input name="info['.$field.']" type="hidden" value="1">
		<fieldset class="blue pad-10">
        <legend>'.L('pic_list').'</legend>';
        $string .='<div class="content_list_image"><ul>';
		$string .= $list_str;
        $string .='</div></ul>';
		$string .= '<div id="'.$field.'" class="picList content_list_image"><ul></ul></div>
		</fieldset>
		<div class="bk10"></div>
		';
		if(!defined('IMAGES_INIT')) {
			$str = '<script type="text/javascript" src="statics/js/swfupload/swf2ckeditor.js"></script>';
			define('IMAGES_INIT', 1);
		}
		$authkey = upload_key("$upload_number,$upload_allowext,$isselectimage");
		$string .= $str."<div class='picBut cu'><a herf='javascript:void(0);' onclick=\"javascript:flashupload('{$field}_images', '".L('attachment_upload')."','{$field}',change_images,'{$upload_number},{$upload_allowext},{$isselectimage}','content','$this->catid','{$authkey}')\"/> ".L('select_pic')." </a></div>";
		return $string;
	}

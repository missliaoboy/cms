	function typeid($field, $value, $fieldinfo) {
		extract($fieldinfo);
		$setting = string2array($setting);
		if(!$value) $value = $setting['defaultvalue'];
		if($errortips) {
			$errortips = $this->fields[$field]['errortips'];
			$this->formValidator .= '$("#'.$field.'").formValidator({onshow:"",onfocus:"'.$errortips.'"}).inputValidator({min:1,onerror:"'.$errortips.'"});';
		}
		$usable_type 		= $this->categorys[$this->catid]['usable_type'];
		$usable_deam_type 	= $this->categorys[$this->catid]['usable_deam_type'];
		$usable_array = $usable_deam_array = array();
		if($usable_type) $usable_array = explode(',',$usable_type);
		if($usable_deam_type) $usable_deam_array = explode(',',$usable_deam_type);
		
		//获取站点ID
		if(intval($_GET['siteid'])){
			$siteid = intval($_GET['siteid']);
		}else{
			$siteid = $this->siteid;
		}
		$data2 = array();
		$type_data = getcache('type_content_'.$siteid,'commons');
		$type_deam = getcache('type_deam_'.$siteid,'commons');
		if($type_data) {
			foreach($type_data as $_key=>$_value) {
				if(in_array($_key,$usable_array)) $data[$_key] = $_value['name'];
			}
			if(!empty($usable_deam_type)){
				foreach ($usable_deam_array as $k => $val) {
					foreach($type_data as $_key=>$_value) {
						if($_value['type_deam_id'] == $val ) $data2[$val][$_key] = $_value['name'];
					}
				}
			}
		}
		$return = '';
		$this->db->table_name 		= $this->db->db_tablepre . 'type_deam';
		if(!empty($data2)){
			foreach ($data2 as $key => $val) {
				$return .= "<div style='margin:5px 0 0 0;border-bottom:1px dashed black;'><span style='font-weight:bold;font-size:15px;'>".$type_deam[$key]['name'].":</span>".form::checkbox($val,$value,'name="info['.$field.'][]" id="'.$field.'" '.$formattribute.' '.$css,L('copyfrom_tips')) ."</div>";
			}
		} else {
			return form::checkbox($data,$value,'name="info['.$field.'][]" id="'.$field.'" '.$formattribute.' '.$css,L('copyfrom_tips'));
		}
		return $return;
	}

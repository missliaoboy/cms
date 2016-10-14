	function typeid($field, $value) {
		$arr2 = array(); 
		if(!empty($value) && is_array($value)){
			foreach($value as $val){
				if($val != -99)$arr2[] = $val;
			}
			return implode(',',$arr2);
		}
		return $value;
	}

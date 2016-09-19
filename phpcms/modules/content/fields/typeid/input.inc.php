	function typeid($field, $value) {
		if(!empty($value) && is_array($value)){
			array_shift($value);
			return implode(',',$value);
		}
		return $value;
	}

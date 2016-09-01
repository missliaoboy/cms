	function images($field, $value) {
		//取得图片列表
		$pictures = $_POST[$field.'_url'];
		//取得图片说明
		$pictures_alt 		= isset($_POST[$field.'_alt']) ? $_POST[$field.'_alt'] : array();
		$pictures_lurl 		= isset($_POST[$field.'_lurl']) ? $_POST[$field.'_lurl'] : array();
		$pictures_intro 	= isset($_POST[$field.'_intro']) ? $_POST[$field.'_intro'] : array();
		$pictures_title   	= isset($_POST[$field.'_title']) ? $_POST[$field.'_title'] : array();
		$pictures_thumb   	= isset($_POST[$field.'_thumb']) ? $_POST[$field.'_thumb'] : array();
		$array = $temp = array();
		if(!empty($pictures)) {
			foreach($pictures as $key=>$pic) {
				$temp['url'] 	= $pic;
				$temp['alt'] 	= str_replace(array('"',"'"),'`',$pictures_alt[$key]);
				$temp['lurl'] 	= str_replace(array('"',"'"),'`',$pictures_lurl[$key]);
				$temp['intro'] 	= str_replace(array('"',"'"),'`',$pictures_intro[$key]);
				$temp['title'] 		= str_replace(array('"',"'"),'`',$pictures_title[$key]);
				$temp['thumb'] 		= str_replace(array('"',"'"),'`',$pictures_thumb[$key]);
				$array[$key] = $temp;
			}
		}
		$array = array2string($array);
		return $array;
	}

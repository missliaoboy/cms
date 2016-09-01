<?php
	defined('IN_PHPCMS') or exit('No permission resources.');
	pc_base::load_sys_class('model', '', 0);
	pc_base::load_app_func('util','content');
	define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
	//异步生成内容页
	class collect_html_curl_model extends model
	{
		private $category,$filename,$html,$url;
		private   $log_maxsize = 4;//日志文件最大的尺寸
		public $category_arr = array();
		public function __construct() {
			$this->filename = PHPCMS_PATH.'caches'.DIRECTORY_SEPARATOR."caches_hao123".DIRECTORY_SEPARATOR."caches_data".DIRECTORY_SEPARATOR."collect_html_curl.cache.php";
			$this->db_config = pc_base::load_config('database');
			$this->db_setting = 'default';
			$this->table_name = 'admin';
			$this->category = getcache('category_content_1','commons');
			$this->model = getcache('model','commons');
			parent::__construct();
		}

		/**
		 * curl_init 生成页面
		 * arr = array( 'modelid'=>xxx,'id'=>xxxx,xxxx);
		 */
		function html_now_set($arr)
		{
			if(empty($arr))return '';
			$url = APP_PATH.'api.php?op=batch_show';
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_POST, 1 );
			curl_setopt ( $ch, CURLOPT_HEADER, 0 );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, $arr );
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			curl_exec($ch);
			curl_close($ch); 
		}


		function unicode_encode($name)  
		{  
		    $name = iconv('UTF-8', 'UCS-2', $name);  
		    $len = strlen($name);  
		    $str = '';  
		    for ($i = 0; $i < $len - 1; $i = $i + 2)  
		    {  
		        $c = $name[$i];  
		        $c2 = $name[$i + 1];  
		        if (ord($c) > 0)  
		        {    // 两个字节的文字  
		            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);  
		        }  
		        else  
		        {  
		            $str .= $c2;  
		        }  
		    }  
		    return $str;  
		}
		public function html_set_now($modelid,$id)
		{
			$this->table_name = 'lishi_'.$this->model[$modelid]['tablename'];
			$r = $this->get_one(array('id'=>$id));
			if(!empty($r)){
				if($r['islink']) continue;
				$this->table_name = $this->table_name.'_data';
				$r2 = $this->get_one(array('id'=>$id));
				if($r2) $r = array_merge($r,$r2);
				//判断是否为升级或转换过来的数据
				if(!$r['upgrade']) {
					$urls = $this->url->show($r['id'], '', $r['catid'],$r['inputtime'],$r['prefix']);
				} else {
					$urls[1] = $r['url'];
				}
				$arr  = $this->get_category($r['catid'],$r['typeid']);
				if(!empty($arr)){
					$urls[0]    = str_replace('renwu', $arr, $urls[0]);
					$urls[1]    = str_replace('renwu', $arr, $urls[1]);
				}
				$this->html->show($urls[1],$r,0,'edit',$r['upgrade']);
			}
		}

		public function batch_show()
		{
			// 手机站生成
			// if(isset($_POST['arr']) && !empty($_POST['arr'])){
			// 	$_POST['arr'] = stripslashes($_POST['arr']);
			// 	$arr = json_decode($_POST['arr'],true);
			// 	if(isset($arr['url']) && isset($arr['id']) && isset($arr['key'])){
			// 		$ch = curl_init();
			// 		curl_setopt($ch,CURLOPT_URL,$arr['url']);
			// 		curl_setopt($ch,CURLOPT_POST,1);
			// 		curl_setopt($ch,CURLOPT_HEADER,0);
			// 		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			// 		curl_setopt($ch,CURLOPT_POSTFIELDS,$arr);
			// 		curl_setopt($ch,CURLOPT_TIMEOUT,1);
			// 		curl_exec($ch);
			// 		curl_close($ch);
			// 	}
			// }
			$ip = ip();
			$id 	= isset($_POST['id']) ? $_POST['id'] : '';
			$modelid 	= isset($_POST['modelid']) ? intval($_POST['modelid']) : '';
			if(!empty($id) && $modelid){
				$table_name 	= $this->model[$modelid]['tablename'];
				if(!empty($table_name)){
					$this->url = pc_base::load_app_class('url','content');
					$this->html = pc_base::load_app_class('html','content');
					$ids 	= explode(',', $id);
					foreach ($ids as $key => $value) {
						$this->html_set_now($modelid,$value);
					}
				}
			}
		}

		public function get_category($id,$typeid)
		{
			if($id != 1)return '';
			if(empty($this->category_arr)){
				$this->table_name = 'lishi_type'; 
				$this->category_arr   = $this->select('');
			}
			foreach ($this->category_arr as $key => $value) {
				if($value['typeid'] == $typeid){
					return trim($value['description']);
				}
			}
		}
	}
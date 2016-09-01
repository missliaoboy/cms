<?php
	//baidu sitemap提交处理类
	defined('IN_PHPCMS') or exit('No permission resources.');
	define('UPLOAD_PATH_HOST', 'http://data2.jianglishi.cn');
	define('UPLOAD_PATH', '/data1/webserver/web/pic.jianglishi.cn/');

	pc_base::load_sys_class('model', '', 0);
	class baidu_sitemap_model extends model
	{
		private  $api_url = 'http://data.zz.baidu.com/urls?site=www.jianglishi.cn&token=lozKSBRv4luzug3D';
		private  $url = array();

		public function __construct() {
			$this->db_config  = pc_base::load_config('database');
			$this->db_setting = 'default';
			$this->table_name = 'sitemap';
			$this->categorys  = getcache('category_content_1','commons');
			parent::__construct();
		}


		//数组重组
		public function start($url)
		{
			if(empty($url) || !is_array($url))return false;
			foreach ($url as $key => $value) {
				$arr2 			= $this->get_one(array('url'=>$value));		
				if(!empty($arr2)){
					if($arr2['type'] != 1){
						$arr['type'] 	= 2;
						$arr['value'] 	= $value;
						$arr['id'] 		= $arr['id'];	
						$this->url[] 	= $arr; 
					}else{
						continue;
					}
				} else {
					$arr['type'] 	= 1;
					$arr['value'] 	= $value;
					$this->url[] 	= $arr;
				}
			}
			$this->_post();
		}

		public function add($type,$arr,$msg='')
		{
			if($arr['type'] == 2){
				if($type == 1){ //success
					$this->update(array('type'=>1),array('id'=>$arr['id']));
				} else {
					$this->update(array('msg'=>$msg),array('id'=>$arr['id']));
				}
			} else {
				$add 				= array();
				$add['type'] 		= intval($type);
				$add['url'] 		= $arr['value'];
				$add['add_time']	= time();
				$add['msg']			= $msg;
				$this->insert($add);
			}
		}

		//url向百度提交
		public function _post()
		{
			if(empty($this->url))return false;
			foreach ($this->url as $key => $value) {
				if(empty($value) || empty($value['value']))continue;
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$this->api_url);
				curl_setopt($ch,CURLOPT_POST,true);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_POSTFIELDS,$value['value']);
				curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: text/plain'));
				$result = curl_exec($ch);
				$arr = json_decode($result);
				if( !empty($arr) ){
					if( $arr->success == 1 ){
						$this->add(1,$value,$result);
					}else{
						$this->add(2,$value,$result);
					}
				}				
			}
		}

		//提交失败数据处理
		public function _error()
		{
			$arr = $this->select(' type = 1 ','*');
			foreach ($arr as $key => $value) {
				$array['id'] 	= $value['id'];
				$array['value'] = $value['url'];
				$array['type'] 	= 2;
				$this->url[] 	= $array;
			}
			$this->_post();
		}
	}
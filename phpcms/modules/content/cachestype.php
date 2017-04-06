<?php
set_time_limit(300);
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
//定义在单独操作内容的时候，同时更新相关栏目页面
define('RELATION_HTML',true);

pc_base::load_sys_class('form','',0);
pc_base::load_app_func('util');
pc_base::load_sys_class('format','',0);
pc_base::load_app_class('admin','admin',0);
class cachestype {
	private $db,$priv_db;
	public $siteid,$categorys,$deam_type,$cache_type;
	public function __construct() {
		$this->db = pc_base::load_model('content_model');
		$this->url = pc_base::load_app_class('url', 'content');
		$this->categorys 	= getcache('category_content_1','commons');
		$this->deam_type 	= getcache('type_deam_1','commons');
		$this->cache_type 	= getcache('type_content_1','commons');
	}


	public function deam()
	{
		if($_POST['with_set']){
			if($_POST['id']){
				$deam = $_POST['id'];
				include admin::admin_tpl('cachestype_with_set');	
			}
		} else {
			$cache_deam 	= $this->deam_type;
			include admin::admin_tpl('caches_deam');			
		}
	}

	public function type_with()
	{
		$typeid = isset($_POST['typeid']) ? intval($_POST['typeid']) : '';
		if($typeid > 0){

			$arr = array();
			foreach ($this->cache_type as $key => $value) {
				if($value['type_deam_id'] == $typeid){
					$arr[] = $value['typeid'];
				}
			}
			$return 	= $this->deam_type_with($typeid);;
			$return['idall'] = $arr;
			if($return)exit(json_encode($return));
		}
	}

	public function type_child_with()
	{
		$typeid = isset($_POST['typeid']) ? intval($_POST['typeid']) : '';
		if($typeid > 0){
			$return = $this->set_type_with($typeid);
			if($return)exit(json_encode($return));
		} 
	}

	private function set_type_with($typeid)
	{
		set_time_limit(0);
		$time_start 	= microtime(true);
		$value 	= $this->cache_type[$typeid];
		$html = pc_base::load_app_class('html', 'content');
		$value['path'] 	= $value['prefix_url'];
		$value['lastname'] 	= str_replace('组', '', $arr_type['name']);
		$value['level'] 	= 2;
		$str 		= PHPCMS_PATH.trim($value['path'],'/');  //静态文件生成的地址
		$template 	= $value['template']; //文件生成使用的模板
		if(!empty($template)){
			$page = 1;
			do {
				if($page == 1 ){
					$file 	= $str.'/index.html';
				} else {
					$file 	= $str.'/list_'.$page.'.html';
				}
				$html->category_type($value,$page,$file,$template);
				$page++;
				$total_number = MAX_PAGES;
			} while ($page <= MAX_PAGES);
			$return = array();
			$return['title'] 	= '【'.$this->deam_type[$value['type_deam_id']]['name'].'】【' .$value['name'] . '】';
			$return['time'] 	= round(microtime(true) - $time_start,3);
			$return['url'] 		= APP_PATH .trim($value['path'],'/') . '/index.html';
			$return['type'] 	= 2;
			return $return;			
		}
		return '';
	}

	//类别组页面生成
	private function deam_type_with($typeid)
	{
		set_time_limit(0);
		$time_start 	= microtime(true);
		$value 	= $this->deam_type[$typeid];
		$html = pc_base::load_app_class('html', 'content');
		$value['path'] 	= $value['prefix_url'];
		$value['lastname'] 	= str_replace('组', '', $arr_type['name']);
		$value['level'] 	= 1;
		$str 		= PHPCMS_PATH.trim($value['path'],'/');  //静态文件生成的地址
		$template 	= $value['template']; //文件生成使用的模板
		if($template){
			$page = 1;
			do {
				if($page == 1 ){
					$file 	= $str.'/index.html';
				} else {
					$file 	= $str.'/list_'.$page.'.html';
				}
				$html->category_type($value,$page,$file,$template);
				$page++;
				$total_number = MAX_PAGES;
			} while ($page <= MAX_PAGES);
			$return = array();
			$return['title'] 	= '【' .$value['name'] . '】';
			$return['time'] 	= round(microtime(true) - $time_start,3);
			$return['url'] 		= APP_PATH  .trim($value['path'],'/') . '/index.html';
			$return['type'] 	= 2;
			return $return;			
		}
		return $return;
	}

	public function type_show()
	{
		$typeid 	= isset($_GET['typeid']) && $_GET['typeid'] > 0 ? intval($_GET['typeid']) : '';
		if($typeid > 0){
			$this->set_type_with($typeid);
		}
	}

	public function deam_show()
	{
		$id 	= isset($_GET['id']) && $_GET['id'] > 0 ? intval($_GET['id']) : '';
		if($id > 0){
			$this->deam_type_with($id);
		}
	}
}
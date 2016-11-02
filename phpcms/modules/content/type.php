<?php
set_time_limit(300);
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
//定义在单独操作内容的时候，同时更新相关栏目页面
define('RELATION_HTML',true);

pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form','',0);
pc_base::load_app_func('util');
pc_base::load_sys_class('format','',0);

class type extends admin {
	private $db,$priv_db;
	public $siteid,$categorys;
	public function __construct() {
		parent::__construct();
		$this->db = pc_base::load_model('content_model');
		$this->url = pc_base::load_app_class('url', 'content');
		$this->siteid = $this->get_siteid();
		$this->categorys = getcache('category_content_'.$this->siteid,'commons');
		//权限判断
		if(isset($_GET['catid']) && $_SESSION['roleid'] != 1 && ROUTE_A !='pass' && strpos(ROUTE_A,'public_')===false) {
			$catid = intval($_GET['catid']);
			$this->priv_db = pc_base::load_model('category_priv_model');
			$action = $this->categorys[$catid]['type']==0 ? ROUTE_A : 'init';
			$priv_datas = $this->priv_db->get_one(array('catid'=>$catid,'is_admin'=>1,'action'=>$action));
			if(!$priv_datas) showmessage(L('permission_to_operate'),'blank');
		}
	}

	public function type_list()
	{
		if ($_POST['dosubmit']) {
			if( $_POST['modelid'] ){
				$c = pc_base::load_model('content_model');
				$c->set_model($_POST['modelid']);
				$info = array();
				$ids = explode('|', $_POST['id']);
				if(is_array($ids)) {
					$table_name 	= $c->table_name;
					foreach($ids as $id) {
						$typeid_arr 	= $_POST['typeid'];
						$c->table_name = $table_name;
						$arr = $c->get_one(array('id'=>$id));
						if(!empty($typeid_arr)){
							$typeid_arr2 	= explode(',', $arr['typeid']);
							$new_arr 	= array_merge($typeid_arr,$typeid_arr2);
							$new_arr 	= array_unique($new_arr);
						}
						if(!empty($new_arr)){
							$typeid 	= implode(',', $new_arr);
							$c->update(array('typeid'=>$typeid),array('id'=>$id));
						}
					}
				}
				showmessage(L('success'), '', '', 'type_push');
			} else {
				showmessage("模型id丢失", '', '', 'type_push');
			}
		} else {
			$ids = explode('|', $_REQUEST['id']);
			if(count($ids) >= 1){
				$modelid = $_REQUEST['modelid'] > 0 ? intval($_REQUEST['modelid']) :1;
				$c = pc_base::load_model('content_model');
				$c->set_model($modelid);
				$usable_deam_type 	= array();
				foreach( $this->categorys as $key=>$value )
				{
					if($value['modelid'] == $modelid){
						if($value['usable_deam_type']){
							$usable = explode(',', $value['usable_deam_type']);
							$usable_deam_type = array_merge($usable,$usable_deam_type);
						}
					}
				}
				$usable_deam_type = array_unique($usable_deam_type);
				$table_name 	= $c->table_name;
				$c->table_name = $table_name;
				$arr = $c->get_one(array('id'=>$ids[0]));
				$arr_type 	= $arr['typeid'] ? explode(',', $arr['typeid']) : '';
			}
			$type 		= getcache('type_content','commons');
			$type_deam 	= getcache('type_deam_'.$this->siteid,'commons');
			include $this->admin_tpl('push_type_list');
		}
	}

}
?>
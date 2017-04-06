<?php
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_class('admin','admin',0); 
class with_set
{
	private $db;
	public function __construct()
	{
		$this->db = pc_base::load_model('content_model');
	}

	public function index()
	{
		set_time_limit(0);
		$this->db->table_name 	= $this->db->db_tablepre .'keyword_config';
		$arr4	= array();
		if(!empty($_POST))
		{
			foreach ($_POST['catid'] as $key => $value) {
				if($value >0){
					$arr 	= $this->db->get_one(array('catid'=>$value));
					$arr2 	= array();
					$arr2['num'] 	= isset($_POST['num'][$key]) ? intval($_POST['num'][$key]) : 0;
					$arr2['modelid'] 	= isset($_POST['modelid'][$key]) ? intval($_POST['modelid'][$key]) : 0;
					if(!isset($_POST['now'])){
						if(!empty($arr)){
							$this->db->update($arr2,array('catid'=>$value));
						} else {
							$arr2['catid'] 		= intval($value);
							$arr2['add_time']	= time();
							$this->db->insert($arr2,true);
						}						
					} else {
						if($arr2['num'] > 0){
							$arr3['num'] 	 = $arr2['num'];
							$arr3['modelid'] = $arr2['modelid'];
							$arr3['catid'] 	 = $value;
							$arr4[] 	 = $arr3;
						}
					}
				}
			}
			if(!isset($_POST['now'])){
				showmessage('修改成功',HTTP_REFERER);
				exit;
			} else {
				include admin::admin_tpl('keywords_html_set');
			}
		} else {
			$category 	= getcache('category_content_1','commons');
			$data  		= $this->db->select(); 		
			$arr 		= array();
			foreach ($data as $key => $value) {
				$arr[$value['catid']] = $value;
			}
			include admin::admin_tpl('keywords_set_with_day');
		}
	}

	//给有生成需求的 栏目分配生成id
	public function get_catid_id()
	{
		if( !empty($_POST['catid']) && !empty($_POST['modelid']) && !empty($_POST['num']) && !empty($_POST['key']) && !empty($_POST['time']) ){
			$catid 	= intval($_POST['catid']);
			$modelid 	= intval($_POST['modelid']);
			$num 	= intval($_POST['num']);
			$key 	= $_POST['key'];
			$time 	= $_POST['time'];
			$model 	= getcache('model','commons');
			if( $time + 5 * 60 > time() ){  //时间验证
				$new_system = pc_base::load_config('system'); //引入配置文件
				$key1 		= md5($new_system['with_set_key'] . $time);
				if( $key == $key1 ){
					$this->db->table_name = $this->db->db_tablepre . $model[$modelid]['tablename'];
					$arr 	= $this->db->select(" catid = '".$catid."' and status = 2 ",' id ','0,'.$num,' rand() ');
					// $arr 	= $this->db->select(" catid = '".$catid."' and status = 2 ",' id ','0,'.$num*5);
					// $arr 	= array_values($arr);
					// shuffle($arr);
					// $arr 	= array_slice($arr, 0,$num);
					if(!empty($arr))exit(json_encode($arr));
				}
			}
		}
	}


	public function ctwh_type()
	{
		set_time_limit(0);
		$type_arr 		= array(35,36,37);
		$this->db->table_name = $this->db->db_tablepre . 'type_deam';
		$deam_type 		= $this->db->select();
		$arr 	= array();
		foreach ($deam_type as $key => $value) {
			$arr[$value['id']] = $value; 
		}
		$new_arr 	= array();
		$cache_type 	= getcache('type_content_1','commons');
		$html = pc_base::load_app_class('html', 'content');
		$time 	= time();
		$new_system = pc_base::load_config('system'); //引入配置文件
		foreach ($cache_type as $key => $value) {
			$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
			$count 	= $this->db->count($value['typeid']." in (typeid) ");
			if( in_array($value['type_deam_id'], $type_arr) )
			{
				$arr2['typeid'] = $value['typeid'];
				$arr2['time'] 	= $time;
				$arr2['key'] 	= md5($time . $new_system['type_with_set_key'] . $value['typeid']);
				$new_arr[] 	= $arr2;
			}
		}
		if(!empty($new_arr)){
			include admin::admin_tpl('type_set_with_day');
		}
	}

	public function ctwh_type_with()
	{
		set_time_limit(0);
		$time_start 	= microtime(true);
		if(!$_POST['typeid'] || !$_POST['key'] || !$_POST['time'])exit(json_encode(array('msg'=>'出错了1111','type'=>1)));
		if( $_POST['time'] + 30*60 > time() ){
			$new_system = pc_base::load_config('system'); //引入配置文件
			$key 	= md5($_POST['time'] . $new_system['type_with_set_key'] . $_POST['typeid'] );
			if($key == $_POST['key']){
				$html = pc_base::load_app_class('html', 'content');
				$cache_type 	= getcache('type_content_1','commons');
				if(isset($cache_type[$_POST['typeid']])){
					$this->db->table_name = $this->db->db_tablepre . 'type_deam';
					$value 	= $cache_type[$_POST['typeid']];
					$arr_type = $this->db->get_one(array('id'=>$value['type_deam_id']));
					$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
					$count 	= $this->db->count($value['typeid']." in (typeid) ");
					$value['path'] 	= $arr_type['prefix_url'].'/';
					$value['lastname'] 	= str_replace('组', '', $arr_type['name']);
					$value['catid'] 	= 83;
					$str = PHPCMS_PATH.$value['path']; 
					if(ceil($count/10) > 15){
						$template = "type_list2";
						$page = 1;
						do {
							if($page == 1 ){
								$file 	= $str.string_split($value['name']).'_list_index.html';
							} else {
								$file 	= $str.string_split($value['name']).'_list_'.$page.'.html';
							}
							$html->category_type($value,$page,$file,$template);
							$page++;
							$total_number = MAX_PAGES;
						} while ($page <= MAX_PAGES);
					} else {
						$template = "type_list";
						$file 	= $str.'list_index.html';
						$html->category_type($value,$page,$file,$template);
					}
					$return = array();
					$return['title'] 	= '【' .$arr_type['name'] . '】'.$value['name'];
					$return['time'] 	= round(microtime(true) - $time_start,3);
					$return['url'] 		= APP_PATH . $value['path'] . string_split($value['name']).'_list_index.html';
					$return['type'] 	= 2;
					exit(json_encode($return));
				}
			}
		}
		exit(json_encode(array('msg'=>'出错了','type'=>1)));
	}

	//传统文化 民族类别生成操作
	public function nation()
	{
		set_time_limit(0);
		$type_arr 		= array(34);
		$deam_type 		= getcache('type_deam_1','commons');
		$new_arr 	= array();
		$cache_type 	= getcache('type_content_1','commons');
		$html = pc_base::load_app_class('html', 'content');
		$time 	= time();
		$new_system = pc_base::load_config('system'); //引入配置文件
		foreach ($cache_type as $key => $value) {
			$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
			$count 	= $this->db->count($value['typeid']." in (typeid) ");
			if( in_array($value['type_deam_id'], $type_arr) )
			{
				$arr2['typeid'] = $value['typeid'];
				$arr2['time'] 	= $time;
				$arr2['key'] 	= md5($time . $new_system['type_with_set_key'] . $value['typeid']);
				$new_arr[] 	= $arr2;
			}
		}
		if(!empty($new_arr)){
			include admin::admin_tpl('type_nation_with_day');
		}
	}

	public function ctwh_nation_with()
	{
		set_time_limit(0);
		$time_start 	= microtime(true);
		if(!$_POST['typeid'] || !$_POST['key'] || !$_POST['time'])exit(json_encode(array('msg'=>'出错了1111','type'=>1)));
		// if( $_POST['time'] + 30*60 > time() ){
			$new_system = pc_base::load_config('system'); //引入配置文件
			$key 	= md5($_POST['time'] . $new_system['type_with_set_key'] . $_POST['typeid'] );
			if($key == $_POST['key']){
				$html = pc_base::load_app_class('html', 'content');
				$cache_type 	= getcache('type_content_1','commons');
				$deam_type 		= getcache('type_deam_1','commons');
				if(isset($cache_type[$_POST['typeid']])){
					$this->db->table_name = $this->db->db_tablepre . 'type_deam';
					$value 	= $cache_type[$_POST['typeid']];
					$arr_type = $this->db->get_one(array('id'=>$value['type_deam_id']));
					$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
					$count 	= $this->db->count($value['typeid']." in (typeid) ");
					$value['path'] 	= Hanzi2PinYin(str_replace('组', '', $arr_type['name'])).'/'.Hanzi2PinYin($value['name']).'/';
					$value['lastname'] 	= str_replace('组', '', $arr_type['name']);
					$str = PHPCMS_PATH.$value['path']; 
					$template = "type_nation";
					$file 	= $str.'index.html';
					$html->category_type($value,$page,$file,$template);
					$this->app_html_set($value,1,$template);
					$return = array();
					$return['title'] 	= '【' .$arr_type['name'] . '】'.$value['name'];
					$return['time'] 	= round(microtime(true) - $time_start,3);
					$return['url'] 		= APP_PATH . $value['path'] . 'index.html';
					$return['type'] 	= 2;
					exit(json_encode($return));
				}
			}
		// }
		exit(json_encode(array('msg'=>'出错了','type'=>1)));
	}
	public function ctwh_nation_category_with()
	{
		set_time_limit(0);
		$time_start 	= microtime(true);
		if(!$_POST['typeid'] || !$_POST['key'] || !$_POST['time'])exit(json_encode(array('msg'=>'出错了1111','type'=>1)));
		$new_system = pc_base::load_config('system'); //引入配置文件
		$key 	= md5($_POST['time'] . $new_system['type_with_set_key'] . $_POST['typeid'] );
		if($key == $_POST['key']){
			$html = pc_base::load_app_class('html', 'content');
			$cache_type 	= getcache('type_content_1','commons');
			$deam_type 		= getcache('type_deam_1','commons');
			if(isset($cache_type[$_POST['typeid']])){
				$value 	= $cache_type[$_POST['typeid']];
				$arr_type = $deam_type[$value['type_deam_id']];
				$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
				$count 	= $this->db->count($value['typeid']." in (typeid) ");
				$value['path'] 	= Hanzi2PinYin(str_replace('组', '', $arr_type['name'])).'/'.Hanzi2PinYin($value['name']).'/'.string_split($cache_type[$_POST['typeid2']]['name']).'_';
				$value['lastname'] 	= str_replace('组', '', $arr_type['name']);
				$value['typeid2'] 	= $_POST['typeid2'];
				$value['typeid2_name'] 	= $cache_type[$_POST['typeid2']]['name'];
				$str = PHPCMS_PATH.$value['path']; 
				$template 	= "type_nation_list";
				$page 		= 1;
				do {
					if($page == 1 ){
						$file 	= $str.'list_index.html';
					} else {
						$file 	= $str.'list_'.$page.'.html';
					}
					$html->category_type($value,$page,$file,$template);
					$page++;
					$total_number = MAX_PAGES;
				} while ($page <= MAX_PAGES);
				$this->app_html_set($value,2,$template);
				$return = array();
				$return['title'] 	= '【' .$arr_type['name'] . '】【'.$value['name'].'】'.$cache_type[$_POST['typeid2']]['name'];
				$return['time'] 	= round(microtime(true) - $time_start,3);
				$return['url'] 		= APP_PATH . $value['path'] . 'list_index.html';
				$return['type'] 	= 2;
				exit(json_encode($return));
			}
		}
		exit(json_encode(array('msg'=>'出错了','type'=>1)));		
	}



	public function gs5000()
	{
		$this->db->table_name = $this->db->db_tablepre .'wenhua';
		$count 	= $this->db->count(' status=66 and catid=99 ');
		$num 	= ceil($count/50);
		for($i=0;$i<$num;$i++){
			$this->db->table_name = $this->db->db_tablepre . 'wenhua';
			$sql = "select * from lishi_wenhua where status=66 and catid=99 order by id desc limit 0,50";
			$result 	= $this->db->query($sql);
			while ( $row = mysql_fetch_assoc($result) ) {
				$content 	= collect_curl_collect($row['collect_url']);
				$content2 	= '';
				preg_match('/<td valign=top id=articlebody>([\S\s]+?)<\/td>/', $content, $get_content);
				if(!empty($get_content)){
					$type_image 	= true;
					$type 	= strripos( $row['collect_url'], '/');
					$type 	= $type !== false ? substr($row['collect_url'],0,$type+1) : '';
					$content2 = collect_content_with($get_content[1],$row['title'],$type);
					preg_match_all('/<img[^>]+?src=[\'|\"]([^>]+?)[\'|\"][^>]+?>/', $content2, $imagelist);
					if($imagelist[1])
					{
						$arr = array();
						foreach ($imagelist[1] as $key => $value) 
						{
							$image 	= collect_getImage($value);
							if($image){
								$arr[] = $image;
							} else {
								$type_image = false;
							}
						}
					}
					if(!$type_image)continue;
					$content2 = str_replace($imagelist[1],$arr,$content2);
				}
				if(!empty($content2)){
					$data 	= array();
					$data['content'] 	= @addslashes(trim($content2));
					$this->db->table_name = $this->db->db_tablepre . 'wenhua_data';
					$this->db->update($data,array('id'=>$row['id']));
					$this->db->table_name = $this->db->db_tablepre . 'wenhua';
					$this->db->update(array('status'=>1),array('id'=>$row['id']));
				}
			}
		}
	}


	public function app_html_set($type_arr,$level = 0,$template='show_ctwh')
	{
		//手机版生成
		$arr 	= array();
		$arr['data']	= !empty($type_arr) ? json_encode($type_arr) : '';
		$arr['template']= $template;
		$arr['level']	= $level;
		$arr['key']		= md5(APP_HOST_URL.$template);
		_post(APP_HOST_URL."/api.php?op=type_set",$arr);
	} 


	public function get_url()
	{
		$arr   = array();
		$url   = isset($_POST['url']) ? trim($_POST['url']) : '';
		if(!$url)exit(json_encode(array('url'=>APP_PATH)));
		$arr2  = explode("#", $url);
		$url   = $arr2[0];
		$repay = getcache('repay','hao123');
		if(isset($repay[$url]) && is_array($repay[$url]))
		{
			$url_all = array();
			foreach ($repay[$url] as $key => $value) 
			{
				if(!empty($value['lurl']))$url_all[] = $value['lurl'];
			}		
			if(!$url_all)$url_all[] = APP_PATH;
			shuffle($url_all);
			exit(json_encode(array('url'=>$url_all[0])));
		}
	}

	public function ceshi_cache()
	{
		pc_base::load_sys_class('redis','',0);
		$redis 	= new RedisWith(1);
		// $redis->redis->del('ceshi');
		// $redis->redis->set('ceshi',222);
		// $arr 	= $redis->redis->zadd('ceshi2',array('modelid'=>1,'catid'=>12,'id'=>1) );
		echo '<pre>';
		// var_dump($arr);
		// echo '<hr>';
		$arr = $redis->redis->lrange('jianglishi_html_set',0,-1);
		// echo $redis->redis->llen('ceshi');
		// echo '<hr>';
		// $arr 	= $redis->redis->get('ceshi2');
		print_r($arr);
		// $arr3 	= $redis->redis->lpop('ceshi');
		// print_r($arr3);
		exit;
	}
			public function ceshi()
		{
			echo getImage('http://img1.160.com/soft/1/e6/screenshot_4414_8359.png');
			
		}
}

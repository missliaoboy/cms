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
		// pc_base::load_app_func('global','admin');
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
					// $arr2['keywords_num'] 	= isset($_POST['keywords_num'][$key]) ? intval($_POST['keywords_num'][$key]) : 0;
					// $arr2['today_num'] 		= isset($_POST['today_num'][$key]) ? intval($_POST['today_num'][$key]) : 0;
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
					$arr 	= $this->db->select(" catid = '".$catid."' and status = 2 ",' id ','0,'.$num);
					if(!empty($arr))exit(json_encode($arr));
				}
			}
		}
	}


	public function with_image()
	{
		set_time_limit(0);
		$this->db->table_name = $this->db->db_tablepre.'news';
		$arr = $this->db->count();
		$num = ceil($arr/50);
		if($num > 0){
			for($i=0;$i<$num;$i++){
				$sql 	= "select n.id,n.thumb,d.content from jm_news n left join jm_news_data d on n.id=d.id where n.ya_type = 0 order by n.id asc limit ".($i*50).',50';
				$result = $this->db->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					// if(!empty($row['thumb'])){
					// 	$thumb = str_replace('http://www.woaijiemi.com/', PHPCMS_PATH, $row['thumb']);
					// 	if(!is_dir(dirname($thumb))){
					// 		mkdir(dirname($thumb),0777,true);
					// 	}
					// 	chmod(dirname($thumb),0777);
					// 	$new = watermark2($thumb,$thumb,1);
					// }
					preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $row['content'], $arr);
					if(!empty($arr[3])){
						foreach ($arr[3] as $key => $value) {
							$thumb = str_replace('http://www.woaijiemi.com/', PHPCMS_PATH, $value);
							if(!is_dir(dirname($thumb))){
								mkdir(dirname($thumb),0777,true);
							}
							chmod(dirname($thumb),0777);
							$new = watermark($thumb,$thumb,1);
						}
					}
					$this->db->table_name = $this->db->db_tablepre.'news';
					$this->db->update(array('ya_type'=>1),array('id'=>$row['id']));
				}
			}
		}
	}


	public function with_image2()
	{
		set_time_limit(0);
		$this->db->table_name = $this->db->db_tablepre.'news';
		$arr = $this->db->count(' catid = 25 and id > 207865');
		$num = ceil($arr/50);
		if($num > 0){
			for($i=0;$i<$num;$i++){
				$sql 	= "select id,collect_url from jm_news where catid = 25 and id > 207865 order by id asc limit ".($i*50).',50';
				$result = $this->db->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					if(empty($row))continue;
					$content 	= file_get_contents($row['collect_url']);
					$encode = mb_detect_encoding($content, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5')); 
					if($encode != 'UTF-8'){
					    $content    = iconv("GBK", "UTF-8//IGNORE", $content);
					}
					preg_match('/<title>([^>]+?)<\/title>/',$content, $title);
					if(isset($title[1]) && !empty($title[1])){
						$title2 = explode('_', $title[1]);
						$title[1] = $title2[0];
						$this->db->table_name = $this->db->db_tablepre.'news';
						$this->db->update(array('title'=>@addslashes(trim($title[1]))),array('id'=>$row['id']));
					}
				}
			}
		}
	}

	function data_original()
	{
		set_time_limit(0);
		$this->db->table_name = $this->db->db_tablepre.'news_data';
		$arr = $this->db->count(' content like "%data-original%"');
		$num = ceil($arr/50);
		if($num > 0){
			for($i=0;$i<$num;$i++){
				$sql 	= "select * from jm_news_data where content like '%data-original%' order by id asc limit ".($i*50).',50';
				$result = $this->db->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					if(empty($row))continue;
					$content = preg_replace('/(<img[^>]*data-original=[\"|\'][^>]*[\"|\'] src=[\"|\'][^>]*[\"|\'][^>]*)>/', '', $row['content']);
					$content = preg_replace('/<p[^>]*><\/p>/', '', $content);
					$this->db->table_name = $this->db->db_tablepre.'news_data';
					$this->db->update(array('content'=>@addslashes($content)),array('id'=>$row['id']));
				}
			}
		}
	}


	public function next_old($catid,$num)
	{
		if($catid > 0 && $num > 0){
			$html       = pc_base::load_app_class('html', 'content');
            $get_url    = pc_base::load_app_class('url', 'content');
			$sql 	= ' select n.*,d.* from jm_news n left join jm_news_data d on n.id = d.id where today_type = 0 and status != 99 and catid ='.$catid." order by rand() limit ".$num;
			$result = $this->db->query($sql);
			while ( $row = mysql_fetch_assoc($result) ) {
				$rand 		= mt_rand(0,3600);
				if(empty($row['prefix'])){
					$row['prefix'] = uniqid();
				}
	            $urls       = $get_url->show($row['id'], 0, $row['catid'],$row['inputtime'],$row['prefix'],$row,'add');
	            if(!empty($urls)){
	            	$news_arr 	= array();
	            	$news_arr['url'] 	= APP_PATH.trim($urls[1],'/');
	            	$news_arr['status'] = 99;
	            	$news_arr['updatetime'] = time() - $rand;
	            	$news_arr['prefix'] = $row['prefix'];
	                $this->db->table_name   = $this->db->db_tablepre .'news';
	                $this->db->update($news_arr,array('id'=>$row['id']));
	            }
	            $html->show($urls[1],$row,0);
			}
		}
	}
	public function keywords_edit()
	{
		$id = intval($_POST['id']);
		if($id > 0){
			$key = $_POST['name'];
			$value = $_POST['value'];
			if($key != 'title'){
				$value = str_replace(array('，',' '), ',', $value);
				$value = trim($value,',');
			}
			if(!empty($key)){
				$this->db->table_name = $this->db->db_tablepre . 'news';
				$this->db->update(array($key=>$value,'updatetime'=>time()),array('id'=>$id));
			}
		}
	}

	public function baidu_sitemap(){
		$url = $_GET['url'];
		$baidu_sitemap_model = pc_base::load_model('baidu_sitemap_model');
		$baidu_sitemap_model->start(array($url));
	}

	//将要生成的类别id传递到模板静态页面 ajax生成页面
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
		$cache_type 	= getcache('type_content','commons');
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
				$cache_type 	= getcache('type_content','commons');
				if(isset($cache_type[$_POST['typeid']])){
					$this->db->table_name = $this->db->db_tablepre . 'type_deam';
					$value 	= $cache_type[$_POST['typeid']];
					$arr_type = $this->db->get_one(array('id'=>$value['type_deam_id']));
					$this->db->table_name 	= $this->db->db_tablepre . 'wenhua';
					$count 	= $this->db->count($value['typeid']." in (typeid) ");
					$value['path'] 		= Hanzi2PinYin(trim($arr_type['name'],'组')).'/'.Hanzi2PinYin($value['name']).'/';
					$value['lastname'] 	= trim($arr_type['name'],'组');
					$str = PHPCMS_PATH.$value['path']; 
					if(ceil($count/10) > 15){
						$template = "type_list2";
						$page = 1;
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
					} else {
						$template = "type_list";
						$file 	= $str.'list_index.html';
						$html->category_type($value,$page,$file,$template);
					}
					$return = array();
					$return['title'] 	= '【' .$arr_type['name'] . '】'.$value['name'];
					$return['time'] 	= round(microtime(true) - $time_start,3);
					$return['url'] 		= APP_PATH . $value['path'] . 'list_index.html';
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

}

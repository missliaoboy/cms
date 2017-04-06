<?php
set_time_limit(0);
defined('IN_PHPCMS') or exit('No permission resources.');
//模型缓存路径
define('CACHE_MODEL_PATH',CACHE_PATH.'caches_model'.DIRECTORY_SEPARATOR.'caches_data'.DIRECTORY_SEPARATOR);
pc_base::load_app_func('util','content');
pc_base::load_app_class('admin','admin',0); 
class basan extends model
{
	private $category,$collect_list_end,$collect_content,$collect_list_url,$content,$model,$modelid,$url_error;
	private   $log_maxsize = 4;//日志文件最大的尺寸
	protected $collect_page; //采集最近页数
	protected $url = 'http://www.chawh.net';

	public function __construct() {
		$this->db_config = pc_base::load_config('database');
		$this->db_setting = 'default';
		$this->table_name = 'admin';
		$this->model    = getcache('model','commons');
		parent::__construct();
		$this->category = getcache('category_content_1','commons');
		$this->catid = '95';
		$this->modelid = '';
	}

	// function index($page=0)
	// {
	// 	echo getImage('http://www.mzb.com.cn/res/Home/1205/3(12).jpg');
	// 	exit;
	// 	$this->collect_page 	= $page;
	// 	$arr[] 	= array('http://www.83133.com/qiqutupian/');
	// 	// $arr[109] = array('http://www.chawh.net/wh/cdcs/'); //茶风茶俗
	// 	// $arr[110] = array('http://www.chawh.net/wh/cccd/'); //茶艺茶道
	// 	// $arr[111] = array('http://www.chawh.net/cj/cjdg/'); //茶具文化
	// 	// $arr[112] = array('http://www.chawh.net/wh/cmgd/'); //茶马古道
	// 	// $arr[113] = array('http://www.chawh.net/wh/crcs/'); //名人与茶
	// 	// $arr[114] = array('http://www.chawh.net/wh/csch/','http://www.chawh.net/wh/ctsk/','http://www.chawh.net/wh/cydl/','http://www.chawh.net/wh/cgcj/'); //文学与茶
	// 	$this->collect_list_end = $this->url_with($arr);
	// 	$this->collect_category_url();
	// }
	//url处理
	public function url_with($url)
	{
		$arr  = array();
		if(is_array($url) && $url)
		{
			foreach ($url as $key => $value) 
			{
				foreach ($value as $k => $val) 
				{
					$content 	= collect_curl_collect($val);
					preg_match('/<a[^>]*href=[\'|\"]([^>]+?)[\'|\"][^>]*>[^>]*尾页[^>]*<\/a>/',$content,$end);
					if($end[1]){
						$arr[$key][] = "http://www.83133.com/" . ltrim($end[1],'/'); 
					} else {
						log_content($value."采集末页出错了");
					}
				}
			}
			return $arr;
		}
	}

	//采集url提取
	public function collect_category_url()
	{
 		if(empty($this->collect_list_end))exit;
		foreach ($this->collect_list_end as $key => $value) {
			if(!empty($value)){
				foreach ($value as $k => $v_arr) {
					if(empty($v_arr))continue;
					$file 	= basename($v_arr);
					$filename 	= explode('.', $file);
					$max 		= explode('-', $filename[0]);
					$page_max   = end($max);
					array_pop($max);
					$new_val 		= $this->collect_page > 0 ? $this->collect_page : intval($page_max);
					for($i=$new_val;$i>0;$i--){
						$new_url 	= dirname($v_arr).'/'.implode('-', $max).'-'.$i.'.'.$filename[1];
						$content 	= collect_curl_collect($new_url);
						preg_match('/<div class="page-list pics-list mt5">([\S\s]+?)<div class="test mt15">/', $content, $zcontent);
						preg_match_all('/<li><a href="([^>]+?)" target="_blank"><img src="[^>]+?" data-original="([^>]+?)"[^>]*><span>[^>]+?<\/span><\/a><\/li>/', $zcontent[1], $list);
						log_content($list);
						if( $list[1] ){
							$num = count($list[1]);
							for ($j=$num;$j>0;$j--){
								$show = $j - 1;
								$this->table_name = $this->db_tablepre."lieqi";
								$insert_wenhua			= array();
								if(!empty($list[1])){
									$insert_wenhua['thumb'] 		= @addslashes(trim($list[2][$show]));  //采集标题
									$insert_wenhua['collect_url'] = trim($list[1][$show]); //采集url
								}
								$wenhua 	= $this->get_one(array('collect_url'=>$insert_wenhua['collect_url'],'thumb'=>''));
								if(!empty($wenhua)){
									$this->update(array('thumb'=>trim($list[2][$show])),array('id'=>$wenhua['id']));
									continue;
								} else {
									continue;
								}
								exit;
								$insert_wenhua['inputtime'] 	= time();
								$insert_wenhua['updatetime'] 	= time();
								$insert_wenhua['status'] 		= 66;
								$insert_wenhua['catid'] 		= $key;
								$insert_wenhua['username'] 	= 'admin';

								if(!empty($insert_wenhua)){
									$this->table_name = $this->db_tablepre."cbk";
									$id 	= $this->insert($insert_wenhua,true);
									if($id > 0){
										$this->table_name = $this->db_tablepre."cbk_data";
										$this->insert(array('id'=>$id));
										$this->table_name 	= $this->db_tablepre . 'hits';
										$hits 		= array();
										$hits['hitsid'] 		= 'c-'.$this->modelid.'-'.$id;
										$hits['catid'] 			= $key;
										$hits['views']			= 0;
										$hits['yesterdayviews'] = 0;
										$hits['dayviews'] 		= 0;
										$hits['weekviews'] 		= 0;
										$hits['monthviews'] 	= 0;
										$hits['modelid'] 		= $this->modelid;
										$hits['id'] 			= $id;
										$hits['updatetime'] 	= time();
										$this->insert($hits);											
									}
								}else { 
									log_content($new_url. "：第二级！！采集失败");
									continue;
								}
							}
						} else {
							log_content($new_url. "：采集失败");
						}
					}
				}
			}
		}
	}

	public function index()
	{
		$this->table_name = $this->db_tablepre . "lieqi";
		$sql = " select count(*) num from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.status=11 ";
		$this->query($sql);
		$arr 	= $this->fetch_array();
		$count 		= $arr[0]['num'];
		$num 		= ceil($count/50);
		$id 		= '';
		for ( $i=0; $i<$num; $i++ ) {
			$this->table_name 	= $this->db_tablepre.'lieqi';  	//指定url存储的临时表
			if($id){
				$id_where = " and n.id > " . $id;
			}
			$sql 	= " select n.id,n.title,n.collect_url,n.url_error,data.content from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.status=11 ".$id_where." limit 0,50";
			$result = $this->query($sql);
			while ( $row = mysql_fetch_assoc($result) ) {
				$id 		= $row['id'];
				if( $row['url_error'] ) {	
					list($all,$data) = $this->collect_content_with($row['url_error'],$row['title']);
					$data['content'] = @addslashes( trim($row['content'],'[page]') . '[page]' . $data['content']);
				} else {
					list($all,$data) = $this->collect_content_with($row['collect_url'],$row['title']);
					$data['content'] = @addslashes($data['content']);
				}
				$data['content'] = trim($data['content'],'[page]');
				$lieqi 	= array();
				if($all['url_error']){
					$lieqi['url_error'] = $all['url_error'];
				} else {
					$lieqi['status'] 	= 22;
				}
				
				$this->table_name = $this->db_tablepre."lieqi";
				$this->update($lieqi,array('id'=>$row['id']));
				$this->table_name = $this->db_tablepre."lieqi_data";
				$this->update($data,array('id'=>$row['id']));
			}
		}
	}

	//采集
	public function collect_content_with($url,$title,$catid,$id)
	{
		$this->url_error 	= $this->content = ''; //错误url、主题内容初始化
		$url 		= trim($url);
		$arr 		= $data = array();
		$arr2 		= pathinfo($url);
		$get_content 	= trim($this->content_with($url,$title));
		if($this->url_error){
			$arr['url_error'] 	= $this->url_error;
		}
		// $get_content 	= trim($this->content_with($url,$title));
		if(!empty($get_content)){
			$data['content'] 	= $get_content;
		}
		preg_match('/<meta name=[\"|\']description[\"|\'] content=[\"|\']([^>]+?)[\"|\'][^>]*>/i', $this->content,$description);
		if(!empty($description)){
			$arr['description'] = @addslashes(trim($description[1]));
		} else if(!empty($data['content'])) {
			$arr['description'] = @addslashes(mb_substr(strip_tags($data['content']),0,180,'utf-8'));
		}
		if(empty($arr['description'])){
			$title 		= explode('—', $arr['description']);
			$arr['description'] = @addslashes(trim(html_entity_decode(end($title))));
		}
		preg_match('/<meta name=[\"|\']keywords[\"|\'] content=[\"|\']([^>]+?)[\"|\'][^>]*>/i', $this->content,$keywords);
		if(!empty($keywords)){
			$arr['keywords'] = trim($keywords[1]);
			$title 		= explode('—', $arr['keywords']);
			$arr['keywords'] = @addslashes(trim(html_entity_decode(end($title))));
		}
		return array($arr,$data);
	}


	//获取文章的主题内容
	public function content_with($url,$title)
	{
		$content2 	= collect_curl_collect($url);
		if(!$content2){
			$arr = get_headers($url);
			var_dump(strpos($arr[0],'404'));
			if(strpos($arr[0],'404') === false){
				$this->url_error = $url;
			} else {
				print_r($arr);
				$this->url_error = '';	
			}
		}
		if( empty($this->content) ) $this->content = $content2;  //用于 description 和 keywords 提取
		preg_match('/<div class="reads">([\S\s]+?)<\/div>/i', $content2, $type); //获取文章主题内容获取
		if(!$type){
			preg_match('/<div class="art-main mt10" id="art_main">([\S\s]+?)<div class="art-main mt10">/i', $content2, $type); //获取文章主题内容获取
		}
		if(!$type){
			preg_match('/<div class="art-main mt10" id="art_main">([\S\s]+?)<\/div>/i', $content2, $type); //获取文章主题内容获取
		}
		// preg_match('/<p[^>]*>([\S\s]*)<\/p>/i', $type[1], $type);
		// if(!$type)preg_match('/<p[^>]*>([\S\s])<\/p>/i', $type[1], $type);
		if(empty($content2))return '';
		$content 	= html_entity_decode(str_replace(array('&nbsp;'),'',$type[0]));
		$get_content 	= collect_content_with($content,$title);
		$end = @addslashes(str_replace(array("\n","\r","\r\n","  "), '', $get_content));
		$arr4 	= pathinfo($url); //采集链接
		preg_match('/<a[^>]*href=[\'|\"]([^>]+?)[\'|\"][^>]*>[^>]*下一页[^>]*<\/a>/i', $content2,$list);
		if($list){
			$filename	= explode('_', $arr4['filename']);
			$arr3 	= parse_url($list[1]);  //下一页采集链接
			$arr2 	= parse_url($url);
			if( !$arr3['host'] ) {
				if(substr($list[1], 0,1) == '/'){
				// 路径不带有主域名
					$list[1] 		= $arr2['scheme'] . "://".$arr2['host'].$list[1];
				} else {
					if(substr($url, -1) == '/'){ // 以/做分割，分页
						echo $list[1] 		= $arr2['scheme'] . "://" . $arr2['host'] . $arr2['path'] . $list[1];
					} else {
						if(strpos($list[1],'javascript') === false){
							$list[1]		= $arr4['dirname'].'/'.$list[1];
						}
					}
				}
			}
			$arr3 	= parse_url($list[1]);
			if($arr4['filename'] > 0){
				$filename[0] = dirname($url);
			}
			if(!empty($arr4['filename']) && strpos($list[1],$filename[0]) !== false ){
				if($arr3['host']){
					$get_content .= '[page]' . $this->content_with($list[1],$title);
				} else {
					$get_content .= '[page]' . $this->content_with($arr4['dirname'].'/'.$list[1],$title);
				}
			}			
		}
		return $get_content;
	}


	public function content_img()
	{
		$this->table_name = $this->db_tablepre . "lieqi";
		$sql = " select count(*) num from ".$this->table_name."  where thumb like '%83133%'";
		$this->query($sql);
		$arr 	= $this->fetch_array();
		$count 		= $arr[0]['num'];
		$num 		= ceil($count/50);
		for( $i=0 ; $i<$num ; $i++ )
		{
			$this->table_name = $this->db_tablepre . 'lieqi';
			$sql 	= " select * from ".$this->table_name." where thumb like '%83133%' limit 0,50";
			$result = $this->query($sql);
			while ( $row = mysql_fetch_assoc($result) ) 
			{
				$catdir = $this->category[$row['catid']]['parentid'] == 0 ? $this->category[$row['catid']]['catdir']: $this->category[$this->category[$row['catid']]['parentid']]['catdir'].'/'.$this->category[$row['catid']]['catdir'];
				$path 	= 'uploadfile/' . $catdir . '/'.date('Y-m',time()).'/'.date('d',time()) . '/';
				$thumb 	= getImage($row['thumb'],$row['collect_url'],$path);
				$this->table_name = $this->db_tablepre . "lieqi";
				$this->update(array('thumb'=> $thumb),array('id'=>$row['id']));
			}
		}
	}
}
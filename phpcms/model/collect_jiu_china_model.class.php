<?php
	defined('IN_PHPCMS') or exit('No permission resources.');
	pc_base::load_sys_class('model', '', 0);
	//http://www.china.com.cn/aboutchina/zhuanti/zgjwh/node_7028313.htm  传统文化-酒文化
	class collect_jiu_china_model extends model
	{
		private $category,$collect_list_end,$collect_content,$collect_list_url,$content,$model;
		private   $log_maxsize = 4;//日志文件最大的尺寸
		protected $collect_page; //采集最近页数
		protected $url = '';
		public function __construct() {
			$this->db_config = pc_base::load_config('database');
			$this->db_setting = 'default';
			$this->table_name = 'admin';
			$this->model    = getcache('model','commons');
			parent::__construct();
			$this->category = getcache('category_content_1','commons');
		}

		//
		function collect_start($page=0)
		{
			set_time_limit(0);
			$url 	=  'http://www.china.com.cn/aboutchina/zhuanti/zgmh09/node_7059674.htm';
			$content 	= collect_curl_collect($url);
			$end 		= strrpos($url, '/');
			$this->url 	= $path 		= substr($url,0,$end+1);
			$content 	= preg_replace('/<a href="([^>]+?)" target="_blank" class="f12_000000">([^>]+?)<\/a>/', '<a href="'.$path.'$1" target="_blank" class="f12_000000">$2</a>', $content);
			preg_match_all('/<a href="([^>]+?)" target="_blank" class="f12_000000">([^>]+?)<\/a>/', $content, $list);
			$this->collect_list_end['89']	= $list[1];
			$this->collect_category_url();
		}

		//采集url提取
		public function collect_category_url()
		{

			if(empty($this->collect_list_end))continue;
			foreach ($this->collect_list_end as $key => $value) {
				if(!empty($value)){
					foreach ($value as $k => $v_arr) {
						if(empty($v_arr))continue;
						$content 	= collect_curl_collect($v_arr);
						preg_match('/<td align="center">([\S\s]+?)<\/table>/', $content, $zcontent);
						preg_match_all('/<a[^>]*href=[\"|\']([^>]+?)[\"|\'] target="_blank" class="[^>]+?">([^>]*)<\/a>/', $zcontent[1], $list);
						if( $list[1] ){
							$num = count($list[1]);
							for ($j=$num;$j>0;$j--){
								$show = $j - 1;
								$this->table_name = $this->db_tablepre."wenhua";
								$insert_wenhua			= array();
								if(!empty($list[1])){
									$insert_wenhua['title'] 		= @addslashes(trim($list[2][$show]));  //采集标题
									$insert_wenhua['collect_url'] = $this->url . trim($list[1][$show]); //采集url
								}
								$wenhua 	= $this->get_one(array('collect_url'=>$insert_wenhua['collect_url']));
								if(!empty($wenhua)){
									continue;
								}
								$insert_wenhua['inputtime'] 	= time();
								$insert_wenhua['updatetime'] 	= time();
								$insert_wenhua['status'] 		= 1;
								$insert_wenhua['catid'] 		= $key;
								$insert_wenhua['username'] 	= 'admin';
								if(!empty($insert_wenhua)){
									$this->table_name = $this->db_tablepre."wenhua";
									$id 	= $this->insert($insert_wenhua,true);
									if($id > 0){
										$this->table_name = $this->db_tablepre."wenhua_data";
										$this->insert(array('id'=>$id));
										$this->table_name 	= $this->db_tablepre . 'hits';
										$hits 		= array();
										$hits['hitsid'] 		= 'c-17-'.$id;
										$hits['catid'] 			= $key;
										$hits['views']			= 0;
										$hits['yesterdayviews'] = 0;
										$hits['dayviews'] 		= 0;
										$hits['weekviews'] 		= 0;
										$hits['monthviews'] 	= 0;
										$hits['modelid'] 		= 17;
										$hits['id'] 			= $id;
										$hits['updatetime'] 	= time();
										$this->insert($hits);											
									}
								}else { 
									$this->log_content($new_url. "：第二级！！采集失败");
									continue;
								}
							}
						} else {
							$this->log_content($new_url. "：采集失败");
						}
					}
				}
			}
		}

		//采集内容提取
		public function collect_category_content($url='')
		{	
			set_time_limit(0);
			$this->table_name 	= $this->db_tablepre .'wenhua';
			$num 		= $this->count(' catid = 99 and status=66 ');
			$c_num 		= ceil($num/50);
			for($i=0;$i<$c_num;$i++){
				$sql 	= " select d.*,data.content from " . $this->db_tablepre ."wenhua d left join " . $this->db_tablepre . "wenhua_data data on d.id=data.id where d.status = 66 and d.catid = 99 and data.content='' order by d.id asc limit 0,50";
				$this->table_name 	= $this->db_tablepre .'wenhua';
				$result = $this->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					if( empty($row['collect_url']) || !empty($row['content']) )continue;
					list($wenhua,$wenhua_data) = $this->collect_content_with($row['collect_url'],$row['title'],$row['catid'],$row['id']);
					if(!empty($wenhua)){
						$this->table_name = $this->db_tablepre.'wenhua';
						$this->update($wenhua,array('id'=>$row['id']));
					}
					if(!empty($wenhua_data)){
						$this->table_name = $this->db_tablepre.'wenhua_data';
						$this->update($wenhua_data,array('id'=>$row['id']));
					}
				}
			}
		}
		public function collect_content_with($url,$title,$catid,$id)
		{
			$url 		= trim($url);
			$arr 		= $data = array();
			$arr2 		= pathinfo($url);
			$get_content 	= @addslashes(trim($this->content_with($url,$title)));
			// $get_content 	= trim($this->content_with($url,$title));
			if(!empty($get_content)){
				$data['content'] 	= $get_content;
			}
			preg_match('/<meta name=[\"|\']description[\"|\'] content=[\"|\']([^>]+?)[\"|\'][^>]*>/', $this->content,$description);
			if(!empty($description)){
				$arr['description'] = @addslashes(trim($description[1]));
			} else if(!empty($data['content'])) {
				$arr['description'] = @addslashes(mb_substr(strip_tags($data['content']),0,180,'utf-8'));
			}
			if(empty($arr['description'])){
				$title 		= explode('—', $arr['description']);
				$arr['description'] = @addslashes(trim(html_entity_decode(end($title))));
			}
			preg_match('/<meta name=[\"|\']keywords[\"|\'] content=[\"|\']([^>]+?)[\"|\'][^>]*>/', $this->content,$keywords);
			if(!empty($keywords)){
				$arr['keywords'] = trim($keywords[1]);
				$title 		= explode('—', $arr['keywords']);
				$arr['keywords'] = @addslashes(trim(html_entity_decode(end($title))));
			}
			return array($arr,$data);
		}

		public function content_with($url,$title)
		{
			$content2 	= collect_curl_collect($url);
			if( empty($this->content) ) $this->content = $content2;
			preg_match('/<td[^>]*id=articlebody[^>]*>([\S\s]+?)<\/body>/', $content2, $type);
			if(!$type)preg_match('/<div[^>]*id="artibody"[^>]*>([\S\s]+?)<\/div>/', $content2, $type);
			if(empty($content2))return '';
			$content 	= html_entity_decode(str_replace(array('&nbsp;'),'',strtolower($type[1])));
			$get_content 	= collect_content_with($content,$title,'');
			$end = @addslashes(str_replace(array("\n","\r","\r\n","  "), '', $get_content));
			$arr2 	= pathinfo($url);

			preg_match('/<a[^>]*href=[\'|\"]([^>]+?)[\'|\"][^>]*>[^>]*下一页[^>]*<\/a>/', $content2,$list);
			$filename	= explode('_', $arr2['filename']);
			if(!empty($arr2['filename']) && strpos($list[1],$filename[0]) !== false ){
				$arr3 	= parse_url($list[1]);
				if($arr3['host']){
					$get_content .= '[page]' . $this->content_with($list[1],$title);
				} else {
					$get_content .= '[page]' . $this->content_with($arr2['dirname'].'/'.$list[1],$title);
				}
			}
			return $get_content;
		}


		public function content_img()
		{
			$this->table_name = $this->db_tablepre . "wenhua";
			$sql = " select count(*) num from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where  n.catid = 99 and n.status=66 and n.thumb_type=0 and data.content like '%<img%'";
			$this->query($sql);
			$arr 	= $this->fetch_array();
			$count 		= $arr[0]['num'];
			$num 		= ceil($count/50);
			for( $i=0 ; $i<$num ; $i++ )
			{
				$this->table_name = $this->db_tablepre . 'wenhua';
				$sql 	= " select data.content,n.id,n.catid,n.thumb,n.collect_url from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.catid = 99 and n.status=66 and n.thumb_type=0 and data.content like '%<img%' limit 0,50";
				$result = $this->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) 
				{
					preg_match_all('/<img[^>]*src=[\'|\"]([\S\s]+?)[\'|\"][^>]*>/', $row['content'], $imageall);
					if($imageall[1])
					{
						// $host   = parse_url($row['collect_url']);
						// $http 	= $host['scheme'] . '://' . $host['host'];
						$catdir = $this->category[$row['catid']]['parentid'] == 0 ? $this->category[$row['catid']]['catdir']: $this->category[$this->category[$row['catid']]['parentid']]['catdir'].'/'.$this->category[$row['catid']]['catdir'];
						$path 	= 'uploadfile/' . $catdir . '/'.date('Y-m',time()).'/'.date('d',time()) . '/';
						$imageall2 	= array();
						$type2 		= true;
						foreach ($imageall[1] as $k => $val) 
						{
							$type =	getImage($val,$row['collect_url'],$path);
							if($type){
								$imageall2[] 	= $type;
							} else {
								++$error_num;
								$type2 = false;
							}
						}
						if( !$type2 )continue;
						$content = str_replace($imageall[1],$imageall2,$row['content']);
						$this->table_name = $this->db_tablepre . "wenhua";
						$this->update(array('thumb_type'=> 1),array('id'=>$row['id']));
						$this->table_name = $this->db_tablepre . "wenhua_data";
						$this->update(array('content'=> @addslashes($content)),array('id'=>$row['id']));
					}
				}
			}
		}

	}

<?php
	defined('IN_PHPCMS') or exit('No permission resources.');
	pc_base::load_sys_class('model', '', 0);
	//火车头采集的数据转入数据库
	class collect_train_model extends model
	{
		private $category,$collect_list_end,$collect_content,$collect_list_url,$content,$model,$title,$catid;
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
			$this->catid = '105';
		}



		//采集url提取
		public function collect_category_url()
		{
			$this->table_name 	= 'tmp';  	//指定url存储的临时表
			$num 	= $this->count(' type = 0 '); //查询临时表数据数目
			$count 	= ceil($num/50);
			for ( $i=0; $i<$count; $i++ ) {
				$this->table_name 	= 'tmp';  	//指定url存储的临时表
				$sql 	= " select * from " . $this->table_name . " where type = 0 limit 0,50";
				$result = $this->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					list($all,$data) = $this->collect_content_with($row['url']);
					$this->table_name = $this->db_tablepre."wenhua";
					$arr = $this->get_one(array('collect_url'=>$all['collect_url']));
					if($arr){
						//重新采集 更新内容
						$this->table_name = $this->db_tablepre."wenhua_data";
						$this->update($data,array('id'=>$arr['id']));
					} else {
						$id 	= $this->insert($all,true);
						if( $id > 0 ){
							$data['id'] = $id;
							$this->table_name = $this->db_tablepre."wenhua_data";
							$this->insert($data);
							$this->table_name 	= $this->db_tablepre . 'hits';
							$hits 		= array();
							$hits['hitsid'] 		= 'c-17-'.$id;
							$hits['catid'] 			= $this->catid;
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
					}
					$this->table_name 	= 'tmp';  	
					$this->update(array('type'=>1),array('id'=>$row['id']));
				}
			}
		}

		//内容采集为空 重新采集
		public function collect_category_url_with()
		{
			$this->table_name = $this->db_tablepre . "wenhua";
			$sql = " select count(*) num from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where   n.status=66 and data.content='' ";
			$this->query($sql);
			$arr 	= $this->fetch_array();
			$count 		= $arr[0]['num'];
			$num 		= ceil($count/50);
			for ( $i=0; $i<$count; $i++ ) {
				$this->table_name 	= $this->db_tablepre.'wenhua';  	//指定url存储的临时表
				$sql 	= " select n.id,n.collect_url from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.status=66 and data.content='' limit 0,50";
				$result = $this->query($sql);
				while ( $row = mysql_fetch_assoc($result) ) {
					list($all,$data) = $this->collect_content_with($row['collect_url']);

					$this->table_name = $this->db_tablepre."wenhua_data";
					$this->update($data,array('id'=>$row['id']));
				}
			}
		}

		public function collect_content_with($url,$title='')
		{
			$url 		= trim($url);
			$arr 		= $data = array();
			$arr2 		= pathinfo($url);
			$arr['collect_url'] 		= $url;
			// $get_content 	= @addslashes(trim($this->content_with($url,'')));
			$get_content 	= trim($this->content_with($url,$title));
			if(!empty($get_content)){
				$content = preg_replace('/<p>责任编辑[^>]*<\/p>/', '', $get_content);
				$data['content'] 	= @addslashes($content);
			}
			preg_match('/<h2>([^>]*)<\/h2>/', $this->content,$title);
			if($title)$arr['title'] 	= @addslashes($title[1]);

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
			$arr['inputtime'] 	= time();
			$arr['updatetime'] 	= time();
			$arr['status'] 		= 66;
			$arr['catid'] 		= $this->catid;
			$arr['username'] 	= 'admin';
			return array($arr,$data);
		}

		public function content_with($url,$title)
		{
			$content2 	= collect_curl_collect($url);
			if( empty($this->content) ) $this->content = $content2;
			if( empty($this->title) ) {
				preg_match('/<h2>([^>]*)<\/h2>/', $this->content,$title2);
				if($title2)$title 	= @addslashes($title[1]);
			}
			if(empty($content2))return '';
			preg_match('/<div class="common_det_con">([\S\s]+?)<\/div>/', $content2, $type2);//你可能也喜欢：
			preg_match_all('/<p[^>]*>([\S\s]+?)<\/p>/', $type2[0], $type);//你可能也喜欢：
			// preg_match('/<div class="common_det_con">([\S\s]+?)<\/div>/', $content2, $type);//你可能也喜欢：
			array_pop($type[0]);
			$content 	= implode('', $type[0]);
			$content 	= html_entity_decode(str_replace(array('&nbsp;'),'',strtolower($content)));
			$get_content 	= collect_content_with($content,$title,'');
			$arr2 	= pathinfo($url);

			preg_match('/<a[^>]*href=[\'|\"]([^>]+?)[\'|\"][^>]*>[^>]*下一页[^>]*<\/a>/', $content2,$list);
			$filename	= explode('_', $arr2['filename']);
			if(!empty($arr2['filename']) && strpos($list[1],$filename[0]) !== false ){
				$arr3 	= parse_url($list[1]);
				if($arr3['host']){
					$get_content .= '[page]' . $this->content_with($list[1],$title);
				} else {
					$substr_str   = substr($list[1], 0,1);
					if($substr_str != '/'){
						$get_content .= '[page]' . $this->content_with($arr2['dirname'].'/'.$list[1],$title);
					} else {
						$host_arr 	= parse_url($url);
						$get_content .= '[page]' . $this->content_with($host_arr['scheme'] . '://' . $host_arr['host'].'/'.$list[1],$title);
					}
				}
			}
			return $get_content;
		}


		public function content_img()
		{
			$this->table_name = $this->db_tablepre . "wenhua";
			$sql = " select count(*) num from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.status=66 and n.thumb_type=0 and data.content like '%<img%'";
			$this->query($sql);
			$arr 	= $this->fetch_array();
			$count 		= $arr[0]['num'];
			$num 		= ceil($count/50);
			for( $i=0 ; $i<$num ; $i++ )
			{
				$this->table_name = $this->db_tablepre . 'wenhua';
				$sql 	= " select data.content,n.title,n.id,n.catid,n.thumb,n.collect_url from ".$this->table_name."  n left join ".$this->table_name ."_data data on n.id = data.id where n.status=66 and n.thumb_type=0 and data.content like '%<img%' limit 0,50";
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
								$imageall2[] 	= "<img src='".$type."' alt='".$row['title']."'>";
							} else {
								++$error_num;
								$type2 = false;
							}
						}
						if( !$type2 )continue;
						$content = str_replace($imageall[0],$imageall2,$row['content']);
						$this->table_name = $this->db_tablepre . "wenhua";
						$this->update(array('thumb_type'=> 1),array('id'=>$row['id']));
						$this->table_name = $this->db_tablepre . "wenhua_data";
						$this->update(array('content'=> @addslashes($content)),array('id'=>$row['id']));
					}
				}
			}
		}

	}

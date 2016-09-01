<?php 
	defined('IN_PHPCMS') or exit('No permission resources.'); 
	$db = pc_base::load_model('collect_html_curl_model');
	$db->batch_show();
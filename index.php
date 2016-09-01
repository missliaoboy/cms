<?php
/**
 *  index.php PHPCMS 入口
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-6-1
 */
 //PHPCMS根目录

define('PHPCMS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

include PHPCMS_PATH.'/phpcms/base.php';
define('MAX_PAGES', 20);
define('TOKEN_AJAX_TEMPLATE', 'hskjdhfkjdshfkwuireweuroiewr');//ajax token 列表审核通过生成token
define('BAIDU_SITEMAP_TYPE', '1');  //站点 百度 sitemap 提交

pc_base::creat_app();

?>
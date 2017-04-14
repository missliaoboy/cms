<?php
/**
 *  extention.func.php 用户自定义函数库
 *
 * @copyright			(C) 2005-2010 PHPCMS
 * @license				http://www.phpcms.cn/license/
 * @lastmodify			2010-10-27
 */


 /**
 * 分页函数
 *
 * @param $num 信息总数
 * @param $curr_page 当前分页
 * @param $perpage 每页显示数
 * @param $urlrule URL规则
 * @param $array 需要传递的数组，用于增加额外的方法
 * @return 分页
 */
function pages_type_all_list($num, $curr_page, $perpage = 20, $urlrule = '', $array = array(),$setpages = 9) {
    if( defined("MAX_PAGES") && MAX_PAGES > 0){
        $curr_page = $curr_page > MAX_PAGES ?  MAX_PAGES : $curr_page;
        if($num > MAX_PAGES * $perpage){
            $num = MAX_PAGES * $perpage;
        }   
    }
    $urlrule = $array['pathurl'].'/list_{$page}.html';
    $multipage = '';
    if($num > $perpage) {
        $page = $setpages+1;
        $offset = ceil($setpages/2-1);
        $pages = ceil($num / $perpage);
        if (defined('IN_ADMIN') && !defined('PAGES')) define('PAGES', $pages);
        $from = $curr_page - $offset;
        $to = $curr_page + $offset;
        $more = 0;
        if($page >= $pages) {
            $from = 2;
            $to = $pages-1;
        } else {
            if($from <= 1) {
                $to = $page-1;
                $from = 2;
            }  elseif($to >= $pages) {
                $from = $pages-($page-2);
                $to = $pages-1;
            }
            $more = 1;
        }
        $urlAppPath = rtrim(APP_PATH,'\/');
        $strPage = '';
        if($curr_page>0) {

            if($curr_page == 1){
                $strPage .= ' <a href="javascript:;" class="active">1</a>';
            }else if($curr_page>4 && $more){

                $multipage .= ' <a href="'.str_replace('list_1.html','index.html',$urlAppPath.pageurl($urlrule, $curr_page-1, $array)).'">上一页</a>';
                $multipage .= ' <a href="'.str_replace('list_1.html','index.html',$urlAppPath.pageurl($urlrule, 1, $array)).'">首页</a>';
            }else{
                
                $strPage .= ' <a href="'.str_replace('list_1.html','index.html',$urlAppPath.pageurl($urlrule, 1, $array)).'">1</a>';
                $multipage .= ' <a href="'.str_replace('list_1.html','index.html',$urlAppPath.pageurl($urlrule, $curr_page-1, $array)).'">'.L('previous').'</a>';
                $multipage .= ' <a href="'.str_replace('list_1.html','index.html',$urlAppPath.pageurl($urlrule, 1, $array)).'">首页</a>';
            }
        }else{ 
            $strPage .= ' <a href="javascript:;" class="active">1</a>';
        }
        $multipage .= $strPage;
        for($i = $from; $i <= $to; $i++) {
            if($i != $curr_page) {
                $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $i, $array).'">'.$i.'</a>';
            } else {
                $multipage .= ' <a href="javascript:;" class="active">'.$i.'</a> ';
            }
        }
        //var_dump($multipage);
        if($curr_page<$pages) {
            
            if($curr_page>=$pages-4 && $more) {
                $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $pages, $array).'">'.$pages.'</a>';
                $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $curr_page+1, $array).'" >'.L('next').'</a>';
                //$multipage .= ' <a href="'.pageurl($urlrule, $pages, $array).'">尾页</a><a href="'.pageurl($urlrule, $curr_page+1, $array).'" >'.L('next').'</a>';
            } else {
                if($pages <= 10)
                  $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $pages, $array).'">'.$pages.'</a>';
                $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $pages, $array).'">尾页</a> <a href="'.$urlAppPath.pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
            }
        } elseif($curr_page==$pages) {
            $multipage .= ' <a href="javascript:;" class="active">'.$pages.'</a>';
        } else {
            $multipage .= ' <a href="'.$urlAppPath.pageurl($urlrule, $pages, $array).'">尾页</a> <a href="'.$urlAppPath.pageurl($urlrule, $curr_page+1, $array).'">'.L('next').'</a>';
        }
    }
    return $multipage;
}
?>

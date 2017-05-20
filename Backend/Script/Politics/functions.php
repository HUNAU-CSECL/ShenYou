<?php
//获取来自百度百科的数据补充
function BK($arr, $str)
{
    $all = array();
    foreach ($arr as $key => $value) {
        if (empty($value['intr'])) {
            $name = isset($value['name']) ? $value['name'] : '';
            $img  = isset($value['img']) ? $value['img'] : '';
            $url  = 'http://baike.baidu.com/item/' . $value['name'];
            phpQuery::newDocumentFile($url);
            $baike   = pq(".main-content")->html();
            $pattern = '/' . $value['job'][0] . '/';
            if (preg_match($pattern, $baike)) {
                $info   = dealintr($baike);
                $school = NLP($info);
            } else {
                $info   = '';
                $school = '';
            }
            $job = array();
            foreach ($value['job'] as $value) {
                $job[] = $str . $value;
            }
            $all[] = [
                'name'   => $name,
                'img'    => $img,
                'job'    => $job,
                'intr'   => $info,
                'school' => $school,
            ];
        } elseif (empty(NLP($value['intr']))) {
            $name = isset($value['name']) ? $value['name'] : '';
            $img  = isset($value['img']) ? $value['img'] : '';
            $intr = isset($value['intr']) ? $value['intr'] : '';
            $url  = 'http://baike.baidu.com/item/' . $value['name'];
            phpQuery::newDocumentFile($url);
            $baike   = pq(".main-content")->html();
            $pattern = '/' . $value['job'][0] . '/';
            if (preg_match($pattern, $baike)) {
                $info   = dealintr($baike);
                $school = NLP($info);
            } else {
                $school = '';
            }
            $job = array();
            foreach ($value['job'] as $value) {
                $job[] = $str . $value;
            }
            $all[] = [
                'name'   => $name,
                'img'    => $img,
                'job'    => $job,
                'intr'   => $intr,
                'school' => $school,
            ];
        } else {
            $name   = isset($value['name']) ? $value['name'] : '';
            $img    = isset($value['img']) ? $value['img'] : '';
            $intr   = isset($value['intr']) ? $value['intr'] : '';
            $school = NLP($value['intr']);
            $job    = array();
            foreach ($value['job'] as $value) {
                $job[] = $str . $value;
            }
            $all[] = [
                'name'   => $name,
                'img'    => $img,
                'job'    => $job,
                'intr'   => $intr,
                'school' => $school,
            ];
        }
    }
    return $all;
}
//处理百度百科简介函数
function dealintr($str)
{
    // $str = preg_replace("/[\s\S]*人物履历<\/h2>?[\n\r]?<\/div>?[\n\r]?/", "", $str);
    $str = preg_replace("/[\s\S]*(人物履历)|(工作经历)<\/h2>?[\n\r]?<\/div>?[\n\r]?/", "", $str);
    $str = preg_replace("/.*anchor-list[\s\S]*/", "", $str);
    $str = strip_tags($str, '<div>');
    $str = preg_replace("/<div.*?>/", "", $str);
    $str = preg_replace("/\[.*?\]/", "", $str);
    $str = preg_replace("/\\n/", "", $str);
    $str = preg_replace("/<\/div>/", "</br>", $str);
    $str = preg_replace("/编辑<\/br>/", "", $str);
    return $str;
}
//检验网页是否改版
function ISFULL($array)
{
    foreach ($array as $key => $value) {
        if ($value['name'] == '' || $value['job'] == '') {
            return true;
        } else {
            return false;
        }
    }
}

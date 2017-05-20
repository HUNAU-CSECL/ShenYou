<?php
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

function dealCont($cont)
{
    $cont = 'http://www.hunan.gov.cn' . $cont;
    return $cont;
}
function dealImg($img)
{
    $cont = 'http://www.hunan.gov.cn/zw/zfld' . substr($img, 1);
    return $cont;
}
function dealName($name)
{
    $name = strstr($name, '<br>');
    $name = str_replace('<br>', '', $name);
    return $name;
}
function dealJob($str)
{
    $str     = preg_replace('/<br>.*/', '', $str);
    $pattern = '/、/';
    if (preg_match($pattern, $str)) {
        $str = explode("、", $str);
    } else {
        $str = array($str);
    }
    $job = array();
    foreach ($str as $value) {
        $job[] = $value;
    }
    return $job;
}
function dealIntro($str)
{
    $str = preg_replace("/<p.*?>/", "", $str);
    $str = preg_replace("/<\/p>/", "<br>", $str);
    return $str;
}

class hngov
{
    public function get()
    {
        set_time_limit(0);
        $ones = [];
        phpQuery::newDocumentFile('http://www.hunan.gov.cn/zw/zfld');
        $some = pq(".leader-list ul li");
        foreach ($some as $li) {
            $one  = pq($li);
            $cont = $one->find('a')->attr('href');
            $img  = $one->find('a img')->attr('src');
            $name = $one->find('a')->attr('title');

            phpQuery::newDocumentFile(dealCont($cont));
            $detail = dealCont($cont) . substr(pq(".leader-main dl dd a")->attr('href'), 2);
            phpQuery::newDocumentFile($detail);
            $introduction = pq(".TRS_Editor")->html();
            $introduction = dealIntro($introduction);

            $ones[] = [
                'img'  => dealImg($img),
                'name' => dealName($name),
                'job'  => dealJob($name),
                'intr' => $introduction,
            ];
        }

        ISFULL($ones);
        return BK($ones, "湖南省");
        die;
    }
}

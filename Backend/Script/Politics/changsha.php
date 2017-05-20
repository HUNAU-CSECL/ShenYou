<?php
//湖南省长沙市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class changsha
{
    public function get()
    {
        set_time_limit(0);
        //去除重复函数

        $articles = [];
        phpQuery::newDocumentFile('http://www.changsha.gov.cn/xxgk/szfxxgkml/ldzc/');
        $artlist = pq(".leaderItem");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('.ul_leaderInfo .leaderName')->text();
            $zhiwu      = $article->find('.ul_leaderInfo li:eq(0)')->text();
            $url        = $article->find('.ul_leaderInfo li:eq(3) a ')->attr("href");
            $tupian     = $article->find('a img')->attr("src");
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'url'    => $url,
            ];

        }
        $a = $articles;

        $articles = [];
        phpQuery::newDocumentFile('http://www.changsha.gov.cn/xxgk/szfxxgkml/ldzc/');
        $artlist = pq(".div_leader");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('ul .leaderName')->text();
            $zhiwu      = $article->find('ul li:eq(0)')->text();
            $tupian     = $article->find('a img')->attr("src");
            $url        = $article->find('ul li:eq(3) a')->attr("href");
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'url'    => $url,
            ];
        }
        $b = $articles;

        //合并数组
        echo "<pre>";
        $c = array_merge_recursive($a, $b);

        //补全职务图片url
        for ($i = 0; $i < 12; $i++) {
            $c[$i]['tupian'] = "http://www.changsha.gov.cn/xxgk/szfxxgkml/ldzc/" . $c[$i]['tupian'];
            $c[$i]['url']    = "http://www.changsha.gov.cn/xxgk/szfxxgkml/ldzc/" . $c[$i]['url'];
            $c[$i]['zhiwu']  = "长沙" . $c[$i]['zhiwu'];
        }

        // 去掉图片url中的多余/.../
        $i = 0;
        while ($i < count($c)) {
            $str1            = addslashes($c[$i]['tupian']);
            $str2            = addslashes($c[$i]['url']);
            $c[$i]['tupian'] = preg_replace('/ldzc\/.\/sjld/', "ldzc/sjld", $str1);
            $c[$i]['url']    = preg_replace('/ldzc\/.\/sjld/', "ldzc/sjld", $str2);
            $i++;
        }

        //去除领导重复
        for ($i = 0; $i < count($c); $i++) {
            for ($j = $i + 1; $j < count($c); $j++) {
                if ($c[$i]['name'] == $c[$j]['name']) {
                    array_remove($c, $j);
                }
            }
        };

        // 去掉名字中的空格
        $i = 0;
        while ($i < count($c)) {
            $c[$i]['name'] = str_replace(' ', '', $c[$i]['name']);
            $i++;
        }

        function zhuaqucs($url)
        {
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq(".docContent");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $info       = $article->find('.personInfo_intro')->html();
                $articles[] = [
                    'info' => $info,
                ];
            }
            $articles[0]['info'] = addslashes($articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<style([\d\D]*)<\/style>/U", "", $articles[0]['info']);
            $articles[0]['info'] = strip_tags($articles[0]['info'], "<p><br>");
            $articles[0]['info'] = addslashes($articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<p(.*)>/U", "", $articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<\/p><\/p>/U", "<br>", $articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<\/p>/U", "<br>", $articles[0]['info']);
            $a                   = $articles;
            return $a[0]['info'];
        }

        //开始抓取个人简介
        $i = 0;
        $g = array(array());
        while ($i < count($c)) {
            $c[$i]['zhiwu'] = str_replace("长沙", "", $c[$i]['zhiwu']);
            $zw             = explode("、", $c[$i]['zhiwu']);
            $g[$i]["name"]  = $c[$i]['name'];
            $g[$i]["job"]   = $zw;
            $g[$i]["img"]   = $c[$i]['tupian'];
            $g[$i]['intr']  = zhuaqucs($c[$i]['url']);
            $i++;
        }

        $ones = $g;
        ISFULL($ones);
        return BK($ones, "长沙市");
        die;
    }
}

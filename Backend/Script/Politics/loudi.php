<?php
//湖南省娄底市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class loudi
{
    public function get()
    {
        set_time_limit(0);

        function zhuaquld($url)
        {
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq(".ldjs_box");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $tupian     = $article->find('img')->attr("src");
                $intr       = $article->find('span')->html();
                $articles[] = [
                    'tupian' => $tupian,
                    'intr'   => $intr,
                ];
            }
            $articles[0]['intr'] = addslashes($articles[0]['intr']);
            $articles[0]['intr'] = preg_replace("/<style([\d\D]*)<\/style>/U", "", $articles[0]['intr']);
            $articles[0]['intr'] = strip_tags($articles[0]['intr'], "<p>");
            $articles[0]['intr'] = addslashes($articles[0]['intr']);
            $articles[0]['intr'] = preg_replace("/<p(.*)>/U", "", $articles[0]['intr']);
            $articles[0]['intr'] = preg_replace("/<\/p><\/p>/U", "<br>", $articles[0]['intr']);
            $articles[0]['intr'] = preg_replace("/<\/p>/U", "<br>", $articles[0]['intr']);
            $b                   = $articles;
            return $b;
        }

        //娄底政府领导
        $articles = [];
        phpQuery::newDocumentFile('http://www.hnloudi.gov.cn/zwgk/szfxxgkml/ldzc/zfld/201503/t20150314_140036.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $url        = $article->find('a')->attr("href");
            $info       = $article->find('a')->text();
            $articles[] = [
                'url'  => $url,
                'info' => $info,
            ];
        }
        $a = $articles;
        //选择所需数组
        $a = array_slice($a, 13, 11);
        // 删除其中一个不需数组
        array_splice($a, 9, 1);

        //去除链接中的../
        $i = 1;
        while ($i < count($a)) {
            $a[$i]['url'] = str_replace("..", "", $a[$i]['url']);
            $i++;
        }
        $a[0]['url'] = str_replace("./", "", $a[0]['url']);
        $a[0]['url'] = "201503/" . $a[0]['url'];
        $a[3]['url'] = "/201503" . $a[03]['url'];
        //补充图片链接
        $i = 1;
        while ($i < count($a)) {
            $a[$i]['url'] = "http://www.hnloudi.gov.cn/zwgk/szfxxgkml/ldzc/zfld" . $a[$i]['url'];
            $i++;
        }
        $a[3]['url'] = str_replace("./", "/", $a[3]['url']);
        $a[0]['url'] = "http://www.hnloudi.gov.cn/zwgk/szfxxgkml/ldzc/zfld/" . $a[0]['url'];
        //抓取/2015/t
        $i = 0;
        $f = [];
        while ($i < count($a)) {
            preg_match('/zfld\/(.*)\/t/U', $a[$i]['url'], $out);
            $f[$i] = $out[1];
            $i++;
        }

        //遍历url开始抓取
        $i = 0;
        while ($i < count($a)) {
            $a[$i]['url'] = zhuaquld($a[$i]['url']);
            $i++;
        }

        //截取info
        $i = 0;
        $d = [];
        while ($i < count($a)) {
            $c               = explode(":", $a[$i]['info']);
            $d[$i]['name']   = $c[1];
            $d[$i]['zhiwu']  = $c[0];
            $d[$i]['tupian'] = $a[$i]['url'][0]['tupian'];
            $d[$i]['intr']   = strip_tags($a[$i]['url'][0]['intr'], "<br>");
            $i++;
        }

        //清洗
        $i = 0;
        while ($i < 4) {
            $d[$i]['tupian'] = str_replace("./", "http://www.hnloudi.gov.cn/zwgk/szfxxgkml/ldzc/zfld/" . $f[$i] . "/", $d[$i]['tupian']);
            $i++;
        }
        $i = 4;
        while ($i < count($d)) {
            $d[$i]['tupian'] = "";
            $i++;
        }
        //单个抓取
        $articles = [];
        phpQuery::newDocumentFile("http://www.hnloudi.gov.cn/zwgk/szfxxgkml/ldzc/zfld/201611/t20161102_322386.html");
        $artlist = pq(".ldjs_box");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $intr       = $article->find('.fr div:eq(1)')->html();
            $articles[] = [
                'intr' => $intr,
            ];
        }
        $articles[0]['intr'] = addslashes($articles[0]['intr']);
        $articles[0]['intr'] = strip_tags($articles[0]['intr'], "<p>");
        $articles[0]['intr'] = addslashes($articles[0]['intr']);
        $articles[0]['intr'] = preg_replace("/<p(.*)>/U", "", $articles[0]['intr']);
        $articles[0]['intr'] = preg_replace("/<\/p><\/p>/U", "<br>", $articles[0]['intr']);
        $articles[0]['intr'] = preg_replace("/<\/p>/U", "<br>", $articles[0]['intr']);
        $d[1]['intr']        = $articles[0]['intr'];

        $i = 0;
        $g = array(array());
        while ($i < count($d)) {
            $zw            = explode("、", $d[$i]['zhiwu']);
            $g[$i]["name"] = $d[$i]['name'];
            $g[$i]["job"]  = $zw;
            $g[$i]["img"]  = $d[$i]['tupian'];
            $g[$i]['intr'] = $d[$i]['intr'];
            $i++;
        }
        $ones = $g;

        //清除前后空格
        $i = 0;
        while ($i < count($ones)) {
            $ones[$i]['intr'] = ltrim($ones[$i]['intr']);
            $ones[$i]['name'] = ltrim($ones[$i]['name']);
            $i++;
        }

        ISFULL($ones);
        return BK($ones, "娄底市");
        die;
    }
}

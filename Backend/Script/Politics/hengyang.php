<?php
//湖南省衡阳市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class hengyang
{
    public function get()
    {
        set_time_limit(0);
        //去除重复函数
        function array_remove(&$arr, $offset)
        {
            array_splice($arr, $offset, 1);
        }
        //衡阳抓取各个领导的链接
        $articles = [];
        phpQuery::newDocumentFile('http://www.hengyang.gov.cn/zfxxgk/szfxxgkml/ldzc/');
        $artlist = pq("a");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('li')->text();
            $url        = $article->find('')->attr("href");
            $articles[] = [
                'name' => $name,
                'url'  => $url,
            ];
        }
        $f = $articles;
        //选择所需数组
        $f = array_slice($f, 65, 39);

        //去除领导重复
        $i = 0;
        for ($i = 0; $i < count($f); $i++) {
            for ($j = $i + 1; $j < count($f); $j++) {
                if ($f[$i]['name'] == $f[$j]['name']) {
                    array_remove($f, $j);
                }
            }
        }
        //备用
        $k = $f;
        //补全各个领导页面url
        $i = 0;
        while ($i < count($f)) {
            $f[$i]['url'] = "http://www.hengyang.gov.cn/zfxxgk/szfxxgkml/ldzc/" . $f[$i]['url'];
            $str          = addslashes($f[$i]['url']);
            $f[$i]['url'] = preg_replace('/ldzc\/./', "ldzc", $str);
            $i++;
        }

        $i = 0;
        $p = array(array());
        while ($i < count($k)) {
            $d = [];
            preg_match("/.\/(.*)\/(.*)\//U", $k[$i]['url'], $d);
            $p[$i] = $d[1] . "/" . $d[2];
            $i++;
        }

        //抓取图片职务
        function zhuaquhy($url)
        {
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq(".mz");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $name       = $article->find('p:eq(0)')->text();
                $zhiwu      = $article->find('p:eq(1)')->text();
                $articles[] = [
                    'name'  => $name,
                    'zhiwu' => $zhiwu,
                ];
            }
            $a        = $articles;
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq(".Zuo");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $tupian     = $article->find('img')->attr("src");
                $articles[] = [
                    'tupian' => $tupian,
                ];
            }
            $b        = $articles;
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq(".You");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $info       = $article->find('div:eq(1) ')->html();
                $articles[] = [
                    'info' => $info,
                ];
            }
            $d              = $articles;
            $str            = addslashes($b[0]['tupian']);
            $b[0]['tupian'] = preg_replace('/.\//U', "", $str);
            $d[0]['info']   = preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si", "", $d[0]['info']);
            $d[0]['info']   = strip_tags($d[0]['info'], "<p>");
            $d[0]['info']   = preg_replace("/<p([\d\D]*)>/U", "", $d[0]['info']);
            $d[0]['info']   = str_replace("</p>", "<br>", $d[0]['info']);
            $c              = array_merge_recursive($a[0], $b[0], $d[0]);
            return $c;
        }

        //遍历各个领导url在，开始抓取
        $i = 0;
        $h = array(array());
        while ($i < count($f)) {
            $h[$i] = zhuaquhy($f[$i]['url']);

            $i++;
        }

        //补全图片链接
        $i = 0;
        while ($i < count($h)) {
            $h[$i]['tupian'] = "http://www.hengyang.gov.cn/zfxxgk/szfxxgkml/ldzc/" . $p[$i] . "/" . $h[$i]['tupian'];
            $i++;
        }

        //去掉职务前的中共
        for ($i = 0; $i < count($h); $i++) {
            $h[$i]['zhiwu'] = str_replace("中共", "", $h[$i]['zhiwu']);
            $h[$i]['zhiwu'] = str_replace("衡阳", "", $h[$i]['zhiwu']);
        }

        //去除名字中的空格
        $i = 0;
        while ($i < count($h)) {
            $h[$i]['name'] = str_replace(" ", "", $h[$i]['name']);
            $i++;
        }
        $i = 0;
        $g = array(array());
        while ($i < count($h)) {
            $g[$i]["name"] = $h[$i]['name'];
            $zw            = explode("、", $h[$i]['zhiwu']);
            $g[$i]["job"]  = $zw;
            $g[$i]["img"]  = $h[$i]['tupian'];
            $g[$i]['intr'] = ($h[$i]['info']);
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
        return BK($ones, "衡阳市");
        die;
    }
}

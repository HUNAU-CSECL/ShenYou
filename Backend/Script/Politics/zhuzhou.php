<?php
//湖南省株洲市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class zhuzhou
{
    public function get()
    {
        //去除重复函数
        function array_remove(&$arr, $offset)
        {
            array_splice($arr, $offset, 1);
        }
        //株洲市政府
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/707/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $a = $articles;
        //补全图片url
        for ($i = 0; $i < 11; $i++) {
            $a[$i]['tupian'] = "http://www.zhuzhou.gov.cn/" . $a[$i]['tupian'];
        }

        //株洲市委
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/705/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $b = $articles;

        //株洲市人大
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/706/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $c = $articles;

        //株洲市政协
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/708/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $d = $articles;

        //株洲市法院
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/709/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $e = $articles;

        //株洲市检察院
        $articles = [];
        phpQuery::newDocumentFile('http://www.zhuzhou.gov.cn/channel/710/index.html');
        $artlist = pq("li");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('h3 a ')->text();
            $zhiwu      = $article->find('h3 span')->text();
            $tupian     = $article->find('a img')->attr("src");
            $info       = $article->find('.leaderTxt p')->html();
            $articles[] = [
                'name'   => $name,
                'zhiwu'  => $zhiwu,
                'tupian' => $tupian,
                'info'   => $info,
            ];
        }
        $f = $articles;

        //合并数组
        echo "<pre>";
        $h = array_merge_recursive($b, $c, $d, $e, $f);

        //补全图片url、职务所在市
        $i = 0;
        while ($i < count($h)) {
            $h[$i]['tupian'] = "http://www.zhuzhou.gov.cn/" . $h[$i]['tupian'];
            $i++;
        }

        //合并数组
        $g = array_merge_recursive($a, $h);

        // 去掉图片url中的多余/.../
        $i = 0;
        while ($i < count($g)) {
            $str             = addslashes($g[$i]['tupian']);
            $g[$i]['tupian'] = preg_replace('/.cn\/(.*)picture/U', ".cn/picture", $str);
            $i++;
        }

        //去除领导重复
        for ($i = 0; $i < count($g); $i++) {
            for ($j = $i + 1; $j < count($g); $j++) {
                if ($g[$i]['name'] == $g[$j]['name']) {
                    array_remove($g, $j);
                }
            }
        };

        //清理
        $i = 0;
        $d = array(array());
        while ($i < count($g)) {
            $zw            = explode("、", $g[$i]['zhiwu']);
            $d[$i]["name"] = $g[$i]['name'];
            $d[$i]["job"]  = $zw;
            $d[$i]["img"]  = $g[$i]['tupian'];
            $d[$i]['intr'] = preg_replace('/　　/', '<br>', $g[$i]['info']);
            $i++;
        }

        $ones = $d;

        //清除前后空格
        $i = 0;
        while ($i < count($ones)) {
            $ones[$i]['name'] = preg_replace("/\（(.*)\）/", "", $ones[$i]['name']);
            $ones[$i]['intr'] = ltrim($ones[$i]['intr']);
            $ones[$i]['name'] = ltrim($ones[$i]['name']);
            $i++;
        }

        ISFULL($ones);
        return BK($ones, "株洲市");
        die;
    }
}

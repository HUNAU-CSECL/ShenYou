<?php
//湖南省常德市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class changde
{
    public function get()
    {
        set_time_limit(0);
        //去除重复函数
        function array_remove(&$arr, $offset)
        {
            array_splice($arr, $offset, 1);
        }

        //常德市委领导
        $articles = [];
        phpQuery::newDocumentFile('http://www.changde.gov.cn/col/col261/index.html');
        $artlist = pq("table");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $info       = $article->find('tbody tr td  a')->text();
            $url        = $article->find('tbody tr td  a')->attr("href");
            $articles[] = [
                'info' => $info,
                'url'  => $url,
            ];
        }
        $a = $articles;

        //选择所需数组
        $a = array_slice($a, 1, 12);

        //常德人大领导
        $articles = [];
        phpQuery::newDocumentFile('http://www.changde.gov.cn/col/col262/index.html');
        $artlist = pq("table");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $info       = $article->find('tbody tr td  a')->text();
            $url        = $article->find('tbody tr td  a')->attr("href");
            $articles[] = [
                'info' => $info,
                'url'  => $url,
            ];
        }

        $b = $articles;
        //选择所需数组
        $b = array_slice($b, 1, 10);

        //常德政府领导
        $articles = [];
        phpQuery::newDocumentFile('http://www.changde.gov.cn/col/col263/index.html');
        $artlist = pq("table");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $info       = $article->find('tbody tr td  a')->text();
            $url        = $article->find('tbody tr td  a')->attr("href");
            $articles[] = [
                'info' => $info,
                'url'  => $url,
            ];
        }
        $c = $articles;

        //选择所需数组
        $c = array_slice($c, 1, 10);

        //常德政协领导
        $articles = [];
        phpQuery::newDocumentFile('http://www.changde.gov.cn/col/col264/index.html');
        $artlist = pq("table");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $info       = $article->find('tbody tr td  a')->text();
            $url        = $article->find('tbody tr td  a')->attr("href");
            $articles[] = [
                'info' => $info,
                'url'  => $url,
            ];
        }
        $d = $articles;

        //选择所需数组
        $d = array_slice($d, 1, 11);

        //合并数组
        $f = array_merge_recursive($a, $b, $c, $d);

        //补全url
        $i = 0;
        while ($i < count($f)) {
            $f[$i]['url'] = "http://www.changde.gov.cn" . $f[$i]['url'];
            $i++;
        }

        //抓取图片
        function zhuaqucd($url)
        {
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq("table");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $tupian     = $article->find('tbody tr td  img')->attr("src");
                $info       = $article->find('[class="bt_content"]')->html();
                $articles[] = [
                    'tupian' => $tupian,
                    'info'   => $info,
                ];
            }
            $articles[0]['info'] = strip_tags($articles[0]['info'], "<p>");
            $articles[0]['info'] = addslashes($articles[0]['info']);
            $articles[0]['info'] = str_replace("<p>", "", $articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<p([\d\D]*)>/U", "", $articles[0]['info']);
            $articles[0]['info'] = str_replace("</p>", "<br>", $articles[0]['info']);
            return $articles[0];
        }

        //开始抓取
        $i = 0;
        while ($i < count($f)) {
            $f[$i]['url'] = zhuaqucd($f[$i]['url']);
            $i++;
        }
        //分割信息
        $i = 0;
        $g = array(array());
        while ($i < count($f)) {
            $t             = explode(":", $f[$i]['info']);
            $g[$i]['name'] = $t[1];
            $g[$i]['job']  = $t[0];
            @$g[$i]['img'] = "http://www.changde.gov.cn" . $f[$i]['url']['tupian'];
            $g[$i]['intr'] = $f[$i]['url']['info'];
            $i++;
        }

        //补全信息
        $i = 12;
        while ($i < 22) {
            $g[$i]['job'] = "市人大" . $g[$i]['job'];
            $i++;
        }
        $i = 33;
        while ($i < 43) {
            $g[$i]['job'] = "市政协" . $g[$i]['job'];
            $i++;
        }

        //去除领导重复
        for ($i = 0; $i < count($g); $i++) {
            for ($j = $i + 1; $j < count($g); $j++) {
                if ($g[$i]['name'] == $g[$j]['name']) {
                    $g[$i]['job'] = $g[$i]['job'] . "、" . $g[$j]['job'];
                    array_remove($g, $j);
                }
            }
        }

        //去除名字职务中的换行符
        $i = 0;
        while ($i < count($g)) {
            $g[$i]['job']  = ltrim($g[$i]['job']);
            $g[$i]['job']  = str_replace(" ", "", $g[$i]['job']);
            $g[$i]['job']  = preg_replace('/[\n]/', '', $g[$i]['job']);
            $g[$i]['name'] = ltrim($g[$i]['name']);
            $g[$i]['name'] = str_replace(" ", "", $g[$i]['name']);
            $g[$i]['name'] = preg_replace('/[\n]/', '', $g[$i]['name']);
            $i++;
        }
        //职务分数组
        $i = 0;
        while ($i < count($g)) {
            $zw           = explode("、", $g[$i]['job']);
            $g[$i]['job'] = $zw;
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
        return BK($ones, "常德市");
        die;
    }
}

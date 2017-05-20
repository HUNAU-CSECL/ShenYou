<?php
//湖南省益阳市政府
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/phpQuery.php";
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";

class yiyang
{
    public function get()
    {
        //去除重复函数
        function array_remove(&$arr, $offset)
        {
            array_splice($arr, $offset, 1);
        }
        //抓取益阳市领导人个人页面
        $articles = [];
        phpQuery::newDocumentFile('http://www.yiyang.gov.cn/yiyang/zfld/ldlb.html');
        $artlist = pq("tr");
        foreach ($artlist as $li) {
            $article    = pq($li);
            $name       = $article->find('td:eq(1) a')->text();
            $url        = $article->find('td:eq(2) a')->attr('href');
            $articles[] = [
                'name' => $name,
                'url'  => $url,
            ];
        }
        $a = $articles;

        //选择所需数组
        $a = array_slice($a, 4, 44);
        //补充链接
        $i = 0;
        while ($i < count($a)) {
            $a[$i]['url'] = str_replace("../", "http://www.yiyang.gov.cn/yiyang/", $a[$i]['url']);
            $i++;
        }

        //抓取图片职务信息
        function zhuaquyy($url)
        {
            $articles = [];
            phpQuery::newDocumentFile($url);
            $artlist = pq("body");
            foreach ($artlist as $li) {
                $article    = pq($li);
                $zhiwu      = $article->find('[class="xxzi1"]')->text();
                $tupian     = $article->find('[align="absmiddle"]')->attr("src");
                $info       = $article->find('[class="ldzi1"] div')->html();
                $articles[] = [
                    'zhiwu'  => $zhiwu,
                    'tupian' => $tupian,
                    'info'   => $info,
                ];
            }
            $articles[0]['info'] = addslashes($articles[0]['info']);
            $articles[0]['info'] = strip_tags($articles[0]['info'], "<p>");
            $articles[0]['info'] = addslashes($articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<p(.*)>/U", "", $articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<\/p><\/p>/U", "<br>", $articles[0]['info']);
            $articles[0]['info'] = preg_replace("/<\/p>/U", "<br>", $articles[0]['info']);
            return $articles;
        }

        //开始抓取
        $i = 0;
        while ($i < count($a)) {
            $b            = zhuaquyy($a[$i]['url']);
            $a[$i]['url'] = $b[0];
            $i++;
        }
        //处理信息
        $i = 0;
        while ($i < count($a)) {
            $a[$i]['url']['zhiwu'] = str_replace($a[$i]['name'], "", $a[$i]['url']['zhiwu']);
            $a[$i]['url']['zhiwu'] = str_replace(" ", "", $a[$i]['url']['zhiwu']);
            $a[$i]['name']         = str_replace(" ", "", $a[$i]['name']);
            $i++;
        }
        $i = 0;
        $h = array(array('name' => "", 'zhiwu' => "", 'tupian' => ""));
        while ($i < count($a)) {
            $h[$i]['name']   = $a[$i]['name'];
            $h[$i]['zhiwu']  = $a[$i]['url']['zhiwu'];
            $h[$i]['tupian'] = $a[$i]['url']['tupian'];
            $h[$i]['intr']   = $a[$i]['url']['info'];
            $i++;
        }

        //去除领导重复
        for ($i = 0; $i < count($h); $i++) {
            for ($j = $i + 1; $j < count($h); $j++) {
                if ($h[$i]['name'] == $h[$j]['name']) {
                    array_remove($h, $j);
                }
            }
        }
        //去除职务中的换行符
        $i = 0;
        while ($i < count($h)) {
            $h[$i]['zhiwu'] = preg_replace('/[\n]/', '', $h[$i]['zhiwu']);
            $i++;
        }
        //补全信息
        $i = 28;
        while ($i < 38) {
            $h[$i]['zhiwu'] = "政协" . $h[$i]['zhiwu'];
            $i++;
        }
        //最后两个是没有图片的
        $i = 36;
        while ($i < count($h)) {
            $h[$i]['tupian'] = "";
            $i++;
        }

        //清理信息
        $i = 0;
        $g = array(array());
        while ($i < count($h)) {
            $zw            = explode("、", $h[$i]['zhiwu']);
            $g[$i]["name"] = $h[$i]['name'];
            $g[$i]["job"]  = $zw;
            $g[$i]["img"]  = $h[$i]['tupian'];
            $g[$i]['intr'] = $h[$i]['intr'];
            $g[$i]['intr'] = mb_convert_encoding($g[$i]['intr'], 'utf-8', 'GBK');
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
        return BK($ones, "益阳市");
        die;
    }
}

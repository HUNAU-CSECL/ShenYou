<?php
//抓取中国科学院院士简历链接
set_time_limit(0);
header("Content-type: text/html; charset=utf-8");

require Root_Path . "/Backend/Script/QueryList/vendor/autoload.php";

use QL\QueryList;

class cas
{
    public function get()
    {
        $urls = array(
            'http://www.casad.cas.cn/chnl/371/index.html', //中国国籍在世院士列表
            'http://www.casad.cas.cn/chnl/315/index.html', //外籍在世院士列表
            'http://www.casad.cas.cn/chnl/316/index.html', //中国国籍已故院士列表
            'http://www.casad.cas.cn/chnl/317/index.html', //外籍已故院士列表
        );

        $mh = curl_multi_init();

        foreach ($urls as $i => $url) {
            $conn[$i] = curl_init($url);
            curl_setopt($conn[$i], CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36"); //用户代理
            curl_setopt($conn[$i], CURLOPT_HEADER, 0); //启用时会将头文件的信息作为数据流输出。
            curl_setopt($conn[$i], CURLOPT_CONNECTTIMEOUT, 60); //在尝试连接时等待的秒数。设置为0，则无限等待
            curl_setopt($conn[$i], CURLOPT_RETURNTRANSFER, true); //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYPEER, false); //FALSE 禁止 cURL 验证对等证书
            curl_setopt($conn[$i], CURLOPT_SSL_VERIFYHOST, false); //FALSE 不检查证书
            curl_multi_add_handle($mh, $conn[$i]); //向curl批处理会话中添加单独的curl句柄
        }

        do {
            curl_multi_exec($mh, $active); //执行
        } while ($active);

        foreach ($urls as $i => $url) {
            $data  = curl_multi_getcontent($conn[$i]);
            $rules = array(
                'url' => array('#allNameBar>dd>span>a', 'href'),
            );
            $data = QueryList::Query($data, $rules)->getData(function ($item) {
                return $item['url'];
            });
            foreach ($data as $value) {
                $all[] = $value;
            }
        }

        foreach ($urls as $i => $url) {
            curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
            curl_close($conn[$i]);
        }

        curl_multi_close($mh); //关闭一组cURL句柄

        $sci = array_unique($all);
        return $sci;
    }
}

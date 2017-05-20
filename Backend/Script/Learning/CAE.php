<?php
//抓取中国工程院院士简历链接
set_time_limit(0);
header("Content-type: text/html; charset=utf-8");

class cae
{
    public function get()
    {
        $urls = array(
            'http://www.cae.cn/cae/jsp/qtysmd.jsp?ColumnID=135', //中国国籍在世院士列表
            'http://www.cae.cn/cae/jsp/allAF.jsp?ColumnID=177', //外籍在世院士列表
            'http://www.cae.cn/cae/jsp/queryAByYgys.jsp?ColumnID=173', //中国国籍已故院士列表
            'http://www.cae.cn/cae/jsp/allAygys.jsp?ColumnID=178', //外籍已故院士列表
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
            $data = curl_multi_getcontent($conn[$i]);
            preg_match_all('/(jump|wjys_ger)\.jsp\?oid=.*?"/', $data, $matched);
            foreach ($matched[0] as $key => $value) {
                $url1  = preg_replace('/"/', '', $value);
                $url   = 'http://www.cae.cn/cae/jsp/' . $url1;
                $all[] = $url;
            }
        }

        foreach ($urls as $i => $url) {
            curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
            curl_close($conn[$i]);
        }

        curl_multi_close($mh); //关闭一组cURL句柄

        $engin = array_unique($all);
        return $engin;
    }
}

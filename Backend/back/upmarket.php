<?php
//更新沪深、新三板、中概股总市值
class upmarket
{
    //$which:沪深hs、新三板sb、中概usa
    public function update($which)
    {
        if ($which == 'hs') {
            $headURL = 'http://d.10jqka.com.cn/v2/realhead/hs_';
        } elseif ($which == 'sb') {
            $headURL = 'http://d.10jqka.com.cn/v2/realhead/sb_';
        } else {
            $headURL = 'http://d.10jqka.com.cn/v3/realhead/usa_';
        }
        $markets = array();

        require_once Root_Path . '/Model/stockcode.php';
        $obj   = new stockcode();
        $array = $obj->$which();

        foreach ($array as $key => $value) {
            $arr[] = $value['code'];
        }
        $num = count($arr);
        for ($n = 0; $n < ceil($num / 320); $n++) {
            $urls = array();
            for ($i = 320 * $n; $i < 320 + 320 * $n; $i++) {
                @$urls[] = $headURL . $arr[$i] . '/last.js';
            }

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
                $data   = curl_multi_getcontent($conn[$i]);
                $string = preg_replace('/.*?\(/', '', $data);
                $json   = substr($string, 0, strlen($string) - 1);
                $array  = json_decode($json, true);
                $market = preg_replace('/\..*/', '', $array['items'][3541450]);
                if ($market == '') {
                    continue;
                } else {
                    $code = preg_replace('/.*\_/', '', $url);
                    $code = preg_replace('/\/last.js/', '', $code);
                    if ($which == 'usa') {
                        $market = $market * 6.8;
                    }
                    //更新企业总市值$string:$code $num:$market
                    $obj->update($code, $market);
                    $markets[] = [
                        'code'   => $code,
                        'market' => $market,
                    ];
                }
            }

            foreach ($urls as $i => $url) {
                curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
                curl_close($conn[$i]);
            }

            curl_multi_close($mh); //关闭一组cURL句柄
        }
        return $markets;
    }
    //平均market,$which:hs、sb、usa
    public function averMarket($which)
    {
        require_once Root_Path . '/Model/stockcode.php';
        $obj = new stockcode();
        //查同一板各企业市值$which:hs、sb、usa
        $array = $obj->allMarket($which);
        $num   = count($array);
        $total = 0;
        foreach ($array as $value) {
            $total += $value['market'];
        }
        return round($total / $num);
    }
    //补齐market,$which:hs、sb、usa
    public function suppMarket($which)
    {
        require_once Root_Path . '/Model/stockcode.php';
        $obj        = new stockcode();
        $averMarket = $this->averMarket($which);
        //更新market空白$which:hs、sb、usa,$num:同一板平均市值
        $obj->suppMarket($which, $averMarket);
    }
    //更新指定板块企业市值$which:hs、sb、usa
    public function updatemarket($which)
    {
        $this->update($which);
        $this->suppMarket($which);
    }
}

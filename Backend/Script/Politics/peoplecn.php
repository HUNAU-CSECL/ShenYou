<?php
require_once Root_Path . "/Backend/Script/QueryList/vendor/autoload.php";
require_once Root_Path . "/Backend/back/functions.php";
use QL\QueryList;

class peoplecn
{
    public function get()
    {
        set_time_limit(0);
        $s   = $this->allId();
        $num = count($s);
        for ($n = 0; $n < ceil($num / 320); $n++) {
            $urls = array();
            for ($i = 320 * $n; $i < 320 + 320 * $n; $i++) {
                @$urls[] = 'http://cpc.people.com.cn/gbzl/html/' . $s[$i] . '.html';
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
                $data = curl_multi_getcontent($conn[$i]);
                if (preg_match('/404 Not Found/', $data) || empty($data)) {
                    continue;
                } else {
                    $info = $this->QLpeo($data);
                    if ($info['name'] !== '' && !empty($info['school'])) {
                        $array  = $info;
                        $name   = $array['name'];
                        $school = $array['school'];
                        $job    = $array['job'];
                        $a      = $array;
                        //查询person表依据姓名
                        $result = findPer_by_name($name);
                        if (empty($result)) {
                            //插入persons表
                            $insertId = $this->insertPerson($a);
                            //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                            match_school($school, $insertId, 1);
                            //匹配政界职位$array:校友当前职位数组
                            $this->addjob($job, $insertId);
                        } else {
                            foreach ($result as $key => $value) {
                                $b = $value;
                                //查询schper依据per_id
                                $schper = findSchper_by_perId($value['id']);
                                //得到type/school数组$types/$school;
                                $types    = array_column($schper, 'type');
                                $sch_idsB = array_column($schper, 'sch_id');
                                //重名判断职位类型
                                if (in_array(1, $types) || empty($types)) {
                                    //匹配高校得到高校ID$array:校友所属高校数组
                                    $sch_ids = read_school($school);
                                    //求两个数组元素相似度$arrayAA方$arrayBB方所属高校ID数组
                                    if (frequency($sch_ids, $sch_idsB)) {
                                        //与重名者是同一人，更新个人全部信息
                                        //更新persons表$arrayAA方数据$arrayBB方数据库数据
                                        $this->updatePerson($a, $b);
                                        //补充schper表$arrayB方persons表数据$arrayAA方$arrayBB方所属高校ID数组
                                        suppSchper($b, $sch_ids, $sch_idsB, 1);
                                        //补充pol_perjob表$arrayAA校友当前职位数组$arrayBB方persons表数据
                                        $this->suppPerjob($job, $b);
                                        continue 2;
                                    }
                                }
                            }
                            //插入persons表
                            $insertId = $this->insertPerson($a);
                            //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                            match_school($school, $insertId, 1);
                            //匹配政界职位$array:校友当前职位数组
                            $this->addjob($job, $insertId);
                        }
                    }
                }
            }

            foreach ($urls as $i => $url) {
                curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
                curl_close($conn[$i]);
            }
            curl_multi_close($mh); //关闭一组cURL句柄
        }
        // return $allInfo;
    }
    //所有可能存在的ID
    public function allId()
    {
        for ($i = 1210; $i < 1213; $i++) {
            for ($n = 0; $n < 2000; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        for ($i = 1301; $i < 1313; $i++) {
            for ($n = 0; $n < 121; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        for ($i = 1401; $i < 1413; $i++) {
            for ($n = 0; $n < 501; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        for ($i = 1501; $i < 1513; $i++) {
            for ($n = 0; $n < 351; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        for ($i = 1601; $i < 1613; $i++) {
            for ($n = 0; $n < 101; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        for ($i = 1701; $i < 1706; $i++) {
            for ($n = 0; $n < 101; $n++) {
                $s[] = $i . str_pad($n, 5, "0", STR_PAD_LEFT);
            }
        }
        return $s;
    }
    //querylist个人信息页面与调用自定义函数获取所属高校
    public function QLpeo($array)
    {
        $rules = array(
            'name' => array('.gr_img>strong:eq(0)', 'text'),
            'img'  => array('.gr_img>img', 'src'),
            'job'  => array('.gr_img>p:eq(0)', 'text'),
            'intr' => array('.jili', 'html'),
        );
        $data = QueryList::Query($array, $rules)->getData(function ($item) {
            if (preg_match('/现任/', $item['job'])) {
                $item['job'] = preg_replace('/现任/', '', $item['job']);
            }
            if (preg_match('/，/', $item['job'])) {
                $item['job'] = explode('，', $item['job']);
            } else {
                $item['job'] = explode(',', $item['job']);
            }
            return $item;
        });
        foreach ($data as $key => $value) {
            if (count($value['job']) > 1) {
                $first = mb_substr($value['job'][0], 0, 1);
                for ($i = 1; $i < count($value['job']); $i++) {
                    $str = mb_substr($value['job'][$i], 0, 1);
                    if (preg_match('/' . $str . '/', $value['job'][0])) {
                        if ($str !== $first) {
                            $add              = mb_substr($value['job'][0], 0, strpos($value['job'][0], $str));
                            $value['job'][$i] = $add . $value['job'][$i];
                        }
                    }
                }
            }
            $data = [
                'name'   => trim($value['name']),
                'img'    => trim($value['img']),
                'job'    => $value['job'],
                'intr'   => rtrim(trim($value['intr']), "<br>"),
                'school' => SSSNOW($value['intr']),
            ];
        }
        return $data;
    }
    //插入persons表
    public function insertPerson($array)
    {
        require_once Root_Path . '/Model/persons.php';
        $obj = new persons();
        $obj->insertPerson($array['name'], $array['img'], $array['intr']);
        return insertId();
    }
    //匹配政界职位$array:校友当前职位数组
    public function addjob($array, $num)
    {
        foreach ($array as $value) {
            require_once Root_Path . '/Model/pol_jobs.php';
            require_once Root_Path . '/Model/pol_perjob.php';
            $jobs   = new pol_jobs();
            $perjob = new pol_perjob();
            $row    = $jobs->find_by_name($value);
            if (empty($row)) {
                $jobs->insert($value);
                $insertId = $jobs->insertId();
                $perjob->insert($num, $insertId);
            } else {
                $perjob->insert($num, $row['id']);
            }
        }
    }
    //更新persons表$arrayAA方数据$arrayBB方数据库数据
    public function updatePerson($arrayA, $arrayB)
    {
        require_once Root_Path . '/Model/persons.php';
        $obj = new persons();
        $obj->update($arrayA['img'], $arrayA['intr'], $arrayB['id']);
    }

    //补充pol_perjob表$array:A校友当前职位数组$arrayBB方persons表数据
    public function suppPerjob($arrayA, $arrayB)
    {
        foreach ($arrayA as $value) {
            require_once Root_Path . '/Model/pol_jobs.php';
            require_once Root_Path . '/Model/pol_perjob.php';
            $jobs   = new pol_jobs();
            $perjob = new pol_perjob();
            $row    = $jobs->find_by_name($value);
            if (empty($row)) {
                $jobs->insert($value);
                $insertId = $jobs->insertId();
                $perjob->insert($arrayB['id'], $insertId);
            } else {
                //查pol_perjob表依据per_job与job_id
                $result = $perjob->find_by_perJobid($arrayB['id'], $row['id']);
                if (is_null($result)) {
                    $perjob->insert($arrayB['id'], $row['id']);
                } else {
                    continue;
                }
            }
        }
    }
}

<?php
set_time_limit(0);
//更新中国科学院院士
require_once Root_Path . "/Backend/Script/QueryList/vendor/autoload.php";
require_once Root_Path . "/Backend/back/functions.php";

use QL\QueryList;

class upCAS
{
    public function updateCAS()
    {
        //中国科学院院士简历链接
        $array = $this->url();
        $num   = count($array);
        for ($n = 0; $n < ceil($num / 320); $n++) {
            $urls = array();
            for ($i = 320 * $n; $i < 320 + 320 * $n; $i++) {
                if (array_key_exists($i, $array)) {
                    if (!empty($array[$i])) {
                        @$urls[] = $array[$i];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
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
                //querylist院士简历页面
                $info = $this->QLCAS($data);
                //更新一个院士$string:职业，$array:院士信息
                $array_job = array('中国科学院院士');
                $this->update($array_job, $info);
                $all[] = $info;
            }

            foreach ($urls as $i => $url) {
                curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
                curl_close($conn[$i]);
            }
            curl_multi_close($mh); //关闭一组cURL句柄
        }
        return $all;
    }
    //中国科学院院士简历链接
    public function url()
    {
        require_once Root_Path . '/Backend/Script/learning/CAS.php';
        $obj = new cas();
        return $obj->get();
    }
    //querylist院士简历页面
    public function QLCAS($data)
    {
        $rules = array(
            'name'  => array('.title>h1', 'text'),
            'photo' => array('#zoom>.acadImg>img', 'src'),
            'intr'  => array('#zoom', 'text'),
        );
        @$data = QueryList::Query($data, $rules)->getData(function ($item) {
            $item['name'] = trim($item['name']);
            $item['intr'] = trim($item['intr']);
            return $item;
        });
        return $data;
    }
    //更新一个院士$array_job:职业，$array:院士信息
    public function update($array_job, $array)
    {
        foreach ($array as $key => $value) {
            if ($value['intr'] == '') {
                continue;
            } else {
                $school = SSSNOW($value['intr']);
                if (empty($school)) {
                    continue;
                } else {
                    $name = $value['name'];
                    $job  = $array_job;
                    $a    = $value;
                    //查询person表依据姓名
                    $result = findPer_by_name($name);
                    if (empty($result)) {
                        //插入persons表
                        $insertId = $this->insertPerson($a);
                        //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                        match_school($school, $insertId, 3);
                        //匹配学界职位$array:校友当前职位数组
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
                            if (in_array(3, $types) || empty($types)) {
                                //匹配高校得到高校ID$array:校友所属高校数组
                                $sch_ids = read_school($school);
                                //求两个数组元素相似度$arrayAA方$arrayBB方所属高校ID数组
                                if (frequency($sch_ids, $sch_idsB)) {
                                    //与重名者是同一人，更新个人全部信息
                                    //更新persons表$arrayAA方数据$arrayBB方数据库数据
                                    $this->updatePerson($a, $b);
                                    //补充schper表$arrayB方persons表数据$arrayAA方$arrayBB方所属高校ID数组
                                    suppSchper($b, $sch_ids, $sch_idsB, 3);
                                    //补充lea_perjob表$array:A校友当前职位数组$arrayBB方persons表数据
                                    $this->suppPerjob($job, $b);
                                    continue 2;
                                }
                            }
                        }
                        //插入persons表
                        $insertId = $this->insertPerson($a);
                        //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                        match_school($school, $insertId, 3);
                        //匹配学界职位$array:校友当前职位数组
                        $this->addjob($job, $insertId);
                    }
                }
            }
        }
    }
    //插入persons表
    public function insertPerson($array)
    {
        require_once Root_Path . '/Model/persons.php';
        //判断图片是否为空
        if (preg_match('/\.(jpg|png)/', $array['photo'])) {
            $string = '';
        } else {
            $string = $array['photo'];
        }
        $obj = new persons();
        $obj->insertPerson($array['name'], $string, $array['intr']);
        return insertId();
    }
    //匹配政界职位$array:校友当前职位数组
    public function addjob($array, $num)
    {
        foreach ($array as $value) {
            require_once Root_Path . '/Model/lea_jobs.php';
            require_once Root_Path . '/Model/lea_perjob.php';
            $jobs   = new lea_jobs();
            $perjob = new lea_perjob();
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
        //判断图片是否为空
        if (preg_match('/\.(jpg|png)/', $arrayA['photo'])) {
            $obj->updatePh($arrayA['intr'], $arrayB['id']);
        } else {
            $obj->update($arrayA['photo'], $arrayA['intr'], $arrayB['id']);
        }
    }
    //补充lea_perjob表$array:A校友当前职位数组$arrayBB方persons表数据
    public function suppPerjob($arrayA, $arrayB)
    {
        foreach ($arrayA as $value) {
            require_once Root_Path . '/Model/lea_jobs.php';
            require_once Root_Path . '/Model/lea_perjob.php';
            $jobs   = new lea_jobs();
            $perjob = new lea_perjob();
            $row    = $jobs->find_by_name($value);
            if (empty($row)) {
                $jobs->insert($value);
                $insertId = $jobs->insertId();
                $perjob->insert($arrayB['id'], $insertId);
            } else {
                //查lea_perjob表依据per_job与job_id
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

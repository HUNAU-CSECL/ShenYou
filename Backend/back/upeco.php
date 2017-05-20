<?php
//开启多线程
//更新商界人员
require_once Root_Path . "/Backend/Script/QueryList/vendor/autoload.php";
require_once Root_Path . "/Backend/back/functions.php";
use QL\QueryList;

class upeco
{
    public function updateEco($which)
    {
        //获取需要更新企业的股票代码$array
        $array = $this->Code($which);
        $num   = count($array);
        for ($n = 0; $n < ceil($num / 320); $n++) {
            $urls = array();
            for ($i = 320 * $n; $i < 320 + 320 * $n; $i++) {
                if ($which !== 'usa') {
                    @$urls[] = 'http://stockpage.10jqka.com.cn/' . $array[$i] . '/company/';
                } else {
                    @$urls[] = 'http://stockpage.10jqka.com.cn/' . $array[$i] . '/manager/';
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
                if ($which !== 'usa') {
                    //据url获取国内上市公司股票代码
                    $code = $this->codeURLhssb($url);
                    //querylist国内上市公司页面
                    $company = $this->QLhssb($data);
                    //更新一个企业的人员$string:企业股票代码code，$array:企业人员信息
                    $this->update($code,$company);
                } else {
                    //据url获取中概股公司股票代码
                    $code = $this->codeURLusa($url);
                    //querylist中概公司页面
                    $company = $this->QLusa($data);
                    //更新一个企业的人员$string:企业股票代码code，$array:企业人员信息
                    $this->update($code,$company);
                }
            }

            foreach ($urls as $i => $url) {
                curl_multi_remove_handle($mh, $conn[$i]); //移除curl批处理句柄资源中的某个句柄资源
                curl_close($conn[$i]);
            }
            curl_multi_close($mh); //关闭一组cURL句柄
        }
    }
    //获取需要更新企业的股票代码$array
    public function Code($which)
    {
        $arr = array();
        require_once Root_Path . '/Model/stockcode.php';
        $obj   = new stockcode();
        $array = $obj->$which();
        foreach ($array as $key => $value) {
            $arr[] = $value['code'];
        }
        return $arr;
    }
    //querylist国内上市公司页面
    public function QLhssb($data)
    {
        $rules = array(
            'name' => array('.turnto', 'text'),
            'job'  => array('.jobs', 'text'),
            'intr' => array('.mainintro>div>p:eq(0)', 'text'),
        );
        $data = QueryList::Query($data, $rules, '[class="tc name"]')->getData(function ($item) {
            if (preg_match('/，/', $item['job'])) {
                $item['job'] = explode('，', $item['job']);
            } else {
                $item['job'] = explode(',', $item['job']);
            }
            return $item;
        });
        return $data;
    }
    //querylist中概公司页面
    public function QLusa($data)
    {
        //高管名单
        $rules1 = array(
            'name' => array('.manager-click', 'text'),
            'job'  => array('td:eq(2)', 'text'),
        );
        $data1 = QueryList::Query($data, $rules1, 'tr')->getData(function ($item) {
            if (preg_match('/，/', $item['job'])) {
                $item['job'] = explode('，', $item['job']);
            } else {
                $item['job'] = explode(',', $item['job']);
            }
            return $item;
        });
        //取出含空数组
        foreach ($data1 as $key => $value) {
            if ($value['name'] == '' || $value['job'] == '') {
                unset($data1[$key]);
            }
        }
        //去除$array键值$key重复项
        $data1 = $this->array_unset($data1);
        //高管简介
        $rules2 = array(
            'name' => array('dt', 'text'),
            'intr' => array('dd:eq(0)', 'text'),
        );
        $data2 = QueryList::Query($data, $rules2, '[class="info-list clearfix"]')->data;
        $data2 = $this->array_unset($data2);
        //高管名单和简介汇总
        $data = array();
        foreach ($data2 as $key2 => $value2) {
            $name = $value2['name'];
            $intr = $value2['intr'];
            foreach ($data1 as $key1 => $value1) {
                if ($value1['name'] == $name) {
                    $data[] = [
                        'name' => $name,
                        'job'  => $value1['job'],
                        'intr' => $intr,
                    ];
                }
            }
        }
        return $data;
    }
    //据url获取国内上市公司股票代码
    public function codeURLhssb($url)
    {
        $code = preg_replace('/.*cn\//', '', $url);
        $code = preg_replace('/\/company\//', '', $code);
        return $code;
    }
    //据url获取中概股公司股票代码
    public function codeURLusa($url)
    {
        $code = preg_replace('/.*cn\//', '', $url);
        $code = preg_replace('/\/manager\//', '', $code);
        return $code;
    }
    //去除$array键值$key重复项
    public function array_unset($array)
    {
        $res = array();
        foreach ($array as $value) {
            if (in_array($value, $res)) {
                continue;
            } else {
                $res[] = $value;
            }
        }
        return $res;
    }
    //更新一个企业的人员$string:企业股票代码code，$array:企业人员信息
    public function update($string, $array)
    {
        foreach ($array as $key => $value) {
            if ($value['intr'] == '' || preg_match('/暂无中文简介/', $value['intr'])) {
                continue;
            } else {
                $school = SSSNOW($value['intr']);
                if (empty($school)) {
                    continue;
                } else {
                    //查stockcode依据code得到公司ID
                    $com_id = $this->getComId_by_code($string);
                    $name   = $value['name'];
                    $job    = $value['job'];
                    $a      = $value;
                    //查询person表依据姓名
                    $result = findPer_by_name($name);
                    if (empty($result)) {
                        //插入persons表
                        $insertId = $this->insertPerson($a);
                        //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                        match_school($school, $insertId, 2);
                        //匹配政界职位$array:校友当前职位数组$num2:企业ID
                        $this->addjob($job, $insertId, $com_id);
                    } else {
                        foreach ($result as $key => $value) {
                            $b = $value;
                            //查询schper依据per_id
                            $schper = findSchper_by_perId($value['id']);
                            //得到type/school数组$types/$school;
                            $types    = array_column($schper, 'type');
                            $sch_idsB = array_column($schper, 'sch_id');
                            //重名判断职位类型
                            if (in_array(2, $types) || empty($types)) {
                                //匹配高校得到高校ID$array:校友所属高校数组
                                $sch_ids = read_school($school);
                                //求两个数组元素相似度$arrayAA方$arrayBB方所属高校ID数组
                                if (frequency($sch_ids, $sch_idsB)) {
                                    //与重名者是同一人，更新个人全部信息
                                    //更新persons表$arrayAA方数据$arrayBB方数据库数据
                                    $this->updatePerson($a, $b);
                                    //补充schper表$arrayB方persons表数据$arrayAA方$arrayBB方所属高校ID数组
                                    suppSchper($b, $sch_ids, $sch_idsB, 2);
                                    //补充eco_perjob表$arrayAA校友当前职位数组$arrayBB方persons表数据$num:企业ID
                                    $this->suppPerjob($job, $b, $com_id);
                                    continue 2;
                                }
                            }
                        }
                        //插入persons表
                        $insertId = $this->insertPerson($a);
                        //匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
                        match_school($school, $insertId, 2);
                        //匹配商界职位$array:校友当前职位数组$num2:企业ID
                        $this->addjob($job, $insertId, $com_id);
                    }
                }
            }
        }
    }
    //插入persons表(除photo)
    public function insertPerson($array)
    {
        require_once Root_Path . '/Model/persons.php';
        $obj = new persons();
        $obj->insertPersonPh($array['name'], $array['intr']);
        return insertId();
    }
    //更新persons表(除photo)$arrayAA方数据$arrayBB方数据库数据
    public function updatePerson($arrayA, $arrayB)
    {
        require_once Root_Path . '/Model/persons.php';
        $obj = new persons();
        $obj->updatePh($arrayA['intr'], $arrayB['id']);
    }
    //匹配政界职位$array:校友当前职位数组$num2:企业ID
    public function addjob($array, $num1, $num2)
    {
        foreach ($array as $value) {
            require_once Root_Path . '/Model/eco_jobs.php';
            require_once Root_Path . '/Model/eco_perjob.php';
            $jobs   = new eco_jobs();
            $perjob = new eco_perjob();
            $row    = $jobs->find_by_name($value);
            if (empty($row)) {
                $jobs->insert($value);
                $insertId = $jobs->insertId();
                $perjob->insert($num1, $num2, $insertId);
            } else {
                $perjob->insert($num1, $num2, $row['id']);
            }
        }
    }
    //查stockcode依据code得到公司ID
    public function getComId_by_code($string)
    {
        require_once Root_Path . '/Model/stockcode.php';
        $obj    = new stockcode();
        $result = $obj->find_by_code($string);
        return $result['id'];
    }
    //补充eco_perjob表$array:A校友当前职位数组$arrayBB方persons表数据$num:企业ID
    public function suppPerjob($arrayA, $arrayB, $num)
    {
        foreach ($arrayA as $value) {
            require_once Root_Path . '/Model/eco_jobs.php';
            require_once Root_Path . '/Model/eco_perjob.php';
            $jobs   = new eco_jobs();
            $perjob = new eco_perjob();
            $row    = $jobs->find_by_name($value);
            if (empty($row)) {
                $jobs->insert($value);
                $insertId = $jobs->insertId();
                $perjob->insert($arrayB['id'], $num, $insertId);
            } else {
                //查pol_perjob表依据per_job与job_id
                $result = $perjob->find_by_perJobid($arrayB['id'], $row['id']);
                if (is_null($result)) {
                    $perjob->insert($arrayB['id'], $num, $row['id']);
                } else {
                    continue;
                }
            }
        }
    }
}

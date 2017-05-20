<?php
//人民网
header('Content-Type: text/html; charset=utf-8');
require_once Root_Path . "/Backend/Script/functions.php";
require_once Root_Path . "/Backend/Script/Politics/functions.php";
require_once Root_Path . "/Backend/back/functions.php";
class uppol
{
    public $flase = array(); //收集网页改版的名称
    //$array:Politics下文件名数组
    public function update($array)
    {
        foreach ($array as $value) {
            if ($value == 'peoplecn') {
                require Root_Path . '/Backend/Script/Politics/peoplecn.php';
                $obj = new peoplecn();
                $obj->get();
            } else {
                //获取谋一个政界脚本的返回值
                $data = $this->Script($value);
                //判断网页是否改版
                if (ISFULL($data)) {
                    $flase[] = $value;
                    continue;
                }
                foreach ($data as $key => $value) {
                    if (empty($value['school'])) {
                        continue;
                    } else {

                        $name   = $value['name'];
                        $school = $value['school'];
                        $job    = $value['job'];
                        $a      = $value;
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
        }
    }
    //获取谋一个政界脚本的返回值
    public function Script($string)
    {
        require_once Root_Path . '/Backend/Script/Politics/' . $string . '.php';
        $obj   = new $string();
        $array = $obj->get();
        return $array;
    }
    //插入persons表
    public function insertPerson($array)
    {
        require_once Root_Path . '/Model/persons.php';
        $obj = new persons();
        $obj->insertPerson($array['name'], $array['img'], $array['intr']);
        return insertId();
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
}

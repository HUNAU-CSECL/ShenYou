<?php
function SSSNOW($string)
{
    require_once Root_Path . '/Backend/back/yue.php';
    $obj = new yue();
    return $obj->SSSNOW($string);
}
//查询person表依据姓名
function findPer_by_name($string)
{
    require_once Root_Path . "/Model/persons.php";
    $obj = new persons();
    return $obj->find_by_name($string);
}
// //插入persons表的id
function insertId()
{
    require_once Root_Path . '/Model/persons.php';
    $obj = new persons();
    return $obj->insertId();
}
//查询schper依据per_id
function findSchper_by_perId($num)
{
    require_once Root_Path . '/Model/schper.php';
    $obj = new schper();
    return $obj->find_by_perid($num);
}
//求两个数组元素相似度$arrayAA方$arrayBB方所属高校ID数组
function frequency($arrayA, $arrayB)
{
    if (!empty($arrayB)) {
        $total = array_merge($arrayB, $arrayA);
        $each  = array_count_values($total);
        foreach ($each as $key => $value) {
            if ($value > 1) {
                return true;
                break;
            } else {
                continue;
            }
        }
    } else {
        return true;
    }

}
//查school依据校名
function findSch_by_name($string)
{
    require_once Root_Path . '/Model/school.php';
    $obj = new school();
    return $obj->find_by_name($string);
}
//查old依据校名
function findOld_by_name($string)
{
    require_once Root_Path . '/Model/old.php';
    $obj = new old();
    return $obj->find_by_name($string);
}
//插入old表（不带now_id）
function insertOld($string)
{
    require_once Root_Path . '/Model/old.php';
    $obj = new old();
    $obj->insert($string);
}
//插入schper表
function insertSchper($per_id, $sch_id, $type)
{
    require_once Root_Path . '/Model/schper.php';
    $obj = new schper();
    $obj->insert($per_id, $sch_id, $type);
}
//匹配高校$array:校友所属高校数组 $type:政界1商界2学界3
function match_school($array, $num, $type)
{
    foreach ($array as $value) {
        $row = findSch_by_name($value);
        if (!empty($row)) {
            insertSchper($num, $row['id'], $type);
        } else {
            $rowOld = findOld_by_name($value);
            if (!empty($rowOld['now_id'])) {
                insertSchper($num, $rowOld['now_id'], $type);
            } else {
                if (!empty($rowOld)) {
                    continue;
                } else {
                    preg_match('/.*?(大学|学院|学校)/', $value, $matched);
                    $school = $matched[0];
                    $row    = findSch_by_name($school);
                    if (!empty($row)) {
                        insertSchper($num, $row['id'], $type);
                    } else {
                        $rowOld = findOld_by_name($school);
                        if (!empty($rowOld['now_id'])) {
                            insertSchper($num, $rowOld['now_id'], $type);
                        } else {
                            if (empty($rowOld)) {
                                insertOld($school);
                            } else {
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }
}
//匹配高校得到高校ID$array:校友所属高校数组
function read_school($array)
{
    $ids = array();
    if (!empty($array)) {
        foreach ($array as $value) {
            $row = findSch_by_name($value);
            if (empty($row)) {
                $rowOld = findOld_by_name($value);
                if (empty($rowOld['now_id'])) {
                    preg_match('/.*?(大学|学院|学校)/', $value, $matched);
                    $school = $matched[0];
                    $row    = findSch_by_name($school);
                    if (empty($row)) {
                        $rowOld = findOld_by_name($school);
                        if (!empty($rowOld['now_id'])) {
                            $id = $rowOld['now_id'];
                        } elseif (!empty($rowOld)) {
                            $id = $value;
                        } else {
                            continue;
                        }
                    } else {
                        $id = $row['id'];
                    }
                } else {
                    $id = $rowOld['now_id'];
                }
            } else {
                $id = $row['id'];
            }
            $ids[] = $id;
        }
        return $ids;
    } else {
        return $ids;
    }
}
//补充schper表$arrayB方persons表数据$arrayAA方$arrayBB方所属高校ID数组
function suppSchper($array, $arrayA, $arrayB, $type)
{
    foreach ($arrayA as $key => $value) {
        if (!in_array($value, $arrayB)) {
            if (is_numeric($value)) {
                insertSchper($array['id'], $value, $type);
            } else {
                $rowOld = findOld_by_name($value);
                if (empty($rowOld)) {
                    insertOld($value);
                } else {
                    continue;
                }
            }
        } else {
            continue;
        }
    }
}
//自动更新商界人影响力$string:职位名称$num:职位等级
function autoEcoGrade($string, $num)
{
    require_once Root_Path . '/Model/eco_jobs.php';
    require_once Root_Path . '/Model/eco_perjob.php';
    require_once Root_Path . '/Model/stockcode.php';

    $eco_jobs   = new eco_jobs();
    $eco_perjob = new eco_perjob();
    $stockcode  = new stockcode();

    $job = $eco_jobs->find_by_name($string);
    $job_id = $job['id'];

    $perjobs = $eco_perjob->find_by_jobid($job_id);
    foreach ($perjobs as $key1 => $value1) {
        $company = $stockcode->find_by_id($value1['com_id']);
        $grade   = mb_strlen(floor($company['market']))*$num;
        $eco_perjob->insertGrade($value1['id'], $grade);
    }
}

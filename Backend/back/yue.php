<?php
require_once Root_Path . "/Backend/Script/functions.php";
class yue
{
    public function SSSNOW($string)
    {
        $school = array();
        if (preg_match('/(大学|学院|学校)/', $string)) {
            //匹配school全部高校名
            $now = $this->allNowSchool();
            foreach ($now as $value) {
                if (strpos($string, $value['name']) !== false) {
                    $school[] = $value['name'];
                } else {
                    continue;
                }
            }

            //匹配old全部高校名
            $old = $this->allOldSchool();
            foreach ($old as $value) {
                if (strpos($string, $value['name']) !== false) {
                    $school[] = $value['name'];
                } else {
                    continue;
                }
            }

            //调用BosonNLP
            // if (empty($school)) {
            //     $school = NLP($string);
            // }

            return $school;
        } else {
            return $school;
        }
    }
    //查school全部高校名
    public function allNowSchool()
    {
        require_once Root_Path . '/Model/school.php';
        $obj = new school();
        return $obj->allName();
    }
    //查old全部高校名
    public function allOldSchool()
    {
        require_once Root_Path . '/Model/old.php';
        $obj = new old();
        return $obj->allName();
    }
}

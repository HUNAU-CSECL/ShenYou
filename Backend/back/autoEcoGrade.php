<?php
//自动更新商界校友影响力
set_time_limit(0);
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Backend/back/functions.php';
require_once Root_Path . '/Model/eco_jobs.php';

$eco_jobs = new eco_jobs();
$rows     = $eco_jobs->allNullGrade();

foreach ($rows as $key => $value) {

    if (preg_match('/(副董事长|董事长助理|副行长|行长助理)/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 3);
        //自动更新商界人影响力$string:职位ID$num:职位等级
        autoEcoGrade($value['name'], 3);

    } elseif (preg_match('/(董事长|创始人|行长)/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 4);
        autoEcoGrade($value['name'], 4);

    } elseif (preg_match('/(董事|监事)/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 3);
        autoEcoGrade($value['name'], 3);

    } elseif (preg_match('/(副总裁|总裁助理|副总经理|副经理|经理助理)/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 1);
        autoEcoGrade($value['name'], 1);

    } elseif (preg_match('/(总裁|总经理|CEO|执行官)/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 2);
        autoEcoGrade($value['name'], 2);

    } elseif (preg_match('/总监|总.*?|.*?师|.*?官|C.*?O/', $value['name'])) {

        $eco_jobs->insertGrade($value['name'], 1);
        autoEcoGrade($value['name'], 1);

    }

}
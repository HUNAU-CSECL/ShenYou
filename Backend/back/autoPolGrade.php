<?php
//自动更新政界校友影响力
set_time_limit(0);
define('Root_Path', preg_replace('/\\\Backend\\\back/', '', dirname(__FILE__)));
require_once Root_Path . '/Model/pol_jobs.php';

$pol_jobs = new pol_jobs();
$rows     = $pol_jobs->allNullGrade();

foreach ($rows as $key => $value) {

    if (preg_match('/(国家主席|中央军委主席|国务院总理|中央政治局常委|中央委员会总书记|中央军事委员会主席|国主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 1);

    } elseif (preg_match('/(中央军委副主席|全国人大常委会委员|国务院副总理|国务委员|中央统战部部长|副国级|中央委员|最高.*?检查长|最高.*?法院.*?长)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 2);

    } elseif (preg_match('/(县委.*?副秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 9);

    } elseif (preg_match('/(副乡长|副镇长|县.*?副局长|县.*?局长助理|县.*?副主任|县.*?主任助理|县.*?副行长|县.*?行长助理|县.*?银行.*?副书记|县.*?银行.*?书记助理|县.*?副会长|县.*?会长助理|县.*?副秘书|县.*?副主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 10);

    } elseif (preg_match('/(县委.*?秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 8);

    } elseif (preg_match('/(乡长|镇长|县.*?局长|县.*?主任|县.*?行长|县.*?银行.*?书记|县.*?会长|县.*?秘书|县.*?主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 9);

    } elseif (preg_match('/(市委.*?副秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 7);

    } elseif (preg_match('/(县.*?委.*?副书记|副区长|区委.*?副书记|副县长|县人大副主任|县政协副主席|市.*?副局长|市.*?局长助理|市.*?副部长|学院.*?党委.*?副书记|学院.*?副院长|副县级|市.*?副主任|市.*?主任助理|市.*?副行长|市.*?行长助理|市.*?银行.*?副书记|市.*?银行.*?书记助理|市.*?副会长|市.*?会长助理|市.*?副秘书|市.*?副主席|县.*?常委|区.*?常委|县.*?主委|区.*?主委|县.*?检察长|区.*?检察长|县.*?法院.*?长|区.*?法院.*?长)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 8);

    } elseif (preg_match('/(市委.*?秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 6);

    } elseif (preg_match('/(县.*?委.*?书记|县长|区长|区委.*?书记|县人大主任|县政协主席|市.*?局长|市.*?部长|.*?处|学院.*?党委.*?书记|学院.*?院长|正县|市.*?主任|市.*?行长|市.*?银行.*?书记|市.*?会长|市.*?秘书|市.*?主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 7);

    } elseif (preg_match('/(省委.*?副秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 5);

    } elseif (preg_match('/(市.*?委.*?副书记|副市长|副州长|州.*?委.*?副书记|市.*?委.*?常委|市纪委.*?书记|市政法委.*?书记|市.*?党组副书记|省.*?副厅长|副局|省.*?副部长|市委.*?副书记|市人大.*?副主任|市.*?副主席|市.*?秘书|大学.*?党委.*?副书记|大学.*?副校长|党校副校长|副厅级|区.*?副部长|省.*?副部长|区.*?部长助理|省.*?部长助理|省.*?副局长|省.*?局长助理|省.*?主任助理|省.*?副行长|省.*?行长助理|省.*?银行.*?副书记|省.*?银行.*?书记助理|省.*?副会长|省.*?会长助理|省.*?副秘书|省.*?副主席|市.*?常委|州.*?常委|市.*?主委|州.*?主委|市.*?检察长|州.*?检察长|市.*?法院.*?长|州.*?法院.*?长)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 6);

    } elseif (preg_match('/(省委.*?秘书)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 4);

    } elseif (preg_match('/(市.*?委.*?书记|区.*?委.*?书记|州.*?委.*?书记|州长|市长|市.*?党组书记|市人大.*?主任|市.*?主席|省.*?厅长|省.*?部长|大学.*?党委.*?书记|大学.*?校长|党校校长|正厅|正局|省.*?副司令|省.*?副政委|区.*?部长|省.*?部长|省.*?局长|省.*?主任|省.*?行长|省.*?银行.*?书记|副董事长|副总裁|副.*?经理|董事|公司.*?副书记|集团.*?副书记|副行长|银行.*?副书记|行长助理|银行.*?书记助理|省.*?会长|副省委|省.*?秘书|副秘书|公司.*?副主席|集团.*?副主席|省.*?主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 5);

    } elseif (preg_match('/(人民银行行长|人民银行.*?书记)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 3);

    } elseif (preg_match('/(省.*?委.*?副书记|副省长|省政协副主席|自治区党委.*?书副记|省人大常委|省纪委.*?书记|省政法委.*?书记|省委常委|省委|中央统战部副部长|副部级|省.*?司令|省.*?政委|副部长|部长助理|副局长|局长助理|副主任|主任助理|行长|人民银行副行长|董事长|总裁|经理|公司.*?书记|集团.*?书记|银行.*?书记|副会长|会长助理|秘书|公司.*?主席|集团.*?主席|主席|省.*?常委|自治区.*?常委|省.*?主委|自治区.*?主委|省.*?检察长|自治区.*?检察长|省.*?法院.*?长|自治区.*?法院.*?长)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 4);

    } elseif (preg_match('/(省.*?委.*?书记|省长|自治区党委.*?书记|省政协主席|省人大常委会主任|外交部部长|正部|部长|局长|主任|会长|副主席)/', $value['name'])) {

        $pol_jobs->insertGrade($value['name'], 3);

    }
}

<?php
function NLP($string)
{
    $data = json_encode(strip_tags($string));
    $url  = "http://api.bosonnlp.com/ner/analysis?sensitivity=5";
    $ch   = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept:application/json',
        //'X-Token:193UQNfN.13315.gYVPoguU5aMq',
         'X-Token:L65qBRtS.3796.j7U8BHuexNLp',
        //'X-Token:xDpuARoJ.13722.zcbO-H3rE1J3',
        //'X-Token:iL1Xx7TL.13909.kAx609er1cu7',
    ));
    $response = curl_exec($ch);
    $a        = curl_multi_getcontent($ch);
    curl_close($ch);
    $b       = json_decode($a, true);
    $word    = $b[0]['word'];
    $entity  = $b[0]['entity'];
    $schools = array();
    foreach ($entity as $key => $value) {
        if ($value[2] == 'org_name') {
            $start = $value[0];
            $end   = $value[1];
            $much  = '';
            for ($i = $start; $i < $end; $i++) {
                $much .= $word[$i];
            }
            if (preg_match('/(大学|学院|学校)/', $much)) {
                $many = '';
                $many = $much;
            } else {
                continue;
            }
        } else {
            continue;
        }
        $schools[] = $many;
    }
    return array_unique($schools);
}

<?php


function success($data = null, $message = '')
{
    $ret = [
        'code'    => 1,
        'message' => '成功',
    ];
    if (!is_bool($data)) {
        if (is_array($data)) {
            $ret['data'] = $data;
        }else if(is_string($data)){
            $ret['message'] = $data;
        }else{
            $ret['message'] = '成功';
        }
    }
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: OPTIONS, POST, GET");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,Accept-Language,TIMEZONE,Cache-Control,CMS-SESSION-ID");
    header("Access-Control-Max-Age: 20");
    header("Pragma:no-cache");
    header("Cache-Control:no-cache,must-revalidate");
    header('Content-type: application/json;charset=utf-8');
    echo json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;  
}




function error($message = '失败')
{
    $ret = [
        'code'    => 0,
        'message' => $message,
    ];
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: OPTIONS, POST, GET");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization,Accept-Language,TIMEZONE,Cache-Control,CMS-SESSION-ID");
    header("Access-Control-Max-Age: 20");
    header("Pragma:no-cache");
    header("Cache-Control:no-cache,must-revalidate");
    header('Content-type: application/json;charset=utf-8');
    echo json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;  
}



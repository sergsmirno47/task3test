<?php

require_once 'core/cfg.php';

$user = new CController();
//var_dump($_POST); exit;
$params = array();
parse_str($_POST['all_user_data'], $params);

$params['first-name'] = trim($params['first-name']);
if(empty($params['first-name']))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'first name is empty';
    echo json_encode($response);
    die();
}
$params['last-name'] = trim($params['last-name']);
if(empty($params['last-name']))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'last name is empty';
    echo json_encode($response);
    die();
}
if(empty($params['user-role']))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'user role is empty';
    echo json_encode($response);
    die();
}
else
{
    $params['user-role'] = intval($params['user-role']);
}
if($params['user-status'] == 'on')
{
    $params['user-status'] = 1;
}
else
{
    $params['user-status'] = 0;
}
$params['user-id-hidd'] = intval($params['user-id-hidd']);
//var_dump($params); exit;
if($params['user-id-hidd'] && $params['user-act-hidd'] == 'upd')
{
    $arResult = $user -> UpdateUserData($params);
    
    if($arResult)
    {
        $response['error'] = NULL;
        $response['user']['id'] = $params['user-id-hidd'];
        $response['user']['first_name'] = $params['first-name'];
        $response['user']['last_name'] = $params['last-name'];
        $response['user']['status'] = $params['user-status'];
        $response['user']['role'] = $params['user-role'];
        echo json_encode($response);
        die();
    }
    else
    {
        $response['error']['code'] = 1;
        $response['error']['message'] = 'Cant update User((';
        echo json_encode($response);
        die();
    }
    
}

if($params['user-act-hidd'] == 'add')
{
    $arResult = $user -> AddUser($params);
    //var_dump($arResult); exit;
    
    if($arResult)
    {//{ ["id"]=> string(2) "17" ["first_name"]=> string(2) "33" ["last_name"]=> string(2) "33" ["status"]=> string(1) "1" ["role"]=> string(1) "2" }
        $response['error'] = NULL;
        $response['user']['id'] = $arResult['id'];
        $response['user']['first_name'] = $arResult['first_name'];
        $response['user']['last_name'] = $arResult['last_name'];
        $response['user']['status'] = $arResult['status'];
        $response['user']['role'] = $arResult['role'];
        echo json_encode($response);
        die();
    }
    else
    {
        $response['error']['code'] = 1;
        $response['error']['message'] = 'Cant add User((';
        echo json_encode($response);
        die();
    }
}


//*/
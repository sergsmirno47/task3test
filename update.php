<?php
require_once 'core/closePages.php';
require_once 'core/cfg.php';

$user = new CController();

$params = $_POST;

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
$params['user-status'] = intval($params['user-status']);

//var_dump($params); exit;
if($params['user-act-hidd'] == 'upd')
{
    if(empty($params['user-id-hidd']))
    {
        $response['error']['code'] = 1;
        $response['error']['message'] = 'cant fing user ID';
        echo json_encode($response);
        die();
    }
    else
    {
        $params['user-id-hidd'] = intval($params['user-id-hidd']);
    }
    
    $arResult = $user -> UpdateUserData($params);

    echo json_encode($arResult);
    die();    
}
elseif($params['user-act-hidd'] == 'add')
{
    $arResult = $user -> AddUser($params);
    //var_dump($arResult); exit;
    
    if($arResult)
    {
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
else
{
    $response['error']['code'] = 1;
    $response['error']['message'] = 'action is wrong';
    echo json_encode($response);
    die();
}


//*/
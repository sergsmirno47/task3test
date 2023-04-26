<?php

require_once 'core/cfg.php';

$user = new CController();
//var_dump($_POST); exit;
$status = intval($_POST['status']);
$arrId = $_POST['arrId'];
//var_dump($arrId); exit;
if($status !== 1 && $status !== 2)
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'action is wrong';
    echo json_encode($response);
    die();
}
if(!is_array($arrId))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'Select user, please';
    echo json_encode($response);
    die();
}
if($status == 2)
{
    $status = 0;
}
//var_dump('+'); exit;

$arResult = $user -> UpdateUsersStatus($arrId, $status);
//var_dump($arResult); exit;

if($arResult)
{
    $response['error'] = NULL;
    $response['user']['id'] = $arrId;
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

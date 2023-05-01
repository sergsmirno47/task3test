<?php

require_once 'core/cfg.php';

$users = new CController();
//var_dump($_POST); exit;

$id = intval($_POST['id']);
//var_dump($id); exit;

if(empty($id))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'ID is empty';
    echo json_encode($response);
    exit;
}

$arResult = $users -> GetUserData($id);
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
    exit;
}
else
{
    $response['error']['code'] = 1;
    $response['error']['message'] = 'Cant find User((';
    echo json_encode($response);
    exit;
}
<?php

require_once 'core/cfg.php';

$users = new CController();

$arrId = $_POST['arrId'];

if(empty($arrId))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'ID is empty';
    echo json_encode($response);
    die();
}

$arResult = $users -> DelUsers($arrId);

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
    $response['error']['message'] = 'Cant delete Users((';
    echo json_encode($response);
    die();
}

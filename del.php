<?php
require_once 'core/closePages.php';
require_once 'core/cfg.php';

$users = new CController();

$arrId = $_POST['arrId'];

if(empty($arrId))
{
    $response['error']['code'] = 2;
    $response['error']['message'] = 'ID is empty';
    echo json_encode($response);
    exit();
}

$arResult = $users -> DelUsers($arrId);
//var_dump($arResult); exit;
echo json_encode($arResult);
exit();
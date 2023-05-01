<?php

require_once 'core/cfg.php';

$user = new CController();
//var_dump($_POST); exit;
$status = intval($_POST['status']);
$arrId = $_POST['arrId'];
//var_dump($arrId); exit;

if(empty($arrId))
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

echo json_encode($arResult);
die(); 

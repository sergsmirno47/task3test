<?php
require_once 'core/closePages.php';
require_once 'core/cfg.php';

$user = new CController();

$status = intval($_POST['status']);
$arrId = $_POST['arrId'];

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

$arResult = $user -> UpdateUsersStatus($arrId, $status);

echo json_encode($arResult);
die(); 

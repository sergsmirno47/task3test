<?php

error_reporting(E_ERROR);

require_once 'model.php';
require_once 'controller.php';

function pr($s){
    echo '<pre><span style="color:red;">';
    print_r($s);
    echo '</span></pre><br>';
}
?>
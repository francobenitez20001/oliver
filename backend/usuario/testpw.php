<?php 
    $pw = $_GET['pw'];
    $hash = password_hash($pw,PASSWORD_DEFAULT);
    echo $hash;
?>
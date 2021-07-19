<?php
    require '../config.php';
    $local = new Local(null,$_POST['nombre'],$_POST['estado']);
    $response = $local->addLocal();
    if ($response){
        echo json_encode(array("ok"=>true));
    }else{
        echo json_encode(array("ok"=>false));
    }
?>
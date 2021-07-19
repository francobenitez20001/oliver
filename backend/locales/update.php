<?php
    require '../config.php';
    if(is_null($_POST['idLocal'])){
        echo json_encode(array(
            'ok'=>false
        ));
    }else{
        $local = new Local($_POST['idLocal'],$_POST['nombre'],$_POST['estado']);
        $data = $local->updateLocal();
        if($data){
            echo json_encode(array(
                'ok'=>true
            ));
        }else{
            echo json_encode(array(
                'ok'=>false
            ));
        }
    }

?>
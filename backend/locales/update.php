<?php
    require '../config.php';
    /// Obtenemos el json enviado
    $data = file_get_contents('php://input');
    // Los convertimos en un array
    $data = json_decode( $data, true );

    if(is_null($_GET['idLocal'])){
        return json_encode(array(
            'ok'=>false
        ));
    }

    $local = new Local($_GET['idLocal'],$data['nombre'],$data['estado']);
    $data = $local->update();
    if($data){
        return json_encode(array(
            'ok'=>true
        ));
    }
    return json_encode(array(
        'ok'=>false
    ));
?>
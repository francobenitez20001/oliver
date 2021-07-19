<?php
    require '../config.php';
    /// Obtenemos el json enviado
    $data = file_get_contents('php://input');
    // Los convertimos en un array
    $data = json_decode( $data, true );

    $local = new Local(null,$data['nombre'],$data['estado']);
    $ok = $local->add();
    if($ok){
        return json_encode(array(
            'ok'=>true
        ));
    }
    return json_encode(array(
        'ok'=>false
    ));
?>
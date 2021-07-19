<?php
    require '../config.php';
    $id = isset($_GET['idLocal']) ? $_GET['idLocal'] : null;
    $local = new Local($id);
    $data = $local->get();
    $json = array();
    foreach ($data as $item) {
        $json[] = array(
            'idLocal' => $item['idLocal'],
            'nombre' => $item['nombre'],
            'estado' => $item['estado']
        );
    }
    echo json_encode(array(
        'ok'=>"true",
        'data'=>$json
    ));
?>
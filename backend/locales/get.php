<?php
    require '../config.php';
    $id = $_GET['idLocal'] ? $_GET['idLocal'] : null;
    $local = new Local($idLocal);
    $data = $local->get();
    $json = array();
    foreach ($data as $item) {
        $json[] = array(
            'idLocal' => $item['idLocal'],
            'nombre' => $item['nombre'],
            'estado' => $item['estado']
        );
    }
    return json_encode($json);
?>
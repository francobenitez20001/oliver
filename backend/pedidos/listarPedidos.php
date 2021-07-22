<?php
    require '../config.php';
    $pedido = new Pedido;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : null;
    $data = $pedido->listarPedidos($limit);
    echo $data;
?>
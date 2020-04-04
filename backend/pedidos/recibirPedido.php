<?php
    require '../classes/Conexion.php';
    require '../classes/Pedido.php';
    $pedido = new Pedido;
    $bool = $pedido->recibirPedido();
    echo $bool;
?>
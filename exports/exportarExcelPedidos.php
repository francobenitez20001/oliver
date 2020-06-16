<?php
    header('Content-type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment;filename=pedidos.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
    require '../backend/classes/Conexion.php';
    require '../backend/classes/Pedido.php';
    $pedido = new Pedido;
    $pedidos = $pedido->listarPedidos();    
    $pedidosArray = json_decode($pedidos,true);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Pedidos</title>
    <script src="js/autenticacion.js"></script>
    <link rel="stylesheet" href="../css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/animate.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../css/sweetalert2.css">
</head>
<body>
    <table class="table text-center fadeIn fast" id="tablaPedidos">
        <thead class="thead-light">
        <tr>
            <th scope="col">Descripción</th>
            <th scope="col">Cantidad</th>
            <th scope="col">Estado</th>
            <th scope="col">Proveedor</th>
            <th scope="col">Fecha</th>
            <th scope="col">Total</th>
        </tr>
        </thead>
        <tbody id="bodyTable">
            <?php
            for ($i=0; $i < count($pedidosArray); $i++) { 
                for ($i=0; $i < $pedidosArray[$i]; $i++) { ?>
                    <tr>
                        <th scope="row"><?php echo $pedidosArray[$i]['descripcion'];?></th>
                        <td><?php echo $pedidosArray[$i]['cantidad']; ?></td>
                        <td><?php echo $pedidosArray[$i]['estado']; ?></td>
                        <td><?php echo $pedidosArray[$i]['proveedor']; ?></td>
                        <td><?php echo $pedidosArray[$i]['fecha']; ?></td>
                        <td><?php echo $pedidosArray[$i]['total']; ?></td>
                    </tr>
                <?php }
            }?>
        </tbody>
    </table><!--tabla productos-->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <script src="../js/sweetalert2.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/app.js"></script>
    <script src="js/pedidos.js"></script>
</body>
<?php
    header('Content-type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment;filename=productos.xls');
    header('Pragma: no-cache');
    header('Expires: 0');
    require '../backend/classes/Conexion.php';
    require '../backend/classes/Producto.php';
    $producto = new Producto;
    $productos = $producto->listarProducto();    
    $productosArray = json_decode($productos,true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Productos</title>
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
            <th scope="col">Producto</th>
            <th scope="col">Stock</th>
            <th scope="col">Stock suelto</th>
            <th scope="col">Precio público</th>
            <th scope="col">Precio Unitario</th>
            <th scope="col">Precio Kilo</th>
            <th scope="col">Precio Costo</th>
            <th scope="col">Porcentaje de ganancia</th>
            <th scope="col">Proveedor</th>
        </tr>
        </thead>
        <tbody id="bodyTable">
            <?php
            for ($i=0; $i < count($productosArray); $i++) { 
                for ($i=0; $i < $productosArray[$i]; $i++) {?>
                    <tr>
                        <th scope="row"><?php echo $productosArray[$i]['producto'];?></th>
                        <td><?php echo $productosArray[$i]['stock']; ?></td>
                        <td><?php echo $productosArray[$i]['stock_suelto']; ?></td>
                        <td><?php echo $productosArray[$i]['precioPublico']; ?></td>
                        <td><?php echo $productosArray[$i]['precioUnidad']; ?></td>
                        <td><?php echo $productosArray[$i]['precioKilo']; ?></td>
                        <td><?php echo $productosArray[$i]['precio_costo']; ?></td>
                        <td><?php echo $productosArray[$i]['porcentaje_ganancia']; ?></td>
                        <td><?php echo $productosArray[$i]['proveedor']; ?></td>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table><!--tabla productos-->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <script src="../js/sweetalert2.js"></script>
    <script src="../js/menu.js"></script>
    <script src="../js/app.js"></script>
</body>
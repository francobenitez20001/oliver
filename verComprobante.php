<?php
    require 'backend/classes/Conexion.php';
    require 'backend/classes/Servicio.php';
    $servicio = new Servicio;
    $response = $servicio->verComprobante();
    $comprobante;
    foreach ($response as $comprobante) {
        $comprobante = $comprobante['comprobante'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante</title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
</head>
<body>
    <div class="container">
        <img class="img-fluid" src="comprobantes/<?php echo $comprobante ?>" alt="">
    </div>
</body>
</html>


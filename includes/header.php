<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistema administrador - Local </title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/main.css?v=1.0.0">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/fontawesome/css/all.css">
    <link rel="stylesheet" href="css/sweetalert2.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
    <a class="navbar-brand logo" href="home.php">
      <img src="assets/img/logo.png" class="img-fluid" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php $_SESSION['REQUEST_URI'] == '/oliver/productos.php' ? 'active' : '' ?>">
          <a class="nav-link" href="productos.php">Productos</a>
        </li>
        <li class="nav-item <?php $_SESSION['REQUEST_URI'] == '/oliver/pedidos.php' ? 'active' : '' ?>">
          <a class="nav-link" href="pedidos.php">Pedidos</a>
        </li>
        <li class="nav-item <?php $_SESSION['REQUEST_URI'] == '/oliver/deudores.php' ? 'active' : '' ?>">
          <a class="nav-link" href="deudores.php">Deudores</a>
        </li>
        <li class="nav-item <?php $_SESSION['REQUEST_URI'] == '/oliver/envios.php' ? 'active' : '' ?>">
          <a class="nav-link" href="envios.php">Envíos</a>
        </li>
        <li class="nav-item <?php $_SESSION['REQUEST_URI'] == '/oliver/servicios.php' ? 'active' : '' ?>">
          <a class="nav-link" href="servicios.php">Servícios</a>
        </li>
        <!-- <li class="nav-item">
          <a href="login.html" class="nav-link"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
        </li> -->
        <?php if(isset($_SESSION['logueado'])){?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fas fa-user"></i>
              Hola <?php echo $_SESSION['user']['name']; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" id="cerrarSesion">Cerrar sesión</a>
            </div>
          </li>
        <?php }; ?>
        <?php if ($_SERVER['REQUEST_URI'] == '/oliver/productos.php'){?>
          <li class="nav-item" id="icon-menu-li">
            <a id="icon-menu" class="nav-link d-xs-none d-sm-none d-md-block"><i class="fas fa-bars"></i></a>
          </li>
        <?php }; ?>
      </ul>
    </div>
  </nav><!--menu-->
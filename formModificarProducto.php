<?php
    require 'backend/classes/Conexion.php';
    require 'backend/classes/Producto.php';
    require 'backend/classes/Marca.php';
    require 'backend/classes/Categoria.php';
    $producto = new Producto;
    $marca = new Marca;
    $categoria = new Categoria;
    $reg = $producto->verProductoPorId();
    $listadoMarca = $marca->listarMarca();
    $listadoCategoria = $categoria->listarCategoria();
    $arrayMarca = json_decode($listadoMarca,true);
    $arrayCategoria = json_decode($listadoCategoria,true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin productos</title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="js/autenticacion.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
    <a class="navbar-brand logo" href="home.html">
      <img src="assets/img/logo.png" class="img-fluid" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link active" href="adminProductos.html">Productos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adminPedidos.html">Pedidos</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adminDeudores.html">Deudores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="adminEnvios.html">Envíos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="adminEnvios.html">Servícios</a>
        </li>
        <!-- <li class="nav-item">
          <a href="login.html" class="nav-link"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!--contenido desde menu.js-->
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" id="cerrarSesion">Cerrar sesión</a>
          </div>
        </li>
        <li class="nav-item d-none" id="icon-menu-li">
          <a id="icon-menu" class="nav-link d-xs-none d-sm-none d-md-block"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </div>
  </nav><!--menu-->
  <div class="container mt-3 fadeIn fast" id="form-modificar-div">
    <div class="alert alert-success d-none" id="alert-warning">Se ha modificado el producto con éxito</div>
    <a class="btn btn-warning" href="adminProductos.html">Regresar al panel de control</a>
    <hr>
    <h1>Formulario de modificación de un producto</h1>
    <hr>
    <form id="formModificarProducto" class="form-group">
        <div class="row">
            <input type="text" name="producto" id="producto" class="form-control col-12 mb-4" value="<?php echo $reg['producto'] ?>" required>
            <div class="col-5">
                <p>Selecciona una marca</p>
                <select class="form-control mb-4" name="idMarca" id="idMarca" required>
                    <option value="<?php echo $reg['idMarca'] ?>"><?php echo $reg['marcaNombre'] ?></option>
                    <?php foreach ($arrayMarca as $mk) {?>
                    <option value="<?php echo $mk['idMarca']?>"><?php echo $mk['marcaNombre']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-5">
                <p>Selecciona una categoria</p>
                <select class="form-control mb-4" name="idCategoria" id="idCategoria" required>
                    <option value="<?php echo $reg['idCategoria'] ?>"><?php echo $reg['categoriaNombre'] ?></option>
                    <?php foreach ($arrayCategoria as $cat) {?>
                    <option value="<?php echo $cat['idCategoria']?>"><?php echo $cat['categoriaNombre']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                </div>
                <input type="text" name="precioPublico" id="precioPublico" class="form-control" value="<?php echo $reg['precioPublico'] ?>">
            </div>
            <div class="col-md-2"></div>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                </div>
                <input type="text" name="precioUnidad" id="precioUnidad" class="form-control" value="<?php echo $reg['precioUnidad'] ?>">
            </div>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                    <div class="input-group-text">$</div>
                </div>
                <input type="text" name="PrecioKilo" id="PrecioKilo" class="form-control" value="<?php echo $reg['precioKilo'] ?>">
            </div>
            <div class="col-md-2"></div>
            <div class="col-12 col-md-5 mb-4">
                <input type="text" name="stock" id="stock" class="form-control" value="<?php echo $reg['stock'] ?>">
            </div>
            <input type="hidden" name="idProducto" value="<?php echo $reg['idProducto'] ?>">
        </div>
        <center><input type="submit" class="btn btn-outline-info" value="Modificar"></center>
    </form>
  </div>

  <div class="menu-secundary bg-dark animated fadeIn fast d-none" id="menu-secundary">
    <i class="fas fa-times-circle" id="botonVolverSecundary"></i>
    <ul>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-ad"></i> Agregar una marca</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-align-left"></i> Agregar una categoria</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-check-double"></i> Modificar varios</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-question-circle"></i> Ayuda</a></li>
    </ul>
  </div><!--menu secundario-->

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script>
        let formulario = document.getElementById('formModificarProducto');
        formulario.addEventListener('submit', event=>{
            event.preventDefault();
            let data = new FormData(formulario);
            fetch('backend/producto/modificarProducto.php',{
                method: 'POST',
                body: data
            })
            .then(res=>res.json())
            .then(newRes=>{
                if (newRes) {
                    alert = document.getElementById('alert-warning');
                    alert.classList.remove('d-none');
                    formulario.classList.add('d-none');
                }
            })
        })
    </script>
    <script src="js/menu.js"></script>
    <script src="js/app.js"></script>
</body>
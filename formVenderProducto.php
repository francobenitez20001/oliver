<?php
    require 'backend/classes/Conexion.php';
    require 'backend/classes/Producto.php';
    require 'backend/classes/Marca.php';
    require 'backend/classes/Categoria.php';
    $producto = new Producto;
    $marca = new Marca;
    $categoria = new Categoria;
    $reg = $producto->verProductoPorId();
    // $listadoMarca = $marca->listarMarca();
    // $listadoCategoria = $categoria->listarCategoria();
    // $arrayMarca = json_decode($listadoMarca,true);
    // $arrayCategoria = json_decode($listadoCategoria,true);
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
    <link rel="stylesheet" href="css/fontawesome/css/all.css">
    <script src="js/autenticacion.js"></script>
    <link rel="stylesheet" href="css/sweetalert2.css">
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
        <li class="nav-item active">
          <a class="nav-link" href="adminProductos.html">Productos</a>
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
          <a class="nav-link" href="adminServicios.html">Servícios</a>
        </li>
        <!-- <li class="nav-item">
          <a href="login.html" class="nav-link"><i class="fas fa-sign-in-alt"></i> Ingresar</a>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!--contenido desde menu.js-->
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" id="cerrarSesion" >Cerrar sesión</a>
          </div>
        </li>
        <li class="nav-item" id="icon-menu-li">
          <a id="icon-menu" class="nav-link d-xs-none d-sm-none d-md-block"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
    </div>
  </nav><!--menu-->
  <div class="container mt-3 fadeIn fast" id="form-modificar-div">
    <div class="alert alert-success d-none" id="alert-success">Venta cargada</div>
    <a class="btn btn-warning" href="adminProductos.html">Regresar al panel de control</a>
    <hr>
    <h1>Nueva venta</h1>
    <hr>
    <form id="formVentaProducto" class="form-group">
        <div class="row">
            <div class="input-group col-12 col-md-5 mb-5">
              <div class="input-group-prepend">
                <div class="input-group-text">Tipo de venta</div>
              </div>
              <select name="tipoDeVenta" onchange="cambiarTipoDeCompra(event)" class="form-control" id="">
                <option value="normal">Normal</option>
                <option value="suelto">Suelto</option>
              </select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-12 col-md-5 text-center mb-4 pt-3 alert alert-info" id="infoStock">
              
            </div>
            <div class="col-12 col-md-5 mb-4">
            <input type="text" name="producto" id="producto" class="form-control" value="<?php echo $reg['producto'] ?>" required>
            </div>
            <div class="col-md-2"></div>
            <div class="input-group col-12 col-md-5 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Cantidad</div>
              </div>
              <select class="form-control" onchange="actualizarTotal(event,true)" name="cantidad" id="cantidad">
                <!--Viene desde ventas.js-->
              </select>
              <input type="number" class="form-control d-none" name="cantidadSuelto" id="cantidadSuelto" step="any">
            </div>
            <div class="col-12 col-md-5">
                <p>Selecciona una marca</p>
                <select class="form-control mb-4" name="idMarca" id="idMarca" required>
                    <option value="<?php echo $reg['idMarca'] ?>"><?php echo $reg['marcaNombre'] ?></option>
                </select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-12 col-md-5">
                <p>Selecciona una categoria</p>
                <select class="form-control mb-4" name="idCategoria" id="idCategoria" required>
                    <option value="<?php echo $reg['idCategoria'] ?>"><?php echo $reg['categoriaNombre'] ?></option>
                </select>
            </div>
            <div class="col-12 col-md-5">
                <p>Selecciona el estado de la compra</p>
                <select class="form-control mb-4" name="estado" id="estado" required>
                    <option value="Pago">Abonado en el momento</option>
                    <option value="Debe">Sumarlo a deudores</option>
                </select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-12 col-md-5">
              <p>Selecciona la forma de pago</p>
              <select name="tipo_pago" id="tipo_pago" onchange="habilitarInput(event)" class="form-control" id="">
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
              </select>
            </div>
            <div class="input-group col-12 col-md-5 my-4" id="div-descuento">
              <div class="input-group-prepend">
                <div class="input-group-text">Descuento</div>
              </div>
              <select name="descuento" onchange="habilitarDescuento(event)" class="form-control" id="">
                <option value="no">No</option>
                <option value="si">Si</option>
              </select>
            </div>
            <div class="col-md-2" id="col-separador"></div>
            <div class="input-group col-12 col-md-5 d-none my-4" id="selectDescuento">
              <div class="input-group-prepend">
                <div class="input-group-text">Valor descuento</div>
              </div>
              <input type="number" class="form-control" id="inputDescuento" name="inputDescuento">
            </div>
            <div class="col-12 col-md-5 d-none my-4" id="nombreCliente">
              <input type="text" class="form-control" name="cliente" placeholder="Nombre del cliente">
            </div>
            <input type="hidden" name="idProducto" value="<?php echo $reg['idProducto'] ?>">
            <div class="col-12 col-md-5 mb-4">
            <!-- campos ocultos importantes para backend -->
            <input type="hidden" name="fecha" id="inputFecha">
            <input type="hidden" name="dia" id="inputDia">
            <input type="hidden" value="<?php echo $reg['stock']?>" name="stockParcial" id="stockParcial">
            <input type="hidden" value="<?php echo $reg['stock_suelto']?>"name="stockSuelto" id="stockSuelto">
            <input type="hidden" name="stock" id="stockFinal">
            <input type="hidden" name="precio" id="precio" value="<?php echo $reg['precioPublico'] ?>">
            <input type="hidden" name="precioKilo" id="precioKilo" value="<?php echo $reg['precioKilo']; ?>">
            <input type="hidden" name="total" id="total">
          </div>
        </div>
        <div class="alert alert-info text-center" id="alert-total"></div>
        <center><input type="submit" class="btn btn-outline-info" value="Agregar"></center>
    </form>
  </div>

  <div class="container mt-3 fadeIn fast d-none" id="form-agregar-div">
    <h1>Detalle del envío</h1>
    <hr>
    <form id="formAgregarEnvio" class="form-group">
      <div class="row">
          <input type="hidden" name="idVenta" id="idVenta">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" class="form-control" name="cliente" placeholder="Cliente" required>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <input type="email" class="form-control" name="email" placeholder="Email del cliente (opcional)">
          </div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="ubicacion" id="ubicacion" class="form-control" placeholder="Ubicación" required>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="descripcionUbicacion" id="descripcionUbicacion" class="form-control" placeholder="Descripción de ubicación">
          </div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="telefono" id="telefono" class="form-control" placeholder="Telefono">
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <select name="estado" class="form-control" required>
              <option value="Sin entregar">Sin entregar</option>
              <option value="Entregado">Entregado</option>
            </select>
          </div>
      </div>
      <center><input type="submit" class="btn btn-outline-info" value="Cargar envío"></center>
    </form>
  </div>

  <div class="menu-secundary bg-dark animated fadeIn fast d-none" id="menu-secundary">
    <i class="fas fa-times-circle" id="botonVolverSecundary"></i>
    <ul>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-ad"></i> Agregar una marca</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-align-left"></i> Agregar una categoria</a></li>
      <li><a href="aumentarPorProveedor.html" class="nav-link link-secundary"><i class="fas fa-check-double"></i> Modificar varios</a></li>
      <li><a href="adminVentas.html" class="nav-link link-secundary"><i class="fas fa-question-circle"></i>Ver ventas</a></li>
    </ul>
  </div><!--menu secundario-->

    <script src="js/bootstrap/jquery.js"></script>
    <script src="js/bootstrap/popper.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/sweetalert2.js"></script>
    <script src="js/menu.js"></script>
    <script src="js/ventas.js"></script>
    <script src="js/app.js"></script>
</body>
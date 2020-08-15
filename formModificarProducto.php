<?php
    require 'backend/classes/Conexion.php';
    require 'backend/classes/Producto.php';
    require 'backend/classes/Marca.php';
    require 'backend/classes/Categoria.php';
    require 'backend/classes/Proveedor.php';
    $producto = new Producto;
    $marca = new Marca;
    $categoria = new Categoria;
    $proveedor = new Proveedor;
    $reg = $producto->verProductoPorId();
    $listadoMarca = $marca->listarMarca();
    $listadoCategoria = $categoria->listarCategoria();
    $listadoProveedor = $proveedor->listarProveedor();
    $arrayMarca = json_decode($listadoMarca,true);
    $arrayCategoria = json_decode($listadoCategoria,true);
    $arrayProveedor = json_decode($listadoProveedor,true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin productos</title>
    <link rel="stylesheet" href="css/bootstrap/bootstrap.css">
    <link rel="stylesheet" href="css/main.css?v=1.0.0">
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
          <div class="col-12 col-md-5 input-group mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Producto</div>
            </div>
            <input type="text" name="producto" id="producto" class="form-control" value="<?php echo $reg['producto'] ?>" required>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 input-group mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Codigo producto</div>
            </div>
            <input type="text" name="codigoProducto" class="form-control" value="<?php echo $reg['codigo_producto'] ?>">
          </div>
            <div class="col-12 col-md-5">
                <p>Selecciona una marca</p>
                <select class="form-control mb-4" name="idMarca" id="idMarca" required>
                    <option value="<?php echo $reg['idMarca'] ?>"><?php echo $reg['marcaNombre'] ?></option>
                    <?php foreach ($arrayMarca as $mk) {?>
                    <option value="<?php echo $mk['idMarca']?>"><?php echo $mk['marcaNombre']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-12 col-md-5">
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
                <div class="input-group-text">Cantidad Unitario</div>
              </div>
              <input type="text" name="cantidadUnitario" id="cantidadUnitario" class="form-control" value="<?php echo $reg['cantidadUnitario'] ?>">
            </div>
            <div class="col-md-2"></div>

            <?php
              if ($reg['precioKilo']!=0) {?>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Venta por kilo</div>
                  </div>
                  <select onchange="handleChangeVentaKilo(event)" class="form-control" id="ventaKiloSelect">
                    <option value="si">Si</option>
                    <option value="no">No</option>
                  </select>
                </div>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Cantidad por kilo</div>
                  </div>
                  <input type="number" name="cantidadKilo" id="cantidadKilo" value="<?php echo $reg['cantidadPorKilo']; ?>" class="form-control input-disable" step="any">
                </div>
                <div class="col-md-2"></div>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">% extra por Kilo</div>
                  </div>
                  <input type="number" name="porcentajePorKilo" id="porcentajePorKilo" value="<?php echo $reg['porcentajeGananciaPorKilo']; ?>" class="form-control input-disable" step="any">
                </div>
              <?php } else { ?>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Venta por kilo</div>
                  </div>
                  <select onchange="handleChangeVentaKilo(event)" class="form-control" id="ventaKiloSelect">
                    <option value="no">No</option>
                    <option value="si">Si</option>
                  </select>
                </div>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Cantidad por kilo</div>
                  </div>
                  <input type="number" name="cantidadKilo" id="cantidadKilo" class="form-control input-disable" disabled="true" step="any">
                </div>
                <div class="col-md-2"></div>
                <div class="input-group col-12 col-md-5 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">% extra por Kilo</div>
                  </div>
                  <input type="number" name="porcentajePorKilo" id="porcentajePorKilo" class="form-control input-disable" disabled="true" step="any">
                </div>
              <?php }; ?>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                  <div class="input-group-text">Stock</div>
                </div>
                <input type="text" name="stock" id="stock" class="form-control" value="<?php echo $reg['stock'] ?>">
            </div>
            <div class="col-md-2"></div>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                  <div class="input-group-text">Stock suelto</div>
                </div>
                <input type="number" name="stockSuelto" id="stock" class="form-control" value="<?php echo $reg['stock_suelto'] ?>"step="any">
            </div>
            <div class="input-group col-12 col-md-5 mb-4">
                <div class="input-group-prepend">
                  <div class="input-group-text">Stock desposito</div>
                </div>
                <input type="number" name="stockDeposito" id="stockDeposito" class="form-control" value="<?php echo $reg['stock_deposito'] ?>">
            </div>
            <div class="col-md-2"></div>
            <div class="input-group col-12 col-md-5 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Proveedor</div>
              </div>
              <select name="idProveedor" class="form-control" id="">
                    <option value="<?php echo $reg['idProveedor'] ?>"><?php echo $reg['proveedor'] ?></option>
                    <?php foreach ($arrayProveedor as $proveedor) {?>
                    <option value="<?php echo $proveedor['idProveedor']?>"><?php echo $proveedor['proveedor']?></option>
                    <?php } ?>
              </select>
            </div>
            <div class="input-group col-12 col-md-5 mb-4 d-none userPrivate">
              <div class="input-group-prepend">
                <div class="input-group-text">% Ganancia</div>
              </div>
              <input type="number" name="porcentaje_ganancia" id="porcentaje_ganancia" class="form-control" value="<?php echo $reg['porcentaje_ganancia'] ?>" step="any">
            </div>
            <div class="col-md-2"></div>
            <div class="input-group col-12 col-md-5 mb-4 d-none userPrivate">
              <div class="input-group-prepend">
                <div class="input-group-text">Precio de costo</div>
              </div>
              <input type="text" name="precio_costo" id="precioCosto" class="form-control" value="<?php echo $reg['precio_costo'] ?>">
            </div>
            <div class="input-group col-12 col-md-5 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Restar al stock suelto</div>
              </div>
              <input type="number" name="restaCostoUnitario" id="" class="form-control" value="0">
            </div>
            <input type="hidden" name="idProducto" value="<?php echo $reg['idProducto'] ?>">
        </div>
        <center><input type="submit" class="btn btn-outline-info" value="Modificar"></center>
    </form>
    <hr/>
    <h3>Aumentar producto</h3>
    <div class="col-12 container">
      <form class="form-group" onsubmit="aumentarProducto(event)" id="formAumentarPorProducto"> 
          <div class="row">
              <div class="col-12 col-md-10 pt-2">
                  <input type="number" step="any" name="porcentaje_aumento" class="form-control" placeholder="Ingrese el porcentaje de aumento" required>
              </div>
              <input type="hidden" name="idProducto" id="idProducto" value="<?php echo $reg['idProducto'] ?>">
              <div class="col-12 col-md-2 pt-2">
                  <input type="submit" class="btn btn-outline-info btn-block" name="" id="" value="Aplicar aumento">
              </div>
          </div>
      </form>
    </div>
  </div>

  <div class="menu-secundary bg-dark animated fadeIn fast d-none" id="menu-secundary">
    <i class="fas fa-times-circle" id="botonVolverSecundary"></i>
    <ul>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-ad"></i> Agregar una marca</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-align-left"></i> Agregar una categoria</a></li>
      <li><a href="aumentarPorProveedor.html" class="nav-link link-secundary"><i class="fas fa-check-double"></i> Modificar varios</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-question-circle"></i> Ayuda</a></li>
    </ul>
  </div><!--menu secundario-->

    <script src="js/bootstrap/jquery.js"></script>
    <script src="js/bootstrap/popper.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/sweetalert2.js" charset="utf-8"></script>
    <script src="js/app.js"></script>
    <script src="js/updateProducto.js?v=1.0.0"></script>
    <script src="js/menu.js"></script>
</body>
<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>
  
  <div class="banner-form" id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de productos</h3>
        <button type="button" data-toggle="modal" data-target="#staticBackdrop" class="btn btn-success mb-3 preventa d-none">Ver productos</button>
        <button type="button" onclick="producto.registrarVenta()" class="btn btn-warning mb-3 preventa d-none">Continuar venta</button>
        <span class="text-muted preventa d-none" id="indicatorCantidadSeleccionados">Productos seleccionados: <b>4</b></span>
      </div>
      <div class="col-12 col-sm-4 col-md-3">
        <form id="form-search" onsubmit="producto.search(event)">
          <div class="input-group">
            <select class="input-group-prepend" onchange="producto.changeCriterioBusqueda(event)" style="margin: 24px 0px;">
              <option value="producto">Producto</option>
              <option value="codigo">Codigo</option>
            </select>
            <input type="text" class="form-control my-4" name="productoSearch" placeholder="Busque su producto">
          </div>
        </form>
      </div>
    </div>
  </div>
  <div>
    <table class="table text-center fadeIn fast" id="tablaProductos">
      <thead class="thead-light">
        <tr>
          <th scope="col">Producto</th>
          <th scope="col">DESC</th>
          <th class="userPrivate" scope="col">Costo</th>
          <th class="userPrivate" scope="col">Ganancia</th>
          <th scope="col">Stock Local 1</th>
          <th scope="col">Stock Local 2</th>
          <th class="bg-important" scope="col">Precio público</th>
          <th scope="col">Precio Unitario</th>
          <th scope="col">Precio por KG</th>
          <th scope="col">
            <?php if ($_SESSION['user']['admin']==1){?>
              <i class="fas fa-plus" style="color:blue;cursor:pointer;" id="botonAgregar" onclick="producto.mostrarFormularioAgregar()"></i>
              <a class="" href="exports/exportarExcelProductos.php"><i class="fas fa-file-export" style="color:blue;cursor:pointer;"></i></a>
            <?php }; ?>
          </th>
        </tr>
      </thead>
      <tbody id="bodyTable">
      
      </tbody>
    </table><!--tabla productos-->
  </div>

  <!--formulario de agregar-->
  <div class="container mt-3 d-none fadeIn fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha agregado el producto con éxito</div>
    <a class="btn btn-warning" onclick="producto.ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1>Formulario de alta de un nuevo producto</h1>
    <hr>
    <form id="formAgregarProducto" class="form-group">
      <div class="row">
          <div class="col-12 col-md-6">
            <input type="text" name="producto" id="producto" class="form-control mb-4" placeholder="Nombre del producto" required>
          </div>
          <div class="col-12 col-md-6">
            <input type="number" name="codigoProducto" class="form-control mb-4" placeholder="Codigo del producto"/>
          </div>

          <div class="col-12 col-md-4">
            <select class="form-control mb-4" name="idMarca" id="idMarca" required>
              <option value="">Seleccione una marca</option>
            </select>
          </div>
          <div class="col-12 col-md-4">
            <select class="form-control mb-4" name="idCategoria" id="idCategoria" required>
              <option value="">Seleccione una categoria</option>
            </select>
          </div>
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Cantidad Unitario</div>
            </div>
            <input type="text" name="cantidadUnitario" id="cantidadUnitario" class="form-control">
          </div>

          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Venta por kilo</div>
            </div>
            <select onchange="window.producto.handleChangeVentaKilo(event)" class="form-control" id="ventaKiloSelect">
              <option value="no">No</option>
              <option value="si">Si</option>
            </select>
          </div>
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Cantidad por kilo</div>
            </div>
            <input type="number" name="cantidadKilo" id="cantidadKilo" class="form-control input-disable" disabled="true" step="any">
          </div>
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">% extra por Kilo</div>
            </div>
            <input type="number" name="porcentajePorKilo" id="porcentajePorKilo" class="form-control input-disable" disabled="true" step="any">
          </div>

          <div class="col-12 col-md-3 mb-4">
            <input type="number" name="stock_local_1" id="stock_local_1" class="form-control" placeholder="Stock local 1">
          </div>
          <div class="col-12 col-md-3 mb-4">
            <input type="number" name="stock_suelto_local_1" id="stock_suelto_local_1" class="form-control" placeholder="Stock suelto local 1">
          </div>
          <div class="col-12 col-md-3 mb-4">
            <input type="number" name="stock_local_2" id="stock_local_2" class="form-control" placeholder="Stock local 2">
          </div>
          <div class="col-12 col-md-3 mb-4">
            <input type="number" name="stock_suelto_local_2" id="stock_suelto_local_2" class="form-control" placeholder="Stock suelto local 2">
          </div>
          
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Proveedor</div>
            </div>
            <select name="idProveedor" class="form-control" id="idProveedor">
                 
            </select>
          </div>
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">% Ganancia</div>
            </div>
            <input type="text" name="porcentaje_ganancia" id="porcentaje_ganancia" class="form-control">
          </div>
          <div class="input-group col-12 col-md-4 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Precio de costo</div>
            </div>
            <input type="text" name="precioCosto" id="precioCosto" class="form-control">
          </div>
      </div>
      <center><input type="submit" class="btn btn-outline-info" value="Agregar"></center>
    </form>
  </div>

  <center><button class="btn btn-outline-dark mb-2" onclick="producto.loadMore()" id="btnverMas">Ver más</button></center>

  <div class="menu-secundary bg-dark animated fadeIn fast d-none" id="menu-secundary">
    <i class="fas fa-times-circle" id="botonVolverSecundary"></i>
    <ul>
      <li><a onclick="mostrarFormularioAgregarMarca()" class="nav-link link-secundary"><i class="fas fa-ad"></i> Agregar una marca</a></li>
      <li><a onclick="mostrarFormularioAgregarCategoria()" class="nav-link link-secundary"><i class="fas fa-align-left"></i> Agregar una categoria</a></li>
      <li><a href="aumentos.php" class="nav-link link-secundary"><i class="fas fa-check-double"></i> Modificar varios</a></li>
      <li><a href="ventas.php" class="nav-link link-secundary"><i class="fas fa-question-circle"></i> Ver ventas</a></li>
    </ul>
  </div><!--menu secundario-->

  <!--formulario agregar marca-->
  <div class="container mt-3 fadeIn fast d-none" id="form-agregar-marca">
    <div class="alert alert-success d-none" id="alert-success">Se ha agregado la marca con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregarMarca()">Regresar al panel de control</a>
    <hr>
    <h1>Formulario de alta de una nueva marca</h1>
    <hr>
    <form id="formAgregarMarca" action="backend/marca/agregarMarca.php" method="POST" class="form-group">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="marcaNombre" id="marcaNombre" class="form-control" placeholder="Nombre de la marca">
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-5">
            <input type="submit" class="btn btn-outline-info col-12" value="Agregar">
          </div>
      </div>
    </form>
  </div>

  <!--form agregar categoria-->
  <div class="container mt-3 fadeIn fast d-none" id="form-agregar-categoria">
    <div class="alert alert-success d-none" id="alert-success">Se ha agregado la categoria con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregarCategoria()">Regresar al panel de control</a>
    <hr>
    <h1>Formulario de alta de una nueva categoria</h1>
    <hr>
    <form id="formAgregarCategoria" class="form-group">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="categoriaNombre" id="categoriaNombre" class="form-control" placeholder="Nombre de la categoria">
          </div>
          <div class="col-md-2"></div>
          <div class="col-md-5">
            <input type="submit" class="btn btn-outline-info col-12" value="Agregar">
          </div>
      </div>
    </form>
  </div>


  <!-- modal para ver los productos agregados al carrito -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 70% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Productos</h5>
            </div>
            <div class="modal-body">
                <div class="col-12 mb-3" id="productosSeleccionadosModal">
                  
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
  </div>

    <div id="slider" class="container__slider d-none">
      <div class="sk-chase">
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
        <div class="sk-chase-dot"></div>
      </div>
    </div>



  <?php include('includes/footer.php'); ?>
  <script type="module" src="js/productos.js?v=1.0.9"></script>
  <script src="js/menu-producto.js"></script>
  <!-- <script src="js/formAgregarProducto.js"></script> -->
</body>
</html>
<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="banner-form" id="banner-form">
    <div class="row align-items-center mb-3">
      <div class="col-12 col-md-8">
        <h3 class="my-4">Panel de administración de pedidos</h3>
      </div>
      <div class="col-12 col-md-4">
        <div class="row">
          <div class="mt-1 col-2 indicadorAmaraillo">
            <div style="
                width: 20px;
                height: 20px;
                background-color: yellow;
                border-radius: 10px;
            "></div>
          </div>
          <div class="col-10"><span class="text-muted">Pedidos recibidos pero no pagados en su totalidad</span></div>
          <div class="mt-1 col-2 indicadorVerde">
            <div style="
                width: 20px;
                height: 20px;
                background-color: green;
                border-radius: 10px;
            "></div>
          </div>
          <div class="col-10"><span class="text-muted">Pedidos recibidos y pagados</span></div>
          <div class="mt-1 col-2 indicadorAmaraillo">
            <div style="
                width: 20px;
                height: 20px;
                background-color: white;
                border: solid 1px black;
                border-radius: 10px;
            "></div>
          </div>
          <div class="col-10"><span class="text-muted">Pedidos no recibidos</span></div>
        </div>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaPedidos">
    <thead class="thead-light">
      <tr>
        <th scope="col">Descripción</th>
        <th scope="col">Cantidad</th>
        <th scope="col">Estado</th>
        <th scope="col">Proveedor</th>
        <th scope="col">
          <div class="row align-items-center">
            <div class="col-6">
              <select name="" onchange="getPedidosPorProveedor(value)" id="filtroPedidoPorProveedor" class="form-control">
                <option value="all">Traer de todos los proveedores</option>
              </select>
            </div>
            <div class="col-6">
              <button class="btn btn-outline-info" id="botonAgregar" onclick="mostrarFormularioAgregar()">Nuevo pedido!</button>
              <a onclick="modalExport('exports/exportarExcelPedidos.php',true)" class="btn btn-outline-info">Exportar</a> 
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->


   <!--formulario de agregar-->
   <div class="container mt-3 fadeIn d-none fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha cargado el pedido con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1 class="mt-2">Formulario de alta de un nuevo pedido</h1>
    <hr>
    <form id="formAgregarPedido" class="form-group">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="descripcion" id="producto" autocomplete="off"  class="form-control" placeholder="Producto" required>
            <div id="productosBusqueda" class="col-12 d-none">
              <option onclick="rellenarInputProducto(event)" value="producto 1">asas</option>
            </div>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="cantidad" id="cantidad" class="form-control" placeholder="Cantidad" required>
          </div>
          <div class="col-12 col-md-5 mb-4">
            <select name="idProveedor" class="form-control" id="proveedor" required>
            </select>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <select name="estado" class="form-control" id="estado" required>
              <option value="No recibido">No recibido</option>
            </select>
          </div>
          <div class="col-12 text-center">
            <input type="hidden" name="fecha" id="fecha">
            <input type="submit" class="btn btn-outline-info pr-4 col-12 col-md-5" value="Agregar">
          </div>
      </div>
    </form>
  </div>

  <div class="cargarComprobante d-none" id="cargarComprobante">
    <form id="formCargarComprobante" class="form-group" enctype="multipart/form-data">
      <input type="text" name="cantidadFinal" id="cantidadFinal" required class="my-3 form-control col-12" placeholder="Ingrese la cantidad que llego del pedido (unidades)">
      <select name="idLocal" id="idLocal" class="my-3 form-control">
        <option value="">Local para modificar stock</option>
      </select>
      <br>
      <div class="row">
        <div class="col-6">
          <input type="hidden" id="idPedido" name="idPedido" value="">
          <input type="button" onclick="recibirPedido()" class="btn btn-danger btn-block" value="Cancelar">
        </div>
        <div class="col-6">
          <input type="submit" class="btn btn-info btn-block" value="Cargar">
        </div>
      </div>
      <br>
      <div class="alert alert-warning text-center d-none" id="alert-loading">Actualizando datos...</div>
      <div class="alert alert-success text-center d-none" id="alert-load">Pedido actualizado</div>
      <div class="alert alert-danger text-center d-none" id="alert-error"></div>
    </form>
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

  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 70% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Modificar Pedido</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formModificarPedido" onsubmit="modificarPedido(event)">
            <div class="modal-body">
              <div class="row px-4">
                <input class="col-12 col-md-6 form-control" disabled="true" id="cantidadPrevia"/>
                <input class="col-12 col-md-6 form-control" required name="cantidadMonto" placeholder="Ingrese cantidad nueva"/>
                <input type="hidden" id="idPedidoModificar" name="idPedido">
              <div>
            </div>
            <div class="modal-footer w-100">
                <div class="alert text-center w-100 animated fadeIn fast" id="alert-response"></div>
                <button type="submit" class="btn btn-info">Cargar</button>
            </div>
        </form>
      </div>
    </div>
  </div>



  <?php include('includes/footer.php'); ?>
  <script src="js/pedidos.js?v=1.0.2"></script>
</body>
</html>

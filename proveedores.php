<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>
  
  <div class="banner-form" id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de proveedores</h3>

        <div class="row mb-3 align-items-center animated fadeIn fast d-none" id="reporteProveedores">
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">Recibidos</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="recibidos">2</p>
                </div>
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">Sin recibir</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="noRecibidos">2</p>
                </div>
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">Total</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="total">2</p>
                </div>
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">Pagado</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="pagado">2</p>
                </div>
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">Sin pagar todo</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="porPagar">-</p>
                </div>
            </div>
          </div>
          <div class="col-6 col-md-2">
            <div class="card bg-light mb-3">
                <div class="card-header">saldo</div>
                <div class="card-body">
                    <p class="card-text text-center p-balance" id="saldo">2</p>
                </div>
            </div>
          </div>
          <button type="button" id="btn-adjuntarComprobante" class="btn btn-warning ml-3">Adjuntar comprobante</button>
          <a id="btnVerComprobantes" target="blank" class="btn btn-success ml-2">Ver comprobantes</a>
          <a id="btnVerPagos" target="blank" class="btn btn-info ml-2">Ver pagos</a>
        </div>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaProveedor">
    <thead class="thead-light">
      <tr>
        <th scope="col">Proveedor</th>
        <th scope="col">Email</th>
        <th scope="col">Telefono</th>
        <th scope="col">
          <div class="row align-items-center">
            <div class="col-6">
              <select name="" onchange="getPedidosPorProveedor(value)" id="filtroPedidoPorProveedor" class="form-control">
                <option value="all">Traer de todos los proveedores</option>
              </select>
            </div>
            <div class="col-6">
              <button class="btn btn-outline-info" id="botonAgregar" onclick="mostrarFormularioAgregar()"><i class="fas fa-plus"></i></button>
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->

  <!--formulario de agregar-->
  <div class="container mt-3 d-none fadeIn fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha agregado el proveedor con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1>Formulario de alta de un nuevo proveedor</h1>
    <hr>
    <form id="formAgregarProveedor" class="form-group">
      <div class="row">
          <input type="text" name="proveedor" id="producto" class="form-control col-12 col-md-5 mb-4" placeholder="Nombre del proveedor" required>
          <div class="col-md-2"></div>
          <input type="email" name="email" id="email" class="form-control col-12 col-md-5 mb-4" placeholder="Email proveedor (opcional)">
          <input type="text" name="telefono" id="telefono" class="form-control col-12 col-md-5 mb-4" placeholder="telefono proveedor (opcional)">
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5">
            <select name="estado" id="estado" class="form-control">
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
          <div class="col-12">
            <input type="submit" class="btn btn-outline-info btn-block" value="Agregar">
          </div>
      </div>
    </form>
  </div>

  <!--form modificar-->
  <div class="container mt-3 d-none fadeIn fast" id="form-modificar-div">
    <div class="alert d-none" id="alerta"></div>
    <a class="btn btn-warning" onclick="ocultarFormularioModificar()">Regresar al panel de control</a>
    <h1>Formulario de modificacion de proveedor</h1>
    <hr>
    <form id="formModificarProveedor" class="form-group">
        <!-- el contenido viene desde el js -->
    </form>
  </div>

  <!-- modal para nuevo pago -->
  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 50% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCargarPago" onsubmit="cargarPago(event)">
              <div class="modal-body">
                <div class="row px-4">
                  <input class="col-12 col-md-6 form-control" required name="total" placeholder="Total del pago"/>
                  <input class="col-12 col-md-6 form-control" required name="monto" placeholder="Total abonado"/>
                  <input type="hidden" name="fecha">
                  <input type="hidden" name="idProveedor">
                <div>
              </div>
              <div class="modal-footer w-100">
                  <div class="alert text-center w-100 animated fadeIn fast" id="alert-response"></div>
                  <button type="submit" class="btn btn-info">Cargar</button>
              </div>
            </form>
        </div>
    </div>
  </div></div>
    </div></div>
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



  <?php include('includes/footer.php') ?>
  <script src="js/proveedor.js"></script>
  <!-- <script src="js/formAgregarProducto.js"></script> -->
</body>
</html>

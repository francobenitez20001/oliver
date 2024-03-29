<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="container mt-3 fadeIn fast" id="form-modificar-div">
    <div class="alert alert-success d-none" id="alert-success">Venta cargada</div>
    <a class="btn btn-warning" href="productos.php">Regresar al panel de control</a>
    <hr>
    <h1>Nueva venta</h1>
    <hr>
    <form id="formVentaProducto" class="form-group d-none">
      
    </form>
    <center><button type="button" class="btn btn-success d-none" id="btn-confirmar-venta" onclick="showModalPago()">Confirmar venta</button></center>
  </div>



    <div class="modal fade" id="modalPago" style="overflow-y:scroll"  data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" style="max-width: 50% !important;">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Pago</h5>
                  <button type="button" class="close" onclick="document.getElementById('modalPago').style.display='none'"  data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row my-4">
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Estado de compra</div>
                        </div>
                        <select class="form-control" onchange="setEstado(event)" required>
                            <option value="Pago">Abonado en el momento</option>
                            <option value="Debe">Sumarlo a deudores</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Local</div>
                        </div>
                        <select onchange="carrito.handleChangeLocal(event)" class="form-control" name="idLocal" id="idLocal" <?php if($_SESSION['user']['admin'] == 0){?> disabled="true" <?php }; ?> >
                          
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row my-4">
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Descuento</div>
                        </div>
                        <select name="descuento" onchange="habilitarDescuento(event)" class="form-control" id="">
                          <option value="no">No</option>
                          <option value="si">Si</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Valor descuento</div>
                        </div>
                        <input type="number" disabled="true" class="form-control" oninput="setDescuento(event)" id="inputDescuento" name="inputDescuento">
                      </div>
                    </div>
                  </div>
                  <div class="row my-4">
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Medio de pago</div>
                        </div>
                        <select name="tipo_pago" id="tipo_pago" onchange="handleChangeMedioPago(event)" class="form-control" id="">
                          <option value="Efectivo">Efectivo</option>
                          <option value="Tarjeta">Tarjeta</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <div class="input-group-text">Nombre del cliente</div>
                        </div>
                        <input disabled="true" type="text" class="form-control" oninput="setCliente(event)" id="cliente" placeholder="Nombre del cliente">
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div id="infoDeCompra" class="d-none">
                    <label class="text-muted">Productos:</label>
                    <ul>
                      <li><b>Producto 1</b></li>
                    </ul>
                    <label class="text-muted">Cliente: <b>Franco Benitez</b></label><br>
                    <label class="text-muted">Estado: <b>Pago</b></label><br>
                    <label class="text-muted">Tipo de pago: <b>Efectivo</b></label><br>
                    <label class="text-muted">Subtotal: <b>1200</b></label><br>
                    <label class="text-muted">Descuento: <b>5%</b></label><br>
                    <label class="text-muted">Total: <b>1100</b></label>
                  </div>
              </div>
              <div class="modal-footer">
                  <p class="alert-info w-100 py-3 px-3" id="total-info"></p>
                  <button type="button" class="btn btn-modal-venta btn-secondary" onclick="carrito.verCarrito()">Ver/ocultar carrito</button>
                  <button type="button" class="btn btn-modal-venta btn-info" onclick="carrito.cargarVenta()">Cargar</button>
              </div>
          </div>
      </div>
    </div>
    








  <div class="container mt-3 fadeIn fast d-none" id="form-agregar-div">
    <h1>Detalle del envío</h1>
    <hr>
    <form id="formAgregarEnvio" class="form-group">
      <div class="row">
          <input type="hidden" name="tipo" id="tipoEnvio">
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

  <?php include('includes/footer.php'); ?>
  <script type="module" src="js/venta.js?v=1.0.5"></script>
</body>

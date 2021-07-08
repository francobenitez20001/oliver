<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="banner-form" id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Ventas</h3>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaServicios">
    <thead class="thead-light">
      <tr>
        <th scope="col">Fecha</th>
        <th scope="col">Forma de pago</th>
        <th scope="col">Cliente</th>
        <th scope="col">Subtotal</th>
        <th scope="col">Descuento</th>
        <th scope="col">Total</th>
        <th scope="col">
          <div class="row">
            <div class="col-10">
              <select name="" onchange="getVentas(value)" id="" class="form-control">
                <option value="null">Traer todos</option>
                <option value="Tarjeta">Solo los abonados con tarjeta</option>
                <option value="Efectivo">Solo los abonado en efectivo</option>
              </select>
            </div>
            <div class="col-2 pt-2">
              <a onclick="modalExport('exports/exportarExcelVentas.php')"><i class="fas fa-file-export d-none" style="color:blue;cursor:pointer;font-size: 20px;"></i></a>
            </div>
          </div>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width:800px">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Detalle de la venta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <table class="table text-center fadeIn fast" id="">
            <thead class="thead-light">
              <tr>
                <th scope="col">Producto</th>
                <th scope="col">Tipo de venta</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody id="cuerpo-tablaventas">
              <tr>
                <th scope="col">1</th>
                <th scope="col">1</th>
                <th scope="col">1</th>
                <th scope="col">1</th>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>
  <script src="js/ventaListado.js?v=1.0.2"></script>
</body>

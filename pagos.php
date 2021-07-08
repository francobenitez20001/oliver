<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <table class="table text-center fadeIn fast" id="tablaComprobantes">
    <thead class="thead-light">
      <tr>
        <th scope="col">Proveedor</th>
        <th scope="col">Pagado</th>
        <th scope="col">Total</th>
      </tr>
    </thead>
    <tbody id="bodyTable">
        
    </tbody>
  </table><!--tabla productos-->

  <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 70% !important;">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Comprobante</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="formCargarPago" onsubmit="actualizarPago(event)">
            <div class="modal-body">
              <div class="row px-4">
                <input class="col-12 col-md-6 form-control" disabled="true" name="total"/>
                <input class="col-12 col-md-6 form-control" required name="monto" placeholder="Ingrese cuanto paga"/>
                <input type="hidden" name="id">
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
  <script src="js/pagoProveedores.js?v=1.0.0"></script>
</body>
</html>
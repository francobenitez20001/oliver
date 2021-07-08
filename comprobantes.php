<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <table class="table text-center fadeIn fast" id="tablaComprobantes">
    <thead class="thead-light">
      <tr>
        <th scope="col">Descripción</th>
        <th scope="col">Proveedor</th>
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
        <div class="modal-body">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>  
  <script src="js/comprobantes.js"></script>
</body>
</html>
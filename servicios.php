<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="banner-form" id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de Servícios</h3>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaServicios">
    <thead class="thead-light">
      <tr>
        <th scope="col">Servicio</th>
        <th scope="col">Fecha</th>
        <th scope="col">Total</th>
        <th scope="col">
          <button class="btn btn-outline-info" id="botonAgregar" onclick="mostrarFormularioAgregar()">Nuevo servício</button>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->

   <!--formulario de agregar-->
   <div class="container mt-3 fadeIn d-none fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha cargado el servício con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1 class="mt-2">Formulario de alta de un nuevo servicio</h1>
    <hr>
    <form id="formAgregarServicio" class="form-group" enctype="multipart/form-data">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="servicioNombre" id="servicioNombre" class="form-control" placeholder="Servicio" required>
            <input type="hidden" name="fecha" id="inputFecha">
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <select name="estado" onchange="habilitarFormComprobante()" id="estado" class="form-control">
              <option value="Pago">Pago</option>
              <option value="No pago">No pago</option>
            </select>
          </div>
          <div class="col-12 col-md-5 text-center">
            <input type="text" name="total" id="total" class="form-control" placeholder="Total" required>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4" id="comprobante">
            <label>Cargue el comprobante</label>
            <input type="file" accept="image/*" name="comprobante">
          </div>
          <div class="col-12 col-md-12 mt-4">
            <input type="submit" class="btn btn-outline-info btn-block pr-4 col-12" value="Agregar">
          </div>
      </div>
    </form>
  </div>

  <div class="cargarComprobante d-none" id="cargarComprobante">
    <form id="formCargarComprobante" class="form-group" enctype="multipart/form-data">
      <label>Adjuntar comprobante</label>
      <br>
      <input type="file" accept="image/*" name="comprobante" id="" required>
      <br><br>
      <div class="row">
        <div class="col-6">
          <input type="hidden" id="idServicio" name="idServicio" value="">
          <input type="button" onclick="switchForm()" class="btn btn-danger btn-block" value="Cancelar">
        </div>
        <div class="col-6">
          <input type="submit" class="btn btn-info btn-block" value="Cargar">
        </div>
      </div>
      <br>
      <div class="alert alert-warning text-center d-none" id="alert-loading">Subiendo comprobante y cambiando el estado del servicio...</div>
      <div class="alert alert-success text-center d-none" id="alert-load">Servicio actualizado</div>
      <div class="alert alert-danger text-center d-none" id="alert-error"></div>
    </form>
  </div>

  

  <?php include('includes/footer.php'); ?>
  <script src="js/servicios.js"></script>
</body>
</html>
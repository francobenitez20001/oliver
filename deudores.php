<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="banner-form " id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de deudores</h3>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaDeudores">
    <thead class="thead-light">
      <tr>
        <th scope="col">Cliente</th>
        <th scope="col">fecha</th>
        <th scope="col">Descripción</th>
        <th scope="col">Total</th>
        <th scope="col">Estado</th>
        <th scope="col">
          <button class="btn btn-outline-info" id="botonAgregar" onclick="mostrarFormularioAgregar()">Agregar deudor</button>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->


   <!--formulario de agregar-->
   <div class="container mt-3 fadeIn d-none fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha cargado el deudor con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1>Formulario de alta de un nuevo deudor</h1>
    <hr>
    <form id="formAgregarDeudor" class="form-group">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="cliente" id="cliente" class="form-control" placeholder="Cliente" required>
            <input type="hidden" id="input" name="fecha">
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Descripción" required>
          </div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="total" id="total" class="form-control" placeholder="Total" required>
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <select name="estado" class="form-control" required id="estado">
              <option value="debe">Debe</option>
              <option value="Saldado">Saldado</option>
            </select>
          </div>
      </div>
      <center><input type="submit" class="btn btn-outline-info" value="Agregar"></center>
    </form>
  </div>

  

  <?php include('includes/footer.php'); ?>
    <script src="js/deudores.js"></script>
    <script>
    let input = document.getElementById('input');
    let f = new Date();
    input.value = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate();    
    </script>
</body>
</html>
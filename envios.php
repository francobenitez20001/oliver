<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>
  <div class="banner-form " id="banner-form">
    <div class="row">
      <div class="col-12 col-sm-8 col-md-9">
        <h3 class="my-4">Panel de administración de envíos</h3>
      </div>
    </div>
  </div>

  <table class="table text-center fadeIn fast" id="tablaEnvios">
    <thead class="thead-light">
      <tr>
        <th scope="col">Cliente</th>
        <th scope="col">Email</th>
        <th scope="col">Ubicación</th>
        <th scope="col">Ubicación (descripcion)</th>
        <th scope="col">Teléfono</th>
        <th scope="col">Estado</th>
        <th scope="col">
          <button class="btn btn-outline-info d-none" id="botonAgregar" onclick="mostrarFormularioAgregar()">Nuevo envío!</button>
          <a class="userPrivate" onclick="modalExport('exports/exportarExcelEnvios.php')"><i class="fas fa-file-export" style="color:blue;cursor:pointer;"></i></a>
        </th>
      </tr>
    </thead>
    <tbody id="bodyTable">
    
    </tbody>
  </table><!--tabla productos-->


   <!--formulario de agregar-->
   <div class="container mt-3 fadeIn d-none fast" id="form-agregar-div">
    <div class="alert alert-success d-none" id="alert-success">Se ha cargado el envío con éxito</div>
    <a class="btn btn-warning" onclick="ocultarFormularioAgregar()">Regresar al panel de control</a>
    <h1>Formulario de alta de un nuevo envío</h1>
    <hr>
    <form id="formAgregarEnvio" method="POST" action="backend/envios/agregarEnvio.php" class="form-group">
      <div class="row">
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="cliente" id="cliente" class="form-control" placeholder="Cliente" required>
            <input type="hidden" id="input" name="fecha">
          </div>
          <div class="col-md-2"></div>
          <div class="col-12 col-md-5 mb-4">
            <input type="text" name="producto" id="producto" class="form-control" placeholder="Producto" required>
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
            <select name="estado" class="form-control" required id="estado">
              <option value="Sin entregar">Sin entregar</option>
              <option value="Entregado">Entregado</option>
            </select>
          </div>
      </div>
      <center><input type="submit" class="btn btn-outline-info" value="Agregar"></center>
    </form>
  </div>

  


  <?php include('includes/footer.php'); ?>
    <script src="js/envios.js"></script>
    <script>
    let input = document.getElementById('input');
    let f = new Date();
    input.value = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate();    
    </script>
</body>
</html>
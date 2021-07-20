<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

    <table class="table text-center fadeIn fast mt-5" id="tablaUsuarios">
        <thead class="thead-light">
          <tr>
            <th scope="col">Usuario</th>
            <th scope="col">Nombre</th>
            <th scope="col">Tipo</th>
            <th scope="col">
              <button class="btn btn-outline-info" id="botonAgregar" onclick="mostrarFormularioAgregar()">Agregar</button>
            </th>
          </tr>
        </thead>
        <tbody id="bodyTable">
        
        </tbody>
    </table><!--tabla productos-->

    <div class="container" style="margin-top: 50px;" id="form-container">
        <!--agregar-->
        <form class="form-group d-none" id="form" onsubmit="agregarUsuario(event)">
            <div class="col-12">
                <button type="button" class="btn btn-outline-warning mb-3" onclick="mostrarFormularioAgregar()">Volver al listado</button>
                <div class="alert text-center" id="alerta"></div>
            </div>
            <div class="row">
                <div class="col-12 col-md-6 px-2 my-3">
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre">
                </div>
                <div class="col-12 col-md-6 px-2 my-3">
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario">
                </div>
                <div class="col-12 col-md-6 px-2 my-3">
                    <input type="text" class="form-control" id="pw" name="pw" placeholder="Contraseña">
                </div>
                <div class="col-12 col-md-6 px-2 my-3 input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Tipo de usuario</div>
                    </div>
                    <select name="superUser" id="superUser" class="form-control" id="" onchange="handleChangeTipoUsuario(event)">
                        <option value="0">Normal</option>
                        <option value="1">Administrador</option>
                    </select>
                </div>
                <div class="col-12 my-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Local con permiso</div>
                        </div>
                        <select class="form-control" name="idLocal" id="idLocal">

                        </select>
                    </div>
                </div>
                <div class="col 12 text-center">
                    <input type="submit" class="btn btn-outline-success btn-block" value="Agregar">
                </div>
            </div>
        </form>
    </div>

    <div class="container" style="margin-top: 50px;" id="form-container">
        <!--agregar-->
        <form class="form-group d-none" id="formModificar" onsubmit="modificarUsuario(event)">
            
        </form>
    </div>

  <?php include('includes/footer.php'); ?>
  <script src="js/usuario.js?v=1.0.1"></script>
</body>
</html>
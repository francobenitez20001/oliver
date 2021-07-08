<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>
    <div class="container mt-5">
        <h3 class="my-4">Aumentos generales</h3>

        <div class="col-12 mb-3">
            <form action="" class="form-group" id="formAumentarPorProveedor">
                <div class="row">
                    <div class="input-group col-12 col-md-4 my-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Proveedor</div>
                        </div>
                        <select name="idProveedor" class="form-control" id="idProveedor" required>
                           
                        </select>
                    </div>
                    <div class="col-12 col-md-5 pt-2">
                        <input type="number" name="porcentaje_aumento" class="form-control" placeholder="Ingrese el porcentaje de aumento" required>
                    </div>
                    <div class="col-12 col-md-3 pt-2">
                        <input type="submit" class="btn btn-outline-info btn-block" name="" id="" value="Aplicar aumento">
                    </div>
                </div>
            </form>
        </div>
        <hr>
        <div class="col-12">
            <form action="" class="form-group" onsubmit="enviar(event,'marca')" id="formAumentarPorMarca">
                <div class="row">
                    <div class="input-group col-12 col-md-4 my-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Marca</div>
                        </div>
                        <select name="idMarca" class="form-control" id="idMarca" required>
                           
                        </select>
                    </div>
                    <div class="col-12 col-md-5 pt-2">
                        <input type="number" name="porcentaje_aumento" class="form-control" placeholder="Ingrese el porcentaje de aumento" required>
                    </div>
                    <div class="col-12 col-md-3 pt-2">
                        <input type="submit" class="btn btn-outline-info btn-block" name="" id="" value="Aplicar aumento">
                    </div>
                </div>
            </form>
        </div>
        <hr/>
        <h3>Aumentar producto individual</h3>
        <div class="col-12">
            <form class="form-group" onsubmit="enviar(event,'producto')" id="formAumentarPorProducto"> 
                <div class="row">
                    <div class="col-12 col-md-5 pt-2">
                        <input type="text" name="producto" id="producto" autocomplete="off" class="form-control" placeholder="Producto" required>
                        <div id="productosBusqueda" class="col-12 d-none">
                          <option onclick="rellenarInputProducto(event)" value=""></option>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 pt-2">
                        <input type="number" step="any" name="porcentaje_aumento" class="form-control" placeholder="Ingrese el porcentaje de aumento" required>
                    </div>
                    <input type="hidden" name="idProducto" id="idProducto" value="">
                    <div class="col-12 col-md-2 pt-2">
                        <input type="submit" class="btn btn-outline-info btn-block" name="" id="" value="Aplicar aumento">
                    </div>
                </div>
            </form>
        </div>

    </div>
    <?php include('includes/footer.php'); ?>
    <script src="js/aumentos.js?v=1.0.0"></script>
</body>
</html>
<?php
    require 'config/config.php';
    require 'backend/classes/Conexion.php';
    require 'backend/classes/Producto.php';
    require 'backend/classes/Marca.php';
    require 'backend/classes/Categoria.php';
    require 'backend/classes/Proveedor.php';
    include('includes/header.php'); 
    $producto = new Producto;
    $marca = new Marca;
    $categoria = new Categoria;
    $proveedor = new Proveedor;
    $reg = $producto->verProductoPorId();
    $listadoMarca = $marca->listarMarca();
    $listadoCategoria = $categoria->listarCategoria();
    $listadoProveedor = $proveedor->listarProveedor();
    $arrayMarca = json_decode($listadoMarca,true);
    $arrayCategoria = json_decode($listadoCategoria,true);
    $arrayProveedor = json_decode($listadoProveedor,true);
?>
  <div class="container mt-3 fadeIn fast" id="form-modificar-div">
    <div class="alert alert-success d-none" id="alert-warning">Se ha modificado el producto con éxito</div>
    <a class="btn btn-warning" href="productos.php">Regresar al panel de control</a>
    <hr>
    <h1>Formulario de modificación de un producto</h1>
    <hr>
    <form id="formModificarProducto" class="form-group">
        <div class="row justify-content-between">

          <div class="col-12 col-md-5 input-group mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Producto</div>
            </div>
            <input type="text" name="producto" id="producto" class="form-control" value="<?php echo $reg['producto'] ?>" required>
          </div>
          <div class="col-12 col-md-5">
              <select class="form-control mb-4" name="idMarca" id="idMarca" required>
                  <option value="<?php echo $reg['idMarca'] ?>"><?php echo $reg['marcaNombre'] ?></option>
                  <?php foreach ($arrayMarca as $mk) {?>
                  <option value="<?php echo $mk['idMarca']?>"><?php echo $mk['marcaNombre']?></option>
                  <?php } ?>
              </select>
          </div>

          <div class="col-12 col-md-5">
              <select class="form-control mb-4" name="idCategoria" id="idCategoria" required>
                  <option value="<?php echo $reg['idCategoria'] ?>"><?php echo $reg['categoriaNombre'] ?></option>
                  <?php foreach ($arrayCategoria as $cat) {?>
                  <option value="<?php echo $cat['idCategoria']?>"><?php echo $cat['categoriaNombre']?></option>
                  <?php } ?>
              </select>
          </div>
          <div class="input-group col-12 col-md-5 mb-4">
            <div class="input-group-prepend">
              <div class="input-group-text">Cantidad Unitario</div>
            </div>
            <input type="text" name="cantidadUnitario" id="cantidadUnitario" class="form-control" value="<?php echo $reg['cantidadUnitario'] ?>">
          </div>

        <?php if ($reg['precioKilo']!=0) {?>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Venta por kilo</div>
              </div>
              <select onchange="handleChangeVentaKilo(event)" class="form-control" id="ventaKiloSelect">
                <option value="si">Si</option>
                <option value="no">No</option>
              </select>
            </div>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Cantidad por kilo</div>
              </div>
              <input type="number" name="cantidadKilo" id="cantidadKilo" value="<?php echo $reg['cantidadPorKilo']; ?>" class="form-control input-disable" step="any">
            </div>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">% extra por Kilo</div>
              </div>
              <input type="number" name="porcentajePorKilo" id="porcentajePorKilo" value="<?php echo $reg['porcentajeGananciaPorKilo']; ?>" class="form-control input-disable" step="any">
            </div>
        <?php } else { ?>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Venta por kilo</div>
              </div>
              <select onchange="handleChangeVentaKilo(event)" class="form-control" id="ventaKiloSelect">
                <option value="no">No</option>
                <option value="si">Si</option>
              </select>
            </div>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Cantidad por kilo</div>
              </div>
              <input type="number" name="cantidadKilo" id="cantidadKilo" class="form-control input-disable" disabled="true" step="any">
            </div>
            <div class="input-group col-12 col-md-4 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">% extra por Kilo</div>
              </div>
              <input type="number" name="porcentajePorKilo" id="porcentajePorKilo" class="form-control input-disable" disabled="true" step="any">
            </div>
        <?php }; ?>


            <div class="input-group col-12 mb-4">
              <div class="input-group-prepend">
                <div class="input-group-text">Proveedor</div>
              </div>
              <select name="idProveedor" class="form-control" id="">
                    <option value="<?php echo $reg['idProveedor'] ?>"><?php echo $reg['proveedor'] ?></option>
                    <?php foreach ($arrayProveedor as $proveedor) {?>
                    <option value="<?php echo $proveedor['idProveedor']?>"><?php echo $proveedor['proveedor']?></option>
                    <?php } ?>
              </select>
            </div>
              
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['admin']==1){?> 
                <div class="input-group col-12 col-md-3 mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Stock Local 1</div>
                    </div>
                    <input type="text" name="stock_local_1" id="stock_local_1" class="form-control" value="<?php echo $reg['stock_local_1'] ?>">
                </div>
                <div class="input-group col-12 col-md-3 mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Stock suelto local 1</div>
                    </div>
                    <input type="number" name="stock_suelto_local_1" id="stock_suelto_local_1" class="form-control" value="<?php echo $reg['stock_suelto_local_1'] ?>"step="any">
                </div>
                <div class="input-group col-12 col-md-3 mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Stock Local 2</div>
                    </div>
                    <input type="text" name="stock_local_2" id="stock_local_2" class="form-control" value="<?php echo $reg['stock_local_2'] ?>">
                </div>
                <div class="input-group col-12 col-md-3 mb-4">
                    <div class="input-group-prepend">
                      <div class="input-group-text">Stock suelto local 2</div>
                    </div>
                    <input type="number" name="stock_suelto_local_2" id="stock_suelto_local_2" class="form-control" value="<?php echo $reg['stock_suelto_local_2'] ?>"step="any">
                </div>

                <div class="input-group col-12 col-md-4 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">% Ganancia</div>
                  </div>
                  <input type="number" name="porcentaje_ganancia" id="porcentaje_ganancia" class="form-control" value="<?php echo $reg['porcentaje_ganancia'] ?>" step="any">
                </div>
                <div class="input-group col-12 col-md-4 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Precio de costo</div>
                  </div>
                  <input type="text" name="precio_costo" id="precioCosto" class="form-control" value="<?php echo $reg['precio_costo'] ?>">
                </div>
                <div class="input-group col-12 col-md-4 mb-4">
                  <div class="input-group-prepend">
                    <div class="input-group-text">Restar al stock suelto</div>
                  </div>
                  <input type="number" name="restaCostoUnitario" id="" class="form-control" value="0">
                </div>
            <?php } else { ?>
                <input type="hidden" name="precioCosto" value="<?php echo $reg['precio_costo'] ?>"/>
                <input type="hidden" name="porcentaje_ganancia" value="<?php echo $reg['porcentaje_ganancia'] ?>"/>
                <input type="hidden" name="precioCosto" value="<?php echo $reg['precio_costo'] ?>"/>
            <?php }; ?>


            <input type="hidden" name="idProducto" value="<?php echo $reg['idProducto'] ?>">
        </div>
        <center><input type="submit" class="btn btn-outline-info" value="Modificar"></center>
    </form>
    <hr/>
    <h3>Aumentar producto</h3>
    <div class="col-12 container">
      <form class="form-group" onsubmit="aumentarProducto(event)" id="formAumentarPorProducto"> 
          <div class="row">
              <div class="col-12 col-md-10 pt-2">
                  <input type="number" step="any" name="porcentaje_aumento" class="form-control" placeholder="Ingrese el porcentaje de aumento" required>
              </div>
              <input type="hidden" name="idProducto" id="idProducto" value="<?php echo $reg['idProducto'] ?>">
              <div class="col-12 col-md-2 pt-2">
                  <input type="submit" class="btn btn-outline-info btn-block" name="" id="" value="Aplicar aumento">
              </div>
          </div>
      </form>
    </div>
  </div>

  <div class="menu-secundary bg-dark animated fadeIn fast d-none" id="menu-secundary">
    <i class="fas fa-times-circle" id="botonVolverSecundary"></i>
    <ul>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-ad"></i> Agregar una marca</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-align-left"></i> Agregar una categoria</a></li>
      <li><a href="aumentarPorProveedor.html" class="nav-link link-secundary"><i class="fas fa-check-double"></i> Modificar varios</a></li>
      <li><a href="" class="nav-link link-secundary"><i class="fas fa-question-circle"></i> Ayuda</a></li>
    </ul>
  </div><!--menu secundario-->

  <?php include('includes/footer.php'); ?>
  <script src="js/updateProducto.js?v=1.0.1"></script>
</body>
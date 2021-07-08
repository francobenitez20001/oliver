<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

  <div class="container mt-4">
    <div class="list-group">
        <a href="productos.php" class="list-group-item list-group-item-action">Panel de administracion de productos</a>
        <a href="pedidos.php" class="list-group-item list-group-item-action">Panel de administracion de pedidos</a>
        <a href="deudores.php" class="list-group-item list-group-item-action">Panel de administracion de deudores</a>
        <a href="envios.php" class="list-group-item list-group-item-action">Panel de administracion de envios</a>
        <a href="proveedores.php" class="list-group-item list-group-item-action userPrivate">Panel de administracion de proveedores</a>
        <a href="balance.php" class="list-group-item list-group-item-action userPrivate">Mira tu balance</a>
        <?php if ($_SESSION['admin']==1) { ?>
          <a href="usuario.php" class="list-group-item list-group-item-action userPrivate">Manejo de usuarios</a>
        <?php }; ?> 
    </div>
  </div><!--tabla de opciones-->



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
</body>
</html>
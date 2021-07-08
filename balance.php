<?php 
  require 'config/config.php';
  include('includes/header.php'); 
?>

    <div class="container mt-5">
        <div class="alert" id="alerta"></div>
    </div>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="row card-header">
                      <div class="col-4 pt-2">
                        VENTAS
                      </div>
                      <div class="col-8">
                        <select name="" onchange="filtrarVentas(event)" class="form-control bg-success" style="color:#fff" id="">
                          <option value="dia">Ventas del dia</option>
                          <option value="mes">Ventas del mes</option>
                          <option value="diaEfectivo">Ventas del dia en efectivo</option>
                          <option value="diaTarjeta">Ventas del dia con tarjeta</option>
                          <option value="mesEfectivo">Ventas del mes efectivo</option>
                          <option value="mesTarjeta">Ventas del mes con tarjeta</option>
                          
                        </select>
                      </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-center p-balance" id="ventasTotal">$5000</p>
                    </div>
                </div>
                <a href="adminVentas.html">Ver en detalle</a>
            </div>
            <div class="col-12 col-md-4">
                <div class="card text-white bg-danger mb-3">
                  <div class="row card-header">
                    <div class="col-6 pt-2">
                      Proveedores
                    </div>
                    <div class="col-6">
                      <select name="" onchange="filtrarPagoProveedores(event)" class="form-control bg-danger" style="color:#fff" id="">
                        <option value="dia">Pagos del dia</option>
                        <option value="mes">Pagos del mes</option>
                        <option value="pagoDia">Monto pagado del día</option>
                        <option value="pagoMes">Monto pagado del mes</option>
                      </select>
                    </div>
                  </div>
                  <div class="card-body">
                      <p class="card-text text-center p-balance" id="pagosTotal">$3000</p>
                  </div>
                </div>
                <a href="adminProveedores.html">Ver en detalle</a>
            </div>
            <div class="col-12 col-md-4">
                <div class="card text-white bg-warning mb-3">
                  <div class="row card-header">
                    <div class="col-5 pt-2">
                      SERVICIOS
                    </div>
                    <div class="col-6 ml-auto">
                      <select name="" onchange="filtrarServicio(event)" class="form-control bg-warning" style="color:#fff" id="">
                        <option value="por-pagar">Valor a pagar</option>
                        <option value="mes">Todos del mes</option>
                      </select>
                    </div>
                  </div>
                  <div class="card-body">
                      <p class="card-text text-center p-balance" id="serviciosTotal">$2500</p>
                  </div>
                </div>
                <a href="adminServicios.html">Ver en detalle</a>
            </div>
        </div>
    </div>
    <hr>

    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-header">PEDIDOS NO RECIBIDOS</div>
                    <div class="card-body">
                        <p class="card-text text-center p-balance" id="pedidos_sin_entregar">5</p>
                    </div>
                </div>
                <a href="adminPedidos.html">Ver en detalle</a>
            </div>
            <div class="col-12 col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-header">SERVICÍOS SIN PAGAR</div>
                    <div class="card-body">
                        <p class="card-text text-center p-balance" id="servicios_sin_pagar">2</p>
                    </div>
                </div>
                <a href="adminServicios.html">Ver en detalle</a>
            </div>
            <div class="col-12 col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header">DEUDORES POR VENTAS</div>
                    <div class="card-body">
                    <p class="card-text text-center p-balance" id="deudores">8</p>
                    </div>
                </div>
                <a href="adminDeudores.html">Ver en detalle</a>
            </div>
            <div class="col-12 mt-3 d-none" id="containerEstado">
              <div class="card text-white mb-3" id="cardRecaudacionFinal">
                  <div class="card-header row align-items-center">
                    <div class="col-9">ESTADO DE RECAUDACION FINAL (VENTAS-GASTOS)</div>
                    <div class="col-3">
                      <select class="form-control" onchange="changeCriterioBalance(event)">
                        <option value="mes">Estado del mes</option>
                        <option value="dia">Estado del día</option>
                      </select>
                    </div>
                  </div>
                  <div class="card-body">
                  <p class="card-text text-center p-balance" id="recaudacionFinal">8</p>
                  </div>
              </div>
            </div>
            <div class="col-12 text-right mt-3">
                <button class="btn btn-primary" id="btn-balance" onclick="verBalance('mes')">Ver estado actual</button>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container mt-4">
        <div class="alert alert-warning"><strong>Nota: </strong>Todas las acciones mostradas aquí estan saldadas. Para ver todas en su totalidad visite los paneles administradores respondientes a lo que usted desea ver</div>
        <p class="titulo-balance">Tus últimas ventas</p>
        <table class="table text-center" id="tablaVentas">
            <thead>
              <tr>
                <th scope="col">Fecha</th>
                <th scope="col">Tipo de pago</th>
                <th scope="col">Estado</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Total</th>
              </tr>
            </thead>
            <tbody id="tabla-ventas">
              
            </tbody>
        </table>
        <br><br>
        <p class="titulo-balance">Tus últimos pedidos</p>
        <table class="table text-center">
            <thead>
              <tr>
                <th scope="col">Descripción</th>
                <th scope="col">Cantidad</th>
              </tr>
            </thead>
            <tbody id="tablaPedidos">
              
            </tbody>
        </table>
    </div>
    <br class="d-none">
    <hr>
    <br class="d-none">
    <div class="container my-4">
      <div class="row">
        <div class="col-12 col-md-6 text-center">
          <i class="fas fa-credit-card tarjeta-icono"></i>
        </div>
        <div class="col-12 col-md-6">
          <div class="row">
            <div class="col-6 text-center info-tarjeta">
              Total
              <div class="valor-info-tarjeta" id="total-ventas-tarjeta" style="color: blue;">
                15
              </div>
            </div>
            <div class="col-6 text-center info-tarjeta">
              Deudores
              <div class="valor-info-tarjeta" id="total-deudas-tarjeta" style="color:red;">
                15
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="container my-4">
      <select name="" onchange="filtrarProductoVendido(event)" class="form-control  col-12 col-md-4" id="filtro-producto-vendido">
        <option value="mes">Mostrando datos del mes</option>
        <option value="dia">Mostrando datos del dia de hoy</option>
      </select>
      <div class="row">
        <div class="col-12 col-md-6 text-center">
          <i class="fas fa-coins tarjeta-icono"></i>
        </div>
        <div class="col-12 col-md-6 text-center">
          <div id="producto-mas-vendido" class="producto-vendido text-center valor-info-tarjeta">
            DRI-WAT - 1,7 kg
          </div>
          <p class="mt-2">Es el producto más vendido durante el período seleccionado</p>
        </div>
      </div>
    </div>

    <footer class="footer bg-dark py-2">
        <div class="col-12">
            <p class="text-center mt-2">© copyright 2020 - Todos los derechos reservados - Design BY Franco Benitez</p>
        </div>
    </footer>
 

  <?php include('includes/footer.php'); ?>
  <script src="js/balance.js?v=2.0.8"></script>
</body>
</html>

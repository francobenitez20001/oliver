let btnBalance = document.getElementById('btn-balance');
btnBalance.addEventListener('click',verBalance);

let ventasTotal = document.getElementById('ventasTotal');
let tablaVentas = document.getElementById('tabla-ventas');
let tablaPedidos = document.getElementById('tablaPedidos');
let pedidosTotal = document.getElementById('pedidosTotal');
let serviciosTotal = document.getElementById('serviciosTotal');
let pedidos_entregar = document.getElementById('pedidos_sin_entregar');
let servicios_a_pagar = document.getElementById('servicios_sin_pagar');
let deudoresDiv = document.getElementById('deudores');

let totalVentasTarjeta = document.getElementById('total-ventas-tarjeta');
let totalDeudasTarjeta = document.getElementById('total-deudas-tarjeta');
let productoMasVendido = document.getElementById('producto-mas-vendido');

let alerta = document.getElementById('alerta');

let datosBalance = {
  ventas:{
    dia:0,
    mes:0
  },
  pedidos:{
    dia:0,
    mes:0
  },
  servicios:{
    mes:0
  }
};

let recaudacionFinal = 0;

let f = new Date();
let dia = f.getFullYear() + "-0" + (f.getMonth() +1) + "-" + f.getDate();
let mes = f.getFullYear() + "-0" + (f.getMonth() +1);

window.onload = ()=>{
  getVentasTotal();
  getVentasLimit();
  getPedidosLimit();
  getPedidos();
  getServicios();
  getPedidosSinEntregar();
  getServiciosSinPagar();
  getDeudores();
  getVentasTarjeta(null).then(()=>{
    getVentasTarjeta(true).then(()=>{
      getProductoMasVendido();
    })
  })
}

function verBalance() {
  let containerEstado = document.getElementById('containerEstado');
  getVentasTotal('mes',false);
  getPedidos('mes',false);
  getServicios('mes',false);
  recaudacionFinal = datosBalance.ventas.mes-(datosBalance.pedidos.mes+datosBalance.servicios.mes);
  if(recaudacionFinal>0){
    document.getElementById('cardRecaudacionFinal').classList.add('bg-success');
  }else{
    document.getElementById('cardRecaudacionFinal').classList.add('bg-danger');
  }
  document.getElementById('recaudacionFinal').innerHTML = '$'+recaudacionFinal;
  return containerEstado.classList.toggle('d-none');
}

function getVentasTotal(criterio=null,render=true) {
  url = 'backend/ventas/listarVentaMonto.php?dia='+dia;
  switch (criterio) {
    case 'mes':
      url = 'backend/ventas/listarVentaMonto.php?mes='+mes+'&criterio=mes';
      break;
    case 'diaEfectivo':
      url = 'backend/ventas/listarVentaMonto.php?mes='+dia+'&criterio=diaEfectivo';
      break;
    case 'diaTarjeta':
      url = 'backend/ventas/listarVentaMonto.php?mes='+dia+'&criterio=diaTarjeta';
      break;
    case 'mesEfectivo':
      url = 'backend/ventas/listarVentaMonto.php?mes='+mes+'&criterio=mesEfectivo';
      break;
    case 'mesTarjeta':
      url = 'backend/ventas/listarVentaMonto.php?mes='+mes+'&criterio=mesTarjeta';
      break;
    default:
      break;
  }
  fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(venta => {
      if(criterio=='mes'&&!render){datosBalance.ventas.mes =  parseInt(venta.ventas_total);return null};
      (criterio==null)?datosBalance.ventas.dia =  parseInt(venta.ventas_total):null;
      ventasTotal.innerHTML = '$'+venta.ventas_total
      if (venta.ventas_total == null) {
        ventasTotal.innerHTML = '$0';
      }
    });
  })
}

function getVentasLimit() {
  fetch('backend/ventas/listarVentaLimit.php')
  .then(res=>res.json())
  .then(response=>{
    let template = '';
    response.forEach(venta=>{
      template += `
        <tr>
          <th scope="row">${venta.producto}</th>
          <td>${venta.cantidad}</td>
          <td>${venta.fecha}</td>
          <td>${venta.total}</td>
        </tr>
      `
    });
    tablaVentas.innerHTML = template;
  })
}

function getPedidosLimit() {
  fetch('backend/pedidos/listarPedidosLimit.php')
  .then(res=>res.json())
  .then(response=>{
    let template = '';
    response.forEach(venta=>{
      template += `
        <tr>
          <th scope="row">${venta.descripcion}</th>
          <td>${venta.cantidad}</td>
          <td>${venta.total}</td>
        </tr>
      `
    });
    tablaPedidos.innerHTML = template;
  })
}

function getPedidos(criterio=null,render=true) {
  url = 'backend/pedidos/listarPedidosMonto.php?fecha='+dia;
  if (criterio!=null) {
    url = 'backend/pedidos/listarPedidosMonto.php?fecha='+mes+'&criterio=mes';
  }
  fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(pedido=>{
      if(criterio!=null&&!render){
        datosBalance.pedidos.mes =  parseInt(pedido.pedidos_total);
        return;
      }
      datosBalance.pedidos.dia =  parseInt(pedido.pedidos_total);
      pedidosTotal.innerHTML = '$'+pedido.pedidos_total;
      if (pedido.pedidos_total == null) {
        pedidosTotal.innerHTML = '$0';
      }
    })
  })
}

function getServicios(criterio=null,render=true) {
  url = 'backend/servicios/listarServiciosMonto.php?criterio=mes&fecha='+mes;
  if (criterio!=null) {
    url = 'backend/servicios/listarServiciosMonto.php?fecha='+mes;
  }
  fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(servicio=>{
      if(criterio!=null&&!render){datosBalance.servicios.mes =  parseInt(servicio.servicio_total);return;};
      serviciosTotal.innerHTML = '$'+servicio.servicio_total;
      if (servicio.servicio_total == null) {
        serviciosTotal.innerHTML = '$0';
      }
    })
  })
}

function getServiciosSinPagar() {
  fetch('backend/servicios/listarServiciosSinPagar.php')
  .then(res=>res.json())
  .then(response=>{
    response.forEach(servicio=>{
      servicios_a_pagar.innerHTML = servicio.servicios_sin_pagar;
      if (servicio.servicios_sin_pagar == null) {
        servicios_a_pagar.innerHTML = '0';
      }
    })
  })
}

function getPedidosSinEntregar() {
  fetch('backend/pedidos/obtenerPedidosSinEntregar.php')
  .then(res=>res.json())
  .then(response=>{
    response.forEach(pedidos=>{
      pedidos_entregar.innerHTML = pedidos.pedidos_sin_entregar;
      if (pedidos.pedidos_sin_entregar == null) {
        pedidos_entregar.innerHTML = '0';
      }
    })
  })
}

function getDeudores() {
  fetch('backend/deudores/obtenerDeudores.php')
  .then(res=>res.json())
  .then(response=>{
    response.forEach(deudores=>{
      deudoresDiv.innerHTML = deudores.deudores;
      if (deudores.deudores == null) {
        deudoresDiv.innerHTML = '0';
      }
    })
  })
}

function getVentasTarjeta(criterio){
  return new Promise((resolve,reject)=>{
    url = 'backend/ventas/pagos_con_tarjeta.php?fecha='+mes;
    div = totalVentasTarjeta;
    if (criterio!=null) {
      url = 'backend/ventas/pagos_con_tarjeta.php?criterio=deuda&fecha='+mes;
      div = totalDeudasTarjeta;
    }
    fetch(url)
    .then(res=>res.json())
    .then(response=>{
      response.forEach(tarjeta => {
        div.innerHTML = tarjeta.pagos_tarjeta;
        resolve();
      });
    })
  })
}

function getProductoMasVendido(criterio=null) {
  url = 'backend/ventas/producto_mas_vendido.php?fecha='+mes;
  if (criterio!=null) {
    url = 'backend/ventas/producto_mas_vendido.php?fecha=2020-4-14&criterio=dia';
  }
  fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(prd => {
      productoMasVendido.innerHTML = prd.producto
      if (prd.producto == '') {
        productoMasVendido.innerHTML = 'No registrado'
      }
    });
  })
}


// filtros

function filtrarVentas(valor) {
  filtro = valor.target.value;
  switch (filtro) {
    case 'mes':
      getVentasTotal('mes');
      break;
    case 'dia':
      getVentasTotal(null);
      break;
    case 'diaEfectivo':
      getVentasTotal('diaEfectivo');
      break;
    case 'diaTarjeta':
      getVentasTotal('diaTarjeta');
      break;
    case 'mesEfectivo':
      getVentasTotal('mesEfectivo');
      break;
    case 'mesTarjeta':
      getVentasTotal('mesTarjeta');
      break;
    default:
      break;
  }
}

function filtrarPedidos(valor) {
  filtro = valor.target.value;
  if (filtro == 'mes') {
    getPedidos('mes');
  }else if(filtro=='dia'){
    getPedidos(null);
  }
}

function filtrarServicio(valor) {
  filtro = valor.target.value;
  if (filtro == 'mes') {
    getServicios('mes');
  }else if(filtro=='por-pagar'){
    getServicios(null);
  }
}


function filtrarProductoVendido(valor) {
  filtro = valor.target.value;
  if (filtro == 'dia') {
    getProductoMasVendido('dia');
  }else{
    getProductoMasVendido();
  }
}
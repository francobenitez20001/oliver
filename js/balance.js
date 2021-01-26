

let ventasTotal = document.getElementById('ventasTotal');
let tablaVentas = document.getElementById('tabla-ventas');
let tablaPedidos = document.getElementById('tablaPedidos');
let pagosTotal = document.getElementById('pagosTotal');
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
  pagos:{
    dia:0,
    mes:0
  },
  servicios:{
    dia:0,
    mes:0
  }
};

let recaudacionFinal = 0;

let f = new Date();
let numeroDia;
let numeroMes;
(f.getDate()<10)?numeroDia='0'+f.getDate():numeroDia=f.getDate();
numeroMes='-'+((f.getMonth()+1)<10?`0${f.getMonth()+1}`:(f.getMonth()+1));

let dia = f.getFullYear() + numeroMes + '-' + numeroDia;
let mes = f.getFullYear() + numeroMes;

window.onload = ()=>{
 console.log('cache actualizado');
  getVentasTotal();
  getVentasLimit();
  getPedidosLimit();
  getPagosProveedor();
  getServicios('porpagar');
  getPedidosSinEntregar();
  getServiciosSinPagar();
  getDeudores();
  getVentasTarjeta(null).then(()=>{
    getVentasTarjeta(true).then(()=>{
      getProductoMasVendido();
    })
  })
}

const verBalance = async criterio=>{
  try { 
    let containerEstado = document.getElementById('containerEstado');
    await getVentasTotal(criterio,false);
    await getPagosProveedor(criterio,false);
    await getServicios(criterio,false);
    //evito valores Nan
    (datosBalance.servicios.mes<=0)?datosBalance.servicios.mes = 0:null;
    (!datosBalance.pagos.dia>0)?datosBalance.pagos.dia =0:null;
    console.log(datosBalance.servicios.mes);
    if(criterio == 'mes'){
      recaudacionFinal = datosBalance.ventas.mes-(datosBalance.pagos.mes+datosBalance.servicios.mes);
    }else{
      recaudacionFinal = datosBalance.ventas.dia-(datosBalance.pagos.dia+datosBalance.servicios.dia);
    }
    if(recaudacionFinal>0){
      document.getElementById('cardRecaudacionFinal').classList.remove('bg-danger');
      document.getElementById('cardRecaudacionFinal').classList.add('bg-success');
    }else{
      document.getElementById('cardRecaudacionFinal').classList.remove('bg-success');
      document.getElementById('cardRecaudacionFinal').classList.add('bg-danger');
    }
    document.getElementById('recaudacionFinal').innerHTML = '$'+recaudacionFinal;
    return containerEstado.classList.remove('d-none');
  } catch (error) {
    console.log(error);
  }
}

const changeCriterioBalance = event=>{
  if(event.target.value == 'dia'){
    verBalance('dia');
  }else{
    verBalance('mes');
  }
}

const getVentasTotal = async(criterio=null,render=true)=>{
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
  await fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(venta => {
      if(criterio=='mes'&&!render){return datosBalance.ventas.mes =  parseInt(venta.ventas_total);return null};
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
          <th scope="row">${venta.fecha}</th>
          <td>${venta.tipo_pago}</td>
          <td>${venta.estado}</td>
          <td>${venta.subtotal}</td>
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
        </tr>
      `
    });
    tablaPedidos.innerHTML = template;
  })
}

const getPagosProveedor = async(criterio=null,render=true)=>{
  url = 'backend/pagoProveedores/listarPagosMonto.php?fecha='+mes;
  if (criterio=='mes') {
    url = 'backend/pagoProveedores/listarPagosMonto.php?fecha='+mes+'&criterio=mes';
  }else if(criterio == 'pagoDia' || criterio == 'dia'){
    url = 'backend/pagoProveedores/listarPagosMonto.php?fecha='+dia+'&estado=pago';
  }else if(criterio == 'pagoMes'){
    url = 'backend/pagoProveedores/listarPagosMonto.php?fecha='+mes+'&criterio=mes&estado=pago';
  }
  await fetch(url)
  .then(res=>res.json())
  .then(response=>{
    response.forEach(pago=>{
      if(criterio!=null && criterio == 'mes' &&!render){
        return datosBalance.pagos.mes =  parseInt(pago.pagos_total);
      }
      datosBalance.pagos.dia =  parseInt(pago.pagos_total);
      pagosTotal.innerHTML = '$'+ pago.pagos_total;
      if (pago.pagos_total == null) {
        pagosTotal.innerHTML = '$0';
      }
    })
  })
}

const getServicios = async(criterio=null,render=true)=>{
  url = 'backend/servicios/listarServiciosMonto.php?fecha='+mes;//trae todos los del mes
  if(criterio == 'porpagar' || criterio == null){
    url = 'backend/servicios/listarServiciosMonto.php?criterio=nopago&fecha='+mes;//los que faltan pagar
  }else if(criterio == 'dia'){
    url = 'backend/servicios/listarServiciosMonto.php?fecha='+dia+'&criterio=pago';
  }
  await fetch(url)
  .then(res=>res.json())
  .then(response=>{
      if(criterio=='dia'&&!render){ 
        console.log('esta en dia');
        return datosBalance.servicios.dia =  parseInt(response[0].servicio_total);
      }else if(criterio=='mes' && !render){
        console.log('esta en mes');
        datosBalance.servicios.mes =  parseInt(response[0].servicio_total);
        
        return;
      };
      console.log('normal');
      serviciosTotal.innerHTML = '$'+response[0].servicio_total;
      if (response[0].servicio_total == null) {
        serviciosTotal.innerHTML = '$0';
      }
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
    url = `backend/ventas/producto_mas_vendido.php?fecha=${dia}&criterio=dia`;
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

function filtrarPagoProveedores(valor) {
  filtro = valor.target.value;
  if (filtro == 'mes') {
    getPagosProveedor('mes');
  }else if(filtro=='dia'){
    getPagosProveedor(null);
  }else if(filtro == 'pagoDia'){
    getPagosProveedor('pagoDia')
  }else if(filtro == 'pagoMes'){
    getPagosProveedor('pagoMes')
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

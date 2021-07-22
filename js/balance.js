class Balance{
  constructor(fecha){
    this.recaudacionVenta = 0;
    this.gastoProveedores = 0;
    this.gastoServicios = 0;
    this.pedidosNoRecibidos = 0;
    this.serviciosSinPagar = 0;
    this.deudores = 0;
    this.ventas = [];
    this.pedidos = [];
    this.recaudacionFinal = 0;
    this.productosMasVendido = null;
    this.ventasConTarjeta = null;
    this.ventasEnEfectivo = null;
    
    this.fecha = fecha;

    //fetch data
    this.api = 'backend';
  }

  async calcularRecaudacionEnVentas(estado=null,tipo_pago=null){
    let url = `${this.api}/ventas/recaudacion.php?fecha=${this.fecha}`;
    if(estado){
      url += `&estado=${estado}`;
    }
    if(tipo_pago){
      url += `&tipoDePago=${tipo_pago}`
    }
    const req = await fetch(url);
    if(req.status !== 200){
      return modalError(req.statusText);
    }
    const data = await req.json();
    this.recaudacionVenta = isNaN(data[0].ventas_total) ? 0 : parseFloat(data[0].ventas_total);
    return this.recaudacionVenta;
  }

  async calcularGastoEnProveedores(pagados=false){//si viene true, trae el monto pagado a proveedores
    let url = `${this.api}/proveedores/monto.php?fecha=${this.fecha}`;
    if (pagados){
      url += `&estado=pago`;
    }
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.gastoProveedores = isNaN(data[0].valor) ? 0 : parseFloat(data[0].valor);
    return this.gastoProveedores;
  }

  async calcularGastoEnServicios(estado){
    let url = `${this.api}/servicios/monto.php?fecha=${this.fecha}`;
    if(estado){
      url += `&estado=${estado}`
    }
    const req = await fetch(url);
    if(req.status !== 200){
      return modalError(req.statusText);
    }
    const data = await req.json();
    this.gastoServicios =  isNaN(data[0].servicio_total) ? 0 : parseFloat(data[0].servicio_total);
    return this.gastoServicios;
  }

  async obtenerPedidosSinRecibir(){
    let url = `${this.api}/pedidos/sinEntregar.php`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.pedidosNoRecibidos = isNaN(data[0].pedidos_sin_entregar) ? 0 : parseInt(data[0].pedidos_sin_entregar);
    return this.pedidosNoRecibidos;
  }

  async obtenerServiciosSinPagar(){
    let url = `${this.api}/servicios/sinPagar.php`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.serviciosSinPagar = isNaN(data[0].servicios_sin_pagar) ? 0 : parseInt(data[0].servicios_sin_pagar);
    return this.serviciosSinPagar;
  }

  async obtenerDeudores(){
    let url = `${this.api}/deudores/obtenerDeudores.php`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.deudores = isNaN(data[0].deudores) ? 0 : parseInt(data[0].deudores);
    return this.deudores;
  }

  async obtenerVentas(){
    let url = `${this.api}/ventas/listarVenta.php?limit=3`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.ventas = data;
    return this.ventas;
  }

  async obtenerPedidos(){
    let url = `${this.api}/pedidos/listarPedidos.php?limit=3`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.pedidos = data;
    return this.pedidos;
  }

  async obtenerVentasPorMedioDePago(){
    let url = `${this.api}/ventas/ventasPorMedioDePago.php?fecha=${this.fecha}`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    this.ventasConTarjeta = {
      pagadas:data[0].pagos_tarjeta,
      deudores:data[0].deudor_tarjeta
    };
    this.ventasEnEfectivo = {
      pagas:data[0].pagos_efectivo,
      deudores:data[0].deudor_tarjeta
    };
    return
  }

  async obtenerProductoMasVendido(){
    let url = `${this.api}/ventas/masVendido.php?fecha=${this.fecha}`;
    const req = await fetch(url);
    if(req.status !== 200){
      modalError(req.statusText);
      return;
    }
    const data = await req.json();
    if(!data.length) return;
    this.productosMasVendido = {
      nombre:data[0].producto,
      cantidad:data[0].cantidad
    };
    return this.productosMasVendido;
  }

  //GETTERS
  getRecaudacionVenta(){
    return this.recaudacionVenta;
  }

  getGastoProveedores(){
    return this.gastoProveedores;
  }

  getGastoServicios(){
    return this.gastoServicios;
  }

  getpedidosNoRecibidos(){
    return this.pedidosNoRecibidos;
  }

  getServiciosSinPagar(){
    return this.serviciosSinPagar;
  }

  getDeudores(){
    return this.deudores;
  }

  getVentas(){
    return this.ventas;
  }

  getPedidos(){
    return this.pedidos;
  }
  
  getProductoMasVendido(){
    return this.productosMasVendido;
  }

  getVentasConTarjeta(){
    return this.ventasConTarjeta;
  }

  getVentasEnEfectivo(){
    return this.ventasEnEfectivo;
  }

  getFecha(){
    return this.fecha;
  }

  getRecaudacionFinal(){
    return this.recaudacionFinal;
  }

  setFecha(formato){
    this.fecha = moment().format(formato);
  }

};

//BOXS VALORES
const ventasTotal = document.getElementById('ventasTotal');
const proveedoresTotal = document.getElementById('pagosTotal');
const serviciosTotal = document.getElementById('serviciosTotal');
const pedidos_sin_entregar = document.getElementById('pedidos_sin_entregar');
const servicios_sin_pagar = document.getElementById('servicios_sin_pagar');
const deudores = document.getElementById('deudores');

//TABLAS
const tablaVentas = document.getElementById('tabla-ventas');
const tablaPedidos = document.getElementById('tablaPedidos');

//INFO TARJETA DE CREDITO
const totalVentasTarjeta = document.getElementById('total-ventas-tarjeta');
const totalDeudasTarjeta = document.getElementById('total-deudas-tarjeta');

//P PARA MOSTRAR PRD MAS VENIDIDO
const productoMasVendido = document.getElementById('producto-mas-vendido');
const infoMasVendido = document.getElementById('infoMasVendido');

const alerta = document.getElementById('alerta');
const slider = document.getElementById('slider');
const main = document.getElementById('main__section');

//FECHA CON LA QUE SE SETEA EL INICIO DEL BALANCE
let fecha = moment().format('YYYY-MM-DD');
const balance = new Balance(fecha);

window.onload = ()=>{
  toggleLoader();
  let promesas = [
    balance.calcularRecaudacionEnVentas(),
    balance.calcularGastoEnProveedores(),
    balance.calcularGastoEnServicios(),
    balance.obtenerPedidosSinRecibir(),
    balance.obtenerServiciosSinPagar(),
    balance.obtenerDeudores(),
    balance.obtenerPedidos(),
    balance.obtenerVentas(),
    balance.obtenerVentasPorMedioDePago(),
    balance.obtenerProductoMasVendido()
  ]
  Promise.all(promesas).then(()=>{
    ventasTotal.innerHTML = balance.getRecaudacionVenta();
    proveedoresTotal.innerHTML = balance.getGastoProveedores();
    serviciosTotal.innerHTML = balance.getGastoServicios();
    pedidos_sin_entregar.innerHTML = balance.getpedidosNoRecibidos();
    servicios_sin_pagar.innerHTML = balance.getServiciosSinPagar();
    deudores.innerHTML = balance.getDeudores();
    renderTablaVentas(balance.getVentas());
    renderTablaPedidos(balance.getPedidos());

    const {pagadas,deudores:deben} = balance.getVentasConTarjeta();
    totalVentasTarjeta.innerHTML = pagadas;
    totalDeudasTarjeta.innerHTML = deben;

    let masVendido = balance.getProductoMasVendido();
    if(!masVendido){
      productoMasVendido.innerHTML = "No hay registros";
      infoMasVendido.innerHTML = "";
    }else{
      productoMasVendido.innerHTML = masVendido.producto;
      infoMasVendido = "Es el producto más vendido durante el período seleccionado";
    }

    toggleLoader();
  }).catch(err=>{
    modalError(err);
  })
}

const toggleLoader = () =>{
  slider.classList.toggle('d-none');
  main.classList.toggle('d-none');
}

const filtrarVentas = async e =>{
  if(e.target.value == "YYYY-MM-DD" || e.target.value == "YYYY-MM"){
    balance.setFecha(e.target.value);
    const nroVenta = await balance.calcularRecaudacionEnVentas();
    return ventasTotal.innerHTML = nroVenta;
  }
  const {target:{value}} = e;
  let fecha = (value == 'diaEfectivo' || value == 'diaTarjeta') ? 'YYYY-MM-DD' : 'YYYY-MM';
  let tipoPago = (value == 'diaEfectivo' || value == 'mesEfectivo') ? 'Efectivo' : 'Tarjeta';
  balance.setFecha(fecha);
  const nroVenta = await balance.calcularRecaudacionEnVentas(null,tipoPago);
  return ventasTotal.innerHTML = nroVenta;
}

const filtrarPagoProveedores = async e => {
  if(e.target.value == "YYYY-MM-DD" || e.target.value == "YYYY-MM"){
    balance.setFecha(e.target.value);
    const nroProveedores = await balance.calcularGastoEnProveedores();
    return proveedoresTotal.innerHTML = nroProveedores;
  }
  const {target:{value}} = e;
  let fecha = value == 'pagoDia' ? 'YYYY-MM-DD' : 'YYYY-MM';
  balance.setFecha(fecha);
  const nroProveedores = await balance.calcularGastoEnProveedores(true);
  return proveedoresTotal.innerHTML = nroProveedores;
}

const filtrarServicio = async e => {
  const {target:{value}} = e;
  let estado = value == "all" ? null : value;
  balance.setFecha('YYYY-MM');
  const nroServicios = await balance.calcularGastoEnServicios(estado);
  return serviciosTotal.innerHTML = nroServicios;
}

const changeCriterioBalance = event =>{
  
}

const renderTablaVentas = data => {
  let template = "";
  data.forEach(venta=>{
    template += `
      <tr>
        <td>${venta.fecha}</td>
        <td>${venta.tipo_pago}</td>
        <td>${venta.estado}</td>
        <td>${venta.total}</td>
        <td>${venta.vendedor}</td>
        <td>${venta.local}</td>
      </tr>
    `;
  });
  return tablaVentas.innerHTML = template;
}

const renderTablaPedidos = data => {
  let template = "";
  data.forEach(pedido=>{
    template += `
      <tr>
        <td>${pedido.descripcion}</td>
        <td>${pedido.cantidad}</td>
      </tr>
    `;
  });
  return tablaPedidos.innerHTML = template;
}
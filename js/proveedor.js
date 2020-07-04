let f = new Date();
let dia = f.getFullYear() + "-0" + (f.getMonth() +1) + "-" + f.getDate();
let mes = f.getFullYear() + "-0" + (f.getMonth() +1);

let listadoProveedores;
let listadoPedidos;
let listadoPagos;

//datos para mostrar en las cajas cuando se filtra
let pedidosRecibidos = 0;
let pedidosNoRecibidos = 0;
let pagosPagadosParcialmente = 0;
let montoPagado = [];
let montoTotal = [];
let reporteProveedoresDom = {
    recibidos:document.getElementById('recibidos'),
    noRecibidos:document.getElementById('noRecibidos'),
    total:document.getElementById('total'),
    pagado:document.getElementById('pagado'),
    sinPagarTodo:document.getElementById('porPagar'),
    saldo:document.getElementById('saldo')
}
let btnAdjuntarComprobante = document.getElementById('btn-adjuntarComprobante');
btnAdjuntarComprobante.addEventListener('click',subirComprobante);

window.onload =()=>{
    getProveedores();
    fetch('backend/pedidos/listarPedidos.php').then(res=>res.json()).then(data=>{
        listadoPedidos = data;
    });
    getPagos();
}


function getProveedores() {
    fetch('backend/proveedores/listarProveedor.php')
    .then(res=>res.json())
    .then(newRes=>{
        listadoProveedores = newRes;
        render(newRes);//renderiza la table
        renderSelectProveedores();//renderiza el select para filtrar
    })
}

function getPagos() {
    fetch('backend/pagoProveedores/listarPagos.php').then(res=>res.json()).then(data=>{
        listadoPagos = data;
    })
}

function renderSelectProveedores(){
    let template = '';
    listadoProveedores.forEach(proveedor => {
        template += `
            <option value="${proveedor.idProveedor}">${proveedor.proveedor}</option>`;
    });
    return document.getElementById('filtroPedidoPorProveedor').innerHTML += template;
}

function render(data) {
    let bodyTable = document.getElementById('bodyTable');
    let template = '';
    let email;
    let telefono;
    data.forEach(reg => {
        email = reg.email;
        telefono = reg.telefono;
        if (reg.email == null) {
            email = 'No registrado';
        }
        if (reg.telefono == null) {
            telefono = 'No registrado';
        }
        template += `
            <tr>
                <th scope="row">${reg.proveedor}</th>
                <td>${email}</td>
                <td>${telefono}</td>
                <td><i class="fas fa-edit" id="boton-entregar" onclick="verProveedorPorId(${reg.idProveedor})" style="cursor:pointer    color:yellow;font-size:20px"></i>
                <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarProveedor(${reg.idProveedor})"></i>
                <button data-toggle="modal" onclick="insertarDatosEnForm(${reg.idProveedor})" data-target="#staticBackdrop" class="btn btn-success">Nuevo pago</button>
                </td>
            </tr>
        `;
    });
    bodyTable.innerHTML = template;
}


function eliminarProveedor(id) {
    Swal({
        title: '¿Desea eliminar el proveedor?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/proveedores/eliminarProveedor.php?idProveedor='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getProveedores();
                }
                // console.log(newRes);
            })
        }
    })
}


let formAgregarProveedor = document.getElementById('formAgregarProveedor');
formAgregarProveedor.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formAgregarProveedor);
    fetch('backend/proveedores/agregarProveedor.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes.status==200) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            formAgregarProveedor.classList.add('d-none');
        }
        console.log(newRes);
    })
})

function mostrarFormularioAgregar() {
    //ocultar listado de proveedores y mostrar el form para agregar
    let tablaProveedor = document.getElementById('tablaProveedor');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarProveedor');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaProveedor.classList.add('d-none');
    bannerForm.classList.add('d-none');
}

function ocultarFormularioAgregar() {
    let tablaProveedor = document.getElementById('tablaProveedor');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarProveedor');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaProveedor.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getProveedores();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}

function mostrarFormularioModificar() {
    let tablaProveedor = document.getElementById('tablaProveedor');
    let divFormulario = document.getElementById('form-modificar-div');
    let formulario = document.getElementById('formModificarProveedor');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaProveedor.classList.add('d-none');
    bannerForm.classList.add('d-none');
}

function ocultarFormularioModificar() {
    let tablaProveedor = document.getElementById('tablaProveedor');
    let divFormulario = document.getElementById('form-modificar-div');
    let formulario = document.getElementById('formModificarProveedor');
    let alert = document.getElementById('alerta');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaProveedor.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getProveedores();
}

function verProveedorPorId(idProveedor) {
    fetch('backend/proveedores/verProveedorPorId.php?idProveedor='+idProveedor)
    .then(res=>res.json())
    .then(proveedor=>{
        let estadoOpuesto;
        let estadoLabel;
        let estadoLabelOpuesto;
        let template;
        let email;
        let telefono;
        let form = document.getElementById('formModificarProveedor');
        proveedor.forEach(prov => {
            email = prov.email;
            telefono = prov.telefono;
            if (prov.estado == 1){
                estadoLabel = 'Activo';
                estadoOpuesto = 0;
                estadoLabelOpuesto = 'Inactivo';
            }else{
                estadoLabel = 'Inactivo';
                estadoOpuesto = 1;
                estadoLabelOpuesto = 'Activo';
            }
            if (prov.email == null) {
                email = '';
            }
            if (prov.telefono == null) {
                telefono = '';
            }
            template += `
            <div class="row">
                <input type="hidden" value="${prov.idProveedor}" name="idProveedor">
                <input type="text" name="proveedor" id="proveedor" class="form-control col-12 col-md-5 mb-4" value="${prov.proveedor}" required>
                <div class="col-md-2"></div>
                <input type="email" name="email" id="email" class="form-control col-12 col-md-5 mb-4" value="${email}">
                <input type="text" name="telefono" id="telefono" class="form-control col-12 col-md-5 mb-4" value="${telefono}">
                <div class="col-md-2"></div>
                <div class="col-12 col-md-5">
                    <select name="estado" id="" class="form-control">
                        <option value="${prov.estado}">${estadoLabel}</option>
                        <option value="${estadoOpuesto}">${estadoLabelOpuesto}</option>
                    </select>
                </div>
                <div class="col-12">
                    <input type="submit" class="btn btn-outline-info btn-block" value="Modificar">
                </div>
            </div>
            `
        });
        form.innerHTML = template;
        mostrarFormularioModificar();
    })
}

let formModificarProveedor = document.getElementById('formModificarProveedor');
formModificarProveedor.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formModificarProveedor);
    fetch('backend/proveedores/modificarProveedor.php',{
        method:'POST',
        body:data
    })
    .then(res=>res.json())
    .then(response=>{
        // console.log(response.status);
        let alerta = document.getElementById('alerta');
        alerta.classList.toggle('d-none');
        alerta.innerHTML = response.info;
        if (response.status == 200) {
            alerta.classList.add('alert-success');
            return;
        }
        alerta.classList.add('alert-danger');
    })
})



//para el filtro de pedidos por proveedor
function getPedidosPorProveedor(event){
    let proveedor = event;
    if (proveedor == 'all') {
        document.getElementById('reporteProveedores').classList.add('d-none');
        render(listadoProveedores);
        return;
    }
    let filtrados = listadoProveedores.filter(newArr=>newArr.idProveedor == proveedor);
    if (filtrados.length<1) {
        render(listadoProveedores);
        return alert('No hay pedidos con este proveedor');
    }
    getReporteEstadisticas(parseInt(document.getElementById('filtroPedidoPorProveedor').value));//le paso el id del proveedor
    return render(filtrados);
}

function getReporteEstadisticas(idProveedor) {
    let filtrados = listadoPedidos.filter(res=>res.idProveedor == idProveedor);
    //console.log(filtrados);
    pedidosRecibidos = 0;
    pedidosNoRecibidos = 0;
    pedidosRecibidosPorPagar = 0;
    montoPagado.splice(0,montoPagado.length);
    montoTotal.splice(0,montoTotal.length);
    //filtro los pedidos de ese proveedor y verifico cuantos fueron entregados y cuantos no.
    filtrados.filter(res=>{
        (res.estado == 'Recibido')?pedidosRecibidos++:pedidosNoRecibidos++;
    });
    //ahora extraigo todos los datos de los pagos a ese proveedor
    listadoPagos.filter(res=>{
        if(res.idProveedor == idProveedor){
            (res.total > res.monto) ?pagosPagadosParcialmente++:null;
            (res.total)?montoTotal.push(parseInt(res.total)):null;
            (res.monto)?montoPagado.push(parseInt(res.monto)):null;
        }
    })
    reporteProveedoresDom.recibidos.innerText = pedidosRecibidos;
    reporteProveedoresDom.noRecibidos.innerText = pedidosNoRecibidos;
    let totalNumero = montoTotal.reduce((a, b) => a + b, 0);
    let pagadoNumero = montoPagado.reduce((a,b)=> a + b, 0);
    reporteProveedoresDom.total.innerText = totalNumero;
    reporteProveedoresDom.pagado.innerText = pagadoNumero;
    reporteProveedoresDom.sinPagarTodo.innerText = pagosPagadosParcialmente;
    reporteProveedoresDom.saldo.innerText = pagadoNumero-totalNumero;
    document.getElementById('reporteProveedores').classList.remove('d-none');
    document.getElementById('btnVerComprobantes').setAttribute('href','adminComprobantes.html?idProveedor='+parseInt(document.getElementById('filtroPedidoPorProveedor').value));
    document.getElementById('btnVerPagos').setAttribute('href','adminPagos.html?idProveedor='+parseInt(document.getElementById('filtroPedidoPorProveedor').value));
}

function subirComprobante() {
    let idProveedor = parseInt(document.getElementById('filtroPedidoPorProveedor').value);
    Swal.fire({
        title: 'Adjuntar comprobantes',
        html:`<form id="cargarComprobanteProveedor">
                <input id="comprobante" name="comprobante" type="file" class="swal2-input">
                <input id="" name="descripcion" type="text" class="swal2-input">
                <input id="idProveedor" name="idProveedor" value="${idProveedor}" type="hidden">
              </form>`,
        focusConfirm: false,
        preConfirm: () => {
            document.getElementById('slider').classList.remove('d-none');
            let data = new FormData(document.getElementById('cargarComprobanteProveedor'));
            fetch('backend/comprobantes/cargarComprobante.php',{
                method:'POST',
                body:data
            }).then(res=>res.json()).then(data=>{
                if(data.status==200){
                    fetch(`backend/comprobantes/agregarComprobante.php?idProveedor=${data.data.idProveedor}&comprobante=${data.data.comprobante}&descripcion=${data.data.descripcion}`).then(res=>res.json()).then(response=>{
                        document.getElementById('slider').classList.add('d-none');
                        if(response.status==400){
                            Swal.fire(
                                'error',
                                'Oops...',
                                response.info
                            )
                            return;
                        }
                        return Swal.fire(
                            'Comprobante cargado',
                            response.info,
                            'success'
                        )
                    })
                }
            })
        }
    })
}

//cuando se abre el modal para el pago, ejecuto esta funcion que inserta el id del proveedor y la fecha en los inpputs hiddens.
function insertarDatosEnForm(id) {
    document.getElementsByName('idProveedor')[0].value = id;
    document.getElementsByName('fecha')[0].value = dia;
}

function cargarPago(event) {
    event.preventDefault();
    let data =  new FormData(document.getElementById('formCargarPago'));
    fetch('backend/pagoProveedores/agregarPago.php',{
        method:'POST',
        body:data
    }).then(res=>res.json()).then(response=>{
        if(response.status == 200){
            document.getElementById('alert-response').classList.add('alert-info');
        }else{
            document.getElementById('alert-response').classList.add('alert-danger');
        }
        document.getElementById('alert-response').innerHTML = response.info;
        document.getElementById('alert-response').classList.remove('d-none');
        setTimeout(() => {
            window.location.assign('adminProveedores.html');
        }, 1000);
    })
}
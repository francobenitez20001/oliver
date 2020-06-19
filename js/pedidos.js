let f = new Date();
let dia = f.getFullYear() + "-0" + (f.getMonth() +1) + "-" + f.getDate();
let mes = f.getFullYear() + "-0" + (f.getMonth() +1);
let listadoPedidos;
let reporteProveedoresDom = {
    recibidos:document.getElementById('recibidos'),
    noRecibidos:document.getElementById('noRecibidos'),
    total:document.getElementById('total'),
    pagado:document.getElementById('pagado'),
    sinPagarTodo:document.getElementById('porPagar'),
    saldo:document.getElementById('saldo')
}
let pedidosRecibidos = 0,
    pedidosNoRecibidos = 0,
    pedidosRecibidosPorPagar = 0,
    montoTotal = [],
    montoPagado = [];

let btnAdjuntarComprobante = document.getElementById('btn-adjuntarComprobante');
btnAdjuntarComprobante.addEventListener('click',subirComprobante);

window.onload = ()=>{
    getPedidos();
    getProveedores('proveedor');
    getProveedores('filtroPedidoPorProveedor');
}

function getPedidos() {
    fetch('backend/pedidos/listarPedidos.php?mes='+mes)
    .then(res=>res.json())
    .then(newRes=>{
        listadoPedidos = newRes;
        render(listadoPedidos);
    })
}

function render(data) {
    let bodyTable = document.getElementById('bodyTable');
    let template = '';
    buttons = '';
    data.forEach(reg => {
        if (reg.estado == 'No recibido') {
            buttons = `<i class="fas fa-money-check-alt" style="cursor:pointer;color:green;font-size:20px" id="boton-eliminar"onclick="recibirPedido(${reg.idPedido})"></i>
            <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar"onclick="eliminarPedido(${reg.idPedido})"`;
        }else{
            buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar"onclick="eliminarPedido(${reg.idPedido})"></i>`;
            if(reg.total !== reg.pagado){
                buttons += ` <i class="fas fa-money-check-alt" style="cursor:pointer;color:green;font-size:20px"id="boton-eliminar" onclick="recibirPedido(${reg.idPedido},true)"></i>`;
            }
        }
            
        if(reg.total !== reg.pagado){
            template += `
                <tr class="bg-yellow">
                    <th scope="row">${reg.descripcion}</th>
                    <td>${reg.cantidad}</td>
                    <td>${reg.estado}</td>
                    <td>${reg.proveedor}</td>
                    <td>
                        ${buttons}
                    </td>
                </tr>
            `;
        }else if(reg.estado == 'Recibido' && reg.total == reg.pagado){
            template += `
            <tr class="bg-green">
                <th scope="row">${reg.descripcion}</th>
                <td>${reg.cantidad}</td>
                <td>${reg.estado}</td>
                <td>${reg.proveedor}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        }else{
            template += `
            <tr>
                <th scope="row">${reg.descripcion}</th>
                <td>${reg.cantidad}</td>
                <td>${reg.estado}</td>
                <td>${reg.proveedor}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        }
    });
    bodyTable.innerHTML = template;
}

//######################## ELIMINAR PEDIDO  ######################## 


function eliminarPedido(id) {
    Swal({
        title: '¿Desea eliminar el pedido?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/pedidos/eliminarPedido.php?idPedido='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getPedidos();
                }
                // console.log(newRes);
            })
        }
    })
}



function recibirPedido(id=null,pagoCompleto=false) {
    document.getElementById('idPedido').value = id;
    // inputFile = document.getElementsByName('comprobante')[0];
    inputTotal = document.getElementsByName('total')[0];
    inputPago = document.getElementsByName('pago')[0];
    indicadorValores = document.getElementById('indicadorValores');
    //inputFile.classList.remove('d-none');
    if(!indicadorValores.classList.contains('d-none') && inputTotal.value != ''){
        inputTotal.value = '';
        inputTotal.removeAttribute('disabled');
        indicadorValores.classList.add('d-none');
    }
    if(pagoCompleto){
        //inputFile.classList.add('d-none');
        //inputFile.removeAttribute('required');
        //inputTotal.setAttribute('disabled',true);
        document.getElementById('pagarDeuda').value='true';
        fetch(`backend/pedidos/verPedidoPorId.php?idPedido=${id}`).then(res=>res.json()).then(response=>{
            if (response.status == 200) {
                let total = parseInt(response.total);
                let pagado = parseInt(response.pagado);
                let debe = total - pagado;
                inputTotal.value = total;
                indicadorValores.innerHTML = `Pagado: <b>$${pagado}</b>. Por pagar <b>$${debe}</b>`;
                indicadorValores.classList.remove('d-none');
            }
        })
    }
    let cargarComponente = document.getElementById('cargarComprobante');
    cargarComponente.classList.toggle('d-none');
    cargarComponente.classList.toggle('swal2-container');
    cargarComponente.classList.toggle('swal2-center');
    cargarComponente.classList.toggle('swal2-fade');
    cargarComponente.classList.toggle('swal2-shown');
};

function verComprobante(id) {
    window.location.assign('verComprobante.php?recurso=pedidos&idPedido='+id)
}


document.getElementById('cargarComprobante').addEventListener('submit',event=>{
    event.preventDefault();
    let alertLoading = document.getElementById('alert-loading');
    let alertLoad = document.getElementById('alert-load');
    let alertError = document.getElementById('alert-error');
    alertLoading.classList.remove('d-none');
    let data = new FormData(formCargarComprobante);
    if(document.getElementById('pagarDeuda').value != 'true'){
        fetch('backend/pedidos/recibirPedido.php',{
            method:'POST',
            body:data
        }).then(res=>res.json()).then(response=>{
            if (response.status == 400) {
                alertLoading.classList.add('d-none');
                alertError.innerHTML = response.info;
                alertError.classList.remove('d-none');
                return;
            }
            fetch('backend/producto/modificarStock.php?producto='+response.producto+'&cantidad='+response.cantidad)
            .then(res=>res.json()).then(response=>{
                console.log(response)
                if (response.status == 200) {
                    Swal.fire(
                        'Listo!',
                        response.info,
                        'success'
                    );
                    setTimeout(() => {
                        window.location.assign('adminPedidos.html')
                        return;
                    }, 1000);
                }
            })
        })
    }else{
        fetch('backend/pedidos/saldarDeudaPedido.php',{
            method:'POST',
            body:data
        }).then(res=>res.json()).then(response=>{
            alertLoad.innerHTML = response.info;
            alertLoading.classList.add('d-none');
            alertLoad.classList.remove('d-none');
            getPedidos();
            setTimeout(() => {
                window.location.assign('adminPedidos.html')
                return;
            }, 1000);
        })
    }
})



function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaPedidos = document.getElementById('tablaPedidos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarPedido');
    let bannerForm = document.getElementById('banner-form');
    let inputfecha = document.getElementById('fecha');
    inputfecha.setAttribute('value',dia);
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaPedidos.classList.add('d-none');
    bannerForm.classList.add('d-none');
    getProveedores();
    getProductos();
}


let form = document.getElementById('formAgregarPedido');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/pedidos/agregarPedido.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            form.classList.add('d-none');
        }
    })
})

function ocultarFormularioAgregar() {
    let tablaPedidos = document.getElementById('tablaPedidos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarPedido');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaPedidos.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getPedidos();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}


function getProveedores() {
    let select = document.getElementById('proveedor');
    fetch('backend/proveedores/listarProveedor.php')
    .then(res=>res.json())
    .then(proveedores=>{
        let template = '';
        proveedores.forEach(proveedor => {
            template += `
                <option value="${proveedor.idProveedor}">${proveedor.proveedor}</option>
            `
        });
        select.innerHTML = template;
    })
}

function getProductos() {
    fetch('backend/producto/listarProducto.php').then(res=>res.json()).then(response=>{
        i = 0;
        response.forEach(producto => {
            productosArray[i]= {'producto':producto.producto};
            i++;
        });
        i = 0;
    })
}


//autocompletador en input producto

let desplegableProducto = document.getElementById('productosBusqueda');
let productosArray = [];//es llenado cuando se carga el formulario par agregar

let inputProducto = document.getElementById('producto');
inputProducto.addEventListener('keyup',event=>{
    if (inputProducto.value.length > 2) {
        desplegableProducto.classList.remove('d-none');
        searchProducto(inputProducto.value);   
    }else{
        desplegableProducto.classList.add('d-none');
    }
})

function searchProducto(producto) {
    productoFiltrado = productosArray.filter((prd) =>
        prd.producto.toLowerCase().indexOf(producto.toLowerCase()) > -1
    );
    return renderDesplegableProducto(productoFiltrado);
}

function renderDesplegableProducto(producto) {
    let template = '';
    producto.forEach( prd => {
        template += `
            <option onclick="rellenarInputProducto(event)" value="${prd.producto}">${prd.producto}</option>
        `
    });
    return desplegableProducto.innerHTML = template;
}

function rellenarInputProducto(event) {
    producto = event.target.value;
    inputProducto.value = producto;
    desplegableProducto.classList.add('d-none');
}

function getProveedores(domElementId){
    fetch('backend/proveedores/listarProveedor.php').then(res=>res.json()).then(response=>{
        let template = '';
        response.forEach(proveedor => {
            template += `
            <option value="${proveedor.idProveedor}">${proveedor.proveedor}</option>
            `
        });
        return document.getElementById(`${domElementId}`).innerHTML += template;
    })
}

//filtrar pedidos
function getPedidosPorProveedor(event){
    let proveedor = event;
    if (proveedor == 'all') {
        document.getElementById('reporteProveedores').classList.add('d-none');
        render(listadoPedidos);
        return;
    }
    let filtrados = listadoPedidos.filter(newArr=>newArr.idProveedor == proveedor);
    if (filtrados.length<1) {
        render(listadoPedidos);
        return alert('No hay pedidos con este proveedor');
    }
    getReporteEstadisticas(filtrados);
    return render(filtrados);
}

function getReporteEstadisticas(filtrados) {
    console.log(filtrados);
    pedidosRecibidos = 0;
    pedidosNoRecibidos = 0;
    pedidosRecibidosPorPagar = 0;
    montoPagado.splice(0,montoPagado.length);
    montoTotal.splice(0,montoTotal.length);
    filtrados.filter(res=>{
        (res.estado == 'Recibido')?pedidosRecibidos++:pedidosNoRecibidos++;
        (res.estado == 'Recibido' && res.total != res.pagado) ?pedidosRecibidosPorPagar++:null;
        (res.estado == 'Recibido' && res.total != null)?montoTotal.push(parseInt(res.total)):null;
        (res.estado == 'Recibido' && res.pagado != null)?montoPagado.push(parseInt(res.pagado)):null;
    });
    let totalNumero = montoTotal.reduce((a, b) => a + b, 0);
    let pagadoNumero = montoPagado.reduce((a,b)=> a + b, 0);
    reporteProveedoresDom.recibidos.innerText = pedidosRecibidos;
    reporteProveedoresDom.noRecibidos.innerText = pedidosNoRecibidos;
    reporteProveedoresDom.total.innerText = totalNumero;
    reporteProveedoresDom.pagado.innerText = pagadoNumero;
    reporteProveedoresDom.sinPagarTodo.innerText = pedidosRecibidosPorPagar;
    reporteProveedoresDom.saldo.innerText = pagadoNumero-totalNumero;
    document.getElementById('reporteProveedores').classList.remove('d-none');

    document.getElementById('btnVerComprobantes').setAttribute('href','adminComprobantes.html?idProveedor='+parseInt(document.getElementById('filtroPedidoPorProveedor').value));
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
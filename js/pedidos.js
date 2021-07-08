let f = new Date();
let dia = f.getFullYear() + "-0" + (f.getMonth() +1) + "-" + f.getDate();
let mes = f.getFullYear() + "-0" + (f.getMonth() +1);
let listadoPedidos;

window.onload = ()=>{
    getPedidos();
    getProveedores('proveedor');
    getProveedores('filtroPedidoPorProveedor');
}

function getPedidos() {
    fetch('backend/pedidos/listarPedidos.php')
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
            buttons = `
            <i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px" data-toggle="modal" data-target="#staticBackdrop" onclick="insertarDatosFormModificar(${reg.idPedido},${reg.cantidad})"></i>
            <i class="fas fa-money-check-alt" style="cursor:pointer;color:green;font-size:20px" id="boton-eliminar"onclick="recibirPedido(${reg.idPedido})"></i>
            <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar"onclick="eliminarPedido(${reg.idPedido})"</i> `;
        }else{
            buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar"onclick="eliminarPedido(${reg.idPedido})"></i>`;
            if(reg.total !== reg.pagado){
                buttons += ` <i class="fas fa-money-check-alt" style="cursor:pointer;color:green;font-size:20px"id="boton-eliminar" onclick="recibirPedido(${reg.idPedido},true)"></i>`;
            }
        }    
        if(reg.estado == 'Recibido'){
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
    let inputCantidadLlegada = document.getElementById('cantidadFinal');
    inputCantidadLlegada.classList.remove('d-none');
    inputCantidadLlegada.setAttribute('required','');
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
    let alertError = document.getElementById('alert-error');
    alertLoading.classList.remove('d-none');
    let data = new FormData(formCargarComprobante);
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
                    ).then(()=>{
                        window.location.assign('pedidos.php')  
                    })
                }
            })
        })
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
        render(listadoPedidos);
        return;
    }
    let filtrados = listadoPedidos.filter(newArr=>newArr.idProveedor == proveedor);
    if (filtrados.length<1) {
        render(listadoPedidos);
        return alert('No hay pedidos con este proveedor');
    }
    return render(filtrados);
}

function insertarDatosFormModificar(id,cantidad) {
    document.getElementById('cantidadPrevia').value = cantidad;
    document.getElementById('idPedidoModificar').value = id;
}

function modificarPedido(event) {
    event.preventDefault();
    let data = new FormData(document.getElementById('formModificarPedido'));
    fetch('backend/pedidos/modificarPedido.php',{
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
            window.location.assign('pedidos.php');
        }, 1000);
    })
}

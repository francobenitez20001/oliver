function getPedidos() {
    fetch('backend/pedidos/listarPedidos.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'No recibido') {
                buttons = `<button class="btn btn-outline-warning mr-2" id="boton-entregar" onclick="recibirPedido(${reg.idPedido})">Recibido</button>
                <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarPedido(${reg.idPedido})">Eliminar</button>`;
            }else{
                buttons = `<button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarPedido(${reg.idPedido})">Eliminar</button>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.descripcion}</th>
                <td>${reg.cantidad}</td>
                <td>${reg.estado}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}

getPedidos();

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

function recibirPedido(id) {
    Swal.fire({
        title: 'Querés notificar que recibiste el pedido?',
        text: "Pasaremos este pedido a recibidos",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Notificar que lo recibí!'
    }).then((result) => {
        if (result.value) {
            fetch('backend/pedidos/recibirPedido.php?idPedido='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    Swal.fire(
                        'Listo!',
                        'Actulizaste el estado del pedido.',
                        'success'
                    )
                    getPedidos();
                }
            })
        }
    })
}



function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaPedidos = document.getElementById('tablaPedidos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarPedido');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaPedidos.classList.add('d-none');
    bannerForm.classList.add('d-none');
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
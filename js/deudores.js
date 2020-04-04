function getDeudores() {
    fetch('backend/deudores/listarDeudores.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'debe') {
                buttons = `<button class="btn btn-outline-warning mr-2" id="boton-entregar" onclick="saldarDeudor(${reg.idDeudor})">Pagó</button>
                <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarDeudor(${reg.idDeudor})">Eliminar</button>`;
            }else{
                buttons = `<button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarDeudor(${reg.idDeudor})">Eliminar</button>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.cliente}</th>
                <td>${reg.fecha}</td>
                <td>${reg.descripcion}</td>
                <td>${reg.total}</td>
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

getDeudores();

//######################## ELIMINAR ENVIO  ######################## 


function eliminarDeudor(id) {
    Swal({
        title: '¿Desea eliminar el deudor?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/deudores/eliminarDeudor.php?idDeudor='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getDeudores();
                }
                // console.log(newRes);
            })
        }
    })
}

function saldarDeudor(id) {
    Swal.fire({
        title: 'Querés notificar que el deudor pagó?',
        text: "Pasaremos el cliente a pagado",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Entregado!'
    }).then((result) => {
        if (result.value) {
            fetch('backend/deudores/saldarDeudor.php?idDeudor='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    Swal.fire(
                        'Listo!',
                        'Actulizaste el estado del deudor.',
                        'success'
                    )
                    getDeudores();
                }
            })
        }
    })
}



function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaDeudores = document.getElementById('tablaDeudores');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarDeudor');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaDeudores.classList.add('d-none');
    bannerForm.classList.add('d-none');
}


let form = document.getElementById('formAgregarDeudor');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/deudores/agregarDeudor.php',{
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
    let tablaDeudores = document.getElementById('tablaDeudores');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarDeudor');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaDeudores.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getDeudores();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}
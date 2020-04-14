function getEnvios() {
    fetch('backend/envios/listarEnvios.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'Sin entregar') {
                buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarEnvio(${reg.idEnvio})"></i>
                <i class="fas fa-ambulance" style="cursor:pointer;color:green;font-size:20px" id="boton-entregar" onclick="entregarEnvio(${reg.idEnvio})"></i>`;
            }else{
                buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarEnvio(${reg.idEnvio})"></i>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.cliente}</th>
                <td>${reg.email}</td>
                <td>${reg.ubicacion}</td>
                <td>${reg.descripcionUbicacion}</td>
                <td>${reg.telefono}</td>
                <td>${reg.estado}</td>
                <td>${reg.fecha}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}

getEnvios();

//######################## ELIMINAR ENVIO  ######################## 


function eliminarEnvio(id) {
    Swal({
        title: '¿Desea eliminar el envío?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/envios/eliminarEnvio.php?idEnvio='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getEnvios();
                }
                // console.log(newRes);
            })
        }
    })
}

function entregarEnvio(id) {
    Swal.fire({
        title: 'Querés notificar que entregaste el envío?',
        text: "Pasaremos este envio a entregados",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Entregado!'
    }).then((result) => {
        if (result.value) {
            fetch('backend/envios/entregarEnvio.php?idEnvio='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    Swal.fire(
                        'Listo!',
                        'Actulizaste el estado del envío.',
                        'success'
                    )
                    getEnvios();
                }
            })
        }
    })
}



function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaEnvios = document.getElementById('tablaEnvios');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarEnvio');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaEnvios.classList.add('d-none');
    bannerForm.classList.add('d-none');
}


let form = document.getElementById('formAgregarEnvio');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/envios/agregarEnvio.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            let alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            form.classList.add('d-none');
        }
    })
})

function ocultarFormularioAgregar() {
    let tablaEnvios = document.getElementById('tablaEnvios');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarEnvio');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaEnvios.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getEnvios();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}
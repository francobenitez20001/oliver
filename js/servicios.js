function getServicios() {
    fetch('backend/servicios/listarServicio.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'No pago') {
                buttons = `<button class="btn btn-outline-warning mr-2" id="boton-entregar" onclick="saldarServicio(${reg.idServicio})">Saldado</button>
                <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarServicio(${reg.idServicio})">Eliminar</button>`;
            }else{
                buttons = `<button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarServicio(${reg.idServicio})">Eliminar</button>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.servicioNombre}</th>
                <td>${reg.fecha}</td>
                <td>${reg.total}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}
getServicios();



function eliminarServicio(id) {
    Swal({
        title: '¿Desea eliminar el servicio?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/servicios/eliminarServicio.php?idServicio='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getServicios();
                }
                // console.log(newRes);
            })
        }
    })
}

function saldarServicio(id) {
    Swal.fire({
        title: 'Querés notificar que pagaste el servício?',
        text: "Pasaremos el servicio a pagado",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar!'
    }).then((result) => {
        if (result.value) {
            fetch('backend/servicios/saldarServicio.php?idServicio='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    Swal.fire(
                        'Listo!',
                        'Actulizaste el estado del servício.',
                        'success'
                    )
                    getServicios();
                }
            })
        }
    })
}

let formAgregarServicio = document.getElementById('formAgregarServicio');
formAgregarServicio.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formAgregarServicio);
    fetch('backend/servicios/agregarServicio.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            formAgregarServicio.classList.add('d-none');
        }
        console.log(newRes);
    })
})




//fecha
let input = document.getElementById('inputFecha');
let f = new Date();
input.value = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate(); 


function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaServicios = document.getElementById('tablaServicios');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarServicio');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaServicios.classList.add('d-none');
    bannerForm.classList.add('d-none');
}

function ocultarFormularioAgregar() {
    let tablaServicios = document.getElementById('tablaServicios');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarServicio');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaServicios.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getServicios();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}

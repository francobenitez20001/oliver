function getServicios() {
    fetch('backend/servicios/listarServicio.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'No pago') {
                buttons = `<i class="fas fa-donate" style="cursor:pointer;color:green;font-size:20px" id="boton-entregar" onclick="switchForm(${reg.idServicio})"></i>
                <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarServicio(${reg.idServicio})"></i>`;
            }else{
                buttons = `<i class="fas fa-file-alt" id="boton-entregar" style="cursor:pointer;color:yellow;font-size:20px" onclick="verComprobante(${reg.idServicio})"></i>
                <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarServicio(${reg.idServicio})"></i>`;
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

let formCargarComprobante = document.getElementById('formCargarComprobante');
formCargarComprobante.addEventListener('submit',event=>{
    event.preventDefault();
    let alertLoading = document.getElementById('alert-loading');
    let alertLoad = document.getElementById('alert-load');
    let alertError = document.getElementById('alert-error');
    alertLoading.classList.remove('d-none');
    let data = new FormData(formCargarComprobante);
    fetch('backend/servicios/cargarComprobante.php',{
        method:'POST',
        body:data
    })
    .then(res=>res.json())
    .then(response=>{
        if (response.status == 400) {
            alertLoading.classList.add('d-none');
            alertError.innerHTML = response.info;
            alertError.classList.remove('d-none');
            return;
        }
        fetch('backend/servicios/saldarServicio.php?idServicio='+response.data.idServicio+'&comprobante='+response.data.comprobante)
        .then(res=>res.json())
        .then(response=>{
            if (response.status == 400) {
                alertLoading.classList.add('d-none');
                alertError.innerHTML = response.info;
                alertError.classList.remove('d-none');
                return;
            }
            alertLoad.innerHTML = response.info;
            alertLoading.classList.add('d-none');
            alertLoad.classList.remove('d-none');
            getServicios();
            setTimeout(() => {
                switchForm();
                return;
            }, 1000);
        })
    })
})


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
        if (newRes.status==200) {
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
    habilitarFormComprobante();
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaServicios = document.getElementById('tablaServicios');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarServicio');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaServicios.classList.add('d-none');
    bannerForm.classList.add('d-none');
    estado = document.getElementById('estado');
    console.log(estado.value);
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

function switchForm(id=null){
    document.getElementById('idServicio').value = id;
    let cargarComponente = document.getElementById('cargarComprobante');
    cargarComponente.classList.toggle('d-none');
    cargarComponente.classList.toggle('swal2-container');
    cargarComponente.classList.toggle('swal2-center');
    cargarComponente.classList.toggle('swal2-fade');
    cargarComponente.classList.toggle('swal2-shown');
}

function habilitarFormComprobante()
{
    comprobante = document.getElementById('comprobante');
    estado = document.getElementById('estado');
    if (estado.value == 'No pago') {
        comprobante.classList.add('d-none');   
    }else{
        comprobante.classList.remove('d-none');
    }
}

function verComprobante(idServicio) {
    window.location.assign('verComprobante.php?recurso=servicio&idServicio='+idServicio);
}
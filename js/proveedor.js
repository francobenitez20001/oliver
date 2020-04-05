function getProveedores() {
    fetch('backend/proveedores/listarProveedor.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        let email;
        let telefono;
        newRes.forEach(reg => {
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
                <td> <button class="btn btn-outline-info mr-2" id="boton-entregar" onclick="verProveedorPorId(${reg.idProveedor})">Modificar</button>
                <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarProveedor(${reg.idProveedor})">Eliminar</button>
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}
getProveedores();



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
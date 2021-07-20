let alert = document.getElementById('alerta');
let bodyTable = document.getElementById('bodyTable');
let tablaUsuario = document.getElementById('tablaUsuarios');
let formulario = document.getElementById('form');
let formModificar = document.getElementById('formModificar');
let templatee = '';

window.onload = ()=>{
    alert.classList.add('d-none');
    getUsuarios();
}

function getUsuarios() {
    fetch('backend/usuario/listarUsuario.php?tipo=todos').then(res=>res.json()).then(response=>{
        // console.log(response);
        superUser = '';
        response.forEach(user => {
            (user.superUser == 1)?superUser='Administrador':superUser='Normal';
            templatee += `
                <tr>
                    <th scope="row">${user.usuario}</th>
                    <td>${user.nombre}</td>
                    <td>${superUser}</td>
                    <td>
                        <a onclick="mostrarModificarUsuario(${user.idUsuario})"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                        <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarUsuario(${user.idUsuario})"></i>
                    </td>
                </tr>
            `;
        });
        bodyTable.innerHTML = templatee;
        templatee = '';
    })
}

async function getUsuario(id) {
    const req = await fetch(`backend/usuario/verUsuarioPorId.php?idUsuario=${id}`);
    if(req.status !== 200){
        return modalError(req.statusText);
    }
    const data = await req.json();
    return data;
}

async function getLocales() {
    let url = `backend/locales/get.php`;
    const req = await fetch(url);
    if(req.status !== 200){
        return modalError(req.statusText);
    }
    const {data} = await req.json();
    return data;
}

function validarForm() {
    let dom = {
        nombre:document.getElementById('nombre').value,
        usuario:document.getElementById('usuario').value,
        pw:document.getElementById('pw').value,
        superUser:document.getElementById('superUser').value
    };
    if(dom.nombre == '' || dom.usuario == '' || dom.pw == '' || dom.superUser == ''){
        return false;
    }
    //console.log(dom);
    return true;
}


function agregarUsuario(event) {
    event.preventDefault();
    if(!validarForm()){
        alert.classList.remove('d-none');
        alert.classList.add('alert-danger');
        alert.innerHTML = 'Completa todos los campos';
        return false;
    };
    let data = new FormData(document.getElementById('form'));
    fetch('backend/usuario/agregarUsuario.php',{
        method:'POST',
        body:data
    }).then(res=>res.json()).then(response=>{
        alert.classList.remove('d-none');
        if(response.status == 200){
            alert.classList.add('alert-success');
        }else{
            alert.classList.add('alert-danger');
        }
        alert.innerHTML = response.info;
    })
}


async function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    formulario.classList.toggle('d-none');
    tablaUsuario.classList.toggle('d-none');
    //getUsuarios();
    let locales = await getLocales();
    let html = "";
    locales.forEach(local => {
        html += `<option value="${local.idLocal}">${local.nombre}</option>`
    });
    return document.getElementById('idLocal').innerHTML = html;
}

function ocultarFormModificar(){
    formModificar.classList.toggle('d-none');
    tablaUsuario.classList.toggle('d-none');
    getUsuarios();
}

async function mostrarModificarUsuario(id) {
    formModificar.classList.toggle('d-none');
    tablaUsuario.classList.toggle('d-none');
    let locales = await getLocales();
    let usuario = await getUsuario(id);
    const user = usuario[0];
    let superUserTemplate = `
        <option value="${user.superUser}">Normal</option>
        <option value="1">Administrador</option>
    `;
    let localesTemplate = `<option value="${user.idLocal}">${user.local}</option>`;
    let template = '';

    if (user.superUser == 1) {
        superUserTemplate = `
            <option value="${user.superUser}">Administrador</option>
            <option value="0">Normal</option>
        `;
    }

    locales.forEach(local => {
        localesTemplate += (local.idLocal == user.idLocal) ? '' : `<option value="${local.idLocal}">${local.nombre}</option>`;
    });

    template += `
        <div class="col-12">
            <button type="button" class="btn btn-outline-warning mb-1" onclick="ocultarFormModificar()">Volver al listado</button>
            <div class="alert text-center" id="alerta-update"></div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4 px-2 my-3">
                <input type="text" class="form-control" id="nombre" required name="nombre" value="${user.nombre}">
            </div>
            <div class="col-12 col-md-4 px-2 my-3 input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Local</div>
                </div>
                <select name="idLocal" id="idLocal" required class="form-control" id="" ${user.superUser=="1" ? 'disabled="true' : ''}>
                    ${localesTemplate}
                </select>
            </div>
            <div class="col-12 col-md-4 px-2 my-3 input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">Tipo de usuario</div>
                </div>
                <select name="superUser" id="superUser" required class="form-control" id="" onchange="handleChangeTipoUsuario(event)">
                    ${superUserTemplate}
                </select>
            </div>
            <div class="col 12 text-center">
                <input type="hidden" name="idUsuario" value="${user.idUsuario}">
                <input type="submit" class="btn btn-outline-success btn-block" value="Modificar">
            </div>
        </div>
    `;

    return formModificar.innerHTML = template;

}

function eliminarUsuario(id) {
    modalDelete('¿Desea eliminar el usuario?')
    .then((result) => {
        if (result.value) {
            fetch('backend/usuario/eliminarUsuario.php?idUsuario='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getUsuarios();
                }
                // console.log(newRes);
            })
        }
    })
}

function modificarUsuario(event) {
    let data = new FormData(document.getElementById('formModificar'));
    event.preventDefault();
    fetch('backend/usuario/modificarUsuario.php',{
        method:'POST',
        body: data
    })
    .then(response=>response.json())
    .then(newRes=>{
        if(newRes.status == 200){
            document.getElementById('alerta-update').classList.add('alert-success');
            document.getElementById('alerta-update').innerHTML = newRes.info;
        }
    })
}

function handleChangeTipoUsuario(e) {
    let selectsLocal = [...document.querySelectorAll('#idLocal')];
    selectsLocal.forEach(select=>{
        if(e.target.value == "1"){
            select.removeAttribute('required');
            select.setAttribute('disabled','true');
            return;
        }
        select.setAttribute('required','');
        select.removeAttribute('disabled');
    })
}
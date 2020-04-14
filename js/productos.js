
// ESTAS DOS VARIABLES SON PARA EL BETWEEN EN SQL
let inicioDeRangoDeRegistros = 0;
let finDeRangoDeRegistros = 100;

//ESTA CONSTANTE ES EL VALOR DE CUANTOS REGISTROS VOY MOSTRAR CADA VEZ QUE CLICKEO EL BOTON 'VER MAS'
const LIMITE_REGISTROS_A_MOSTRAR = 100;

//ESTA FUNCION TRAE LOS REGISTROS ENTRE UN RANGO DETERMINADO. ESE RANGO SON LOS PARAMETROS QUE PIDE
function getProductos(inicio,fin) {
    fetch('backend/producto/listarProducto.php?inicio='+inicio+'&fin='+fin)
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        newRes.forEach(reg => {
            if(reg.stock <=0){
                template += `
                <tr>
                    <td scope="row">${reg.producto}</td>
                    <td>${reg.marcaNombre}</td>
                    <td>${reg.stock}</td>
                    <td>${reg.precioPublico}</td>
                    <td>${reg.precioUnidad}</td>
                    <td>${reg.precioKilo}</td>
                    <td>
                        <a href="formModificarProducto.php?idProducto=${reg.idProducto}"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                        <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarProducto(${reg.idProducto})"></i>
                    </td>
                </tr>
                `;
            }else{
                template += `
                <tr>
                    <td scope="row">${reg.producto}</td>
                    <td>${reg.marcaNombre}</td>
                    <td>${reg.stock}</td>
                    <td>${reg.precioPublico}</td>
                    <td>${reg.precioUnidad}</td>
                    <td>${reg.precioKilo}</td>
                    <td>
                        <a href="formModificarProducto.php?idProducto=${reg.idProducto}"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                        <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarProducto(${reg.idProducto})"></i>
                        <a href="formVenderProducto.php?idProducto=${reg.idProducto}" class=""id="boton-modificar"><i class="fas fa-shopping-cart" style="cursor:pointer;color:green;font-size:20px"></i></a>
                    </td>
                </tr>
                `;
            }
        });
        bodyTable.innerHTML = template;

        //RESETEO VARIABLES PARA QUE LA PROXIMA VEZ QUE CLICKEO EN 'VER MÁS' ME MUESTRE OTROS 100 REGISTROS POSTERIORES
        inicioDeRangoDeRegistros = fin+1;
        finDeRangoDeRegistros = inicioDeRangoDeRegistros + LIMITE_REGISTROS_A_MOSTRAR;
    })
}


getProductos(inicioDeRangoDeRegistros,finDeRangoDeRegistros);

//FUNCIONALIDAD DE BOTON 'VER MÁS'
let botonVerMas = document.getElementById('botonVerMas');
botonVerMas.addEventListener("click",()=>{
    getProductos(inicioDeRangoDeRegistros,finDeRangoDeRegistros);
});



let formSearch = document.getElementById('form-search');
formSearch.addEventListener('submit',event=>{
    event.preventDefault();
    let data  = new FormData(formSearch);
    fetch('backend/producto/buscarProducto.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        console.log(newRes);
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        newRes.forEach(reg => {
            if(reg.stock <=0){//no hay stock para vender, entonces deshabilito el boton de vender
                template += `
                <tr>
                    <td scope="row">${reg.producto}</td>
                    <td>${reg.marcaNombre}</td>
                    <td>${reg.stock}</td>
                    <td>${reg.precioPublico}</td>
                    <td>${reg.precioUnidad}</td>
                    <td>${reg.precioKilo}</td>
                    <td>
                        <a href="formModificarProducto.php?idProducto=${reg.idProducto}" class="btn btn-outline-warning" id="boton-modificar">Modificar</a>
                        <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarProducto(${reg.idProducto})">Eliminar</button>
                        <a href="formVenderProducto.php?idProducto=${reg.idProducto}" class="btn btn-outline-dark disabled" tabindex="-1" aria-disabled="true" id="boton-modificar">Vender</a>
                    </td>
                </tr>
                `;
            }else{
                template += `
                <tr>
                    <th scope="row">${reg.idProducto}</th>
                    <td>${reg.producto}</td>
                    <td>${reg.marcaNombre}</td>
                    <td>${reg.stock}</td>
                    <td>${reg.precioPublico}</td>
                    <td>${reg.precioUnidad}</td>
                    <td>${reg.precioKilo}</td>
                    <td>
                        <a href="formModificarProducto.php?idProducto=${reg.idProducto}" class="btn btn-outline-warning" id="boton-modificar">Modificar</a>
                        <button class="btn btn-outline-danger" id="boton-eliminar" onclick="eliminarProducto(${reg.idProducto})">Eliminar</button>
                        <a href="formVenderProducto.php?idProducto=${reg.idProducto}" class="btn btn-outline-success" id="boton-modificar">Vender</a>
                    </td>
                </tr>
                `;
            }
        });
        bodyTable.innerHTML = template;
    })
})


//######################## AGREGAR PRODUCTO  ######################## 

function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarProducto');
    let bannerForm = document.getElementById('banner-form');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaProductos.classList.add('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    getMarcas();
    getCategorias();
}

function getCategorias() {
    let selectCategoria = document.getElementById('idCategoria');
    let template = '';
    fetch('backend/categoria/listarCategoria.php')
    .then(res=>res.json())
    .then(newRes=>{
        newRes.forEach(reg => {
            template += `
            <option value="${reg.idCategoria}">${reg.categoriaNombre}</option>
            `;
        });
        selectCategoria.innerHTML = template;
        // console.log(newRes);
    })
}

function getMarcas() {
    let selectMarca = document.getElementById('idMarca');
    let template = '';
    fetch('backend/marca/listarMarca.php')
    .then(res=>res.json())
    .then(newRes=>{
        newRes.forEach(reg => {
            template += `
            <option value="${reg.idMarca}">${reg.marcaNombre}</option>
            `;
        });
        selectMarca.innerHTML = template;
        // console.log(newRes);
    })
}


let form = document.getElementById('formAgregarProducto');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/producto/agregarProducto.php',{
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
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarProducto');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaProductos.classList.remove('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}


//######################## ELIMINAR PRODUCTO  ######################## 


function eliminarProducto(id) {
    Swal({
        title: '¿Desea eliminar el Producto?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/producto/eliminarProducto.php?idProducto='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getProductos(0,100);
                }
                // console.log(newRes);
            })
        }
    })
}

//######################## AGREGAR MARCA  ######################## 

function mostrarFormularioAgregarMarca() {
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-marca');
    let formulario = document.getElementById('formAgregarMarca');
    let bannerForm = document.getElementById('banner-form');
    let menuSecunadario = document.getElementById('menu-secundary');
    let botonMenu = document.getElementById('icon-menu-li');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaProductos.classList.add('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    menuSecunadario.classList.toggle('d-none');
    botonMenu.classList.add('d-none');
}

function ocultarFormularioAgregarMarca() {
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-marca');
    let formulario = document.getElementById('formAgregarMarca');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    let botonMenu = document.getElementById('icon-menu-li');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otra marca, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaProductos.classList.remove('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    botonMenu.classList.remove('d-none');
    getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}


let formAgregarMarca = document.getElementById('formAgregarMarca');
formAgregarMarca.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formAgregarMarca);
    fetch('backend/marca/agregarMarca.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            formAgregarMarca.classList.add('d-none');
        }
        console.log(newRes);
    })
})


//######################## AGREGAR CATEGORIA  ######################## 

function mostrarFormularioAgregarCategoria() {
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-categoria');
    let formulario = document.getElementById('formAgregarCategoria');
    let bannerForm = document.getElementById('banner-form');
    let menuSecunadario = document.getElementById('menu-secundary');
    let botonMenu = document.getElementById('icon-menu-li');
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaProductos.classList.add('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    menuSecunadario.classList.toggle('d-none');
    botonMenu.classList.add('d-none');
}

function ocultarFormularioAgregarCategoria() {
    let tablaProductos = document.getElementById('tablaProductos');
    let divFormulario = document.getElementById('form-agregar-categoria');
    let formulario = document.getElementById('formAgregarCategoria');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    let botonMenu = document.getElementById('icon-menu-li');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaProductos.classList.remove('d-none');
    botonVerMas.classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    botonMenu.classList.remove('d-none');
    getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}

let formAgregarCategoria = document.getElementById('formAgregarCategoria');
formAgregarCategoria.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formAgregarCategoria);
    fetch('backend/categoria/agregarCategoria.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            formAgregarCategoria.classList.add('d-none');
        }
        console.log(newRes);
    })
})


let listadoProducto = [];
let inicio = 0;
let fin = 20;

let criterioBusqueda = 'producto';
let filtrados = [];

window.onload = ()=>{
    document.getElementById('slider').classList.remove('d-none');
    getProductos();
}

function getAll() {
    fetch('backend/producto/listarProducto.php')
    .then(res=>res.json())
    .then(newRes=>{
        for (let index = 0; index < newRes.length; index++) {
            listadoProducto[index] = newRes[index];
        }
        return listadoProducto;
    })
}

//ESTA FUNCION TRAE LOS REGISTROS ENTRE UN RANGO DETERMINADO. ESE RANGO SON LOS PARAMETROS QUE PIDE
function getProductos() {
    fetch(`backend/producto/listarProducto.php?desde=${inicio}&hasta=${fin}`)
    .then(res=>res.json())
    .then(newRes=>{
        render(newRes);
        getAll();
    })
}

function submitSearch(event) {
    event.preventDefault();
    if(document.getElementsByName('productoSearch')[0].value.length>0){
        console.log(1);
        return;
    }
    render(listadoProducto);
}

function changeCriterioBusqueda(event){
    if (event.target.value === 'producto') {
        criterioBusqueda = 'producto';
    }else{
        criterioBusqueda = 'codigo';
    }
}


function buscar(event) {
    if(filtrados.length>0){
        filtrados = [];
        console.log('eliminado');
        console.log(filtrados);
    }
    let input = document.getElementsByName('productoSearch')[0];
    if(input.value.length==0){
        render(listadoProducto);
        return;
    }
    if(criterioBusqueda === 'producto'){
        filtrados = listadoProducto.filter(newArray => newArray.producto.toLowerCase().includes(input.value.toLowerCase()));
    }else{
        for (let index = 0; index < listadoProducto.length; index++) {
            if(listadoProducto[index].codigo_producto && listadoProducto[index].codigo_producto == input.value){
                filtrados.push(listadoProducto[index]);
            }
        }
    }
    console.log(filtrados);
    render(filtrados);
    return;
}

function render(data,search=false,loadMore=false) {
    let bodyTable = document.getElementById('bodyTable');
    let template = '';
    if(loadMore){
        template = bodyTable.innerHTML;
    }
    permiso = checkUserSession();
    data.forEach(reg => {
        if(reg.stock <=0){
            template += `
                <tr>
                    <td scope="row">${reg.producto}</td>
                    <td>
                        <input type="number" onkeyup="setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" onchange="setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" id="descuento" style="width:45px"/>
                    </td>
                    <td class="userPrivate">$${reg.precio_costo}</td>
                    <td class="userPrivate">${reg.porcentaje_ganancia}%</td>
                    <td>${reg.stock}</td>
                    <td class="bg-important" id="precioPublico_${reg.idProducto}">${reg.precioPublico}</td>
                    <td id="precioUnidad_${reg.idProducto}">${reg.precioUnidad}</td>
                    <td class="bg-important-yellow" id="precioKilo_${reg.idProducto}">${reg.precioKilo}</td>
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
                        <td>
                        <input type="number" onkeyup="setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" onchange="setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" id="descuento" style="width:45px"/>
                        </td>
                        <td class="userPrivate">$${reg.precio_costo}</td>
                        <td class="userPrivate">${reg.porcentaje_ganancia}%</td>
                        <td>${reg.stock}</td>
                        <td class="bg-important" id="precioPublico_${reg.idProducto}">${reg.precioPublico}</td>
                        <td id="precioUnidad_${reg.idProducto}">${reg.precioUnidad}</td>
                        <td class="bg-important-yellow" id="precioKilo_${reg.idProducto}">${reg.precioKilo}</td>
                        <td>
                            <a href="formModificarProducto.php?idProducto=${reg.idProducto}"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                            <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarProducto(${reg.idProducto})"></i>
                            <a href="formVenderProducto.php?idProducto=${reg.idProducto}" class=""id="boton-modificar"><i class="fas fa-shopping-cart" style="cursor:pointer;color:green;font-size:20px"></i></a>
                        </td>
                    </tr>
                `;
            }
        });
        if(!search){
            document.getElementById('slider').classList.add('d-none');
        };
        bodyTable.innerHTML = template;
        if (!permiso) {
            elementos = document.getElementsByClassName('userPrivate');
            for (let index = 0; index < elementos.length; index++) {
                elementos[index].classList.add('d-none');
            };
        }
}

function loadMore() {
    inicio = inicio + 50;
    fin = fin + 50;
    let arrayLimit = [];
    for (let index = inicio; index <= fin; index++) {
        arrayLimit[index] = listadoProducto[index];
    }
    // console.log(arrayLimit);
    // console.log(inicio,fin);
    render(arrayLimit,false,true);
}


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
    document.getElementById('btnverMas').classList.toggle('d-none');
    bannerForm.classList.toggle('d-none');
    getMarcas();
    getCategorias();
    getProveedores();
    document.getElementById('ventaKiloSelect').value = 'no';
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

function getProveedores() {
    let select = document.getElementById('idProveedor');
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

function handleChangeVentaKilo(event){
    if (event.target.value === 'si') {
        document.getElementsByClassName('input-disable')[0].removeAttribute('disabled');
        document.getElementsByClassName('input-disable')[1].removeAttribute('disabled');
    }else{
        document.getElementsByClassName('input-disable')[0].setAttribute('disabled','true');
        document.getElementsByClassName('input-disable')[1].setAttribute('disabled','true');
    }
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
        try {
            if (newRes) {
                alert = document.getElementById('alert-success');
                alert.classList.remove('d-none');
                form.classList.add('d-none');
            }else{
                modalError('Algo no esta bien');
            }    
        } catch (error) {
            modalError('Ya existe un producto con el codigo ingresado'); 
        }
    })
})

iconMenu.addEventListener('click',()=>{
    menuSecundario.classList.remove('d-none');
})
btnOcultarMenu.addEventListener('click',()=>{
    menuSecundario.classList.toggle('d-none');
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
    document.getElementById('btnverMas').classList.toggle('d-none');
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
    document.getElementById('btnverMas').classList.toggle('d-none');
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
    document.getElementById('btnverMas').classList.toggle('d-none');
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
    document.getElementById('btnverMas').classList.toggle('d-none');
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
    document.getElementById('btnverMas').classList.toggle('d-none');
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

function checkUserSession() {
    if (userSession == 1) {
        return true;
    }
}

function setDescuento(event,publico,unitario,kilo,idProducto) {
    let precioPublico = document.getElementById(`precioPublico_${idProducto}`),
        precioUnidad = document.getElementById(`precioUnidad_${idProducto}`),
        precioKilo = document.getElementById(`precioKilo_${idProducto}`),
        descuento = parseInt(event.target.value);
    if(event.target.value == 0 || event.target.value == ''){
        precioPublico.innerHTML = publico;
        precioUnidad.innerHTML = unitario;
        precioKilo.innerHTML = kilo;
        return;    
    };
    precioPublico.innerHTML = parseInt(precioPublico.textContent) - parseInt(precioPublico.textContent)*descuento/100;
    precioUnidad.innerHTML = parseInt(precioUnidad.textContent) - parseInt(precioUnidad.textContent)*descuento/100;
    precioKilo.innerHTML = parseInt(precioKilo.textContent) - parseInt(precioKilo.textContent)*descuento/100;
}
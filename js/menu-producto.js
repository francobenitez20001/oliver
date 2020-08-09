  //######################## AGREGAR MARCA  ######################## 
  window.mostrarFormularioAgregarMarca = function mostrarFormularioAgregarMarca(){
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

window.ocultarFormularioAgregarMarca = function ocultarFormularioAgregarMarca() {
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
    producto.getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
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

window.mostrarFormularioAgregarCategoria = function mostrarFormularioAgregarCategoria() {
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

window.ocultarFormularioAgregarCategoria = function ocultarFormularioAgregarCategoria() {
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
    producto.getProductos();//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
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
});
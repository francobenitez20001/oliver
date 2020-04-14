let divNombre = document.getElementById('nombreCliente');
let divTotal = document.getElementById('total');
let inputTotal = document.getElementById('inputTotal');
let tipoDeVenta = document.getElementsByName('tipoDeVenta');
let selectDescuento = document.getElementById('div-descuento');

let infoStock = document.getElementById('infoStock');
let cantidad_dos = document.getElementById('cantidad');
let cantidadSuelto = document.getElementById('cantidadSuelto');

window.onload = ()=>{
    infoStock.innerHTML = 'Te quedan ' + stockParcial + ' unidades en stock';
    cantidad_dos.setAttribute('required','');
}

let formVentaProducto = document.getElementById('formVentaProducto');
formVentaProducto.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formVentaProducto);
    fetch('backend/ventas/agregarVenta.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            Swal.fire({
                title: newRes.info,
                text: '¿Desea agregar los datos del envio de esta venta?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Agregar'
            }).then((result) => {
                if (result.value) {
                  let divVenta = document.getElementById('form-modificar-div');
                  let divEnvio = document.getElementById('form-agregar-div');
                  let idVenta = document.getElementById('idVenta');
                  idVenta.setAttribute('value',newRes.idVenta);
                  divVenta.classList.add('d-none');
                  divEnvio.classList.remove('d-none');
                }else{
                    window.location.assign('adminVentas.html');
                    return;
                }
            })
        }
        console.log(newRes);
    })
})

let formEnvio = document.getElementById('formAgregarEnvio');
formEnvio.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formEnvio);
    fetch('backend/envios/agregarEnvio.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        let divVenta = document.getElementById('form-modificar-div');
        let divEnvio = document.getElementById('form-agregar-div');
        // let idVenta = document.getElementById('idVenta');
        divVenta.classList.remove('d-none');
        divEnvio.classList.add('d-none');
        if (newRes.status == 200) {   
            Swal.fire({
                icon: 'success',
                title: 'Agregado',
                text: newRes.info
            }).then(()=>{
                window.location.assign('adminVentas.html');
            })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ups..',
                text: newRes.info
            }).then(()=>{
                window.location.assign('adminVentas.html');
            })
        }
    })
})
              
//fecha
let input = document.getElementById('inputFecha');
let f = new Date();
input.value = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate(); 
let inputDia = document.getElementById('inputDia');
let semana = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
let diaSemana = f.getDay();
// console.log(semana[diaSemana-1]);
inputDia.value = semana[diaSemana-1];

//stocks
let stockParcial = parseInt(document.getElementById('stockParcial').value);
let stockSuelto = parseInt(document.getElementById('stockSuelto').value);
let cantidad = parseInt(document.getElementById('cantidad').value);

// select para elegir cantidad menor o igual al stock disponible
let selectCantidad = document.getElementById('cantidad');
let template = '';
for (let index = 1; index <= stockParcial; index++) {
    template += `<option value="${index}">${index}</option>`;
}
selectCantidad.innerHTML = template;



function habilitarInput(data){
    console.log(data.target.value);
    if (data.target.value == 'Tarjeta') {
        divNombre.classList.remove('d-none');
    }else{
        divNombre.classList.add('d-none');
    }
}

function habilitarTotal(data) {
    if (data.target.value == 'si') {
        inputTotal.setAttribute('required','');
        divTotal.classList.remove('d-none');
    }else{
        inputTotal.removeAttribute('required');
        divTotal.classList.add('d-none');
    }
}

function cambiarTipoDeCompra(event) {
    let tipoDeCompra = event.target.value;
    colSeparador = document.getElementById('col-separador');
    if (tipoDeCompra == 'normal') {
        selectDescuento.classList.remove('d-none');
        divTotal.classList.add('d-none');
        divTotal.removeAttribute('required');
        cantidad_dos.classList.toggle('d-none');
        cantidadSuelto.classList.toggle('d-none');
        cantidad_dos.setAttribute('required','');
        cantidadSuelto.removeAttribute('required');
        infoStock.innerHTML = 'Te quedan ' + stockParcial + ' unidades en stock';
        colSeparador.classList.remove('d-none');
    }else if(tipoDeCompra == 'suelto'){
        selectDescuento.classList.add('d-none');
        cantidad_dos.classList.add('d-none');
        cantidadSuelto.classList.remove('d-none');
        divTotal.classList.remove('d-none');
        cantidad_dos.removeAttribute('required');
        cantidadSuelto.setAttribute('required','');
        divTotal.setAttribute('required','');
        infoStock.innerHTML = 'Te quedan ' + stockSuelto + ' Kg en stock';
        colSeparador.classList.add('d-none');
    }
}
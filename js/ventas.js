let datosVenta = {}; //objeto donde guardo el precio,cantidad y descuento en vivo, porque puede cambiar si vendo por unidad o por kilo, etc. 

let divNombre = document.getElementById('nombreCliente');
let divTotal = document.getElementById('total');//input no visible que lo capturo para setearle el valor cuando el usuario cambia la cantidad de productos a vender.

let divAlertTotal = document.getElementById('alert-total'); //alerta para mostrar el total en vivo
let precio = parseInt(document.getElementById('precio').value); //precio por unidad
let precioKilo = parseInt(document.getElementById('precioKilo').value); //precio por kilo

let selectDescuento = document.getElementById('div-descuento'); //select de decuento (si-no)
let inputDescuento = document.getElementById('inputDescuento'); //valor del descuento

let tipoDeVenta = document.getElementsByName('tipoDeVenta');//select para vender por unidad o suelto

let infoStock = document.getElementById('infoStock');
let cantidad_dos = document.getElementById('cantidad');
let cantidadSuelto = document.getElementById('cantidadSuelto');


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
let stockSuelto = parseFloat(document.getElementById('stockSuelto').value);
let cantidad = parseFloat(document.getElementById('cantidad').value);

// select para elegir cantidad menor o igual al stock disponible
let selectCantidad = document.getElementById('cantidad');
let template = '';
for (let index = 1; index <= stockParcial; index++) {
    template += `<option value="${index}">${index}</option>`;
}
selectCantidad.innerHTML = template;


// funciones

window.onload = ()=>{
    infoStock.innerHTML = 'Te quedan ' + stockParcial + ' unidades en stock';
    cantidad_dos.setAttribute('required','');
    datosVenta.precio = precio;
    datosVenta.cantidad = parseFloat(selectCantidad.value);
    datosVenta.descuento = 0;
    calcularTotal(datosVenta.precio,datosVenta.cantidad);
}

//funcion para actualizar el total cada vez que el usuario tipea un valor en el input descuento
inputDescuento.addEventListener('keyup',event=>{
    if (inputDescuento.value == '') {
        return;
    }
    datosVenta.descuento = parseInt(inputDescuento.value);
    calcularTotal(datosVenta.precio,datosVenta.cantidad,datosVenta.descuento);
})

//funcion para actualizar el total cada vez que el usuario tipea un valor en la cantidad SUELTO
cantidadSuelto.addEventListener('keyup',event=>{
    datosVenta.precio = precioKilo;
    datosVenta.cantidad = parseFloat(cantidadSuelto.value);
    calcularTotal(datosVenta.precio,datosVenta.cantidad);
})


//registrar la venta
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
        console.log(newRes)
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


//registrar el envio de la venta
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

//habilita el input de nombre del cliente para registrarlo en el caso de que se pague con tarjeta
function habilitarInput(data){
    console.log(data.target.value);
    if (data.target.value == 'Tarjeta') {
        divNombre.classList.remove('d-none');
    }else{
        divNombre.classList.add('d-none');
    }
}

//en el caso de que se seleccione descuento 'si', se habilita el input para que ingrese el valor del descuento
function habilitarDescuento(data) {
    if (data.target.value == 'si') {
        document.getElementById('selectDescuento').classList.toggle('d-none');
        selectDescuento.setAttribute('required','');
    }else{
        document.getElementById('selectDescuento').classList.toggle('d-none');
        selectDescuento.removeAttribute('required');
    }
}

//funcion para adecuar algunos inputs de acuerdo al tipo de compra suelto o por unidad(normal)
function cambiarTipoDeCompra(event) {
    let tipoDeCompra = event.target.value;
    colSeparador = document.getElementById('col-separador');
    if (tipoDeCompra == 'normal') {
        selectDescuento.classList.remove('d-none');
        cantidad_dos.classList.toggle('d-none');
        cantidadSuelto.classList.toggle('d-none');
        cantidad_dos.setAttribute('required','');
        cantidadSuelto.removeAttribute('required');
        infoStock.innerHTML = 'Te quedan ' + stockParcial + ' unidades en stock';
        colSeparador.classList.remove('d-none');
    }else if(tipoDeCompra == 'suelto'){
        cantidad_dos.classList.add('d-none');
        cantidadSuelto.classList.remove('d-none');
        cantidad_dos.removeAttribute('required');
        cantidadSuelto.setAttribute('required','');
        infoStock.innerHTML = 'Te quedan ' + stockSuelto + ' Kg en stock';
    }
}

//funcion que me permite hacer el calculo de cuanto es la venta y lo muestra en la alerta inferior que esta arriba del boton de agregar
function calcularTotal(precio,cantidad,descuento=null) {
    if (precio!=null && cantidad!=null) {
        totalActual = precio * cantidad;
        if (descuento!=null) {
            totalActual = totalActual - (totalActual*descuento/100);
        }
        divAlertTotal.innerHTML = 'El total de la venta es de $'+totalActual;
        divTotal.value = totalActual;   
    }
}

//funcion que se ejecuta cuando cambia el valor en el select de cantidad cuando se vende por unidad.
function actualizarTotal(event,unitario){
    datosVenta.precio = precio;
    datosVenta.cantidad = parseInt(event.target.value); 
    if (unitario) {
        
    }
    calcularTotal(datosVenta.precio,datosVenta.cantidad);  
}

class Carrito{
    constructor(){
        this.carrito = [];
        this.dom = {
            form:document.getElementById('formVentaProducto')
        }
    }

    agregarProducto(){
        let producto = new FormData(this.dom.form);
        this.carrito.push(producto);
        console.log(this.carrito);
        
        this.dom.form.reset();
    }
}

let carrito = new Carrito();
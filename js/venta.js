
import Carrito from './utils/Carrito.js?v=1.0.3';
window.dom = {
    formVenta:document.getElementById('formVentaProducto')
};

window.carrito = new Carrito();
window.onload = ()=>{
    carrito.productosSeleccionados = JSON.parse(localStorage.getItem('productos'));
    carrito.renderFormVenta();
    window.renderSelectLocales();
}

//habilita el input de nombre del cliente para registrarlo en el caso de que se pague con tarjeta
window.handleChangeMedioPago = function habilitarInput(data){
    let inputCliente = document.getElementById('cliente');
    if(inputCliente.hasAttribute('disabled')){
        carrito.carrito.tipo_pago = 'Tarjeta';
        inputCliente.setAttribute('required','');
        inputCliente.removeAttribute('disabled');
        return;
    }
    carrito.carrito.tipo_pago = 'Efectivo';
    inputCliente.removeAttribute('required');
    inputCliente.value = "";
    return inputCliente.setAttribute('disabled','true');
}

//en el caso de que se seleccione descuento 'si', se habilita el input para que ingrese el valor del descuento
window.habilitarDescuento = function habilitarDescuento(data) {
    let inputDescuento = document.getElementById('inputDescuento');
    if(data.target.value == 'si'){
        inputDescuento.setAttribute('required','');
        return inputDescuento.removeAttribute('disabled');
    }
    carrito.carrito.descuento = 0;
    inputDescuento.removeAttribute('required');
    inputDescuento.value = "";
    return inputDescuento.setAttribute('disabled','true');   
}

window.showModalPago = function showModalPago() {
    document.getElementById('modalPago').classList.add('show');
    document.getElementById('modalPago').style.display = 'block';
    document.getElementById('total-info').innerHTML = `El total es <b>$${carrito.carrito.subtotal.toFixed(2)}<b>`
}

window.setDescuento = function setDescuento(event) {
    let valorConDescuento;
    if(document.getElementById('inputDescuento').value == ''){
        valorConDescuento = carrito.carrito.subtotal;
        return;
    }else{
        carrito.carrito.descuento = parseFloat(document.getElementById('inputDescuento').value);
        valorConDescuento = carrito.carrito.subtotal - (carrito.carrito.subtotal * carrito.carrito.descuento / 100);
    }
    document.getElementById('total-info').innerHTML = `El total es <b>$${valorConDescuento.toFixed(2)}<b>`
}

window.setEstado = function setEstado(event) {
    if (event.target.value == 'Debe') {
        carrito.carrito.estado = 'Debe';
    }else{
        carrito.carrito.estado = 'Pago'
    }
}

window.setCliente = function setCliente(event) {
    if(document.getElementById('cliente').value.length<=2){carrito.carrito.cliente = 'No registrado';return}
    carrito.carrito.cliente = document.getElementById('cliente').value;
}

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
        divVenta.classList.remove('d-none');
        divEnvio.classList.add('d-none');
        if (newRes.status == 200) {   
            Swal.fire({
                icon: 'success',
                title: 'Agregado',
                text: newRes.info
            }).then(()=>{
                window.location.assign('ventas.php');
            })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Ups..',
                text: newRes.info
            }).then(()=>{
                window.location.assign('ventas.php');
            })
        }
    })
});
window.habilitarDescuentoIndividual = index=>{
    document.getElementsByClassName('selectDecuentoIndividual')[index].classList.toggle('d-none');
    document.getElementsByName('totalConDescuento')[index].classList.toggle('d-none');
}

window.setDescuentoIndividual = (index,event)=>{
    //index es el indice del elemento de la lista de los productos seleccionados y event el evento del input
    if(document.getElementsByName('descuentoIndividualValor')[index].value == ''){
        if(document.getElementsByName('tipoDeVenta')[index].value == 'normal') return document.getElementsByName('totalConDescuento')[index].innerHTML = `El total es <b>$${carrito.productosSeleccionados[index].precioUnidad}</b>`;
        document.getElementsByName('totalConDescuento')[index].innerHTML = `El total es <b>$${parseFloat(carrito.productosSeleccionados[index].precioKilo * parseFloat(document.getElementsByName('cantidadSuelto')[index].value)).toFixed(2)}</b>`;
        return;
    }
    let valor = parseInt(event.target.value);
    let cantidad = parseInt(document.getElementsByName('cantidad')[index].value);
    let precio= parseFloat(carrito.productosSeleccionados[index].precioUnidad).toFixed(2);
    if(document.getElementsByName('tipoDeVenta')[index].value == 'suelto'){
        precio = parseFloat(carrito.productosSeleccionados[index].precioKilo).toFixed(2);
        cantidad = parseFloat(document.getElementsByName('cantidadSuelto')[index].value);
    }
    console.log(precio,cantidad,valor);
    let subtotal = precio * cantidad;
    window.totalConDescuento = parseFloat(subtotal - (subtotal * valor / 100)).toFixed(2);
    return document.getElementsByName('totalConDescuento')[index].innerHTML = `El total es <b>$${totalConDescuento}</b>`;
}

window.renderSelectLocales = async () => {
    const req = await fetch('backend/locales/get.php?activo=1');
    if(req.status !== 200){
        return modalError(req.statusText);
    }
    const {data:locales} = await req.json();
    let selectLocales = document.getElementById('idLocal');
    let localDelUsuario = locales.filter(loc=>loc.idLocal == localStorage.getItem('idLocal'));
    let template = `<option value="${localDelUsuario[0].idLocal}">${localDelUsuario[0].nombre}</option>`;
    locales.forEach(local=>{
        if(local.idLocal == localDelUsuario[0].idLocal) return;
        template += `<option value="${local.idLocal}">${local.nombre}</option>`
    });
    selectLocales.innerHTML = template;
}
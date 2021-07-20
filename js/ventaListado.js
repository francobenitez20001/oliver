function getVentas(filtro=null) {
    fetch('backend/ventas/listarVenta.php?tipo-pago='+filtro)
    .then(res=>res.json())
    .then(newRes=>{
        // console.log(newRes)
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'Debe') {
                buttons = `<i class="fas fa-hand-holding-usd" style="cursor:pointer;color:green;font-size:20px" id="boton-eliminar" onclick="SaldarVenta(${reg.idVenta})"></i>
                <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarVenta(${reg.idVenta})"></i>`;
            }else{
                buttons = `<button type="button" onclick="obtenerProductosVenta(${reg.idVenta})" data-toggle="modal" data-target="#exampleModal" class="mx-2 btn btn-success">Detalles</button><i class="fas fa-trash-alt mx-2" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarVenta(${reg.idVenta})"></i>`;
            }
            template += `
            <tr>
                <td>${reg.fecha}</td>
                <td>${reg.tipo_pago}</td>
                <td>${reg.cliente}</td>
                <td>${reg.subtotal}</td>
                <td>${reg.descuento}%</td>
                <td>${reg.total}</td>
                <td>${reg.vendedor}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}
window.onload = ()=>(getVentas());



function eliminarVenta(id) {
    Swal({
        title: '¿Desea eliminar la operacion?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/ventas/eliminarVenta.php?idVenta='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getVentas();
                }
                // console.log(newRes);
            })
        }
    })
}


function saldarVenta(id) {
    Swal.fire({
        title: 'Querés notificar que se saldó la venta?',
        text: "Pasaremos la venta a pagado",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmar!'
    }).then((result) => {
        if (result.value) {
            fetch('backend/ventas/saldarVenta.php?idVenta='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    Swal.fire(
                        'Listo!',
                        'Actulizaste el estado de la venta.',
                        'success'
                    )
                    getVentas();
                }
            })
        }
    })
}


const obtenerProductosVenta = idVenta=>{
    fetch(`backend/productosVenta/listarProductoPorVenta.php?idVenta=${idVenta}`).then(res=>res.json()).then(data=>{
        let tabla = document.getElementById('cuerpo-tablaventas');
        let template = '';
        data.forEach(producto=>{
            template += `
                <tr>
                    <th scope="col">${producto.producto}</th>
                    <th scope="col">${producto.tipoVenta}</th>
                    <th scope="col">${producto.cantidad}</th>
                    <th scope="col">${producto.total}</th>
                </tr>
            `;
        });
        return tabla.innerHTML = template;
    })
}
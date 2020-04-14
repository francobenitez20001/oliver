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
                buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarVenta(${reg.idVenta})"></i>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.producto}</th>
                <td>${reg.cantidad}</td>
                <td>${reg.fecha}</td>
                <td>${reg.total}</td>
                <td>${reg.tipo_pago}</td>
                <td>${reg.cliente}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}
getVentas();



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

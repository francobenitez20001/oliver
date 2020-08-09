function getIdProveedor(idProveedor) {
    idProveedor = idProveedor.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + idProveedor + "=([^&#]*)"),
    results = regex.exec(location.search);
    return parseInt(results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " ")));
}

const getPagos = ()=>{
    const idProveedor = getIdProveedor('idProveedor');
    fetch(`backend/pagoProveedores/listarPagosPorProveedor.php?idProveedor=${idProveedor}`).then(res=>res.json()).then(data=>{
        render(data);
    })
}

const render = data=>{
    let template = '';
    let tableBody = document.getElementById('bodyTable');
    let clase;
    let displayBtn;
    data.forEach(reg => {
        (parseFloat(reg.total)>parseFloat(reg.monto))?clase='bg-yellow':clase='bg-green';
        (parseFloat(reg.total)<=parseFloat(reg.monto))?displayBtn='d-none':displayBtn='d-block';
        template+=`
            <tr class="${clase}">
                <th scope="row">${reg.proveedor}</th>
                <td>${reg.monto}</td>
                <td>${reg.total}</td>
                <td>
                    <i class="fas fa-trash-alt" style="cursor:pointer;color:black;font-size:20px" onClick="eliminarPago(${reg.id})"></i>
                </td>
            </tr>
        `;
    });
    return tableBody.innerHTML = template;
}

const insertarDatosEnForm = (id,total)=>{
    document.getElementsByName('total')[0].value = total;
    document.getElementsByName('id')[0].value = id;
}

const actualizarPago = event=>{
    event.preventDefault();
    let data =  new FormData(document.getElementById('formCargarPago'));
    fetch('backend/pagoProveedores/actualizarPago.php',{
        method:'POST',
        body:data
    }).then(res=>res.json()).then(response=>{
        if(response.status == 200){
            document.getElementById('alert-response').classList.add('alert-info');
        }else{
            document.getElementById('alert-response').classList.add('alert-danger');
        }
        document.getElementById('alert-response').innerHTML = response.info;
        document.getElementById('alert-response').classList.remove('d-none');
        setTimeout(() => {
            getPagos();
        }, 1000);
    })
}

const eliminarPago = id=>{
    Swal({
        title: '¿Desea eliminar el pago?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/pagoProveedores/eliminarPago.php?id='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes.status==200){
                    animationDelete();//funcion de animacion que esta en app.js
                    getPagos();
                }
            })
        }
    })
}

window.onload = ()=>{
    getPagos();
}
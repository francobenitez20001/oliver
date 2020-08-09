function getIdProveedor(idProveedor) {
    idProveedor = idProveedor.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + idProveedor + "=([^&#]*)"),
    results = regex.exec(location.search);
    return parseInt(results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " ")));
}

const getComprobantes = ()=>{
    const idProveedor = getIdProveedor('idProveedor');
    fetch(`backend/comprobantes/listarComprobantePorProveedor.php?idProveedor=${idProveedor}`).then(res=>res.json()).then(data=>{
        render(data);
    })
}

const render = data=>{
    let template = '';
    let tableBody = document.getElementById('bodyTable');
    data.forEach(reg => {
        template+=`
            <tr>
                <th scope="row">${reg.descripcion}</th>
                <td>${reg.proveedor}</td>
                <td><i class="fas fa-file-alt" style="cursor:pointer;color:green;font-size:20px" onClick="verComprobante(${reg.idComprobante})" data-toggle="modal" data-target="#staticBackdrop"></i></td>
            </tr>
        `;
    });
    return tableBody.innerHTML = template;
}

const verComprobante = id=>{
    let modalBody = document.getElementsByClassName('modal-body')[0];
    fetch(`backend/comprobantes/verComprobantePorId.php?idComprobante=${id}`).then(res=>res.json()).then(data=>{
        console.log(data[0].comprobante);
        modalBody.innerHTML = `<img src="comprobantes/${data[0].comprobante}" class="img-fluid" alt="${data[0].comprobante}">`;
    })
}

window.onload=()=>{
    getComprobantes();
}
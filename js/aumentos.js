function getProveedores() {
    fetch('backend/proveedores/listarProveedor.php')
    .then(res=>res.json())
    .then(data=>{
        let template = '';
        let select = document.getElementById('idProveedor');
        data.forEach(proveedor => {
            template += `<option value="${proveedor.idProveedor}">${proveedor.proveedor}</option>`
        });
        select.innerHTML = template;
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

getProveedores();
getMarcas();

let form = document.getElementById('formAumentarPorProveedor');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/producto/aumentarPorProveedor.php',{
        method:'POST',
        body: data
    })
    .then(res=>res.json())
    .then(response=>{
        if (response.status == 200) {
            Swal.fire({
                icon: 'success',
                title: 'Listo!',
                text: response.info
            })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: response.info,
            })
        }
    })
})

function enviar(e,tipo) {
    e.preventDefault();
    let data = new FormData(e.target);
    let url = 'backend/producto/aumentarPorProveedor.php';
    if (tipo == 'marca') {
        url = 'backend/producto/aumentarPorMarca.php';
    };
    fetch(url,{
        method:'POST',
        body:data
    })
    .then(res=>res.json())
    .then(response=>{
        if (response.status == 200) {
            Swal.fire(
                'Listo!',
                response.info,
                'success'
            )
        }else{
            Swal.fire(
                'Error!',
                response.info,
                'error'
            )
        }
    })
}
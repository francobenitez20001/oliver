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

getProveedores();

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
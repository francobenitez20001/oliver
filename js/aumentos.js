let productosArray = [];

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

function getProductos() {
    fetch('backend/producto/listarProducto.php').then(res=>res.json()).then(res=>{
        i = 0;
        res.forEach(producto => {
            productosArray[i]= {
                'producto':producto.producto,
                'idProducto':producto.idProducto
            };
            i++;
        });
    })
}

window.onload = ()=>{
    getProveedores();
    getMarcas();
    getProductos();
}

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
    }else if(tipo == 'producto'){
        url = 'backend/producto/aumentarPorProducto.php';
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

let desplegableProducto = document.getElementById('productosBusqueda');

let inputProducto = document.getElementById('producto');
inputProducto.addEventListener('keyup',event=>{
    console.log(1);
    if (inputProducto.value.length > 2) {
        desplegableProducto.classList.remove('d-none');
        searchProducto(inputProducto.value);   
    }else{
        desplegableProducto.classList.add('d-none');
    }
})

function searchProducto(producto) {
    productoFiltrado = productosArray.filter((prd) =>
        prd.producto.toLowerCase().indexOf(producto.toLowerCase()) > -1
    );
    return renderDesplegableProducto(productoFiltrado);
}

function renderDesplegableProducto(producto) {
    let template = '';
    producto.forEach( prd => {
        template += `
            <option onclick="rellenarInputProducto(event,${prd.idProducto})" value="${prd.producto}">${prd.producto}</option>
        `
    });
    return desplegableProducto.innerHTML = template;
}

function rellenarInputProducto(event,idProducto) {
    producto = event.target.value;
    inputProducto.value = producto;
    document.getElementById('idProducto').value = idProducto;
    desplegableProducto.classList.add('d-none');
}
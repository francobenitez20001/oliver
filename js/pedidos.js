let f = new Date();
let dia = f.getFullYear() + "-0" + (f.getMonth() +1) + "-" + f.getDate();
let mes = f.getFullYear() + "-0" + (f.getMonth() +1);

function getPedidos() {
    fetch('backend/pedidos/listarPedidos.php')
    .then(res=>res.json())
    .then(newRes=>{
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        buttons = '';
        newRes.forEach(reg => {
            if (reg.estado == 'No recibido') {
                buttons = `<i class="fas fa-money-check-alt" style="cursor:pointer;color:green;font-size:20px" id="boton-eliminar" onclick="recibirPedido(${reg.idPedido})"></i>
                <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarPedido(${reg.idPedido})"`;
            }else{
                buttons = `<i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="eliminarPedido(${reg.idPedido})"></i>`;
            }
            template += `
            <tr>
                <th scope="row">${reg.descripcion}</th>
                <td>${reg.cantidad}</td>
                <td>${reg.estado}</td>
                <td>${reg.proveedor}</td>
                <td>
                    ${buttons}
                </td>
            </tr>
            `;
        });
        bodyTable.innerHTML = template;
    })
}

getPedidos();

//######################## ELIMINAR PEDIDO  ######################## 


function eliminarPedido(id) {
    Swal({
        title: '¿Desea eliminar el pedido?',
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    }).then((result) => {
        if (result.value) {
            fetch('backend/pedidos/eliminarPedido.php?idPedido='+id)
            .then(response=>response.json())
            .then(newRes=>{
                if(newRes){
                    animationDelete();//funcion de animacion que esta en app.js
                    getPedidos();
                }
                // console.log(newRes);
            })
        }
    })
}

function recibirPedido(id) {
    Swal.fire({
        title: 'Ingrese el total',
        input: 'text',
        inputAttributes: {
            name:'total'
        },
        showCancelButton: true,
        inputValidator: (value) => {
          fetch('backend/pedidos/recibirPedido.php?total='+value+'&idPedido='+id)
          .then(res => res.json())
          .then(response=>{
            //   console.log(response);
              if (response.status == 200) {
                  getPedidos();
              }
          })
        }
    })
}



function mostrarFormularioAgregar() {
    //ocultar listado de eventos y mostrar el form para agregar
    let tablaPedidos = document.getElementById('tablaPedidos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarPedido');
    let bannerForm = document.getElementById('banner-form');
    let inputfecha = document.getElementById('fecha');
    inputfecha.setAttribute('value',dia);
    formulario.classList.remove('d-none');
    divFormulario.classList.remove('d-none');
    tablaPedidos.classList.add('d-none');
    bannerForm.classList.add('d-none');
    // habilitarTotal();
    getProveedores();
}


let form = document.getElementById('formAgregarPedido');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/pedidos/agregarPedido.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            form.classList.add('d-none');
        }
    })
})

function ocultarFormularioAgregar() {
    let tablaPedidos = document.getElementById('tablaPedidos');
    let divFormulario = document.getElementById('form-agregar-div');
    let formulario = document.getElementById('formAgregarPedido');
    let alert = document.getElementById('alert-success');
    let bannerForm = document.getElementById('banner-form');
    alert.classList.add('d-none');
    formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
    divFormulario.classList.add('d-none');
    tablaPedidos.classList.remove('d-none');
    bannerForm.classList.remove('d-none');
    getPedidos();
    // getProductos(0,100);//llamo a la funcion de getData para obtener la tabla actualizada con lo que agregue
}


function getProveedores() {
    let select = document.getElementById('proveedor');
    fetch('backend/proveedores/listarProveedor.php')
    .then(res=>res.json())
    .then(proveedores=>{
        let template = '';
        proveedores.forEach(proveedor => {
            template += `
                <option value="${proveedor.idProveedor}">${proveedor.proveedor}</option>
            `
        });
        select.innerHTML = template;
    })
}

function habilitarTotal(){
    let estado = document.getElementById('estado').value;
    let total = document.getElementById('input-total');
    if (estado != 'No recibido') {
        total.classList.remove('d-none');
    }else{
        total.classList.add('d-none');
    }
}
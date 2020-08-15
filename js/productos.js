import Carrito from './utils/Carrito.js?v=1.0.2';
import {getMarcas,getCategorias,getProveedores} from './utils/helpers.js';

class Producto{
    constructor(){
        this.listadoProducto = [];
        this.inicio = 0;
        this.fin = 20;
        
        this.criterioBusqueda = 'producto';
        this.filtrados = [];
        this.getProductos();

        this.dom = {};
        this.carrito = new Carrito();
    }

    getProductos() {
        fetch(`backend/producto/listarProducto.php?desde=${this.inicio}&hasta=${this.fin}`)
        .then(res=>res.json())
        .then(newRes=>{
            this.render(newRes);
            this.getAll();
        })
    }
    getAll() {
        fetch('backend/producto/listarProducto.php')
        .then(res=>res.json())
        .then(newRes=>{
            for (let index = 0; index < newRes.length; index++) {
                this.listadoProducto[index] = newRes[index];
            }
            return this.listadoProducto;
        })
    }

    render(data,search=false,loadMore=false) {
        let bodyTable = document.getElementById('bodyTable');
        let template = '';
        if(loadMore){
            template = bodyTable.innerHTML;
        }
        let permiso = this.checkUserSession();
        data.forEach(reg => {
            if(reg.stock <=0 && reg.stock_suelto<=0){
                template += `
                    <tr>
                        <td scope="row">${reg.producto}</td>
                        <td>
                            ${(reg.stock_deposito == 0)?`
                                <button type="button" disabled="true" id="buttonStockDeposito_${reg.idProducto}" onclick="producto.habilitarModificacionStockDeposito(${reg.idProducto})" class="btn btn-outline-info text-center" id="" style="width:35px;"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:15px"></i></button>`:
                                `<button type="button" id="buttonStockDeposito_${reg.idProducto}" onclick="producto.habilitarModificacionStockDeposito(${reg.idProducto})" class="btn btn-outline-info text-center" id="" style="width:35px;"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:15px"></i></button>`
                            }
                            <input type="number" disabled="true" value="${reg.stock_deposito}" id="inputStockDeposito_${reg.idProducto}" style="width:45px"/>
                        <td>
                            <input type="number" onkeyup="producto.setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" onchange="producto.setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" id="descuento" style="width:45px"/>
                        </td>
                        <td class="userPrivate">$${reg.precio_costo}</td>
                        <td class="userPrivate">${reg.porcentaje_ganancia}%</td>
                        <td>${reg.stock}</td>
                        <td class="bg-important" id="precioPublico_${reg.idProducto}">${reg.precioPublico}</td>
                        <td id="precioUnidad_${reg.idProducto}">${reg.precioUnidad}</td>
                        <td class="bg-important-yellow" id="precioKilo_${reg.idProducto}">${reg.precioKilo}</td>
                        <td>
                            <a href="formModificarProducto.php?idProducto=${reg.idProducto}"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                            <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" id="boton-eliminar" onclick="producto.eliminarProducto(${reg.idProducto})"></i>
                            <i class="fas fa-barcode" style="cursor:pointer;color:blue;font-size:20px" id="boton-codigo" onclick="producto.modificarCodigo(${reg.idProducto},'${reg.codigo_producto}')"></i>
                        </td>
                    </tr>
                `;
            }else{
                template += `
                    <tr>
                        <td scope="row">${reg.producto}</td>
                        <td>
                            ${(reg.stock_deposito == 0)?`
                                <button type="button" disabled="true" id="buttonStockDeposito_${reg.idProducto}" onclick="producto.habilitarModificacionStockDeposito(${reg.idProducto})" class="btn btn-outline-info text-center" id="" style="width:35px;"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:15px"></i></button>`:
                                `<button type="button" id="buttonStockDeposito_${reg.idProducto}" onclick="producto.habilitarModificacionStockDeposito(${reg.idProducto})" class="btn btn-outline-info text-center" id="" style="width:35px;"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:15px"></i></button>`
                            }
                            <input type="number" disabled="true" value="${reg.stock_deposito}" id="inputStockDeposito_${reg.idProducto}" style="width:45px"/>
                        </td>
                        <td>
                        <input type="number" onkeyup="producto.setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" onchange="producto.setDescuento(event,${reg.precioPublico},${reg.precioUnidad},${reg.precioKilo},${reg.idProducto})" id="descuento" style="width:45px"/>
                        </td>
                        <td class="userPrivate">$${reg.precio_costo}</td>
                        <td class="userPrivate">${reg.porcentaje_ganancia}%</td>
                        <td>${reg.stock}</td>
                        <td class="bg-important" id="precioPublico_${reg.idProducto}">${reg.precioPublico}</td>
                        <td id="precioUnidad_${reg.idProducto}">${reg.precioUnidad}</td>
                        <td class="bg-important-yellow" id="precioKilo_${reg.idProducto}">${reg.precioKilo}</td>
                        <td>
                            <a href="formModificarProducto.php?idProducto=${reg.idProducto}"><i class="fas fa-edit" style="cursor:pointer;color:yellow;font-size:20px"></i></a>
                            <i class="fas fa-trash-alt" style="cursor:pointer;color:red;font-size:20px" onclick="producto.eliminarProducto(${reg.idProducto})" id="boton-eliminar"></i>
                            <a onclick="producto.agregarCompra(${reg.idProducto})" class=""id="boton-modificar"><i class="fas fa-shopping-cart" style="cursor:pointer;color:green;font-size:20px"></i></a>
                            <i class="fas fa-barcode" style="cursor:pointer;color:blue;font-size:20px" id="boton-codigo" onclick="producto.modificarCodigo(${reg.idProducto},'${reg.codigo_producto}')"></i>
                        </td>
                    </tr>
                `;
            }
        });
        if(!search){
            document.getElementById('slider').classList.add('d-none');
        };
        bodyTable.innerHTML = template;
        if (!permiso) {
            let elementos = document.getElementsByClassName('userPrivate');
            for (let index = 0; index < elementos.length; index++) {
                elementos[index].classList.add('d-none');
            };
        }
    }

    search(event) {
        this.filtrados = [];
        event.preventDefault();
        let data = new FormData(document.getElementById('form-search')).get('productoSearch');
        if(data==''){
            this.render(this.listadoProducto)
            return;
        }
        if(this.criterioBusqueda === 'producto'){
            this.filtrados = this.listadoProducto.filter(newArray => newArray.producto.toLowerCase().includes(data));
        }else{
            for (let index = 0; index < this.listadoProducto.length; index++) {
                if(this.listadoProducto[index].codigo_producto && this.listadoProducto[index].codigo_producto == data){
                    this.filtrados.push(this.listadoProducto[index]);
                }
            }
        }
        this.render(this.filtrados);
        return;
    }

    changeCriterioBusqueda(event){
        if (event.target.value === 'producto') {
            this.criterioBusqueda = 'producto';
        }else{
            this.criterioBusqueda = 'codigo';
        }
    }

    loadMore() {
        this.inicio = this.inicio + 50;
        this.fin = this.fin + 50;
        let arrayLimit = [];
        for (let index = this.inicio; index <= this.fin; index++) {
            arrayLimit[index] = this.listadoProducto[index];
        }
        this.render(arrayLimit,false,true);
    }

    handleChangeVentaKilo(event){
        if (event.target.value === 'si') {
            document.getElementsByClassName('input-disable')[0].removeAttribute('disabled');
            document.getElementsByClassName('input-disable')[1].removeAttribute('disabled');
        }else{
            document.getElementsByClassName('input-disable')[0].setAttribute('disabled','true');
            document.getElementsByClassName('input-disable')[1].setAttribute('disabled','true');
        }
    }
    //------------------------------------------------------------------------------------------//
                                    //Formulario de agregar producto
    //------------------------------------------------------------------------------------------//
    mostrarFormularioAgregar() {
        //ocultar listado de eventos y mostrar el form para agregar
        let tablaProductos = document.getElementById('tablaProductos');
        let divFormulario = document.getElementById('form-agregar-div');
        let formulario = document.getElementById('formAgregarProducto');
        let bannerForm = document.getElementById('banner-form');
        formulario.classList.remove('d-none');
        divFormulario.classList.remove('d-none');
        tablaProductos.classList.add('d-none');
        document.getElementById('btnverMas').classList.toggle('d-none');
        bannerForm.classList.toggle('d-none');
        getMarcas();
        getCategorias();
        getProveedores();
        document.getElementById('ventaKiloSelect').value = 'no';
    }

    ocultarFormularioAgregar() {
        let tablaProductos = document.getElementById('tablaProductos');
        let divFormulario = document.getElementById('form-agregar-div');
        let formulario = document.getElementById('formAgregarProducto');
        let alert = document.getElementById('alert-success');
        let bannerForm = document.getElementById('banner-form');
        alert.classList.add('d-none');
        formulario.reset(); //reseteo los campos del form para que si luego quiero agregar otro evento, los inputs esten vacios.
        divFormulario.classList.add('d-none');
        tablaProductos.classList.remove('d-none');
        document.getElementById('btnverMas').classList.toggle('d-none');
        bannerForm.classList.toggle('d-none');
        this.getProductos();//llamo a la funcion de getProductos para obtener la tabla actualizada con lo que agregue
    }

    agregarCompra(idProducto) {
        let prd = this.listadoProducto.filter(res=>res.idProducto == idProducto);
        this.carrito.agregarProductoSeleccionado(prd)
        if(this.carrito.productosSeleccionados.length>0){
            for (let index = 0; index < document.getElementsByClassName('preventa').length; index++) {
                 document.getElementsByClassName('preventa')[index].classList.remove('d-none');
            }
            document.getElementById('indicatorCantidadSeleccionados').innerHTML = `Productos seleccionados: <b>${this.carrito.productosSeleccionados.length}<b/>`
        }
    }

    eliminarProducto(id) {
        Swal({
            title: '¿Desea eliminar el Producto?',
            text: "Esta acción no se puede deshacer",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#30d685',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmo'
        }).then((result) => {
            if (result.value) {
                fetch('backend/producto/eliminarProducto.php?idProducto='+id)
                .then(response=>response.json())
                .then(newRes=>{
                    if(newRes){
                        animationDelete();//funcion de animacion que esta en app.js
                        this.getProductos();
                    }
                    // console.log(newRes);
                })
            }
        });
    }

    setDescuento(event,publico,unitario,kilo,idProducto) {
        let precioPublico = document.getElementById(`precioPublico_${idProducto}`),
            precioUnidad = document.getElementById(`precioUnidad_${idProducto}`),
            precioKilo = document.getElementById(`precioKilo_${idProducto}`),
            descuento = parseInt(event.target.value);
        if(event.target.value == 0 || event.target.value == ''){
            precioPublico.innerHTML = publico;
            precioUnidad.innerHTML = unitario;
            precioKilo.innerHTML = kilo;
            return;    
        };
        precioPublico.innerHTML = parseInt(precioPublico.textContent) - parseInt(precioPublico.textContent)*descuento/100;
        precioUnidad.innerHTML = parseInt(precioUnidad.textContent) - parseInt(precioUnidad.textContent)*descuento/100;
        precioKilo.innerHTML = parseInt(precioKilo.textContent) - parseInt(precioKilo.textContent)*descuento/100;
    }

    registrarVenta(){
        localStorage.setItem('productos',JSON.stringify(this.carrito.productosSeleccionados));
        return window.location.assign('venta.html');
    }

    checkUserSession() {
        if (userSession == 1) {
            return true;
        }
    }

    habilitarModificacionStockDeposito = id=>{
        let inputStockDeposito = document.getElementById(`inputStockDeposito_${id}`);
        let buttonStockDeposito = document.getElementById(`buttonStockDeposito_${id}`);
        inputStockDeposito.removeAttribute('disabled');
        buttonStockDeposito.removeAttribute('onclick');
        buttonStockDeposito.setAttribute('onclick',`producto.modificarStockDeposito(${id})`);
        buttonStockDeposito.className = 'btn btn-success';
        buttonStockDeposito.innerHTML = `<i class="fas fa-redo"></i>`; 
    }
    
    modificarStockDeposito = (idProducto)=>{
        //obtengo el stock en deposito de esta manera porque el valor que hay en el input el usuario lo va cambiar y ya no me sirve porque pierdo la referencia del numero que estaba en un principio.
        let stockDepositoDelProducto = parseInt(this.listadoProducto.filter(res=>res.idProducto == idProducto)[0].stock_deposito);
        let nuevoValorStockDeposito = parseInt(document.getElementById(`inputStockDeposito_${idProducto}`).value);
        let unidadesIngresadas = stockDepositoDelProducto - nuevoValorStockDeposito;
        Swal({
            title: '¿Desea Actualizar el stock?',
            text: `Vas a notificar que ingresaste ${unidadesIngresadas} unidad/es del deposito`,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#30d685',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmo'
        }).then((result) => {
            if (result.value) {
                fetch('backend/producto/actualizarStockDeposito.php',{
                    method:'POST',
                    body:JSON.stringify({
                        idProducto:idProducto,
                        stockDepositoDelProducto:stockDepositoDelProducto,
                        nuevoStockDeposito:nuevoValorStockDeposito
                    })
                }).then(res=>res.json()).then(response=>{
                    if(response.status == 400){
                        return modalError(response.info);
                    }
                    Swal.fire(
                        'Listo!',
                        response.info,
                        'success'
                    ).then(()=>(this.getProductos()))
                })
            }
        });
    }

    async modificarCodigo(id,codigoRegistrado){
        try {
            Swal.fire({
                title: 'Modificación de código',
                html:`
                    Codigo actual: <b>${codigoRegistrado}</b>
                    <input id="codigoProducto" name="codigoProducto" class="swal2-input">`,
                focusConfirm: false,
                preConfirm: () => {
                    if(document.getElementById('codigoProducto').value === '') return false;
                    let codigo = document.getElementById('codigoProducto').value;
                    fetch('backend/producto/modificarCodigo.php',{
                        method:'POST',
                        body:JSON.stringify({idProducto:id,codigoProducto:codigo})
                    }).then(res=>res.json()).then(response=>{
                        let iconResponse = 'error',titleResponse = 'Ups..';
                        if(response.status == 200){
                            iconResponse='success'
                            titleResponse = 'Listo!'
                        };
                        return Swal.fire(
                            titleResponse,
                            response.info,
                            iconResponse
                        ).then(()=>this.getProductos());
                    });
                }
            })
        } catch (error) {
            alert(error);
        }
    }
}

let form = document.getElementById('formAgregarProducto');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/producto/agregarProducto.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        try {
            if (newRes) {
                alert = document.getElementById('alert-success');
                alert.classList.remove('d-none');
                form.classList.add('d-none');
            }else{
                modalError('Algo no esta bien');
            }    
        } catch (error) {
            modalError('Ya existe un producto con el codigo ingresado'); 
        }
    })
})

window.producto = new Producto();

//botones del menu
iconMenu.addEventListener('click',()=>{
    menuSecundario.classList.remove('d-none');
})
btnOcultarMenu.addEventListener('click',()=>{
    menuSecundario.classList.toggle('d-none');
})
class Carrito{
    constructor() {
        this.API_PRODUCTOS = 'backend/producto/';
        this.productosSeleccionados = [];
        this.productos = [];
        this.carrito = {
            productos:[],
            subtotal:0,
            total:0,
            estado:'Pago',
            tipo_pago:'Efectivo',
            cliente:'No registrado',
            descuento:0,
            idLocal:localStorage.getItem('idLocal'),
            idUsuario:localStorage.getItem('idUsuario')
        };
        //this.getProductos();
    }

    agregarProductoSeleccionado(prd){
        this.productosSeleccionados.push(prd[0]);
        this.renderLabelsCarrito();
    }

    renderLabelsCarrito(){
        let template = '';
        this.productosSeleccionados.forEach(producto=>{
            template += `<span class="badge badge-secondary">${producto.producto}</span><span class="eliminarProductoCarrito" aria-hidden="true" onclick="producto.carrito.eliminarProductoSeleccionado(${producto.idProducto})">&times;</span>`
        })
        return document.getElementById('productosSeleccionadosModal').innerHTML = template;
    }

    eliminarProductoSeleccionado(id){
        let productoSeleccionadoNuevo = this.productosSeleccionados.filter(res=>res.idProducto!=id);
        this.productosSeleccionados = productoSeleccionadoNuevo;
        document.getElementById('indicatorCantidadSeleccionados').innerHTML = `Productos seleccionados: <b>${this.productosSeleccionados.length}<b/>`;
        return this.renderLabelsCarrito();
    }

    agregarAlCarrito(index){
        let producto = {
            producto:document.getElementsByName('producto')[index].value,
            cantidad:parseInt(document.getElementsByName('cantidad')[index].value),
            idCategoria:this.productosSeleccionados[index].idCategoria,
            idMarca:this.productosSeleccionados[index].idMarca,
            idProducto:this.productosSeleccionados[index].idProducto,
            precio:parseFloat(this.productosSeleccionados[index].precioPublico),
            total:0,
            tipoDeVenta:'normal'
        }
        if(document.getElementsByName('tipoDeVenta')[index].value == 'suelto'){
            producto.tipoDeVenta = 'suelto';
            producto.cantidad = parseFloat(document.getElementsByName('cantidadSuelto')[index].value);
            producto.precio=parseFloat(this.productosSeleccionados[index].precioKilo);
        }else if(document.getElementsByName('tipoDeVenta')[index].value == 'precio'){
            producto.tipoDeVenta = 'suelto';
            producto.cantidad = parseFloat(document.getElementsByName('cantidadPrecio')[index].value) / this.productosSeleccionados[index].precioKilo;
            producto.cantidad = parseFloat(producto.cantidad.toFixed(2));//para que tenga hasta dos decimales la cantidad
            producto.precio=parseFloat(this.productosSeleccionados[index].precioKilo);
        }else{
            producto.tipoDeVenta='normal';
        };
        if(document.getElementsByName('descuentoIndividualEstado')[index].value == 'si'){//si marco un descuento en ese producto..
            producto.total=parseFloat(window.totalConDescuento);
        }else if(document.getElementsByName('tipoDeVenta')[index].value == 'precio'){
            producto.total = parseFloat(document.getElementsByName('cantidadPrecio')[index].value); 
        }else{
            producto.total = producto.precio*producto.cantidad;
        }
        this.carrito.productos.push(producto);
        this.calcularTotal(this.carrito,index);//le paso el index para ocultar el row de los datos de ese producto
    }

    calcularTotal(carrito,index){
        let totales= [];
        for (let index = 0; index < carrito.productos.length; index++) {
            console.log(carrito.productos[index].total);
            totales.push(carrito.productos[index].total);
        }
        this.carrito.subtotal = totales.reduce((a, b) => a + b, 0);
        document.getElementsByClassName('btn-agregar-carrito')[index].classList.add('d-none');
        document.getElementsByClassName('alert-carrito')[index].classList.remove('d-none');
    }

    renderFormVenta(){
        document.getElementById('btn-confirmar-venta').classList.remove('d-none');
        let template = '';
        let templateSelectTipoVenta = '';
        let index = 0;
        this.productosSeleccionados.forEach(prd=>{
            if(prd.stock_suelto_local_1>0 && prd.stock_local_1>0){
                templateSelectTipoVenta = `<option value="normal">Normal</option>
                <option value="suelto">Suelto</option>
                <option value="precio">Por precio</option>`;
            }else if(prd.stock_suelto_local_1==0 && prd.stock_local_1>0){
                templateSelectTipoVenta = `<option value="normal">Normal</option>`; 
            }else{
                templateSelectTipoVenta = `<option value="suelto">Suelto</option>
                <option value="precio">Por precio</option>`
            }
            template += `
                <div class="row container-productoSeleccionado">
                    <div class="alert alert-success text-center col-12 alert-carrito d-none">Producto agregado al carrito</div>
                    <div class="input-group col-12 col-md-5 mb-5">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Tipo de venta</div>
                        </div>
                        <select name="tipoDeVenta" onchange="carrito.cambiarTipoDeCompra(event,${index})" class="form-control" id="">
                            ${templateSelectTipoVenta}
                        </select>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-12 col-md-5 text-center mb-4 pt-3 alert alert-info infoStock">
                    </div>
                    <div class="col-12 col-md-5 mb-4 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Producto</div>
                        </div>
                        <input type="text" name="producto" disabled="true" class="form-control" value="${prd.producto}" required>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="input-group col-12 col-md-5 mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Cantidad</div>
                        </div>
                        <select class="form-control selectCantidad cantidad" name="cantidad">
                            
                        </select>
                        <input type="number" class="form-control d-none cantidadSuelto" name="cantidadSuelto" step="any">
                        <input type="number" class="form-control d-none cantidadPrecio" name="cantidadPrecio" step="any">
                    </div>
                    <div class="input-group col-12 col-md-5 mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Descuento</div>
                        </div>
                        <select class="form-control" name="descuentoIndividualEstado" onchange="habilitarDescuentoIndividual(${index})">
                            <option value="no">No</option>
                            <option value="si">Si</option>
                        </select>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="input-group col-12 col-md-5 mb-4 d-none selectDecuentoIndividual">
                        <div class="input-group-prepend">
                            <div class="input-group-text">% Descuento</div>
                        </div>
                        <input type="text" class="form-control" name="descuentoIndividualValor" oninput="setDescuentoIndividual(${index},event)"/>
                    </div>
                    <div class="col-12 d-flex" style="justify-content:space-between">
                        <p class="alert alert-info d-none" name="totalConDescuento">Ingrese descuento..</p>
                        <input type="button" class="btn btn-outline-success mt-4 btn-agregar-carrito" onclick="carrito.agregarAlCarrito(${index})" value="Agregar al carrito">
                    </div>
                </div>
            </div>
            <hr>
            `;
            index++;
        });
        dom.formVenta.innerHTML = template
        //pintando los select de cantidad segun el producto y su stock
        let selectCantidad = document.getElementsByClassName('selectCantidad');
        let infoStock = document.getElementsByClassName('infoStock');
        let localDelUsuario = localStorage.getItem('idLocal');
        for (let index = 0; index < selectCantidad.length; index++) {
            let stock = this.productosSeleccionados[index].stock_local_1;
            let stock_suelto = this.productosSeleccionados[index].stock_suelto_local_1;
            if(localDelUsuario == "2"){
                stock = this.productosSeleccionados[index].stock_local_2;
                stock_suelto = this.productosSeleccionados[index].stock_suelto_local_2;
            } 
            if(stock == 0 && stock_suelto > 0){
                selectCantidad[index].classList.add('d-none');
                document.getElementsByName('cantidadSuelto')[index].classList.remove('d-none');
                infoStock[index].innerHTML = `Te quedan ${stock_suelto} Kg en stock`;
            }else{
                for(let c=1; c<=stock ;c++){
                    selectCantidad[index].innerHTML += `<option value="${c}">${c}</option>`;
                    infoStock[index].innerHTML = `Te quedan ${stock} unidades en stock`;
                }
            }
        }

        dom.formVenta.classList.remove('d-none')
    }

    cambiarTipoDeCompra(event,indiceProducto) {
        let tipoDeCompra = event.target.value,
            cantidadSuelto = document.getElementsByClassName('cantidadSuelto')[indiceProducto],
            cantidadNormal = document.getElementsByClassName('cantidad')[indiceProducto],
            cantidadPrecio = document.getElementsByClassName('cantidadPrecio')[indiceProducto];
        if (tipoDeCompra == 'normal') {
            document.getElementsByName('descuentoIndividualEstado')[indiceProducto].removeAttribute('disabled','');
            cantidadSuelto.classList.add('d-none');
            cantidadNormal.classList.remove('d-none');
            cantidadPrecio.classList.add('d-none');
            cantidadNormal.setAttribute('required','');
            cantidadSuelto.removeAttribute('required');
            cantidadPrecio.removeAttribute('required');
            document.getElementsByClassName('infoStock')[indiceProducto].innerHTML = `Te quedan ${this.productosSeleccionados[indiceProducto].stock} unidades en stock`;
        }else if(tipoDeCompra == 'suelto'){
            document.getElementsByName('descuentoIndividualEstado')[indiceProducto].removeAttribute('disabled','');
            cantidadSuelto.classList.remove('d-none');
            cantidadNormal.classList.add('d-none');
            cantidadPrecio.classList.add('d-none');
            cantidadNormal.removeAttribute('required');
            cantidadSuelto.setAttribute('required','');
            cantidadPrecio.removeAttribute('required');
            document.getElementsByClassName('infoStock')[indiceProducto].innerHTML = `Te quedan ${this.productosSeleccionados[indiceProducto].stock_suelto} Kg en stock`;
        }else{
            cantidadSuelto.classList.add('d-none');
            cantidadNormal.classList.add('d-none');
            cantidadPrecio.classList.remove('d-none');
            cantidadNormal.removeAttribute('required');
            cantidadSuelto.removeAttribute('required');
            cantidadPrecio.setAttribute('required','');
            document.getElementsByClassName('infoStock')[indiceProducto].innerHTML = `Te quedan ${this.productosSeleccionados[indiceProducto].stock_suelto} Kg en stock (Estas por vender por precio)`;

            document.getElementsByClassName('selectDecuentoIndividual')[indiceProducto].classList.add('d-none');
            document.getElementsByName('totalConDescuento')[indiceProducto].classList.add('d-none');
            document.getElementsByName('descuentoIndividualEstado')[indiceProducto].value = 'no';
            document.getElementsByName('descuentoIndividualEstado')[indiceProducto].setAttribute('disabled','true');
        }
    }

    verCarrito(){
        let listaProductos = '';
        for (let index = 0; index < this.carrito.productos.length; index++) {
            if(this.carrito.productos[index].tipoDeVenta == 'suelto'){
                listaProductos += `<li><b>${this.carrito.productos[index].producto} -  $${this.carrito.productos[index].total} (${this.carrito.productos[index].cantidad} Kg)</b></li>`
            }else{
                listaProductos += `<li><b>${this.carrito.productos[index].producto} -  $${this.carrito.productos[index].total} (${this.carrito.productos[index].cantidad} Unidades)</b></li>`
            }
        }
        let div = document.getElementById('infoDeCompra');
        div.innerHTML = `
            <label class="text-muted">Productos:</label>
            <ul>
                ${listaProductos}
            </ul>
            <label class="text-muted">Cliente: <b>${this.carrito.cliente}</b></label><br>
            <label class="text-muted">Estado: <b>${this.carrito.estado}</b></label><br>
            <label class="text-muted">Tipo de pago: <b>${this.carrito.tipo_pago}</b></label><br>
            <label class="text-muted">Subtotal: $<b>${this.carrito.subtotal}</b></label><br>
            <label class="text-muted">Descuento: <b>${this.carrito.descuento}%</b></label><br>
            <label class="text-muted">Total: <b>$${this.carrito.subtotal - (this.carrito.subtotal * this.carrito.descuento / 100)}</b></label>
        `;
        div.classList.toggle('d-none');
    }

    handleChangeLocal = e =>{
        this.carrito.idLocal = e.target.value;
    }

    cargarVenta(){
        document.getElementsByClassName('btn-modal-venta')[0].classList.add('d-none');
        document.getElementsByClassName('btn-modal-venta')[1].classList.add('d-none');
        this.carrito.total = this.carrito.subtotal - (this.carrito.subtotal * this.carrito.descuento /100);
        this.carrito.total = this.carrito.total.toFixed(2);
        console.log(this.carrito.total);
        console.log(this.carrito);
        fetch('backend/ventas/agregarVentaJson.php',{
            method:'POST',
            body:JSON.stringify(this.carrito)
        }).then(res=>res.json()).then(response=>{
            if(response.status == 200){
                fetch('backend/productosVenta/insertarProductos.php',{
                    method:'POST',
                    body:JSON.stringify({
                        idVenta:response.idVenta,
                        carrito:this.carrito
                    })
                }).then(res=>res.json()).then(response=>{
                    if(response.status == 400){
                        return Swal.fire(
                            'Venta agregada pero con problemas',
                            response.info,
                            'warning'
                        )
                    }
                    localStorage.removeItem('productos');//elimino los datos de los productos seleccionados del localStorage
                    Swal.fire({
                        title: response.info,
                        text: '¿Desea agregar los datos del envio de esta venta?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Agregar'
                    }).then((result) => {
                        document.getElementById('modalPago').classList.remove('show');
                        document.getElementById('modalPago').style.display = 'none';
                        if (result.value) {
                            let divVenta = document.getElementById('form-modificar-div');
                            let divEnvio = document.getElementById('form-agregar-div');
                            let tipoEnvio = document.getElementById('tipoEnvio');
                            tipoEnvio.setAttribute('value','varios');
                            divVenta.classList.add('d-none');
                            divEnvio.classList.remove('d-none');
                        }else{
                            window.location.assign('ventas.php');
                        }
                    })
                })
            }
        })
    }
} 
export default Carrito;
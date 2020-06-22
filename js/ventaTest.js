et f = new Date();
let fecha = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate();
let semana = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
let dia = semana[f.getDay()-1];
class Venta{
    constructor() {
        this.API_PRODUCTOS = 'backend/producto/';
        this.productos = [];
        this.productosSeleccionados = [];
        this.carrito = {
            productos:[],
            total:0,
            fecha,
            dia,
            estado:'Pagado',
            tipo_pago:'Efectivo',
            cliente:'No registrado',
            descuento:0
        };
        this.getProductos();
    }

    getProductos(){
        fetch(this.API_PRODUCTOS+'listarProducto.php').then(res=>res.json()).then(data=>{
            data.forEach(producto=>{
                this.productos.push(producto);
            })
            return this.productos;
        });
    }

    filterProductos(input,strict=false){
        let filtrados;
        if(strict){
            filtrados = this.productos.filter(fil=>fil.producto.toLowerCase() == input.toLowerCase());
        }else{
            filtrados = this.productos.filter(fil=>fil.producto.toLowerCase().includes(input.toLowerCase()));
        }
        return filtrados;
    }

    agregarProductoSeleccionado(prd){
        let producto = this.filterProductos(prd,true);
        this.productosSeleccionados.push(producto[0]);
        this.renderLabelsCarrito(this.productosSeleccionados);
    }

    eliminarProductoSeleccionado(id){
        let productoSeleccionadoNuevo = this.productosSeleccionados.filter(res=>res.idProducto!=id);
        this.productosSeleccionados = productoSeleccionadoNuevo;
        return this.renderLabelsCarrito(this.productosSeleccionados);
    }

    renderLabelsCarrito(seleccionados){
        template = '';
        seleccionados.forEach(producto=>{
            template += `<span class="badge badge-secondary">${producto.producto}</span><span class="eliminarProductoCarrito" aria-hidden="true" onclick="venta.eliminarProductoSeleccionado(${producto.idProducto})">&times;</span>`
        })
        return dom.labelProductosSeleccionados.innerHTML = template;
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
        }else{
            producto.tipoDeVenta='normal';
        };
        producto.total = producto.precio*producto.cantidad;
        this.carrito.productos.push(producto);
        this.calcularTotal(this.carrito,index);//le paso el index para ocultar el row de los datos de ese producto
    }

    calcularTotal(carrito,index){
        let totales= [];
        for (let index = 0; index < carrito.productos.length; index++) {
            totales.push(carrito.productos[index].total);
        }
        this.carrito.total = totales.reduce((a, b) => a + b, 0);
        document.getElementsByClassName('btn-agregar-carrito')[index].classList.add('d-none');
        document.getElementsByClassName('alert-carrito')[index].classList.remove('d-none');
    }

    renderFormVenta(){
        document.getElementById('btn-agregar-productos').classList.add('d-none');//oculto boton de agregar productos
        document.getElementById('btn-confirmar-venta').classList.remove('d-none');
        let template = '';
        let templateSelectTipoVenta = '';
        let index = 0;
        this.productosSeleccionados.forEach(prd=>{
            if(prd.stock_suelto>0){
                templateSelectTipoVenta = `<option value="normal">Normal</option>
                <option value="suelto">Suelto</option>`;
            }else{
                templateSelectTipoVenta = `<option value="normal">Normal</option>`; 
            }
            template += `
                <div class="row container-productoSeleccionado">
                    <div class="alert alert-success text-center col-12 alert-carrito d-none">Producto agregado al carrito</div>
                    <div class="input-group col-12 col-md-5 mb-5">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Tipo de venta</div>
                        </div>
                        <select name="tipoDeVenta" onchange="venta.cambiarTipoDeCompra(event,${index})" class="form-control" id="">
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
                    </div>
                    <div class="col-12 text-right"><input type="button" class="btn btn-outline-success mt-4 btn-agregar-carrito" onclick="venta.agregarAlCarrito(${index})" value="Agregar al carrito"></div>
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
        let templateSelectCantidad = '';
        for (let index = 0; index < selectCantidad.length; index++) {
            for(let c=1;c<=this.productosSeleccionados[index].stock;c++){
                selectCantidad[index].innerHTML += `<option value="${c}">${c}</option>`;
                infoStock[index].innerHTML = `Te quedan ${this.productosSeleccionados[index].stock} unidades en stock`;
            }
        }

        dom.formVenta.classList.remove('d-none')
    }

    cambiarTipoDeCompra(event,indiceProducto) {
        let tipoDeCompra = event.target.value,
            cantidadSuelto = document.getElementsByClassName('cantidadSuelto')[indiceProducto],
            cantidadNormal = document.getElementsByClassName('cantidad')[indiceProducto];
        cantidadSuelto.classList.toggle('d-none');
        cantidadNormal.classList.toggle('d-none');
        if (tipoDeCompra == 'normal') {
            cantidadNormal.setAttribute('required','');
            cantidadSuelto.removeAttribute('required');
            document.getElementsByClassName('infoStock')[indiceProducto].innerHTML = `Te quedan ${this.productosSeleccionados[indiceProducto].stock} unidades en stock`;
        }else if(tipoDeCompra == 'suelto'){
            cantidadNormal.removeAttribute('required');
            cantidadSuelto.setAttribute('required','');
            document.getElementsByClassName('infoStock')[indiceProducto].innerHTML = `Te quedan ${this.productosSeleccionados[indiceProducto].stock_suelto} Kg en stock`;
        }
    }

    verCarrito(){
        let listaProductos = '';
        for (let index = 0; index < this.carrito.productos.length; index++) {
            listaProductos += `<li><b>${this.carrito.productos[index].producto}</b></li>`
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
            <label class="text-muted">Subtotal: $<b>${this.carrito.total}</b></label><br>
            <label class="text-muted">Descuento: <b>${this.carrito.descuento}%</b></label><br>
            <label class="text-muted">Total: <b>$${this.carrito.total - (this.carrito.total * this.carrito.descuento / 100)}</b></label>
        `;
        div.classList.toggle('d-none');
    }

    cargarVenta(){
        this.carrito.total = this.carrito.total - (this.carrito.total * this.carrito.descuento /100);
        this.carrito.total = this.carrito.total.toFixed(2);
        fetch('backend/ventas/agregarVentaJson.php',{
            method:'POST',
            body:JSON.stringify(this.carrito)
        }).then(res=>res.text()).then(console.log)
    }
} 

let venta = new Venta();
let dom = {
    inputSearchProducto:document.getElementById('productoSearch'),
    itemsFiltradosModal:document.getElementById('lista-productos-modal'),
    labelProductosSeleccionados:document.getElementById('label-productos'),
    formVenta:document.getElementById('formVentaProducto')
};

dom.inputSearchProducto.addEventListener('input',event=>{
    let f = venta.filterProductos(dom.inputSearchProducto.value);
    renderItemsFiltrados(f);
})

const renderItemsFiltrados = filtrados=>{
    let template = '';
    filtrados.forEach(prd=>{
        if(prd.stock == '0'){
            template += `
            <a class="list-group-item list-group-item-action disabled" style="cursor:pointer">
                <span>${prd.producto}</span>
            </a>
            `; 
        }else{
            template += `
                <a class="list-group-item list-group-item-action" style="cursor:pointer" onclick="venta.agregarProductoSeleccionado('${prd.producto}')">
                    <span>${prd.producto}</span>
                </a>
            `;
        }
    })
    return dom.itemsFiltradosModal.innerHTML = template;
}


//habilita el input de nombre del cliente para registrarlo en el caso de que se pague con tarjeta
function habilitarInput(data){
    let divNombre = document.getElementById('nombreCliente');
    if (data.target.value == 'Tarjeta') {
        venta.carrito.tipo_pago = 'Tarjeta';
        divNombre.classList.remove('d-none');
    }else{
        venta.carrito.tipo_pago = 'Efectivo';
        divNombre.classList.add('d-none');
    }
}

//en el caso de que se seleccione descuento 'si', se habilita el input para que ingrese el valor del descuento
function habilitarDescuento(data) {
    let selectDescuento = document.getElementById('div-descuento'); //select de decuento (si-no)
    if (data.target.value == 'si') {
        document.getElementById('selectDescuento').classList.toggle('d-none');
        selectDescuento.setAttribute('required','');
    }else{
        document.getElementById('inputDescuento').value = '';//limpio por las dudas el input de desc
        venta.carrito.descuento = 0;
        document.getElementById('selectDescuento').classList.toggle('d-none');
        selectDescuento.removeAttribute('required');
    }
}

function showModalPago() {
    document.getElementById('modalPago').classList.add('show');
    document.getElementById('modalPago').style.display = 'block';
    document.getElementById('total-info').innerHTML = `El total es <b>$${venta.carrito.total.toFixed(2)}<b>`
}

function setDescuento(event) {
    let valorConDescuento;
    if(document.getElementById('inputDescuento').value == ''){
        valorConDescuento = venta.carrito.total;
        return;
    }else{
        venta.carrito.descuento = parseFloat(document.getElementById('inputDescuento').value);
        valorConDescuento = venta.carrito.total - (venta.carrito.total * venta.carrito.descuento / 100);
    }
    document.getElementById('total-info').innerHTML = `El total es <b>$${valorConDescuento.toFixed(2)}<b>`
}

function setEstado(event) {
    if (event.target.value == 'Debe') {
        venta.carrito.estado = 'Debe';
    }else{
        venta.carrito.estado = 'Pago'
    }
}

function setCliente(event) {
    if(document.getElementById('cliente').value.length<=2){venta.carrito.cliente = 'No registrado';return}
    venta.carrito.cliente = document.getElementById('cliente').value;
}
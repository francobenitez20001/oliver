class Venta{
    constructor() {
        this.API_PRODUCTOS = 'backend/producto/';
        this.productos = [];
        this.productosSeleccionados = [];
        this.carrito = [];
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

    agregarAlCarrito(prd){
        let producto = this.filterProductos(prd,true);
        this.productosSeleccionados.push(producto[0]);
        this.renderLabelsCarrito(this.productosSeleccionados);
    }

    eliminarDelCarrito(id){
        let productoSeleccionadoNuevo = this.productosSeleccionados.filter(res=>res.idProducto!=id);
        this.productosSeleccionados = productoSeleccionadoNuevo;
        return this.renderLabelsCarrito(this.productosSeleccionados);
    }

    renderLabelsCarrito(seleccionados){
        template = '';
        seleccionados.forEach(producto=>{
            template += `<span class="badge badge-secondary">${producto.producto}</span><span class="eliminarProductoCarrito" aria-hidden="true" onclick="venta.eliminarDelCarrito(${producto.idProducto})">&times;</span>`
        })
        return dom.labelProductosSeleccionados.innerHTML = template;
    }

    renderFormVenta(){
        let template = '';
        this.productosSeleccionados.forEach(prd=>{
            template += `
                <div class="row">
                    <div class="input-group col-12 col-md-5 mb-5">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Tipo de venta</div>
                        </div>
                        <select name="tipoDeVenta" onchange="cambiarTipoDeCompra(event)" class="form-control" id="">
                            <option value="normal">Normal</option>
                            <option value="suelto">Suelto</option>
                        </select>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-12 col-md-5 text-center mb-4 pt-3 alert alert-info infoStock">
                    </div>
                    <div class="col-12 col-md-5 mb-4 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Producto</div>
                        </div>
                        <input type="text" name="producto" id="producto" class="form-control" value="${prd.producto}" required>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="input-group col-12 col-md-5 mb-4">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Cantidad</div>
                        </div>
                        <select class="form-control selectCantidad" onchange="actualizarTotal(event,true)" name="cantidad">
                            
                        </select>
                        <input type="number" class="form-control d-none" name="cantidadSuelto" id="cantidadSuelto" step="any">
                    </div>
                    <div class="col-12 col-md-5 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Marca</div>
                        </div>
                        <select class="form-control" name="idMarca" id="idMarca" required>
                            <option value="${prd.marcaNombre}">${prd.marcaNombre}</option>
                        </select>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-12 col-md-5 input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Categoria</div>
                        </div>
                        <select class="form-control" name="idCategoria" id="idCategoria" required>
                            <option value="">${prd.categoriaNombre}</option>
                        </select>
                    </div>
                
                    <input type="hidden" name="idProducto" value="">
                    <div class="col-12 col-md-5 mb-4">
                    <!-- campos ocultos importantes para backend -->
                    <input type="hidden" name="fecha" id="inputFecha">
                    <input type="hidden" name="dia" id="inputDia">
                    <input type="hidden" value="${prd.stock}" name="stockParcial" id="stockParcial">
                    <input type="hidden" value="${prd.stockSuelto}"name="stockSuelto" id="stockSuelto">
                    <input type="hidden" value="${prd.stock}" name="stock" id="stockFinal">
                </div>
            </div>
            <hr>
            `;
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
            <a class="list-group-item list-group-item-action disabled" style="cursor:pointer" onclick="venta.agregarAlCarrito('${prd.producto}')">
                <span>${prd.producto}</span>
            </a>
            `; 
        }else{
            template += `
                <a class="list-group-item list-group-item-action" style="cursor:pointer" onclick="venta.agregarAlCarrito('${prd.producto}')">
                    <span>${prd.producto}</span>
                </a>
            `;
        }
    })
    return dom.itemsFiltradosModal.innerHTML = template;
}


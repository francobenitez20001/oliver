let formVentaProducto = document.getElementById('formVentaProducto');
formVentaProducto.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formVentaProducto);
    fetch('backend/ventas/agregarVenta.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            formVentaProducto.classList.add('d-none');
        }
        console.log(newRes);
    })
})
              
//fecha
let input = document.getElementById('inputFecha');
let f = new Date();
input.value = f.getFullYear() + "/" + (f.getMonth() +1) + "/" + f.getDate(); 
let inputDia = document.getElementById('inputDia');
let semana = ['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'];
let diaSemana = f.getDay();
// console.log(semana[diaSemana-1]);
inputDia.value = semana[diaSemana-1];

//stocks
let stockParcial = parseInt(document.getElementById('stockParcial').value);
let cantidad = parseInt(document.getElementById('cantidad').value);

// select para elegir cantidad menor o igual al stock disponible
let selectCantidad = document.getElementById('cantidad');
let template = '';
for (let index = 1; index <= stockParcial; index++) {
    template += `<option value="${index}">${index}</option>`;
}
selectCantidad.innerHTML = template;




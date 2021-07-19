// productos
let menuSecundario = document.getElementById('menu-secundary');
let btnOcultarMenu = document.getElementById('botonVolverSecundary');
let iconMenu = document.getElementById('icon-menu');

// iconMenu.addEventListener('click',()=>{
//     menuSecundario.classList.remove('d-none');
// })

// btnOcultarMenu.addEventListener('click',()=>{
//     menuSecundario.classList.toggle('d-none');
// })


//animacion de delete
function animationDelete() {
    let timerInterval;
    Swal.fire({
        title: 'Eliminando',
        timer: 200,
        timerProgressBar: true,
        onBeforeOpen: () => {
            Swal.showLoading()
            timerInterval = setInterval(() => {
                Swal.getContent().querySelector('b')
                // .textContent = Swal.getTimerLeft()
            }, 100)
        },
        onClose: () => {
            clearInterval(timerInterval)
        }
    })
    .then((result) => {
        if (result.dismiss === Swal.DismissReason.timer){}
    })
}


//logout
let cerrarSesion = document.getElementById('cerrarSesion');
cerrarSesion.addEventListener("click",()=>{
    Swal.fire({
        title: 'Seguro que querés irte?',
        text: "Tendrás que loguearte otra vez para ingresar!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Acepto'
    }).then((result) => {
        if (result.value) {
            fetch('backend/usuario/logout.php')
            .then(res=>res.json())
            .then(newRes=>{
                if (newRes) {
                    Swal.fire(
                        'Hasta pronto!',
                        'Cerraste la sesión con éxito',
                        'success'
                    ).then((result) => {
                        window.location.assign('login.html');
                    })
                }
            })
        }
    })
})


let checkAll = false;

function modalExport(url,proveedores) {
    if(proveedores){
        let listadoProveedores;
        let template;
        fetch('backend/proveedores/listarProveedor.php').then(res=>res.json()).then(data=>{
            listadoProveedores=data;
            listadoProveedores.forEach(provedor => {
                template += `<option value="${provedor.idProveedor}">${provedor.proveedor}</option>`
            });
            Swal.fire({
                title: 'Indique Fechas',
                html:
                    `
                    <select class="form-control" id="idProveedor">
                        ${template}
                    </select>
                    ` +
                    '<input id="inicio" name="inicio" type="date" class="swal2-input">' +
                    '<input id="fin" name="fin" type="date" class="swal2-input">' +
                    '<input id="all" type="checkbox" class="">Exportar todo',
                focusConfirm: false,
                showCancelButton: true,
                preConfirm: () => {
                    if(document.getElementById('all').checked){
                        console.log('todo')
                        return window.location.assign(url);
                    };  
                    inicio = document.getElementById('inicio').value;
                    fin = document.getElementById('fin').value;
                    idProveedor = document.getElementById('idProveedor').value;
                    return window.location.assign(url+'?inicio='+inicio+'&fin='+fin+'&idProveedor='+idProveedor);
                }
            })
        });
    }else{
        Swal.fire({
            title: 'Indique Fechas',
            html:
                '<input id="inicio" name="inicio" type="date" class="swal2-input">' +
                '<input id="fin" name="fin" type="date" class="swal2-input">' +
                '<input id="all" type="checkbox" class="">Exportar todo',
            focusConfirm: false,
            showCancelButton: true,
            preConfirm: () => {
                if(document.getElementById('all').checked){
                    console.log('todo')
                    return window.location.assign(url);
                };  
                inicio = document.getElementById('inicio').value;
                fin = document.getElementById('fin').value;
                console.log(url+'?inicio='+inicio+'&fin='+fin)
                return window.location.assign(url+'?inicio='+inicio+'&fin='+fin);
            },
        })
    }
}


function modalDelete(titulo) {
    return Swal({
        title: titulo,
        text: "Esta acción no se puede deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#30d685',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Confirmo'
    })
}

function modalError(body) {
    return Swal.fire({
        title: 'Error',
        text: body,
        type: 'error',
        confirmButtonColor: '#30d685',
        confirmButtonText: 'Ok'
    })    
}

function modalSuccess(message) {
    return Swal.fire({
        title:'Listo',
        text:message,
        type:'success',
        confirmButtonColor: '#30d685',
        confirmButtonText: 'Ok'
    })
}
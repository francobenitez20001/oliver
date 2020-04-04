// productos
let menuSecundario = document.getElementById('menu-secundary');
let btnOcultarMenu = document.getElementById('botonVolverSecundary');
let iconMenu = document.getElementById('icon-menu');

iconMenu.addEventListener('click',()=>{
    menuSecundario.classList.remove('d-none');
})

btnOcultarMenu.addEventListener('click',()=>{
    menuSecundario.classList.toggle('d-none');
})


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
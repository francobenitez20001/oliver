let formLogin = document.getElementById('form-login');
formLogin.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(formLogin);
    fetch('backend/usuario/login.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            console.log(newRes)
            formLogin.classList.add('d-none');
            nombre = newRes;
            Swal.fire({
                title: 'Bienvenido ' + nombre,
                showClass: {
                  popup: 'animated fadeInDown faster'
                },
                hideClass: {
                  popup: 'animated fadeOutUp faster'
                }
            })
            setTimeout(() => {
                window.location.assign('home.html');
            }, 3000);
        }else{
            alert = document.getElementById('alert-danger');
            alert.classList.remove('d-none');
        }
    })
})
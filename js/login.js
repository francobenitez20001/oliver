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
            }).then(()=>{
                window.location.assign('/oliver/home.php');
            })
        }else{
            alert = document.getElementById('alert-danger');
            alert.classList.remove('d-none');
        }
    })
})
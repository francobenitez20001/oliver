let userSession = '';
fetch('backend/usuario/autenticacion.php')
.then(res=>res.json())
.then(newRes=>{
    // console.log(newRes)
    if (!newRes) {
        alert('Para ver esta pagina inicia sesión previamente');
        window.location.assign('login.html');
    }else{
        // console.log(newRes)
        userSession = newRes.session;
    }
})

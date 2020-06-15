let userSession = '';
fetch('backend/usuario/autenticacion.php?admin=true')
.then(res=>res.json())
.then(newRes=>{
    // console.log(newRes)
    if (!newRes) {
        alert('Para ver esta pagina inicia sesión previamente');
        window.location.assign('login.html');
    }else{
        if(newRes.login){
            userSession = newRes.session;
        }else{
            window.location.assign('home.html');
        }
    }
})
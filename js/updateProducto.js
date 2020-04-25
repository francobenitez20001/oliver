function checkUserSession() {
    if (userSession == 1) {
      elementos = document.getElementsByClassName('userPrivate');
      for (let index = 0; index < elementos.length; index++) {
        elementos[index].classList.remove('d-none');
      };
    }else{
      return true;
    } 
}

let formulario = document.getElementById('formModificarProducto');
formulario.addEventListener('submit', event=>{
    event.preventDefault();
    let data = new FormData(formulario);
    fetch('backend/producto/modificarProducto.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-warning');
            alert.classList.remove('d-none');
            formulario.classList.add('d-none');
        }
    })
})

window.onload = ()=>{
    checkUserSession();
}
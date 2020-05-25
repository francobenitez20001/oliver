function checkUserSession() {
    setTimeout(() => {
      if (userSession == 1) {
        elementos = document.getElementsByClassName('userPrivate');
        for (let index = 0; index < elementos.length; index++) {
          elementos[index].classList.remove('d-none');
        };
        return true;
      }else{
        return true;
      } 
    }, 2000);
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
        }else{
          modalError('Ya existe un producto con el codigo ingresado');
        }
    })
})

window.onload = ()=>{
    let bool = checkUserSession();
    console.log(bool);
}
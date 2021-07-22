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

function handleChangeVentaKilo(event){
  if (event.target.value === 'si') {
    document.getElementsByClassName('input-disable')[0].removeAttribute('disabled');
    document.getElementsByClassName('input-disable')[1].removeAttribute('disabled');
  }else{
      document.getElementsByClassName('input-disable')[0].setAttribute('disabled','true');
      document.getElementsByClassName('input-disable')[0].value="";
      document.getElementsByClassName('input-disable')[1].setAttribute('disabled','true');
      document.getElementsByClassName('input-disable')[1].value="";
  }
}


function aumentarProducto(e) {
  e.preventDefault();
  let data = new FormData(e.target);
  let url = 'backend/producto/aumentarPorProducto.php';
  fetch(url,{
      method:'POST',
      body:data
  })
  .then(res=>res.json())
  .then(response=>{
      if (response.status == 200) {
          Swal.fire(
              'Listo!',
              response.info,
              'success'
          ).then(()=>(window.location.assign('adminProductos.html')));
      }else{
          Swal.fire(
              'Error!',
              response.info,
              'error'
          )
      }
  })
}
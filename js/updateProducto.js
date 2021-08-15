const enviar = async event=>{
    event.preventDefault();
    let data = new FormData(event.target);
    //console.log(data.get('cantidadKilo'));
    const res = await fetch('backend/producto/modificarProducto.php',{
        method: 'POST',
        body: data
    });
    if(res.status!==200){
        return modalError('Error al modificar');
    }
    return modalSuccess('Modificado').then(()=>window.location.assign('/productos.php'));
};

function handleChangeVentaKilo(event){
  if (event.target.value === 'si') {
    document.getElementsByClassName('input-disable')[0].removeAttribute('disabled');
    document.getElementsByClassName('input-disable')[1].removeAttribute('disabled');
  }else{
    // document.getElementsByClassName('input-disable')[0].setAttribute('disabled','true');
    document.getElementsByClassName('input-disable')[0].value="0";
    // document.getElementsByClassName('input-disable')[1].setAttribute('disabled','true');
    document.getElementsByClassName('input-disable')[1].value="0";
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
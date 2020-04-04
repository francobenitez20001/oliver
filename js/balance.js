let btnGuardar = document.getElementById('btn-guardar');
btnGuardar.addEventListener('click',guardar);

function guardar() {
    Swal.fire({
        title: 'Seguro que desea guardar las acciones de hoy?',
        text: "Puede volver a guardar posteriormente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Guardar'
    }).then((result) => {
        if (result.value) {
          Swal.fire(
            'Listo!',
            'Se han guardado las acciones del día de la fecha',
            'success'
          )
        }
    })
}
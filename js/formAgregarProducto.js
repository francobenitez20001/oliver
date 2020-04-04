let form = document.getElementById('formAgregarProducto');
form.addEventListener('submit',event=>{
    event.preventDefault();
    let data = new FormData(form);
    fetch('backend/agregarProducto.php',{
        method: 'POST',
        body: data
    })
    .then(res=>res.json())
    .then(newRes=>{
        if (newRes) {
            alert = document.getElementById('alert-success');
            alert.classList.remove('d-none');
            form.classList.add('d-none');
        }
    })
})


function getCategorias() {
    let selectCategoria = document.getElementById('idCategoria');
    let template = '';
    fetch('backend/listarCategoria.php')
    .then(res=>res.json())
    .then(newRes=>{
        newRes.forEach(reg => {
            template += `
            <option value="${reg.idCategoria}">${reg.categoriaNombre}</option>
            `;
        });
        selectCategoria.innerHTML = template;
        // console.log(newRes);
    })
}

function getMarcas() {
    let selectMarca = document.getElementById('idMarca');
    let template = '';
    fetch('backend/listarMarca.php')
    .then(res=>res.json())
    .then(newRes=>{
        newRes.forEach(reg => {
            template += `
            <option value="${reg.idMarca}">${reg.marcaNombre}</option>
            `;
        });
        selectMarca.innerHTML = template;
        // console.log(newRes);
    })
}

getMarcas();
getCategorias();
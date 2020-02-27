let navbarDropdown = document.getElementById('navbarDropdown');
fetch('backend/usuario/listarUsuario.php')
.then(res=>res.json())
.then(newRes=>{
    nombre = newRes[0].nombre;
    template = `
    <i class="fas fa-user"></i>
    Hola ${nombre}
    `;
    navbarDropdown.innerHTML = template;
})
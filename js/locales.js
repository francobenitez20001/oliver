class Local{
    constructor(){
        this.table = document.getElementById('tablaLocales');
        this.bodyTable = document.getElementById('bodyTable');
        this.btnAgregar = document.getElementById('botonAgregar');
        this.api = "backend/locales";
        this.loader = document.getElementById('loader');
        this.modal = document.getElementById('modal');
        this.form = document.getElementById('form-local');
        this.formInputs = {
            idLocal:document.getElementById('idLocal'),
            nombre:document.getElementById('nombre'),
            estado:document.getElementById('estado')
        }
        this.agregarListeners();
    }

    async getLocales(idLocal=null){
        this.loader.classList.remove('d-none');
        let url = `${this.api}/get.php`;
        if(idLocal){
            url += `?idLocal=${idLocal}`;
        }
        const req = await fetch(url);
        this.loader.classList.add('d-none');
        if(req.status !== 200){
            return modalError(req.statusText);
        }
        const {data} = await req.json();
        if(!idLocal){
            return this.render(data);
        }
        this.formInputs.idLocal.value = idLocal;
        this.formInputs.nombre.value = data[0].nombre;
        this.formInputs.estado.value = data[0].estado;
    }

    async agregarLocal(data){
        locales.loader.classList.remove('d-none');
        let url = `${this.api}/add.php`;
        let config = {
            method:'POST',
            body:new FormData(data)
        }
        const req = await fetch(url,config);
        locales.loader.classList.add('d-none');
        if(req.status !== 200){
            return modalError(req.statusText);
        }
        return modalSuccess('Se ha agregado el local').then(()=>{
            this.limpiarForm();
            this.cerrarModal();
            this.getLocales();
        });
    }

    async modificarLocal(data){
        locales.loader.classList.remove('d-none');
        let url = `${this.api}/update.php`;
        let config = {
            method:'POST',
            body:new FormData(data)
        };
        const req = await fetch(url,config);
        locales.loader.classList.add('d-none');
        if(req.status !== 200){
            return modalError(req.statusText);
        }
        return modalSuccess('Se ha modificado el local').then(()=>{
            this.limpiarForm();
            this.cerrarModal();
            this.getLocales();
        });
    }

    agregarListeners(){
        this.btnAgregar.addEventListener('click',()=>this.abrirModal(null));
        this.form.addEventListener('submit',e => this.enviar(e));
    }

    abrirModal(id=null){
        locales.modal.classList.add('show');
        locales.modal.style.display = 'block';
        if(id){
            return locales.getLocales(id);
        }
        this.formInputs.idLocal.value = null;
    }

    cerrarModal(){
        locales.modal.classList.remove('show');
        locales.modal.style.display = 'none';
    }

    enviar = e =>{
        e.preventDefault();
        console.log(this.formInputs.idLocal.value);
        if(this.formInputs.idLocal.value != ""){
            return this.modificarLocal(e.target);
        }
        return this.agregarLocal(e.target);
    }

    limpiarForm(){
        this.formInputs.idLocal.value = null;
        this.formInputs.nombre.value = "";
        this.formInputs.estado.value = "1";
    }

    render(data){
        let html = "";
        data.forEach(local => {
            html += `
                <tr>
                    <th scope="col">${local.nombre}</th>
                    <td>${local.estado == 1 ? 'Activo' : 'Suspendido'}</td>
                    <td>
                        <button type="button" onClick="locales.abrirModal(${local.idLocal})" class="btn btn-outline-warning"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
            `
        });
        return this.bodyTable.innerHTML = html;
    }
}

let locales = new Local();
locales.getLocales();

<template>
    <main class="main">
        <div class="card">
                <div class="card-header text-center ">
                <strong>MANTENIMIENTO - USUARIO</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-sm-3">
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                            <i class="fa fa-file"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <input type="text" v-model="buscar" @keyup.enter="listarRegistro(1,buscar)" class="form-control" placeholder="Búsqueda por Usuario">
                            <button type="submit" @click="listarRegistro(1,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Acción</th>
                            <th class="text-center">Nombre de Usuario</th>
                            <th class="text-center">Correo Eletrónico</th>
                            <th class="text-center">Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in arrayUser" :key="user.id">
                            <td class="text-center">
                                <button type="button" @click="abrirModal('actualizar',user)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <template v-if="user.estado">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(user.id)">
                                        <i class="fa fa-check"> A</i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(user.id)">
                                        <i class="fa fa-trash-alt"> D</i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="user.name"></td>
                            <td v-text="user.email"></td>
                            <td v-text="user.nombre"></td>
                        </tr>                                
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Anterior</a>
                        </li>
                        <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                        </li>
                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Siguiente</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
      
        <div class="modal fade" id="muestraModal" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog modal-primary modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong v-text="tituloModal"></strong>
                        <button type="button" class=" btn btn-primary close text-white" @click="cerrarModal()">
                            <i class="fa fa-window-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" enctype="multipart/form-data" class="form-horizontal">
                            
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                                    </div>
                                    <input class="form-control focusNext" type="text" v-model="name" tabindex="1" maxlength="20" placeholder="Nombre de usuario">
                                </div>
                                <span v-if="errorsCampo.name" class="text-danger error">{{ errorsCampo.name[0] }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <input class="form-control focusNext" type="password" v-model="password" tabindex="2" maxlength="20" placeholder="Contraseña">
                                </div>
                                <span v-if="errorsCampo.password" class="text-danger error">{{ errorsCampo.password[0] }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input class="form-control focusNext" type="email" v-model="email" tabindex="3" maxlength="50" placeholder="Correo electrónico">
                                </div>
                                <span v-if="errorsCampo.email" class="text-danger error">{{ errorsCampo.email[0] }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="input-group">
                                    <select class="form-control focusNext" tabindex="4" v-model="rol_id" @change="selectRol">
                                        <option value="0" disabled selected="1">-- Asigna un Rol (*) --</option>
                                        <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                    </select>
                                </div>
                                <span v-if="errorsCampo.rol_id" class="text-danger error">{{ errorsCampo.rol_id[0] }}</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" v-if="tipoAccion==1" tabindex="5" class="btn btn-primary focusNext" @click="registrar()">
                            <i class="fa fa-save"></i> Grabar</button>
                        <button type="button" v-if="tipoAccion==2" tabindex="5" class="btn btn-primary focusNext" @click="actualizar()">
                            <i class="fa fa-pencil-alt"></i> Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
import vSelect from 'vue-select';
export default {
  data() {
    return {
      user_id: null,
      name: "",
      password: "",
      email: "",
      personal_id: 0,
      rol_id: 0,
      tituloModal: "",
      tipoAccion: 0,
      errorsCampo: [],
      pagination: {
        total: 0,
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0
      },
      offset: 3,
      buscar: "",
      arrayUser: [],
      arrayRol: [],
      arrayPersonal: []
    };
  },
    components:{
        vSelect
    },
  methods: {
    listarRegistro(page, buscar) {
      let me = this;
      var url ="/user?page=" + page +"&buscar=" + buscar;
      axios.get(url).then(function(response) {
          var respuesta = response.data;
          me.arrayUser = respuesta.user.data;
          me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    cambiarPagina(page, buscar) {
      let me = this;
      me.pagination.current_page = page;
      me.listarRegistro(page, buscar);
    },

    selectRol() {
      let me = this;
      axios
        .get("/rol/selectRol")
        .then(function(response) {
          var respuesta = response.data;
          me.arrayRol = respuesta.rol;
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    buscarPersonal(search, loading) {
      let me = this;
      loading(true)
      var url='/personal/buscarPersonal?filtro='+search;
      axios.get(url).then(function(response) {
          let respuesta = response.data;
          q:search
          me.arrayPersonal = respuesta.personal;
          loading(false)
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    getDatosRegistro(val1)
    {
        let me=this;
        me.loading=true;
        me.personal_id=val1.id;
    },

    registrar() {
      let me = this;
      axios.post("/user/registrar", {
          name: this.name,
          password: this.password,
          email: this.email,
          personal_id: this.personal_id,
          rol_id: this.rol_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(1, "");
          swal("Grabado!", "Registro guardado con éxito!", "success", {button: false,timer: 1000});
        }).catch(error=> {
            if(error.response.status==422){
                this.errorsCampo=error.response.data.errors;
            }
        });
    },

    actualizar() {
      let paginaActual=this.isActived;
      let me = this;
      axios
        .put("/user/actualizar", {
          id: this.user_id,
          name: this.name,
          email: this.email,
          password: this.password,
          personal_id: this.personal_id,
          rol_id: this.rol_id,
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(paginaActual,"");
          swal("Actualizado!", "Registro modificado con éxito!", "info", {button: false,timer: 1000});
        }).catch(error=> {
            if(error.response.status==422){
                this.errorsCampo=error.response.data.errors;
            }
        });
    },

    desactivar(id) {
        swal("DESACTIVAR!", "Desea desactivar el registro?", "error", {buttons:true, dangerMode: true})
        .then(confirma => {
            if (confirma) {
                let me=this;
                let paginaActual=this.isActived;
                axios.put("/user/desactivar", { id: id }).then(function(response) {
                    me.listarRegistro(paginaActual, "");
                });
            } else {
                swal.close();
            }
        });
    },

    activar(id) {
        swal("ACTIVAR!", "Desea activar el registro?", "info", {buttons:true, dangerMode: false})
        .then(confirma => {
        if (confirma) {
            let me=this;
            let paginaActual=this.isActived;
            axios.put("/user/activar", { id: id }).then(function(response) {
            me.listarRegistro(paginaActual, "");
            });
        } else {
            swal.close();
        }
        });
    },

    cerrarModal() {
      $("#muestraModal").modal("hide");
      this.tituloModal = "";
      this.user_id = null;
      this.name = "";
      this.password = "";
      this.email = "";
      this.personal_id = 0;
      this.rol_id = 0;
      this.errorsCampo = [];
    },

    abrirModal(accion, data = []) {
      this.selectRol();
      switch (accion) {
        case "registrar": {
            this.tipoAccion = 1;
            this.tituloModal = "REGISTRAR USUARIO";
            this.name = "";
            this.password = "";
            this.email = "";
            this.rol_id = 0;
            this.personal_id = 0;
            break;
        }
        case "actualizar": {
          this.tituloModal = "ACTUALIZAR USUARIO";
          this.tipoAccion = 2;
          this.user_id= data["id"];
          this.name = data["name"];
          this.password = data["password"];
          this.email = data["email"];
          this.personal_id = data["personal_id"];
          this.rol_id = data["rol_id"];
          break;
        }
      }
    }
  },
  computed: {
    isActived: function() {
      return this.pagination.current_page;
    },
    pagesNumber: function() {
      if (!this.pagination.to) {
        return [];
      }
      var from = this.pagination.current_page - this.offset;
      if (from < 1) {
        from = 1;
      }

      var to = from + this.offset * 2;
      if (to >= this.pagination.last_page) {
        to = this.pagination.last_page;
      }
      var pagesArray = [];
      while (from <= to) {
        pagesArray.push(from);
        from++;
      }
      return pagesArray;
    }
  },

  mounted() {
    this.listarRegistro(1, '');
  }
};
</script>
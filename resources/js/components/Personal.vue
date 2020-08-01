<template>
    <main class="main">
    <div class="card">
            <div class="card-header text-center bg-primary">
            <strong>MANTENIMIENTO - PERSONAL SERVICIO</strong>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-2">
                    <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                        <i class="fa fa-file"></i>&nbsp;Nuevo
                    </button>
                </div>
                <div class="col-sm-3">
                    <select class="form-control" v-model="buscar" @change="listarRegistro(1,buscar,'grupo')">
                        <option value="">-- Busqueda por Grupo --</option>
                        <option value="A">GRUPO A</option>
                        <option value="B">GRUPO B</option>
                        <option value="C">GRUPO C</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <select class="form-control col-sm-3" v-model="criterio">
                            <option value="">Filtrar Todo</option>
                            <option value="apellidos">Apellidos</option>
                            <option value="nombres">Nombres</option>
                            <option value="dni">N° de DNI</option>
                        </select>
                        <input type="text" v-model="buscar" @keyup.enter="listarRegistro(1,buscar,criterio)" class="form-control" placeholder="Búsqueda">
                        <button type="submit" @click="listarRegistro(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-sm table-responsive-md">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">Acción</th>
                        <th class="text-center">Apellidos y nombres</th>
                        <th class="text-center">DNI N°</th>
                        <th class="text-center">Cargo</th>
                        <th class="text-center">F. Nacimiento</th>
                        <th class="text-center">Teléfono</th>
                        <th class="text-center">Grupo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="personal in arrayPersonal" :key="personal.id">
                        <td class="text-center">
                            <button type="button" @click="abrirModal('actualizar',personal)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <template v-if="personal.estado">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(personal.id)">
                                    <i class="fa fa-check"> A</i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(personal.id)">
                                    <i class="fa fa-trash-alt"> D</i>
                                </button>
                            </template>
                        </td>
                        <td v-text="personal.apellidos+' '+personal.nombres"></td>
                        <td v-text="personal.dni"></td>
                        <td v-text="personal.cargo_nombre"></td>
                        <td v-text="personal.fechaNacimiento"></td>
                        <td v-text="personal.telefono"></td>
                        <td v-text="personal.grupo"></td>
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
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong v-text="tituloModal"></strong>
                    <button type="button" class=" btn btn-primary close text-white" @click="cerrarModal()">
                        <i class="fa fa-window-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <input type="text" v-model="apellidos" tabindex="1" class="form-control focusNext" autofocus maxlength="60" placeholder="Apellidos (*)">
                                <span v-if="errorsCampo.apellidos" class="text-danger error">{{ errorsCampo.apellidos[0] }}</span>
                                <input type="text" v-model="nombres" tabindex="2" class="form-control focusNext" maxlength="60" placeholder="Nombres (*)">
                                <span v-if="errorsCampo.nombres" class="text-danger error">{{ errorsCampo.nombres[0] }}</span>
                                <input type="text" v-model="dni" tabindex="3" class="form-control focusNext" minlength="8" maxlength="8" placeholder="N° DNI (*)">
                                <span v-if="errorsCampo.dni" class="text-danger error">{{ errorsCampo.dni[0] }}</span>
                            </div>
                            <div class="form-group col-md-4"> 
                                <div class="input-group">
                                    <label for="fechaNacimiento">Nació el (*):&nbsp;</label>
                                    <input type="date" v-model="fechaNacimiento" tabindex="4" class="form-control focusNext">
                                </div>
                                <span v-if="errorsCampo.fechaNacimiento" class="text-danger error">{{ errorsCampo.fechaNacimiento[0] }}</span>
                            
                                <input type="text" v-model="telefono" tabindex="5" class="form-control focusNext" maxlength="30" placeholder="N° Teléfono">
                                <span v-if="errorsCampo.telefono" class="text-danger error">{{ errorsCampo.telefono[0] }}</span>
                            
                                <input type="email" v-model="correo" tabindex="6" class="form-control focusNext" maxlength="58" placeholder="Correo Electrónico">
                                <span v-if="errorsCampo.correo" class="text-danger error">{{ errorsCampo.correo[0] }}</span>
                            </div>
                            <div class="form-group col-md-3">
                                <textarea v-model="direccion" tabindex="7" rows="4" class="form-control focusNext" maxlength="150" placeholder="Dirección"></textarea>
                                <span v-if="errorsCampo.direccion" class="text-danger error">{{ errorsCampo.direccion[0] }}</span>
                            </div>
                        </div>
                        
                        <div class="form-row border border-info">
                            <div class="form-group col-lg-5">
                                <div class="input-group">
                                    <label for="fechaIngreso">Ingresa el (*):</label>&nbsp;
                                    <input type="date" v-model="fechaIngreso" tabindex="8" class="form-control focusNext">
                                </div>
                                <span v-if="errorsCampo.fechaIngreso" class="text-danger error">{{ errorsCampo.fechaIngreso[0] }}</span>
                            </div>
                            <div class="form-group col-lg-3">
                                <select class="form-control focusNext" tabindex="9" v-model="cargo_id" @change="selectCargo">
                                    <option value="0" disabled selected="1">-- Cargo (*) --</option>
                                    <option v-for="cargo in arrayCargo" :key="cargo.id" :value="cargo.id" v-text="cargo.nombre"></option>
                                </select>
                                <span v-if="errorsCampo.cargo_id" class="text-danger error">{{ errorsCampo.cargo_id[0] }}</span>
                            </div>
                            <div class="form-group col-lg-4">
                                <select class="form-control focusNext" tabindex="10" v-model="grupo">
                                    <option value="" disabled selected="0">-- GRUPO (*) --</option>
                                    <option value="A">GRUPO A</option>
                                    <option value="B">GRUPO B</option>
                                    <option value="C">GRUPO C</option>
                                </select>
                                <span v-if="errorsCampo.grupo" class="text-danger error">{{ errorsCampo.grupo[0] }}</span>
                            </div>
                        </div>
                    
                        <div class="form-row border border-danger">
                            <div class="form-group col-lg-4">
                                <div class="input-group">
                                    <label for="fechaCese">Fecha de Cese:</label>&nbsp;
                                    <input type="date" v-model="fechaCese" tabindex="11" class="form-control focusNext">
                                </div>
                                <span v-if="errorsCampo.fechaCese" class="text-danger error">{{ errorsCampo.fechaCese[0] }}</span>
                            </div>    
                            <div class="form-group col-lg-8">
                                <input type="text" v-model="observacion" tabindex="12" class="form-control focusNext" maxlength="150" placeholder="Observaciones">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" v-if="tipoAccion==1" tabindex="13" class="btn btn-primary focusNext" @click="registrar()">
                        <i class="fa fa-save"></i> Grabar</button>
                    <button type="button" v-if="tipoAccion==2" tabindex="13" class="btn btn-primary focusNext" @click="actualizar()">
                        <i class="fa fa-pencil-alt"></i> Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    </main>
</template>

<script>
export default {
data() {
    return {
    personal_id: null,
    apellidos: "",
    nombres: "",
    dni: "",
    fechaNacimiento: null,
    direccion: "",
    telefono: "",
    correo: "",
    fechaIngreso: null,
    cargo_id: null,
    grupo: '',
    fechaCese: null,
    observacion: "",
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
    criterio: "",
    buscar: "",
    arrayPersonal: [],
    arrayCargo: []
    };
},

methods: {
    listarRegistro(page, buscar, criterio) {
        let me = this;
        axios.get('/personal', {
            params: {
                page:page,
                buscar: buscar,
                criterio: criterio
            }
        }).then(function(response) {
            var respuesta = response.data;
            me.arrayPersonal = respuesta.personal.data;
            me.pagination = respuesta.pagination;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    cambiarPagina(page, buscar, criterio) {
      let me = this;
      me.pagination.current_page = page;
      me.listarRegistro(page, buscar, criterio);
    },

    selectCargo() {
      let me = this;
      axios
        .get("/cargo/selectCargo")
        .then(function(response) {
          var respuesta = response.data;
          me.arrayCargo = respuesta.cargo;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    registrar() {
        let me = this;
        axios
        .post("/personal/registrar", {
          apellidos: this.apellidos,
          nombres: this.nombres,
          dni: this.dni,
          fechaNacimiento: this.fechaNacimiento,
          distrito_id: this.distrito_id,
          direccion: this.direccion,
          telefono: this.telefono,
          correo: this.correo,
          fechaIngreso: this.fechaIngreso,
          cargo_id: this.cargo_id,
          grupo: this.grupo,
          fechaCese: this.fechaCese,
          observacion: this.observacion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(1, "", "");
          swal("Grabado!", "Registro guardado con éxito!", "success", {button: false,timer: 1000});
        })
        .catch(error=> {
            if(error.response.status==422){
                this.errorsCampo=error.response.data.errors;
            }
        });
    },

    actualizar() {
        let me = this;
        this.errorsCampo=[];
        let paginaActual=this.isActived;
      axios
        .put("/personal/actualizar", {
          id: this.personal_id,
          apellidos: this.apellidos,
          nombres: this.nombres,
          dni: this.dni,
          fechaNacimiento: this.fechaNacimiento,
          distrito_id: this.distrito_id,
          direccion: this.direccion,
          telefono: this.telefono,
          correo: this.correo,
          fechaIngreso: this.fechaIngreso,
          cargo_id: this.cargo_id,
          grupo: this.grupo,
          fechaCese: this.fechaCese,
          observacion: this.observacion
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(paginaActual, "", "");
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
                axios.put("/personal/desactivar", { id: id }).then(function(response) {
                    console.log(response.data.id)
                    me.listarRegistro(paginaActual, "", "");
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
            axios.put("/personal/activar", { id: id }).then(function(response) {
                console.log(response.data.id)
            me.listarRegistro(paginaActual, "", "");
            });
        } else {
            swal.close();
        }
        });
    },

    cerrarModal() {
      $("#muestraModal").modal("hide");
      this.tituloModal = "";
      this.personal_id = null;
      this.apellidos = "";
      this.nombres = "";
      this.dni = "";
      this.fechaNacimiento = null;
      this.direccion = "";
      this.telefono = "";
      this.correo = "";
      this.fechaIngreso = null;
      this.cargo_id = 0;
      this.fechaCese = null;
      this.observacion = "";
      this.errorsCampo = [];
      this.arrayCargo = 0;
    },

    abrirModal(accion, data = []) {
      this.selectCargo();

      switch (accion) {
        case "registrar": {
          this.tituloModal = "REGISTRAR PERSONAL";
          this.apellidos = "";
          this.nombres = "";
          this.dni = "";
          this.fechaNacimiento = null;
          this.direccion = "";
          this.telefono = "";
          this.correo = "";
          this.fechaIngreso = null;
          this.cargo_id = 0;
          this.grupo = '';
          this.fechaCese = null;
          this.observacion = "";
          this.tipoAccion = 1;
          break;
        }
        case "actualizar": {
          this.tituloModal = "ACTUALIZAR PERSONAL";
          this.tipoAccion = 2;
          this.personal_id = data["id"];
          this.apellidos = data["apellidos"];
          this.nombres = data["nombres"];
          this.dni = data["dni"];
          this.fechaNacimiento = data["fechaNacimiento"];
          this.direccion = data["direccion"];
          this.telefono = data["telefono"];
          this.correo = data["correo"];
          this.fechaIngreso = data["fechaIngreso"];
          this.cargo_id = data["cargo_id"];
          this.grupo = data["grupo"];
          this.fechaCese = data["fechaCese"];
          this.observacion = data["observacion"];
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
    this.listarRegistro(1, "", "");
  }
};
</script>
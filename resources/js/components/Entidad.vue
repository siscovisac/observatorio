<template>
    <main class="main">
    <div class="card">
        <div class="card-header text-center bg-primary">
            <strong>MANTENIMIENTO - ENTIDADES/ÁREAS</strong>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                    <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                        <i class="fa fa-file"></i> &nbsp;Nuevo
                    </button> 
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <label for="">Busqueda por nombre: </label> &nbsp;
                        <input type="text" v-model="buscar" @keyup.enter="listarRegistro(buscar)" class="form-control" placeholder="Escriba nombre de dependencia">
                        <button type="submit" @click="listarRegistro(buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">Acción</th>
                        <th class="text-center">Entidad</th>
                        <th class="text-center">Descripción</th>
                        <th class="text-center">Direccion</th>
                        <th class="text-center">Origen</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entidad in ArrayEntidad" :key="entidad.id">
                        <td class="text-center">
                            <button type="button" @click="abrirModal('actualizar',entidad)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <template v-if="entidad.estado">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(entidad.id)">
                                    <i class="fa fa-check"> A</i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(entidad.id)">
                                    <i class="fa fa-trash-alt"> D</i>
                                </button>
                            </template>
                        </td>
                        <td v-text="entidad.nombre"></td>
                        <td v-text="entidad.descripcion"></td>
                        <td v-text="entidad.direccion"></td>
                        <td v-text="entidad.origen"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
        
    <div class="modal fade" id="muestraModal" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong v-text="tituloModal"></strong>
                    <button type="button" class=" btn btn-primary close text-white" @click="cerrarModal()">
                        <i class="fa fa-window-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Entidad o Dependencia:</label>
                                <input type="text" v-model="nombre" class="form-control focusNext" tabindex="1" maxlength="50" placeholder="Nombre" autofocus>
                                <span v-if="errorsCampo.nombre" class="text-danger error">{{ errorsCampo.nombre[0] }}</span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="descripcion">Descripción:</label>
                                <textarea type="text" rows="3" v-model="descripcion" class="form-control focusNext" tabindex="2" maxlength="250" placeholder="Descripción de entidad o dependencia"></textarea>
                                <span v-if="errorsCampo.descripcion" class="text-danger error">{{ errorsCampo.descripcion[0] }}</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7">
                                <label for="descripcion">Dirección:</label>
                                <textarea type="text" rows="3" v-model="direccion" class="form-control focusNext" tabindex="3" maxlength="250" placeholder="Dirección de entidad o dependencia"></textarea>
                                <span v-if="errorsCampo.direccion" class="text-danger error">{{ errorsCampo.direccion[0] }}</span>
                            </div>
                            
                            <div class="form-group col-md-5">
                                <label for="incidente">Origen (*):</label>
                                <select class="form-control focusNext" tabindex="4" v-model="origen">
                                    <option value="" disabled>-- Seleccione --</option>
                                    <option value="INTERNO">INTERNO</option>
                                    <option value="EXTERNO">EXTERNO</option>
                                </select>
                                <span v-if="errorsCampo.origen" class="text-danger error">{{ errorsCampo.origen[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" v-if="tipoAction==1" class="btn btn-primary focusNext" tabindex="5" @click="registrar()">
                        <i class="fa fa-save"></i> Grabar</button>
                    <button type="button" v-if="tipoAction==2" class="btn btn-primary focusNext" tabindex="5" @click="actualizar()">
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
      entidad_id: 0,
      nombre: "",
      descripcion: "",
      direccion: "",
      origen: "",
      ArrayEntidad: [],
      tituloModal: "",
      tipoAction: 0,
      buscar : '',
      errorsCampo: []
    };
  },

  methods: {
    listarRegistro(buscar) {
      let me = this;
      var url= '/entidad?buscar=' + buscar;
      axios
        .get(url)
        .then(function(response) {
          me.ArrayEntidad = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    },

    registrar() {
      let me = this;
      axios
        .post("/entidad/registrar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          direccion: this.direccion,
          origen: this.origen
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(this.buscar);
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
      axios
        .put("/entidad/actualizar", {
          nombre: this.nombre,
          descripcion: this.descripcion,
          direccion: this.direccion,
          origen: this.origen,
          id: this.entidad_id
        })
        .then(function(response) {
          me.cerrarModal();
          me.listarRegistro(this.buscar);
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
                axios.put("/entidad/desactivar", { id: id }).then(function(response) {
                    me.listarRegistro(this.buscar);
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
            axios.put("/entidad/activar", { id: id }).then(function(response) {
            me.listarRegistro(this.buscar);
            });
        } else {
            swal.close();
        }
        });
    },

    abrirModal(accion, data = []) {
      switch (accion) {
        case "registrar": {
            this.tituloModal = "REGISTRAR ENTIDAD";
            this.nombre = "";
            this.descripcion = "";
            this.direccion = "";
            this.origen = "";
            this.tipoAction = 1;
            break;
        }
        case "actualizar": {
            this.tituloModal = "ACTUALIZAR ENTIDAD";
            this.tipoAction = 2;
            this.entidad_id = data["id"];
            this.nombre = data["nombre"];
            this.descripcion = data["descripcion"];
            this.direccion = data["direccion"];
            this.origen = data["origen"];
            break;
        }
      }
    },

    cerrarModal() {
        $("#muestraModal").modal("hide");
        this.modal = 0;
        this.tituloModal = "";
        this.nombre = "";
        this.descripcion = "";
        this.direccion = "";
        this.origen = "";
        this.errorsCampo=[];
    }
  },

  mounted() {
    this.listarRegistro(this.buscar);
  }
};
</script>
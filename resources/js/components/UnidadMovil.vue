<template>
    <main class="main">
    <div class="card">
        <div class="card-header text-center bg-primary">
            <strong>MANTENIMIENTO - UNIDAD MOVIL O PATRULLERO</strong>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-3">
                    <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                        <i class="fa fa-file"></i> &nbsp;Nuevo
                    </button>
                </div>
            </div>
            <table class="table table-bordered table-striped table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th class="text-center" colspan="2">Acción</th>
                        <th class="text-center">Unidad Movil</th>
                        <th class="text-center">Placa</th>
                        <th class="text-center">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="unidadMovil in arrayUnidadMovil" :key="unidadMovil.id">
                        <td class="text-center">
                            <button type="button" @click="abrirModal('actualizar',unidadMovil)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <template v-if="unidadMovil.estado">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(unidadMovil.id)">
                                    <i class="fa fa-check"> A</i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(unidadMovil.id)">
                                    <i class="fa fa-trash-alt"> D</i>
                                </button>
                            </template>
                        </td>
                        <td v-text="unidadMovil.nombre"></td>
                        <td v-text="unidadMovil.placa"></td>
                        <td v-text="unidadMovil.descripcion"></td>
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
                            <div class="form-group col-md-8">
                                <label for="nombre">Unidad Movil:</label>
                                <input type="text" v-model="nombre" class="form-control focusNext" tabindex="1" maxlength="45" placeholder="Nombre del vehículo" autofocus>
                                <span v-if="errorsCampo.nombre" class="text-danger error">{{ errorsCampo.nombre[0] }}</span>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="placa">Placa del Vehículo:</label>
                                <input type="text" v-model="placa" class="form-control focusNext" tabindex="2" maxlength="7" placeholder="Placa">
                                <span v-if="errorsCampo.placa" class="text-danger error">{{ errorsCampo.placa[0] }}</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="nombre">Descripcion:</label>
                                <textarea type="text" v-model="descripcion" row="2" class="form-control focusNext" tabindex="3" maxlength="145" placeholder="Descripción del vehículo"></textarea>
                                <span v-if="errorsCampo.descripcion" class="text-danger error">{{ errorsCampo.descripcion[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" v-if="tipoAction==1" class="btn btn-primary focusNext" tabindex="4" @click="registrar()">
                        <i class="fa fa-save"></i> Grabar</button>
                    <button type="button" v-if="tipoAction==2" class="btn btn-primary focusNext" tabindex="4" @click="actualizar()">
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
        unidadMovil_id: 0,
        nombre: "",
        placa: "",
        descripcion: "",
        arrayUnidadMovil: [],
        tituloModal: "",
        tipoAction: 0,
        errorsCampo: []
        };
    },

    methods: {
        listarRegistro() {
        let me = this;
        axios.get("/unidadMovil").then(function(response) {
            var respuesta = response.data;
            me.arrayUnidadMovil = respuesta;
            }).catch(function(error) {
            console.log(error);
            });
        },

        registrar() {
        let me = this;
        axios.post("/unidadMovil/registrar", {
            placa: this.placa,
            nombre: this.nombre,
            descripcion: this.descripcion
        }).then(function(response) {
            me.cerrarModal();
            me.listarRegistro();
            swal("Grabado!", "Registro guardado con éxito!", "success", {button: false,timer: 1000});
        }).catch(error=> {
                if(error.response.status==422){
                    this.errorsCampo=error.response.data.errors;
                }
            });
        },

        actualizar() {
        let me = this;
        axios
            .put("/unidadMovil/actualizar", {
            nombre: this.nombre,
            placa: this.placa,
            descripcion: this.descripcion,
            id: this.unidadMovil_id
            })
            .then(function(response) {
            me.cerrarModal();
            me.listarRegistro();
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
                    axios.put("/unidadMovil/desactivar", { id: id }).then(function(response) {
                        me.listarRegistro();
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
                axios.put("/unidadMovil/activar", { id: id }).then(function(response) {
                me.listarRegistro();
                });
            } else {
                swal.close();
            }
            });
        },

        abrirModal(accion, data = []) {
        switch (accion) {
            case "registrar": {
                this.tituloModal = "REGISTRAR UNIDAD MOVIL";
                this.nombre = "";
                this.placa = "";
                this.descripcion = "";
                this.tipoAction = 1;
                break;
            }
            case "actualizar": {
                this.tituloModal = "ACTUALIZAR UNIDAD MOVIL";
                this.tipoAction = 2;
                this.unidadMovil_id = data["id"];
                this.placa = data["placa"];
                this.nombre = data["nombre"];
                this.descripcion = data["descripcion"];
                break;
            }
        }
        },

        cerrarModal() {
            $("#muestraModal").modal("hide");
            this.modal = 0;
            this.tituloModal = "";
            this.placa = "";
            this.nombre = "";
            this.descripcion = "";
            this.errorsCampo=[];
        }
    },

  mounted() {
    this.listarRegistro();
  }
};
</script>
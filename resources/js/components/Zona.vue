<template>
    <main class="main">
    <div class="card">
        <div class="card-header text-center bg-primary">
            <strong>MANTENIMIENTO - ZONA O CONOS</strong>
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
                        <th class="text-center">Zona</th>
                        <th class="text-center">Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="zona in arrayZona" :key="zona.id">
                        <td class="text-center">
                            <button type="button" @click="abrirModal('actualizar',zona)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                <i class="fa fa-pencil-alt"></i>
                            </button>
                        </td>
                        <td class="text-center">
                            <template v-if="zona.estado">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(zona.id)">
                                    <i class="fa fa-check"> A</i>
                                </button>
                            </template>
                            <template v-else>
                                <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(zona.id)">
                                    <i class="fa fa-trash-alt"> D</i>
                                </button>
                            </template>
                        </td>
                        <td v-text="zona.nombre"></td>
                        <td v-text="zona.referencia"></td>
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
                            <div class="form-group col-md-4">
                                <label for="nombre">Zonas:</label>
                                <input type="text" v-model="nombre" class="form-control focusNext" tabindex="1" maxlength="50" placeholder="Nombre de zona" autofocus>
                                <span v-if="errorsCampo.nombre" class="text-danger error">{{ errorsCampo.nombre[0] }}</span>
                            </div>
                            <div class="form-group col-md-8">
                                <label for="referencia">Referencia:</label>
                                <textarea type="text" rows="3" v-model="referencia" class="form-control focusNext" tabindex="2" maxlength="145" placeholder="Referencia"></textarea>
                                <span v-if="errorsCampo.referencia" class="text-danger error">{{ errorsCampo.referencia[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" v-if="tipoAction==1" class="btn btn-primary focusNext" tabindex="3" @click="registrar()">
                        <i class="fa fa-save"></i> Grabar</button>
                    <button type="button" v-if="tipoAction==2" class="btn btn-primary focusNext" tabindex="3" @click="actualizar()">
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
        zona_id: 0,
        nombre: "",
        referencia: "",
        arrayZona: [],
        tituloModal: "",
        tipoAction: 0,
        errorsCampo: []
        };
    },

    methods: {
        listarRegistro() {
        let me = this;
        axios
            .get("/zona")
            .then(function(response) {
            var respuesta = response.data;
            me.arrayZona = respuesta;
            })
            .catch(function(error) {
            console.log(error);
            });
        },

        registrar() {
        let me = this;
        axios
            .post("/zona/registrar", {
            nombre: this.nombre,
            referencia: this.referencia
            })
            .then(function(response) {
            me.cerrarModal();
            me.listarRegistro();
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
        axios.put("/zona/actualizar", {
            nombre: this.nombre,
            referencia: this.referencia,
            id: this.zona_id
        }).then(function(response) {
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
                    axios.put("/zona/desactivar", { id: id }).then(function(response) {
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
                axios.put("/zona/activar", { id: id }).then(function(response) {
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
                this.tituloModal = "REGISTRAR ZONA";
                this.nombre = "";
                this.referencia = "";
                this.tipoAction = 1;
                break;
            }
            case "actualizar": {
                this.tituloModal = "ACTUALIZAR ZONA";
                this.tipoAction = 2;
                this.zona_id = data["id"];
                this.nombre = data["nombre"];
                this.referencia = data["referencia"];
                break;
            }
        }
        },

        cerrarModal() {
            $("#muestraModal").modal("hide");
            this.modal = 0;
            this.tituloModal = "";
            this.nombre = "";
            this.referencia = "";
            this.errorsCampo=[];
        }
    },

    mounted() {
        this.listarRegistro();
    }
};
</script>
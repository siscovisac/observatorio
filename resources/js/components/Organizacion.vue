<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <strong>DATOS DE LA ORGANIZACIÓN</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th>Modificación</th>
                            <th>Nombre de Organización</th>
                            <th>Logotipo</th>
                            <th>Encabezado de Página</th>
                            <th>Pie de Página</th>
                            <th>Refresca</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="organizacion in arrayOrganizacion" :key="organizacion.id">
                            <td class="text-center">
                                <button type="button" @click="abrirModal('actualizar',organizacion)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <div>
                                    <label for="" v-text="organizacion.updated_at"></label>
                                </div>
                            </td>
                            <td v-text="organizacion.nombre"></td>
                            <td>
                                <img :src="organizacion.logo" class="rounded mx-auto" width="200px">
                            </td>
                            <td v-text="organizacion.encabezadoPagina"></td>
                            <td v-text="organizacion.piePagina"></td>
                            <td>
                                <button type="button" class="btn btn-outline-warning" @click="listarRegistro()"><i class="fa fa-sync"></i> Refrescar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="alert alert-danger" role="alert">
                    <strong>IMPORTANTE!</strong> Después de modificar datos de la organización. <a href="#" class="alert-link" @click="listarRegistro()"> Refresque</a>. Enseguida <strong>Cerrar Sesión</strong> e ingrese nuevamente al Sistema.
                    <br>
                    <strong>TAMAÑO DEL LOGOTIPO: </strong> debe ser tipo: jpg tamaño: 250Kb <strong>Ejemplo: </strong> Alto: 100px Ancho 250px (Ancho 2.5x mas.)
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="muestraModal" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal-title" v-text="tituloModal"></p>
                        <button type="button" class=" btn btn-primary close text-white" @click="cerrarModal()">
                            <i class="fa fa-window-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="put" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label class="form-control-label" for="nombre">Organización (*): </label>
                                </div>
                                <div class="form-group col-sm-8">
                                    <input type="text" v-model="nombre" class="form-control focusNext" tabindex="1" maxlength="145" placeholder="Nombre la Organización">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class=" form-control-label" for="meta">Encabezado de Página:</label>
                                </div>
                                <div class="form-group col-md-8">
                                    <input type="text" v-model="encabezadoPagina" class="form-control focusNext" tabindex="2" maxlength="145" placeholder="Escriba para el encabezado: eslogan, lema, u otro">        
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label class=" form-control-label" for="meta">Pie de Página:</label>
                                </div>
                                <div class="form-group col-md-8">
                                    <input type="text" v-model="piePagina" class="form-control focusNext" tabindex="3" maxlength="145" placeholder="Escriba para el pie: teléfono, dirección u otro">        
                                </div>
                            </div>
                            <div class="text-center">
                                <vue-dropzone ref="myVueDropzone" id="dropzone" 
                                :options="dropzoneOptions" v-on:vdropzone-sending="recogerDatos">
                                </vue-dropzone>
                                <div class="alert alert-danger" role="alert" v-show="mensajeErrorFoto">
                                    <li v-for="error in muestraMsgErrorFoto" :key="error" v-text="error"></li>
                                </div>
                            </div>
                            <div class="alert alert-danger" role="alert" v-show="mensajeError">
                                <strong>Error!</strong>
                                <div v-for="error in muestraMsgError" :key="error" v-text="error"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary focusNext" v-if="tipoAccion==1" tabindex="4" @click="actualizar()">
                            <i class="fa fa-pencil-alt"></i> Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    export default {
        components: {
            vueDropzone: vue2Dropzone
        },
        data (){
            return {
                organizacion_id: 0,
                nombre:'',
                encabezadoPagina:'',
                piePagina:'',
                logo : null,
                tituloModal : '',
                tipoAccion:0,
                arrayOrganizacion : [],
                mensajeError : 0,
                muestraMsgError : [],
                mensajeErrorFoto:0,
                fotoCargado:null,
                muestraMsgErrorFoto:[],
                dropzoneOptions: {
                    url: '/subirLogo',
                    autoProcessQueue: false,
                    maxFilesize: 0.25,
                    maxFiles: 1,
                    acceptedFiles:'image/*',
                    thumbnailWidth: 150,
                    thumbnailHeight: 150,
                    addRemoveLinks: true,
                    dictRemoveFile:'Cancelar',
                    dictMaxFilesExceeded: '1 Foto como Máximo. Cancele',
                    dictFileTooBig: 'Tamaño supera a 250 Kb. Cancele',
                    dictDefaultMessage: "<i class='fa fa-image fa-2x'></i> Tamaño de foto Máximo 250 Kb",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    }
                }
            }
        },

        methods : {
            listarRegistro(){
                let me=this;
                axios.get("/organizacion").then(function (response) {
                    me.arrayOrganizacion = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
          
            recogerDatos (file, xhr, formData) {
                formData.append('id',this.organizacion_id),
                formData.append('nombre',this.nombre),
                formData.append('encabezadoPagina',this.encabezadoPagina),
                formData.append('piePagina',this.piePagina),
                formData.append('logoAnterior',this.logo)
            },

            actualizar(){
                if (this.validarCampos()){
                    return;
                }
                if (this.ValidarEntradaImagen()){
                    return
                }
                this.$refs.myVueDropzone.processQueue();
                this.cerrarModal();
                swal("Grabado!", "Registro guardado con Éxito!", "success", {button: false,timer: 1000});
            },
            
            cerrarModal(){
                $("#muestraModal").modal("hide");
                this.tituloModal='';
                this.nombre = '';
                this.encabezadoPagina= '';
                this.piePagina= '';
                this.logo= null;
                this.mensajeError=0;
                this.muestraMsgError=[];
            },

            validarCampos(){
                this.mensajeError=0;
                this.muestraMsgError =[];
                if (!this.nombre) this.muestraMsgError.push("El nombre de entidad está vacío.");
                if (this.muestraMsgError.length) this.mensajeError = 1;
                return this.mensajeError;
            },

            ValidarEntradaImagen(){
                this.mensajeErrorFoto=0;
                this.muestraMsgErrorFoto =[];
                this.fotoCargado=this.$refs.myVueDropzone.getAcceptedFiles();
                if (this.fotoCargado=='') this.muestraMsgErrorFoto.push("CANCELE y CARGUE el Logotipo, para actualizar los datos");
                if (this.muestraMsgErrorFoto.length) this.mensajeErrorFoto = 1;
                return this.mensajeErrorFoto;
            },

            abrirModal(accion, data = []){
                this.$refs.myVueDropzone.removeAllFiles();
                switch (accion) {
                    case "actualizar": {    
                        this.tipoAccion=1;
                        this.tituloModal='MODIFICAR DATOS DE ORGANIZACIÓN';
                        this.organizacion_id=data['id'];
                        this.nombre=data['nombre'];
                        this.encabezadoPagina= data['encabezadoPagina'];
                        this.piePagina= data['piePagina'];
                        this.logo=data['logo'];
                        if (data['logo']==null) {
                        }
                        else{
                            var file = { size: 50, name: "logo",type:"image/jpg" };
                            this.$refs.myVueDropzone.manuallyAddFile(file, data['logo']);
                        }
                        break;
                    }
                }
            }
    },
    mounted() {
        this.listarRegistro();
    }
}
</script>
<style>
    .dropzone {
        border: 2px dashed #0087F7;
        background: white;
        border-radius: 5px;
        color: #0087F7;
        font-size: 1.5em;
    }
</style>
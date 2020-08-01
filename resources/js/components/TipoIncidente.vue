<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <strong>MANTENIMIENTO - SUB TIPO DE INCIDENCIAS</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                            <i class="fa fa-file"></i> &nbsp;Nuevo
                        </button>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group">
                            <label for="">Busqueda por nombre: </label> &nbsp;
                            <input type="text" v-model="buscar" @keyup.enter="listarRegistro(1,buscar,criterio)" class="form-control" placeholder="Escriba un sub tipo de incidencia">
                            <button type="submit" @click="listarRegistro(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <button type="button" class="btn btn-outline-warning" @click="generarPDF()"><i class="fa fa-file-pdf"></i> Reporte PDF</button>
                        <button type="button" class="btn btn-outline-success" @click="descargarExcel()"><i class="fa fa-file-excel"></i> Descargar Excel</button>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Acción</th>
                            <th class="text-center">Incidencia</th>
                            <th class="text-center">Sub Tipo de Incidencia</th>
                            <th class="text-center">Ordenanza Municipal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="tipoIncidente in arrayTipoIncidente" :key="tipoIncidente.id">
                            <td class="text-center">
                                <button type="button" @click="abrirModal('actualizar',tipoIncidente)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <template v-if="tipoIncidente.estado">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(tipoIncidente.id)">
                                        <i class="fa fa-check"> A</i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(tipoIncidente.id)">
                                        <i class="fa fa-trash-alt"> D</i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="tipoIncidente.incidente_nombre"></td>
                            <td v-text="tipoIncidente.nombre"></td>
                            <td v-text="tipoIncidente.ordena"></td>
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
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label for="incidente">Tipo de Incidencia (*):</label>
                                    <select class="form-control focusNext" tabindex="1" v-model="incidente_id">
                                        <option value="0" disabled>-- Seleccione --</option>
                                        <option v-for="incidente in arrayIncidente" :key="incidente.id" :value="incidente.id" v-text="incidente.nombre"></option>
                                    </select>
                                    <span v-if="errorsCampo.incidente_id" class="text-danger error">{{ errorsCampo.incidente_id[0] }}</span>                               
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="nombre">Sub Tipo de Incidencia (*):</label>
                                    <input type="text" v-model="nombre" class="form-control focusNext" tabindex="2" placeholder="Describe una tipoIncidente" autofocus>
                                    <span v-if="errorsCampo.nombre" class="text-danger error">{{ errorsCampo.nombre[0] }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="nombre">Descripción:</label>
                                    <textarea name="" rows="2" class="form-control" v-model="descripcion"></textarea>
                                    <span v-if="errorsCampo.descripcion" class="text-danger error">{{ errorsCampo.descripcion[0] }}</span>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="unidadMedida">Ordenanza Municipal:</label>
                                    <textarea name=""  rows="2" class="form-control" v-model="ordenanza"></textarea>
                                    <span v-if="errorsCampo.ordenanza" class="text-danger error">{{ errorsCampo.ordenanza[0] }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary focusNext" tabindex="7" @click="registrar()">
                            <i class="fa fa-save"></i> Grabar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary focusNext" tabindex="7" @click="actualizar()">
                            <i class="fa fa-pencil-alt"></i> Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data (){
            return {
                tipoIncidente_id: 0,
                incidente_id: 0,
                nombre : '',
                descripcion : '',
                ordenanza : '',
                tituloModal : '',
                tipoAccion : 0,                
                errorsCampo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                arrayTipoIncidente : [],
                arrayIncidente : [],
            }
        },

        computed:{
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function() {
                if(!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset; 
                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2); 
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }  
                var pagesArray = [];
                while(from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },

        methods : {
            listarRegistro (page,buscar,criterio){
                let me=this;
                var url= '/tipoIncidente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTipoIncidente = respuesta.tipoIncidente.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectIncidente(){
                let me=this;
                axios.get('/selectIncidente').then(function (response) {
                    var respuesta= response.data;
                    me.arrayIncidente = respuesta.incidente;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },


            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarRegistro(page,buscar,criterio);
            },

            registrar(){
                let me = this;
                this.errorsCampo=[];
                axios.post('/tipoIncidente/registrar',{
                    'incidente_id': this.incidente_id,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'ordenanza':this.ordenanza,
                }).then(function (response) {
                    me.listarRegistro(1,'','nombre');
                    me.cerrarModal();
                    swal("Grabado!", "Registro guardado con éxito!", "success", {button: false,timer: 1000});
                }).catch(error=> {
                    if(error.response.status==422){
                        this.errorsCampo=error.response.data.errors;
                    }
                });
            },

            actualizar(){
                var me=this;
                this.errorsCampo=[];
                let paginaActual=this.isActived;
                axios.put('/tipoIncidente/actualizar',{
                    'id': this.tipoIncidente_id,
                    'incidente_id': this.incidente_id,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'ordenanza':this.ordenanza
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarRegistro(paginaActual,"","nombre");
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
                        axios.put("/tipoIncidente/desactivar", { id: id }).then(function(response) {
                            me.listarRegistro(paginaActual, "", "nombre");
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
                    axios.put("/tipoIncidente/activar", { id: id }).then(function(response) {
                    me.listarRegistro(paginaActual, "", "nombre");
                    });
                } else {
                    swal.close();
                }
                });
            },

            cerrarModal(){
                $("#muestraModal").modal("hide");
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
                this.ordenanza='';
                this.tipoIncidente_id= 0;
                this.errorsCampo=[];
                this.buscar='';
                this.criterio='';
            },

            abrirModal(accion, data = []){
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'REGISTRAR TIPO DE INCIDENCIA';
                        this.nombre='';
                        this.descripcion='';
                        this.ordenanza='';
                        this.incidente_id= 0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        this.tituloModal='ACTUALIZAR TIPO DE INCIDENCIA';
                        this.tipoAccion=2;
                        this.tipoIncidente_id=data['id'];
                        this.nombre=data['nombre'];
                        this.descripcion=data['descripcion'];
                        this.ordenanza=data['ordenanza'];
                        this.incidente_id= data['incidente_id'];
                        break;
                    }
                }
                this.selectIncidente();
            },

            generarPDF(){
                window.open('/tipoIncidentePDF','_blank');
            },
            
            descargarExcel(){
                window.open('/tipoIncidenteExcel','_blank');
            }

        },
        mounted() {
            this.listarRegistro(1,this.buscar,this.criterio);
        }
    }
</script>
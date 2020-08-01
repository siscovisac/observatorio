<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <strong>MANTENIMIENTO - UBICACIONES</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                            <i class="fa fa-file"></i> &nbsp;Nuevo
                        </button>
                    </div>
                    <div class="col-md-7">
                        <div class="input-group">
                            <label for="">Busqueda por nombre: </label> &nbsp;
                            <input type="text" v-model="buscar" @keyup.enter="listarRegistro(1,buscar,criterio)" class="form-control" placeholder="Escriba un lugar">
                            <button type="submit" @click="listarRegistro(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th class="text-center" colspan="2">Acción</th>
                            <th class="text-center">Zona</th>
                            <th class="text-center">Lugares</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ubicacion in arrayUbicacion" :key="ubicacion.id">
                            <td class="text-center">
                                <button type="button" @click="abrirModal('actualizar',ubicacion)" class="btn btn-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                <template v-if="ubicacion.estado">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(ubicacion.id)">
                                        <i class="fa fa-check"> A</i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(ubicacion.id)">
                                        <i class="fa fa-trash-alt"> D</i>
                                    </button>
                                </template>
                            </td>
                            <td v-text="ubicacion.zona_nombre"></td>
                            <td v-text="ubicacion.nombre"></td>
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
                                <div class="form-group col-lg-4">
                                    <label for="zona">ZONA (*):</label>
                                    <select class="form-control focusNext" tabindex="1" v-model="zona_id">
                                        <option value="0" disabled>-- Seleccione --</option>
                                        <option v-for="zona in arrayZona" :key="zona.id" :value="zona.id" v-text="zona.nombre"></option>
                                    </select>
                                    <span v-if="errorsCampo.zona_id" class="text-danger error">{{ errorsCampo.zona_id[0] }}</span>                               
                                </div>
                                <div class="form-group col-lg-8">
                                    <label for="nombre">NOMBRE DEL LUGAR (*):</label>
                                    <input type="text" v-model="nombre" class="form-control focusNext" tabindex="2" placeholder="Describe ubicacion, calle..." autofocus>
                                    <span v-if="errorsCampo.nombre" class="text-danger error">{{ errorsCampo.nombre[0] }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary focusNext" tabindex="3" @click="registrar()">
                            <i class="fa fa-save"></i> Grabar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary focusNext" tabindex="3" @click="actualizar()">
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
                ubicacion_id: 0,
                zona_id: 0,
                nombre : '',
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
                arrayUbicacion : [],
                arrayZona : [],
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
                var url= '/ubicacion?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUbicacion = respuesta.ubicacion.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectZona(){
                let me=this;
                axios.get('/selectZona').then(function (response) {
                    var respuesta= response.data;
                    me.arrayZona = respuesta.zona;
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
                axios.post('/ubicacion/registrar',{
                    'zona_id': this.zona_id,
                    'nombre': this.nombre
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
                axios.put('/ubicacion/actualizar',{
                    'id': this.ubicacion_id,
                    'zona_id': this.zona_id,
                    'nombre': this.nombre
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
                        axios.put("/ubicacion/desactivar", { id: id }).then(function(response) {
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
                    axios.put("/ubicacion/activar", { id: id }).then(function(response) {
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
                this.ubicacion_id= 0;
                this.zona_id= 0;
                this.errorsCampo=[];
                this.buscar='';
                // this.criterio='';
            },

            abrirModal(accion, data = []){
                switch(accion){
                    case 'registrar':
                    {
                        this.tituloModal = 'REGISTRAR LUGARES';
                        this.nombre='';
                        this.descripcion='';
                        this.zona_id= 0;
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        this.tituloModal='ACTUALIZAR LUGARES';
                        this.tipoAccion=2;
                        this.ubicacion_id=data['id'];
                        this.nombre=data['nombre'];
                        this.zona_id= data['zona_id'];
                        break;
                    }
                }
                this.selectZona();
            }
        },
        mounted() {
            this.listarRegistro(1,this.buscar,this.criterio);
        }
    }
</script>
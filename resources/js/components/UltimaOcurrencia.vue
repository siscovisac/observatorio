<template>
    <main class="main">
        <template v-if="verDetalle">
            <div class="card">
                <div class="card-header text-center">
                    <strong>ÚLTIMAS OCURRENCIAS</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-2">
                            <span class="text-primary"> Campos obligatorio (*)</span>
                        </div>
                        <div class="col-md-8">
                            <div class="form-row">
                                <div class="input-group col-md-6">
                                    <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha">    
                                    <input class="form-control" type="date" title="Buscar hasta Fecha" v-model="hastaFecha">
                                </div>
                                <div class="input-group col-md-6">
                                    <select class="form-control" v-model="buscar" @change="listarRegistro(1,criterio,buscar)">
                                        <option value="">-- Seleccione Incidencia --</option>
                                        <option v-for="incidente in arrayIncidente" :key="incidente.id" :value="incidente.id" v-text="incidente.nombre"></option>
                                    </select>
                                    <!-- <button type="submit" @click="listarRegistro(1,criterio,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>  -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped table-sm table-responsive-md" style="border-color:white;">
                        <thead>
                            <tr>
                                <th class="text-center">CASO N°</th>
                                <th class="text-center" colspan="3">OCURRENCIA</th>
                                <th class="text-center">ZONA Y LUGAR</th>
                                <th class="text-center">INTERVIENE</th>
                                <th class="text-center">USUARIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="registroOcurrencia in arrayRegistroOcurrencia" :key="registroOcurrencia.id">
                                <td v-text="registroOcurrencia.id +'-'+registroOcurrencia.fecha">
                                </td>
                                
                                <td>{{ formatoFecha(registroOcurrencia.fecha+' '+registroOcurrencia.hora) }}</td>
                                <td>
                                    <template v-if="registroOcurrencia.inicio==='PATRULLAJE'">
                                        <i class="fa fa-taxi"></i>
                                    </template>
                                    <template v-else-if="registroOcurrencia.inicio==='VIDEO VIGILANCIA'">
                                        <i class="fa fa-video"></i>
                                    </template>
                                    <template v-else-if="registroOcurrencia.inicio==='LLAMADA'">
                                        <i class="fa fa-phone"></i>
                                    </template>
                                    <template v-else>
                                        <i class="fa fa-comment-dots"></i>    
                                    </template>
                                </td>
                                <td>
                                    <div class="input-group">
                                        {{registroOcurrencia.incidente_nombre}}
                                    </div>
                                </td>
                                <td>{{ registroOcurrencia.zona_nombre +" - "+registroOcurrencia.ubicacion_nombre }}</td>
                                <td>
                                    <div class="input-group">
                                        {{ registroOcurrencia.unidadMovil_nombre }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <template v-if="registroOcurrencia.fin">
                                        <a href="#" @click="mostrarDetalleOcurrencia(registroOcurrencia)"><span class="badge badge-primary" >Finalizado</span> 
                                            {{ registroOcurrencia.usuario_nombre }}    
                                        </a> 
                                        
                                    </template>
                                    <template v-else>
                                        <a href="#" @click="mostrarDetalleOcurrencia(registroOcurrencia)"><span class="badge badge-danger" >Pendiente</span>
                                            {{ registroOcurrencia.usuario_nombre }}
                                        </a>
                                    </template>
                                </td>
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
        </template>
        <template v-else>
            <div class="card">
                <div class="card-header text-center">
                    <strong class="modal-title">DETALLE DEL REGISTRO DE OCURRENCIAS</strong>
                    <button type="link" class="btn btn-outline-info btn-sm close" title="Ir al registro" @click="mostrarOcurrencia">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-responsive-md table-sm">
                        <thead class="bg-secondary bg-light">
                            <th style="width: 40%;" colspan="2"><h5>
                                <strong>1. <u>REGISTRO DE OCURRENCIA N° {{registroOcurrencia_id}}-{{formatoFecha(fecha)}}</u></strong></h5></th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>FECHA:</strong> {{ fechaLong(fecha) }} <br> <strong> HORA:</strong> {{ hora }}</td>
                                <td><strong>TIPO DE INCIDENCIA:</strong> {{ incidente_nombre }}
                                    <br>
                                        <strong>SUB TIPO DE INCIDENCIA:</strong> {{ tipoIncidente_nombre }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>ZONA: </strong> {{ zona_nombre }}
                                    <br>
                                    <strong>LUGAR: </strong>{{ ubicacion_nombre }}
                                    <br>
                                    <strong>REFERENCIA: </strong>{{referencia}}
                                </td>
                                <td><strong>OCURRENCIA: </strong> {{ ocurrencia}} </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <strong>INTERVENIDO POR: </strong>&nbsp; {{ unidadMovil_nombre }} &nbsp;
                                        <div v-for="apel in personalServicio" :key="apel.id">
                                            <li>{{ apel.personal_nombre }}</li>
                                        </div>
                                    </div>
                                </td>
                                <td v-html="detalleParteServicio"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="form-control bg-light">
                        <h5><strong>2. <u>PANEL FOTOGRÁFICO</u></strong></h5>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-6" v-for="panelFotografico in arrayPanelFotografico" :key="panelFotografico.id">
                            <img class="card-img-top" :src="'storage/'+panelFotografico.fotoOriginal" alt="">
                            <div class="text-center">{{ panelFotografico.leyenda }}</div>
                            <br/>
                        </div>
                    </div>
                </div> 
            </div>
            
            <div class="card">
                <div class="col-md-12" v-for="parteServicio in arrayParteServicio" :key="parteServicio.id">
                    <img class="card-img-top" :src="'storage/'+parteServicio.parteOriginal" alt="">
                    <div class="form-row">
                        <div class="col-md-6">
                            {{ parteServicio.nombreParte }}
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="link" class="btn btn-outline-info btn-sm" title="Ir al registro" @click="mostrarOcurrencia">
                                <i class="fa fa-times"></i> Volver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </main>
</template>

<script>
    import moment from 'moment'
    moment.locale('es');

    export default {
        data (){
            return {
                registroOcurrencia_id: 0,
                incidente_id:'',
                fecha:'',
                hora:'',
                ocurrencia:'',
                tipoIncidente_nombre:'',
                zona_nombre:'',
                ubicacion_nombre:'',
                unidadMovil_nombre:'',
                ocurrencia:'',
                personalServicio:'',
                detalleParteServicio:'',
                arrayIncidente : [],
                arrayRegistroOcurrencia:[],
                arrayPanelFotografico:[],
                arrayParteServicio:[],
                verDetalle:true,
                tituloModal : '',
                tipoAccion : 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0
                },
                offset : 3,
                criterio : '',
                buscar : '',
                desdeFecha : '',
                hastaFecha : '',
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
            fechaLong:function(d){
                return moment(d).format('dddd, DD MMMM YYYY')
            },
            horaActual(){
                var horaMoment=moment().fromNow()
                // var horaMoment=moment().format('HH:mm')
                this.hora=horaMoment;
            },

            formatoFecha: function(d){
                return moment(d).format('DD-MM-YYYY');
            },

            listarRegistro(page,criterio, buscar){
                let me=this;
                axios.get('/registroOcurrencia/listarUltima',{
                        params: {
                            'page': page,
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha,
                            buscar: buscar
                        }
                    }).then(function (response) {
                    var respuesta= response.data;
                    me.arrayRegistroOcurrencia = respuesta.registroOcurrencia.data;
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

            verDetallePanelFotos(){
                let me=this
                axios.get('/panelFotografico/verPanel',{
                    params:{
                        registroOcurrencia_id:this.registroOcurrencia_id
                    }
                })
                .then(function (response) {
                    me.arrayPanelFotografico=response.data.panelFotografico;
                    me.$refs.myVueDropzone.removeAllFiles();
                })
            },

            
            mostrarDetalleOcurrencia(datos){
                let me=this;
                me.verDetalle=false;
                me.registroOcurrencia_id=datos["id"];
                me.fecha=datos['fecha'],
                me.hora=datos['hora'],
                me.ocurrencia=datos['hora'],
                me.tipoIncidente_nombre=datos['tipoIncidente_nombre'],
                me.incidente_nombre=datos['incidente_nombre'],
                me.referencia=datos['referencia'],
                me.detalleParteServicio=datos['detalleParteServicio'],
                me.zona_nombre=datos['zona_nombre'],
                me.ubicacion_nombre=datos['ubicacion_nombre'],
                me.unidadMovil_nombre=datos['unidadMovil_nombre']
                me.ocurrencia=datos['ocurrencia']
                me.personalServicio=datos['personalServicio']
                axios.get('/registroOcurrencia/listarDetalle',{
                    params:{
                        registroOcurrencia_id:datos['id']
                    }
                })
                .then(function (response) {
                    me.arrayPanelFotografico=response.data.panelFotografico;
                    me.arrayParteServicio=response.data.parteServicio;
                })
                .catch(function (error) {
                })
                
            },
            
            mostrarOcurrencia(){
                this.verDetalle=true;
            },

        },
        mounted() {
            this.listarRegistro(1,this.buscar,this.criterio);
            this.selectIncidente();
        }
    }
</script>
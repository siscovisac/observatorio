<template>
    <main class="main">
    <template v-if="fotografia === 0">
        <div class="card">
            <div class="card-header text-center">
                <strong>REGISTRO DIARIO DE OCURRENCIAS</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-2">
                        <button type="button" @click="abrirModal('registrar')" class="btn btn-primary" data-toggle="modal" data-target="#muestraModal">
                            <i class="fa fa-file"></i> &nbsp;Nuevo
                        </button>
                    </div>
                    <div class="col-md-7">
                        <div class="form-row">
                            <div class="input-group col-md-7">
                                <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha">    
                                <input class="form-control" type="date" title="Buscar hasta Fecha" v-model="hastaFecha">
                            </div>
                            <div class="input-group col-md-5">
                                <input class="form-control" type="text" placeholder="Detalle de búsqueda" v-model="buscar" @keyup.enter="listarRegistro(1,criterio,buscar)">
                                <button type="submit" @click="listarRegistro(1,criterio,buscar)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" @click="descargarExcel()" class="btn btn-outline-success"><i class="fa fa-file-excel"></i> Descargar</button> 
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr>
                            <th class="text-center">N°</th>
                            <th class="text-center" colspan="2">ACCIÓN</th>
                            <th class="text-center">FECHA</th>
                            <th class="text-center" colspan="2">OCURRENCIA</th>
                            <th class="text-center">LUGAR</th>
                            <th class="text-center">UNIDAD_MOVIL/AGENTE(S)</th>
                            <th class="text-center">RESOLUCIÓN</th>
                            <th class="text-center" colspan="2">SUSTENTO</th>
                            <th class="text-center">USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="registroOcurrencia in arrayRegistroOcurrencia" :key="registroOcurrencia.id">
                            <td v-text="registroOcurrencia.id">
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.estado">
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Desactivar" @click="desactivar(registroOcurrencia.id)">
                                        <i class="fa fa-check"> A</i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button type="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Activar" @click="activar(registroOcurrencia.id)">
                                        <i class="fa fa-trash-alt"> D</i>
                                    </button>
                                </template>
                            </td>

                            <td class="text-center">
                                <button type="button" @click="abrirModal('actualizar',registroOcurrencia)" class="btn btn-outline-primary btn-sm" data-toggle="modal" title="Modificar" data-target="#muestraModal">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                            </td>
                            
                            <td width="100px">{{ formatoFecha(registroOcurrencia.fecha) }}
                                <br> {{ registroOcurrencia.hora }}
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.inicio==='PATRULLAJE'">
                                    <i class="fa fa-microphone"></i>
                                </template>
                                <template v-else-if="registroOcurrencia.inicio==='VIDEO VIGILANCIA'">
                                    <i class="fa fa-video"></i>
                                </template>
                                <template v-else>
                                    <i class="fa fa-phone"></i>{{registroOcurrencia.telefono}}  
                                </template>
                            </td>
                            <td>
                                <div class="input-group">
                                    <strong><u>{{registroOcurrencia.tipoIncidente_nombre}}</u></strong> &nbsp;
                                    {{ registroOcurrencia.ocurrencia }}
                                </div>
                            </td>
                            <td>{{registroOcurrencia.zona_nombre}} - {{registroOcurrencia.ubicacion_nombre}} - {{registroOcurrencia.referencia}}</td>
                            <td>
                                <div v-for="vehi in registroOcurrencia.unidadMovil" :key="vehi.id">
                                    <li><u>{{ vehi.unidad_movil }}</u></li>
                                </div>
                                <div v-for="apel in registroOcurrencia.personalServicio" :key="apel.id">
                                    <li>{{ apel.apellidos }}</li>
                                </div>
                            </td>
                            <td>
                                {{ registroOcurrencia.solucion }}
                                <template v-if="registroOcurrencia.entidad_id>1">
                                    SE DERIVÓ A {{registroOcurrencia.entidad_nombre}}
                                </template>
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.parteServicio">
                                    <label title="Existe parte de servicio" class="text-success"><i  class="fa fa-file-signature"></i></label>
                                </template>
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.fuenteImagen_id>1"> 
                                    <a class="text-success" href="#" title="Agregar Fotos" @click="muestraSubirFotos(registroOcurrencia)"><i class="fa fa-images"></i> Foto({{registroOcurrencia.abreveatura}})</a>
                                </template>
                            </td>
                            <td class="text-center">
                                <template v-if="registroOcurrencia.fin">
                                    <a class="badge badge-success text-white" title="Registro Finalizado">{{ registroOcurrencia.usuario_nombre }}</a>
                                </template>
                                <template v-else>
                                    <a class="badge badge-danger text-white" title="Registro Pendiente">{{ registroOcurrencia.usuario_nombre }}</a>
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
            
        <div class="modal fade" id="muestraModal" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <strong class="modal-title" v-text="tituloModal"></strong>
                        <button type="button" class=" btn btn-primary close text-white" @click="cerrarModal()">
                            <i class="fa fa-window-close"></i>
                        </button>
                    </div>
                    <div class="modal-body bg-light">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> 
                            <div class="form-row border-bottom">
                                <div class="form-group col-md-2">
                                    <label for="fecha">FECHA (*):</label>
                                    <input class="form-control focusNext" type="date" tabindex="1" v-model="fecha" name="fecha" v-validate="'required|date_format:YYYY-MM-DD'">
                                    <span v-show="errors.has('fecha')" class="text-danger">{{ errors.first('fecha') }}</span>
                                </div>
                                
                                <div class="form-group col-md-2">
                                    <label for="hora">HORA (*):</label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-primary" @click="horaActual"><i class="fa fa-clock"></i></button>
                                        <input class="form-control focusNext" type="time"  tabindex="2" v-model="hora" name="hora" v-validate="'required'">
                                        <span v-show="errors.has('hora')" class="text-danger">{{ errors.first('hora') }}</span>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <div class="custom-control custom-radio mt-2">
                                        <input type="radio" id="llamadaRadio" class="custom-control-input" value="LLAMADA" v-model="inicio">
                                        <label class="custom-control-label" for="llamadaRadio"> <i class="fa fa-phone"></i> ¿POR LLAMADA?</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="patrullajeRadio" class="custom-control-input" value="PATRULLAJE" v-model="inicio">
                                        <label class="custom-control-label" for="patrullajeRadio"> <i class="fa fa-microphone"></i> ¿EN EL PATRULLAJE?</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="videoRadio" class="custom-control-input" value="VIDEO VIGILANCIA" v-model="inicio">
                                        <label class="custom-control-label" for="videoRadio"> <i class="fa fa-video"></i> EN VIDEO VIGILANCIA?</label>
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-5">
                                    <div class="input-group">
                                        <label for="fecha" class="mt-2">Nº DE TELÉFONO ENTRANTE (S):</label> &nbsp;
                                        <input class="form-control focusNext" v-bind:disabled="inicio!=='LLAMADA'" type="text" tabindex="3" placeholder="N° tel. entrante" v-model="telefono" maxlength="100" name="telefono" v-validate="'required|min:7|max:100'">
                                    </div>
                                    <span v-show="errors.has('telefono')" class="text-danger">{{ errors.first('telefono') }}</span>
                                    <div class="input-group">
                                         <label for="fecha" class="mt-2">ADMINISTRADO:</label> &nbsp;
                                          <input class="form-control focusNext" type="text" tabindex="4" maxlength="60" placeholder="Nombre de la persona" v-model.trim="administradoMayus">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row border-bottom">
                                <div class="form-group col-md-4">
                                    <label for="ocurrencia">DETALLE DE OCURRENCIA (*):</label>
                                    <textarea  class="form-control focusNext" tabindex="5" rows="2" maxlength="146" placeholder="Describe brevemente el problema, máximo 145 caracteres." v-model="ocurrenciaMayus" name="ocurrencia" v-validate="'required|min:10|max:145'"></textarea>
                                    <span v-show="errors.has('ocurrencia')" class="text-danger">{{ errors.first('ocurrencia') }}</span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="incidente">INCIDENCIA (*):</label>
                                    <select class="form-control focusNext" tabindex="6" v-model="incidente_id" @change="selectTipoIncidente" name="incidente" v-validate="'required'">
                                        <option value="" disabled>-- Seleccione --</option>
                                        <option v-for="incidente in arrayIncidente" :key="incidente.id" :value="incidente.id" v-text="incidente.nombre+' ('+incidente.generico_id+')'"></option>
                                    </select>
                                    <span v-show="errors.has('incidente')" class="text-danger">{{ errors.first('incidente') }}</span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tipoIncidente">TIPO DE INCIDENCIA (*):</label>
                                    <select class="form-control focusNext" tabindex="7" v-model="tipoIncidente_id" name="tipoIncidente_id" v-validate="'required'">
                                        <option value="" disabled>-- Seleccione --</option>
                                        <option v-for="tipoIncidente in arrayTipoIncidente" :key="tipoIncidente.id" :value="tipoIncidente.id" v-text="tipoIncidente.nombre"></option>
                                    </select>
                                    <span v-show="errors.has('tipoIncidente_id')" class="text-danger">{{ errors.first('tipoIncidente_id') }}</span>
                                </div>
                            </div>

                            <div class="form-row border-bottom">
                                <div class="form-group col-md-3">
                                    <label for="zona">ZONA (*):</label>
                                    <select class="form-control focusNext" tabindex="8" v-model="zona_id" @change="selectUbicacion" name="zona" v-validate="'required'">
                                        <option value="" disabled>-- Seleccione --</option>
                                        <option v-for="zona in arrayZona" :key="zona.id" :value="zona.id" v-text="zona.nombre"></option>
                                    </select>
                                    <span v-show="errors.has('zona')" class="text-danger">{{ errors.first('zona') }}</span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ubicacion">UBICACION / LUGAR (*):</label>
                                    <select class="form-control focusNext" tabindex="9" v-model="ubicacion_id" name="ubicacion" v-validate="'required'">
                                        <option value="" disabled>-- Seleccione --</option>
                                        <option v-for="ubicacion in arrayUbicacion" :key="ubicacion.id" :value="ubicacion.id" v-text="ubicacion.nombre"></option>
                                    </select>
                                    <span v-show="errors.has('ubicacion')" class="text-danger">{{ errors.first('ubicacion') }}</span>              
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="fecha">REFERENCIA / OTRO LUGAR:</label>
                                    <input class="form-control focusNext" tabindex="10" type="text" placeholder="Especifica una referencia" v-model="referenciaMayus">
                                </div>
                                <div class="form-group col-md-2 text-center bg-secondary">
                                    <div>
                                        <label>¿LOS AGENTE(S) ACUDE?</label>
                                    </div>
                                    <label class="switch switch-label switch-success">
                                        <input type="checkbox" class="switch-input focusNext" checked v-model="interviene">
                                        <span class="switch-slider" data-checked="SI" data-unchecked="NO"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-row border-bottom">
                                <div class="form-group col-md-5">
                                    <label for="unidadMovil">UNIDAD VEHICULAR MOVILIZADO:</label>
                                    <v-select multiple placeholder="Buscar y seleccionar" class="bg-white" v-bind:disabled="interviene==false"
                                        :options="arrayUnidadMovil" label="unidad_movil"
                                        v-model="unidadMovil">
                                        <template slot="option" slot-scope="option">
                                            {{ option.unidad_movil }}
                                        </template>
                                    </v-select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="registroOcurrencia">PERSONAL DE SERVICIO MOVILIZADO:</label>
                                    <v-select multiple placeholder="Buscar y seleccionar" class="bg-white" v-bind:disabled="interviene==false"
                                        :options="arrayPersonalServicio" label="apellidos"
                                        v-model="personalServicio"
                                        v-validate:personalServicio="'required'" name="personalServicio" :class="{ danger: errors.has('personalServicio') }">
                                        <template slot="option" slot-scope="option">
                                            {{ option.personal_nombre }}
                                        </template>
                                    </v-select>
                                    <span v-show="errors.has('personalServicio')" class="text-danger">{{ errors.first('personalServicio') }}</span>
                                </div>
                                <div class="form-group col-md-2 text-center bg-secondary">
                                    <div>
                                        <label for="">¿LLAMADA ES VERDADERO?</label>
                                    </div>
                                    <label class="switch switch-label switch-success">
                                        <input type="checkbox" class="switch-input focusNext" checked tabindex="14" v-model="llamada">
                                        <span class="switch-slider" data-checked="SI" data-unchecked="FALSO"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-4 bg-secondary">
                                    <label for="">SE SOLICITA Y/O SE DERIVA A ENTIDAD/ÁREA: </label>
                                    <select class="form-control" v-model="entidad_id">
                                        <option v-for="entidad in arrayEntidad" :key="entidad.id" :value="entidad.id" v-text="entidad.nombre"></option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 bg-secondary text-center">
                                    <div>
                                        <label for="">¿ENTIDAD/ÁREA ATENDIÓ?</label>
                                    </div>
                                    <label class="switch switch-label switch-success">
                                        <input type="checkbox" class="switch-input focusNext" checked tabindex="14" v-model="asume">
                                        <span class="switch-slider" data-checked="SI" data-unchecked="NO"></span>
                                    </label>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="">SE ADJUNTA IMÁGENE(S):</label>
                                    <select class="form-control" v-model="fuenteImagen_id">
                                        <option v-for="fuenteImagen in arrayFuenteImagen" :key="fuenteImagen.id" :value="fuenteImagen.id" v-text="fuenteImagen.nombre"></option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 bg-secondary text-center">
                                    <div>
                                        <label>¿EXISTE PARTE SERVICIO?</label>
                                    </div>
                                    <label class="switch switch-label switch-success">
                                        <input type="checkbox" class="switch-input" checked v-model="parteServicio">
                                        <span class="switch-slider" data-checked="SI" data-unchecked="NO"></span>
                                    </label>
                                </div>
                            </div>
                            <template v-if="parteServicio">
                                <div class="form-group col-md-12">
                                    <vue-editor  class="bg-white" id="editor1" :editorToolbar="customToolbar" v-model="detalleParteServicio" placeholder="SISTESIS DEL PARTE DE SERVICIO"> </vue-editor>
                                </div>
                            </template>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <textarea  class="form-control" rows="2" placeholder="Describe una breve resolución. Máximo 255 caracteres" v-model="solucionMayus" maxlength="245"></textarea>
                                </div>    
                                <div class="form-group col-md-2 text-center bg-secondary">
                                    <div>¿REGISTRO FINALIZADO?</div>
                                    <label class="switch switch-label switch-success">
                                        <input type="checkbox" class="switch-input" checked v-model="fin">
                                        <span class="switch-slider" data-checked="SI" data-unchecked="NO"></span>
                                    </label>
                                </div>
                                <div class="form-group col-md-1 text-center">
                                </div>
                                <div class="form-group col-md-3">
                                    <button type="button" v-if="tipoAccion==1" class="btn btn-primary btn-block btn-lg" @click="registrar()">
                                        <i class="fa fa-save"></i> Grabar Registro
                                    </button>
                                    <button type="button" v-if="tipoAccion==2" class="btn btn-primary btn-block btn-lg" @click="actualizar()">
                                        <i class="fa fa-pencil-alt"></i> Actualizar Registro
                                    </button>
                                    <span v-if="errorCabecera.tipoIncidente_id" class="text-danger error">{{ errorCabecera.tipoIncidente_id[0] }}</span>
                                    <span v-show="errors.has('tipoIncidente_id')" class="text-danger">{{ errors.first('tipoIncidente_id') }}</span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template v-else-if="fotografia === 1">
        <div class="card border-info">
            <div class="card-header text-center">
                <strong class="modal-title">SUSTENTO FOTOGRÁFICO DE OCURRENCIA</strong>
                <button type="link" class="btn btn-outline-info btn-sm close" title="Ir al registro" @click="mostrarRegistroOcurrencia">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <div class="text-center">
                        <div class="input-group">
                            <div class="col-sm-12 mt-3 bg-primary">
                                <strong> ÁREA DE COLOCACIÓN FOTOGRÁFICA </strong>
                            </div>
                        </div>
                        <vue-dropzone ref="myVueDropzone" id="dropzone" 
                            v-on:vdropzone-sending="enviarDatosPanel"
                            :options="dropzoneOptionsFotos">
                        </vue-dropzone>
                    </div>
                    <div class="alert alert-danger" role="alert" v-show="mensajeErrorFoto">
                        <li v-for="error in muestraMsgErrorFoto" :key="error" v-text="error"></li>
                    </div>

                    <div class="form-row justify-content-end">
                        <div class="col-md-2 d-none d-md-block">
                            <label for="leyenda">Leyenda de Foto(s) (*): </label>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control" maxlength="60" name="leyenda" v-model="leyenda" placeholder="Breve descripción" v-validate="'required'">
                            <span v-show="errors.has('leyenda')" class="text-warning">{{ errors.first('leyenda') }}</span>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary" @click="enviarDetallePanel">
                                <i class="fa fa-save"></i> Subir Fotos
                            </button>
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-responsive-md table-sm table-success">
                    <tbody>
                        <tr>
                            <td><strong>FECHA:</strong> {{ fecha }} <strong> HORA:</strong> {{ hora }}</td>
                            <td><strong>TIPO DE INCIDENCIA:</strong> {{ tipoIncidente_nombre }}</td>
                        </tr>
                        <tr>
                            <td><strong>UBICACIÓN: </strong> {{ zona_nombre }} - {{ ubicacion_nombre }}</td>
                             <td><strong>OCURRENCIA: </strong> {{ ocurrencia}} </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="input-group">
                                    <strong>ATENDIDO POR: </strong>&nbsp; {{ unidadMovil_nombre }} &nbsp;
                                    <div v-for="apel in personalServicio" :key="apel.id">
                                        <li>{{ apel.personal_nombre }}</li>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right"><button type="button" class="btn btn-outline-primary btn-sm" @click="verDetallePanelFotos"><i class="fa fa-sync-alt"></i> Recargar</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-md-4" v-for="panelFotografico in arrayPanelFotografico" :key="panelFotografico.id">
                        <img class="card-img-top" :src="'storage/'+panelFotografico.fotoOriginal" alt="">
                        <div class="form-row">
                            <div class="col-8 text-right">{{ panelFotografico.leyenda }}</div>
                            <div class="col-4 text-right">
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="eliminarDetallePanel(panelFotografico)"><i class="fa fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <br/>
                    </div>
                </div>
            </div>
        </div>
    </template>
    </main>
</template>

<script>
    import { VueEditor } from 'vue2-editor'
    import vSelect from 'vue-select'
    import moment from 'moment'
    import swal from "sweetalert"
    moment.locale('es');

    import VeeValidate from 'vee-validate';
    const VueValidationEs = require('vee-validate/dist/locale/es');
    Vue.use(VeeValidate, {
        locale: 'es',
        dictionary: {
        es: VueValidationEs
        }
    });

    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'

    export default {
        data (){
            return {
                fotografia:0,
                registroOcurrencia_id: 0,
                fecha : null,
                hora : null,
                inicio :'',
                telefono:'',
                administrado:'',
                ocurrencia:'',
                incidente_id:'',
                tipoIncidente_id:'',
                zona_id:'',
                ubicacion_id:'',
                referencia:'',
                unidadMovil:'',
                personalServicio:'',
                interviene:true,
                parteServicio:false,
                llamada:true,
                detalleParteServicio:"<h2><strong><u>SUMILLA:</u></strong></h2><p><br></p>",
                entidad_id:1,
                asume:false,
                fuenteImagen_id:1,
                fin:true,
                solucion:'',

                tipoIncidente_nombre:'',
                zona_nombre:'',
                ubicacion_nombre:'',
                unidadMovil_nombre:'',
                leyenda:'',
                nombreParte:'',
                mensajeErrorFoto:0,
                muestraMsgErrorFoto:[],
                nombreFoto:'',

                arrayIncidente : [],
                arrayTipoIncidente : [],
                arrayZona:[],
                arrayEntidad:[],
                arrayUbicacion:[],
                arrayUnidadMovil:[],
                arrayFuenteImagen:[],
                arrayPersonalServicio:[],
                arrayRegistroOcurrencia:[],
                arrayPanelFotografico:[],
                errorCabecera:[],
                customToolbar: [
                    [{ header: [false, 1, 2, 3, 4, 5, 6] }],
                    ['bold', 'italic', 'underline',"link"],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ color: [] }, { background: [] }],
                    [{ align: "" }, { align: "center" }, { align: "right" }, { align: "justify" }]
                ],

                dropzoneOptionsFotos: {
                    url: '/panelFotografico/subirPanelFotografico',
                    autoProcessQueue: false,
                    maxFilesize: 2,
                    maxFiles: 4,
                    acceptedFiles:'image/*',
                    thumbnailWidth: 250,
                    thumbnailHeight: 140,
                    addRemoveLinks: true,
                    dictRemoveFile:'Cancelar',
                    dictMaxFilesExceeded: 'Se acepta 4 Fotos como máximo. Cancelar',
                    dictFileTooBig: 'El tamaño supera a 2 Mb. Cancelar',
                    dictDefaultMessage: "<i class='fa fa-image fa-2x'></i> Tamaño máximo es 2 Mb",
                    headers: {
                        "X-CSRF-TOKEN": document.head.querySelector("[name=csrf-token]").content
                    }
                },

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
        
        components:{
            vSelect,
            vueDropzone: vue2Dropzone,
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
            },

            administradoMayus: {
                get () { return this.administrado },
                set (administrado) { this.administrado = administrado.toUpperCase()}
            },
            ocurrenciaMayus: {
                get () { return this.ocurrencia },
                set (ocurrencia) { this.ocurrencia = ocurrencia.toUpperCase()}
            },
            referenciaMayus: {
                get () { return this.referencia },
                set (referencia) { this.referencia = referencia.toUpperCase()}
            },
            solucionMayus: {
                get () { return this.solucion },
                set (solucion) { this.solucion = solucion.toUpperCase()}
            }
        },

        methods : {
            fechaActual(){
                var fechaMoment=moment().format('YYYY-MM-DD')
                this.fecha=fechaMoment;
            },
            horaActual(){
                var horaMoment=moment().format('HH:mm')
                this.hora=horaMoment;
            },

            formatoFecha: function(d){
                return moment(d).format('ddd DD-MMM-YY');
            },

            listarRegistro(page,criterio, buscar){
                let me=this;
                axios.get('/registroOcurrencia',{
                        params: {
                            'page': page,
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha,
                            criterio: 'ocurrencia',
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

            selectTipoIncidente(){
                let me=this;
                axios .get(`/selectTipoIncidente/${this.incidente_id}`).then(function (response) {
                    var respuesta= response.data;
                    me.arrayTipoIncidente = respuesta.tipoIncidente;
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

            selectUbicacion(){
                let me=this;
                axios .get(`/selectUbicacion/${this.zona_id}`).then(function (response) {
                    var respuesta= response.data;
                    me.arrayUbicacion = respuesta.ubicacion;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            selectEntidad(){
                let me=this;
                axios.get('/entidad/selectEntidad').then(function (response) {
                    var respuesta= response.data;
                    me.arrayEntidad = respuesta.entidad;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            selectUnidadMovil(){
                let me=this;
                axios.get('/selectUnidadMovil/listarUnidadMovil')
                .then(function (response) {
                    me.arrayUnidadMovil = response.data.unidadMovil;
                })
            },
            
            selectFuenteImagen(){
                let me=this;
                axios.get('/selectFuenteImagen').then(function (response) {
                    var respuesta= response.data;
                    me.arrayFuenteImagen = respuesta.fuenteImagen;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            listarPersonalServicio(){
                let me=this;
                axios.get('/personalServicio/listarPersonalServicio')
                .then(function(response){
                    me.arrayPersonalServicio=response.data.personalServicio;
                })
            },

            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarRegistro(page,buscar,criterio);
            },

            registrar(){
                let me = this;
                this.errorCabecera=[];
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post('/registroOcurrencia/registrar',{
                            'fecha':this.fecha,
                            'hora': this.hora,
                            'inicio': this.inicio,
                            'telefono': this.telefono,
                            'administrado': this.administrado,
                            'ocurrencia': this.ocurrencia,
                            'tipoIncidente_id': this.tipoIncidente_id,
                            'ubicacion_id': this.ubicacion_id,
                            'referencia': this.referencia,
                            'unidadMovil': this.unidadMovil,
                            'personalServicio': this.personalServicio,
                            'interviene': this.interviene,
                            'parteServicio': this.parteServicio,
                            'detalleParteServicio': this.detalleParteServicio,
                            'entidad_id': this.entidad_id,
                            'asume': this.asume,
                            'llamada': this.llamada,
                            'fuenteImagen_id': this.fuenteImagen_id,
                            'fin': this.fin,
                            'solucion': this.solucion
                        }).then(function (response) {
                            me.cerrarModal();
                            swal("Grabado!", "Registro guardado con éxito!", "success", {button: false,timer: 1000});
                            me.listarRegistro(1,'','ocurrencia');
                        }).catch(error=> {
                            if(error.response.status==422){
                                this.errorCabecera=error.response.data.errors;
                            }
                        });            
                    }
                })
            },

            actualizar(){
                var me=this;
                this.errorCabecera=[];
                let paginaActual=this.isActived;
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.put('/registroOcurrencia/actualizar',{
                            'id': this.registroOcurrencia_id,
                            'fecha':this.fecha,
                            'hora': this.hora,
                            'inicio': this.inicio,
                            'telefono': this.telefono,
                            'administrado': this.administrado,
                            'ocurrencia': this.ocurrencia,
                            'tipoIncidente_id': this.tipoIncidente_id,
                            'ubicacion_id': this.ubicacion_id,
                            'referencia': this.referencia,
                            'unidadMovil': this.unidadMovil,
                            'personalServicio': this.personalServicio,
                            'interviene': this.interviene,
                            'parteServicio': this.parteServicio,
                            'detalleParteServicio': this.detalleParteServicio,
                            'llamada': this.llamada,
                            'entidad_id': this.entidad_id,
                            'asume': this.asume,
                            'fuenteImagen_id': this.fuenteImagen_id,
                            'fin': this.fin,
                            'solucion': this.solucion
                        }).then(function (response) {
                            me.cerrarModal();
                            swal("Actualizado!", "Registro modificado con éxito!", "info", {button: false,timer: 1000});
                            me.listarRegistro(paginaActual,"","");
                        }).catch(error=> {
                            if(error.response.status==422){
                                this.errorCabecera=error.response.data.errors;
                            }
                        });
                    }
                })
            },

            desactivar(id) {
                swal("DESACTIVAR!", "Desea desactivar el registro?", "error", {buttons:true, dangerMode: true})
                .then(confirma => {
                    if (confirma) {
                        let me=this;
                        let paginaActual=this.isActived;
                        axios.put("/registroOcurrencia/desactivar", { id: id }).then(function(response) {
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
                    axios.put("/registroOcurrencia/activar", { id: id }).then(function(response) {
                    me.listarRegistro(paginaActual, "", "");
                    });
                } else {
                    swal.close();
                }
                });
            },

            cerrarModal(){
                let me=this;
                $("#muestraModal").modal("hide");
                me.tituloModal = "";
                me.limpiarCamposTexto();
                me.$validator.reset();
                me.errorCabecera=[];
                me.buscar='';
                me.criterio='';
            },

            limpiarCamposTexto(){
                this.hora=null;
                this.inicio='LLAMADA';
                this.telefono='';
                this.administrado='';
                this.ocurrencia='';
                this.incidente_id='';
                this.tipoIncidente_id='';
                this.zona_id='';
                this.ubicacion_id='';
                this.entidad_id=1;
                this.referencia='';
                this.unidadMovil='';
                this.personalServicio='';
                this.interviene=true;
                this.parteServicio=false;
                this.detalleParteServicio="<h2><strong><u>SUMILLA:</u></strong></h2><p><br></p>";
                this.llamada=true;
                this.asume=false;
                this.fin=true;
                this.fuenteImagen_id=1;
                this.solucion='';
            },

            abrirModal(accion, data = []){
                this.fechaActual()
                this.selectIncidente();
                this.selectZona();
                this.selectEntidad();
                this.selectUnidadMovil();
                this.selectFuenteImagen();
                this.listarPersonalServicio();

                switch(accion){
                    case 'registrar':
                    {
                        this.limpiarCamposTexto();
                        this.tituloModal = 'REGISTRAR OCURRENCIAS';
                        this.tipoAccion = 1;
                        break;
                    }
                    case 'actualizar':
                    {
                        this.tituloModal='ACTUALIZAR OCURRENCIAS';
                        this.tipoAccion=2;
                        this.registroOcurrencia_id=data["id"];
                        this.fecha=data["fecha"];
                        this.hora=data["hora"];
                        this.inicio=data["inicio"];
                        this.telefono=data["telefono"];
                        this.administrado=data["administrado"];
                        this.ocurrencia=data["ocurrencia"];
                        this.incidente_id=data["incidente_id"];
                        this.tipoIncidente_id=data["tipoIncidente_id"];
                        this.zona_id=data["zona_id"];
                        this.ubicacion_id=data["ubicacion_id"];
                        this.referencia=data["referencia"];
                        this.unidadMovil=data["unidadMovil"];
                        this.personalServicio=data["personalServicio"];
                        this.interviene=data["interviene"];
                        this.parteServicio=data["parteServicio"];
                        this.detalleParteServicio=data["detalleParteServicio"];
                        this.entidad_id=data["entidad_id"];
                        this.llamada=data["llamada"];
                        this.asume=data["asume"];
                        this.fin=data["fin"];
                        this.fuenteImagen_id=data["fuenteImagen_id"];
                        this.solucion=data["solucion"];
                        this.selectTipoIncidente();
                        this.selectUbicacion();
                        break;
                    }
                }
                
            },

            mostrarRegistroOcurrencia(){
                this.fotografia=0;
                this.$refs.myVueDropzone.removeAllFiles();
                this.leyenda='';
                this.nombreParte='';
            },

            muestraSubirFotos(datos=[]){
                let me=this;
                me.fotografia=1;
                me.registroOcurrencia_id=datos["id"];
                me.fecha=datos['fecha'],
                me.hora=datos['hora'],
                me.ocurrencia=datos['hora'],
                me.tipoIncidente_nombre=datos['tipoIncidente_nombre'],
                me.zona_nombre=datos['zona_nombre'],
                me.ubicacion_nombre=datos['ubicacion_nombre'],
                me.unidadMovil_nombre=datos['unidadMovil_nombre']
                me.ocurrencia=datos['ocurrencia']
                me.personalServicio=datos['personalServicio']
                me.nombreFoto=datos['fecha']+'_'+datos['hora']+'-inc_'+datos['tipoIncidente_id']
                axios.get('/panelFotografico/verPanel',{
                    params:{
                        registroOcurrencia_id:datos['id']
                    }
                })
                .then(function (response) {
                    me.arrayPanelFotografico=response.data.panelFotografico;
                })
                .catch(function (error) {
                })

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

            limpiaDetallePanelFotos(){
                this.nuevoPanelFotoActiva=true;
                this.$refs.myVueDropzone.removeAllFiles();
                this.leyenda='';
                this.nombreParte='';
                this.enviarPanelFotosBoton=false
            },
            
            ValidarEntradaImagen(){
                this.mensajeErrorFoto=0;
                this.muestraMsgErrorFoto =[];
                this.fotoCargado=this.$refs.myVueDropzone.getAcceptedFiles();
                if (this.fotoCargado=='') this.muestraMsgErrorFoto.push("Por favor cargue el archivo.");
                if (this.muestraMsgErrorFoto.length) this.mensajeErrorFoto = 1;
                return this.mensajeErrorFoto;
            },

            enviarDetallePanel(){
                let me=this;
                if (this.ValidarEntradaImagen()){
                    return
                }
                this.$validator.validateAll().then((result) => {
                    if (result) {
                        this.$refs.myVueDropzone.processQueue()
                    }
                })
            },
            
            enviarDatosPanel (file, xhr, formData) {
                formData.append('registroOcurrencia_id',this.registroOcurrencia_id),
                formData.append('leyenda',this.leyenda)
            },

            eliminarDetallePanel(datos){
                swal("Eliminar!", "Desea Eliminar esta Foto?", "error", {buttons:true, dangerMode: true})
                .then(confirma => {
                    if (confirma) {
                        let me=this;
                        axios.delete('/panelFotografico/eliminarPanel',{
                            params: {
                            id:datos.id,
                            fotoOriginal:datos.fotoOriginal
                            }
                        })
                        .then(function(response) {
                            me.verDetallePanelFotos();
                        });
                    } else {
                        swal.close();
                    }
                });
            },

            descargarExcel(){
                if (this.desdeFecha=='') {
                    swal("Fecha Inicio Vacío!", "Debe establecer fecha de inicio", "info");
                }
                else if (this.hastaFecha=='') {
                    swal("Fecha Final Vacío!", "Debe establecer fecha final", "info");
                }
                else if (this.desdeFecha > this.hastaFecha) {
                    swal("Fecha Final es Menor!", "Fecha de inicio debe ser menor que fecha final", "info");
                }
                else{
                    window.open('/generarReporteExcel/'+this.desdeFecha+'/'+this.hastaFecha,'_blank');
                }
            }

        },
        mounted() {
            this.listarRegistro(1,this.buscar,this.criterio);
        }
    }
</script> 
<style>
  #editor1 {
    height: 200px;
  }
  .modal-lg {
    max-width: 90% !important; 
    }

.dropzone {
        border: 2px dashed #0087F7;
        background: white;
        /* border-radius: 5px; */
        color: #0087F7;
        font-size: 1.2em;
    }
</style>
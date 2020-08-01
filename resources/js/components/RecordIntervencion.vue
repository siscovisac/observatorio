<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center">
                <strong>RECORD DE INTERVENCIONES DEL AGENTE</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="input-group col-md-3">
                        DESDE FECHA: <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha">    
                    </div>
                    <div class="input-group col-md-3">
                        HASTA FECHA: <input class="form-control" type="date" title="Buscar hasta Fecha" v-model="hastaFecha">
                    </div>
                    <div class="input-group col-md-6">
                        NOMBRE DEL AGENTE: 
                        <select class="form-control focusNext" tabindex="9" v-model="personal_id" @change="listarRegistro()">
                            <option value="" disabled>-- Seleccione --</option>
                            <option v-for="personal in arrayPersonal" :key="personal.id" :value="personal.id" v-text="personal.personal_nombre"></option>
                        </select>
                        <!-- <button type="button" class="btn btn-outline-primary" @click="generarReportePDF()"><i class="fa fa-file-pdf"></i> Generar Reporte</button> -->
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm table-responsive-md">
                    <thead>
                        <tr><th colspan="6"><strong><h5 class="text-center">RECORD DE INTERVENCIONES DESDE {{desdeFecha}} HASTA {{hastaFecha}}</h5></strong></th></tr>
                        <tr>
                            <th class="text-center">____FECHA____</th>
                            <th class="text-center">OCURRENCIA</th>
                            <th class="text-center">EVENTO - MODALIDAD</th>
                            <th class="text-center">LUGAR</th>
                            <th class="text-center">RESOLUCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
                         <tr v-for="intevencionPersonal in arrayRecordIntervencion" :key="intevencionPersonal.id">
                            <td>{{intevencionPersonal.fecha}} {{intevencionPersonal.hora}}</td>
                            <td>{{intevencionPersonal.ocurrencia}}</td>
                            <td><strong>{{intevencionPersonal.generico}} </strong>- {{intevencionPersonal.evento}} - <i>{{intevencionPersonal.modalidad}}</i></td>
                            <td>{{intevencionPersonal.zona}} - <strong>{{intevencionPersonal.ubicacion}}</strong> - {{intevencionPersonal.referencia}}</td>
                            <td>
                                {{ intevencionPersonal.solucion }}
                                <template v-if="intevencionPersonal.entidad_id>1">
                                    SE DERIVÓ A {{intevencionPersonal.entidad_nombre}}
                                </template>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">TOTAL INTERVENCIONES: </td>
                            <td class="text-center"><strong>{{cantidad}}</strong></td>
                        </tr>
                    </tbody>
                </table>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>NOTA: </strong> Filtra por orden de fecha para mostrar resultados válidos, la ausencia de los registros indica que no existen registros de record de intervencion (es).
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        data (){
            return {
                desdeFecha : '',
                hastaFecha : '',
                personal_id: '',
                lista:0,
                arrayPersonal:[],
                arrayRecordIntervencion:[]
            }
        },
       

        methods : {
            listarRegistro(desdeFecha, hastaFecha, personal_id){
                let me=this;
                axios.get('/incidenteIntervencion',{
                    params: {
                            personal_id: this.personal_id,
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha
                        }
                }).then(function (response) {
                    me.arrayRecordIntervencion = response.data.intevencionPersonal;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            personal(){
                let me=this;
                axios.get('/listarPersonal').then(function (response) {
                    var respuesta= response.data;
                    me.arrayPersonal = respuesta.personalServicio;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            generarReportePDF(){
                if (this.desdeFecha=="") {
                    swal("FECHA VACÍA!", "Ingrese una fecha válida, por favor", "info")
                }
                else{
                    window.open('/resumenIntervencion/'+this.desdeFecha+'/'+this.hastaFecha,'_blank');
                }
            }
        },
        mounted() {
            this.personal();
        }
    }
</script>
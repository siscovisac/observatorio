<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-success">
                <strong>RESUMEN DE OCURRENCIAS POR INTERVALO DE FECHAS</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="input-group col">
                        DESDE FECHA: <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha">    
                    </div>
                    <div class="input-group col">
                        HASTA FECHA: <input class="form-control" type="date" title="Buscar hasta Fecha" v-model="hastaFecha">
                        <button type="submit" @click="[listarRegistro(),resumenZonas(), resumenModalidad(), resumenGenericos()]" class="btn btn-primary"><i class="fa fa-search"></i> FILTRAR</button>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" @click="descargarExcel()" class="btn btn-outline-success"><i class="fa fa-file-excel"></i> Descargar</button> 
                    </div>
                </div>
                 <div class="form-group row">
                     <div class="col-md-5">
                         <table class="table table-bordered table-striped table-sm table-responsive-md">
                             <thead>
                                <tr><th colspan="3"><strong><h5 class="text-center">RESUMEN POR ZONAS DESDE: {{desdeFecha}} HASTA: {{hastaFecha}}</h5></strong></th></tr>
                                <tr>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">SECTORES/ZONAS</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                             </thead>
                             <tbody>
                                 <tr v-for="resumenZonas in arrayResumenZonas" :key="resumenZonas.id">
                                    <td class="text-center" v-text="resumenZonas.zona_id">
                                    </td>
                                    <td>
                                        {{resumenZonas.zona_nombre}}
                                    </td>
                                    <td class="text-center">
                                        <strong>{{resumenZonas.total_zonas}}</strong>
                                    </td>
                                </tr>
                             </tbody>
                         </table>
                        <hr>
                        <table class="table table-bordered table-striped table-sm table-responsive-md">
                             <thead>
                                <tr><th colspan="3"><strong><h5 class="text-center">RESUMEN POR GENERICOS DESDE: {{desdeFecha}} HASTA: {{hastaFecha}}</h5></strong></th></tr>
                                <tr>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">GENÉRICOS</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                             </thead>
                             <tbody>
                                 <tr v-for="resumenGenericos in arrayResumenGenerico" :key="resumenGenericos.id">
                                    <td class="text-center" v-text="resumenGenericos.generico_id">
                                    </td>
                                    <td>
                                        {{resumenGenericos.generico}}
                                    </td>
                                    <td class="text-center">
                                        <strong>{{resumenGenericos.total_generico}}</strong>
                                    </td>
                                </tr>
                             </tbody>
                         </table>
                        <hr>
                        <table class="table table-bordered table-striped table-sm table-responsive-md">
                            <thead>
                                <tr><th colspan="4"><strong><h5 class="text-center">RESUMEN POR EVENTOS DESDE: {{desdeFecha}} HASTA: {{hastaFecha}}</h5></strong></th></tr>
                                <tr>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">GENERICO</th>
                                    <th class="text-center">EVENTOS</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="registroOcurrencia in arrayRegistroOcurrencia" :key="registroOcurrencia.id">
                                    <td class="text-center" v-text="registroOcurrencia.incidente_id">
                                    </td>
                                    <td>
                                        <strong>{{registroOcurrencia.generico}}</strong>
                                    </td>
                                    <td>
                                        {{registroOcurrencia.incidente_nombre}}
                                    </td>
                                    <td class="text-center">
                                        <strong>{{registroOcurrencia.cantidad}}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                     
                     <hr>
                     
                     <div class="col-md-7">     
                       <table class="table table-bordered table-striped table-sm table-responsive-md">
                            <thead>
                                <tr><th colspan="5"><strong><h5 class="text-center">RESUMEN POR MODALIDAD DESDE: {{desdeFecha}} HASTA: {{hastaFecha}}</h5></strong></th></tr>
                                <tr>
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">GENERICO</th>
                                    <th class="text-center">EVENTOS</th>
                                    <th class="text-center">MODALIDAD</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="registroModalidad in arrayRegistroModalidad" :key="registroModalidad.id">
                                    <td class="text-center" v-text="registroModalidad.tipoIncidente_id">
                                    </td>
                                    <td>
                                        <strong>{{registroModalidad.generico}}</strong>
                                    </td>
                                    <td>
                                        {{registroModalidad.evento}}
                                    </td>
                                    <td>
                                        {{registroModalidad.modalidad}}
                                    </td>
                                    <td class="text-center">
                                        <strong>{{registroModalidad.totalModalidad}}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                 </div>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>NOTA:</strong> La ausencia de un ITEM indica que no existen registros de una incidencia (s).
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
                arrayRegistroOcurrencia:[],
                arrayRegistroModalidad:[],
                arrayResumenGenerico:[],
                arrayResumenZonas:[]
            }
        },
       

        methods : {
            listarRegistro(){
                let me=this;
                axios.get('/resumenIncidencia',{
                    params: {
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha
                        }
                }).then(function (response) {
                    me.arrayRegistroOcurrencia = response.data.registroOcurrencia;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            resumenGenericos(){
                let me=this;
                axios.get('/resumenGenericos',{
                    params: {
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha
                        }
                }).then(function (response) {
                    me.arrayResumenGenerico = response.data.resumenGenericos;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

            resumenZonas(){
                let me=this;
                axios.get('/resumenZonas',{
                    params: {
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha
                        }
                }).then(function (response) {
                    me.arrayResumenZonas = response.data.resumenZonas;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            
            resumenModalidad(){
                let me=this;
                axios.get('/resumenModalidad',{
                    params: {
                            desdeFecha: this.desdeFecha,
                            hastaFecha: this.hastaFecha
                        }
                }).then(function (response) {
                    me.arrayRegistroModalidad = response.data.resumenModalidad;
                })
                .catch(function (error) {
                    console.log(error);
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

        }
    }
</script>
<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center">
                <strong>RESUMEN DEL INCIDENCIAS EN TABLA PIVOTE</strong>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="input-group col">
                        DESDE FECHA: <input class="form-control" type="date" title="Buscar desde Fecha" v-model="desdeFecha">    
                    </div>
                    <div class="input-group col">
                        HASTA FECHA: <input class="form-control" type="date" title="Buscar hasta Fecha" v-model="hastaFecha">
                        <button type="submit" @click="listarRegistro()" class="btn btn-primary"><i class="fa fa-search"></i> FILTRAR</button>
                    </div>
                    <div class="col-md-3 text-right">
                        <button type="button" class="btn btn-outline-primary" @click="generarReportePDF()"><i class="fa fa-file-pdf"></i> Generar Reporte</button>
                    </div>
                </div>
                <hr>
                <div>

                </div>
                
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>NOTA: </strong> Filtra por fecha para mostrar resultados, la ausencia de los registros indica que no existen registros de record de intervencion (es).
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
                arrayRecordIntervencion:[]
            }
        },
       
        methods : {
            listarRegistro(){
                let me=this;
                axios.get('/resumenRecord',{
                    params: {
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
            this.listarRegistro();
        }
    }
</script>
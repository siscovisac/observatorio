<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center">
                <strong>GRÁFICOS ESTADÍSTICO DE OCURRENCIAS - SERENAZGO CHANCAY</strong>
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
                            </div>
                        </div>
                    </div>
                </div>
                  
                <div class="small">
                  <line-chart :chart-data="datacollection"></line-chart>     
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import LineChart from './LineChart.js'
    export default {
      components: {
          LineChart
    },
    data () {
        return {
        datacollection: null,
        arrayIncidente : [],
        buscar:'',
        criterio:'',
        desdeFecha:null,
        hastaFecha:null,
        }
    },
    
    mounted() {
        this.fillData();
        this.selectIncidente();
    },

    methods: {
        fillData () {
            this.datacollection = {
                labels: ['ENERO', 'FEBRERO', 'MARZO'],
                datasets: [
                {
                    label: 'LIBANDO LICOR',
                    data: [20, 17,25],
                    backgroundColor: ['rgba(252, 56, 13,0.1)'],
                    borderColor: ['rgba(252, 56, 13)'],
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(41, 128, 185,0.2)',
                    pointRadius: 5,
                },
                {
                    label: 'ACCIDENTE DE TRANSITO',
                    data:[15,22,12],
                    backgroundColor: ['rgba(13, 118, 252,0.1)'],
                    borderColor:'rgba(13, 118, 252)',
                    borderWidth: 3,
                    pointRadius: 5,
                },
                {
                    label: 'VIOLENCIA FAMILIAR',
                    data:[19,23,9],
                    backgroundColor: ['rgba(46, 204, 113,0.1)'],
                    borderColor:'rgba(46, 204, 113)',
                    borderWidth: 3,
                    pointRadius: 5,
                }]
            },
    
            { 
                responsive: true, 
                maintainAspectRatio: true,
                position: relative, 
                display: true,
                text: 'Chart.js Doughnut Chart'
            }   
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


    },

       
    }
</script>

<style>
    .small {
    max-width: 450px;
    margin:  1px auto;
    }
</style>
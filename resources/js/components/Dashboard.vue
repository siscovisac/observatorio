<template>
<main class="main">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h1 class="display-6 text-primary text-center">INTERVENCIONES - SEGURIDAD CIUDADANA</h1>
            </div>
            <div class="card-body">
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
                <div class="row" v-for="registro in arrayDashboard" :key="registro.id">
                    <div class="col-sm-6 col-lg-3">
                        <div class="brand-card">
                            <div class="brand-card-header bg-google-plus">
                                <h5 class="text-center text-white"><i class="fa fa-user-secret"></i> TOTAL</h5>
                            </div>
                            <div class="brand-card-body">
                                <div>
                                    <div class="text-value" v-text="registro.inicio"></div>
                                    <div class="text-uppercase text-muted small">INTERVENCIONES - SERENAZGO</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="brand-card">
                            <div class="brand-card-header bg-facebook">
                                <h5 class="text-center text-white"><i class="fa fa-phone"></i> LLAMADAS TELEFÓNICAS</h5>
                            </div>
                            <div class="brand-card-body">
                                <div>
                                    <div class="text-value">121</div>
                                    <div class="text-uppercase text-muted small">CASOS ATENDIDOS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="brand-card">
                            <div class="brand-card-header bg-twitter">
                                <h5 class="text-center text-white"><i class="fa fa-taxi"></i> DURANTE EL PATRULLAJE</h5>
                            </div>
                            <div class="brand-card-body">
                                <div>
                                    <div class="text-value">35</div>
                                    <div class="text-uppercase text-muted small">CASOS ATENDIDOS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="brand-card">
                            <div class="brand-card-header bg-info">
                                <h5 class="text-center text-white"><i class="fa fa-video"></i> POR VIDEO-VIGILANCIA</h5>
                            </div>
                            <div class="brand-card-body">
                                <div>
                                    <div class="text-value">23</div>
                                    <div class="text-uppercase text-muted small">CASOS ATENDIDOS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-->
            </div>
        </div>
    </div>
</main>
</template>
<script>
export default {
  data() {
    return {
      inicio: "",
      desdeFecha:null,
      hastaFecha:null,
      arrayDashboard:[],
    };
  },
  methods: {
    ResumenDashboard() {
        let me = this;
        axios.get("/dashboard").then(function(response) {
            me.arrayDashboard = response.data;
            })
            .catch(function(error) {
            console.log(error);
            });
        }
    },
  mounted() {
    this.ResumenDashboard();
    }
}
</script>
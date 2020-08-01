<template>
    <main class="main">
        <div class="card">
            <div class="card-header text-center bg-primary">
                <strong>LISTADO - ROLES</strong>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th class="text-center">Roles</th>
                            <th class="text-center">Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rol in arrayRol" :key="rol.id">
                            <td v-text="rol.nombre"></td>
                            <td v-text="rol.descripcion"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</template>

<script>
export default {
  data() {
    return {
      nombre: "",
      descripcion: "",
      arrayRol:[],
    };
  },
  methods: {
    listarRol() {
      let me = this;
      axios.get("/rol").then(function(response) {
          me.arrayRol = response.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }, 
  mounted() {
    this.listarRol();
  }
}
</script>
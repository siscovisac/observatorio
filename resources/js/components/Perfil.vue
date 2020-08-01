<template>
    <main class="main">
        <div class="card">
            <div class="card-header bg-primary text-center">
                <strong>ACTUALIZAR DATOS DEL PERFIL</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input class="form-control focusNext" type="text" v-model="arrayPerfil.name" tabindex="1" maxlength="20" placeholder="Nombre de Usuario">
                    </div>
                    <span v-if="errorsCampo.name" class="text-danger error">{{ errorsCampo.name[0] }}</span>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input class="form-control focusNext" type="email" v-model="arrayPerfil.email" tabindex="2" maxlength="50" placeholder="Correo electrónico">
                    </div>
                    <span v-if="errorsCampo.email" class="text-danger error">{{ errorsCampo.email[0] }}</span>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>
                        <input class="form-control focusNext" type="password" v-model="arrayPerfil.password" tabindex="3" maxlength="20" placeholder="Contraseña">
                    </div>
                    <span v-if="errorsCampo.password" class="text-danger error">{{ errorsCampo.password[0] }}</span>
                </div>
                
                <div class="mb-3">
                    <div class="input-group">
                        ROL ASIGNADO: &nbsp;<strong><label for="rol" v-text="arrayPerfil.nombre"></label></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        MODIFICADO EL: &nbsp;<strong><label for="rol" v-text="arrayPerfil.updated_at"></label></strong>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="button" class="btn btn-primary" @click="actualizar()">
                    <i class="fa fa-pencil-alt"></i> Actualizar</button>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    data(){
        return{
            errorsCampo: [],
            arrayPerfil:[]
        }
    },
    methods:{
        listarRegistro(){
            let me=this;
            axios.get("/perfil").then(function(response){
                me.arrayPerfil=response.data
            })
            .catch(function(error){
                console.log(error);
            });
        },

        actualizar() {
            let me = this;
            axios.put("/perfil/actualizar", {
                id: this.arrayPerfil.id,
                name: this.arrayPerfil.name,
                email: this.arrayPerfil.email,
                password: this.arrayPerfil.password
                })
                .then(function(response) {
                swal("Actualizado!", "Registro modificado con éxito!", "info", {button: false,timer: 1000});
                me.listarRegistro();
                }).catch(error=> {
                    if(error.response.status==422){
                        this.errorsCampo=error.response.data.errors;
                    }
                });
            },
    },
    mounted(){
        this.listarRegistro()
    }
}
</script>
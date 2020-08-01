
@extends('principal')
@section('templatePrincipal')
    @if(Auth::check())
        @if(Auth::user()->rol_id==1)
            <template v-if="menu==0">
                <dashboard></dashboard>
                <!-- @include('principal.includes.bienvenida') -->
            </template>
            
            <template v-if="menu==1">
                <registro-ocurrencia></registro-ocurrencia>
            </template>
            
            <template v-if="menu==2">
                <ultima-ocurrencia></ultima-ocurrencia>
            </template>
            <template v-if="menu==3">
                <sintesis-diaria></sintesis-diaria>
            </template>
           
            <template v-if="menu==4">
                <grafico-estadistico></grafico-estadistico>
            </template>

            <template v-if="menu==5">
                <resumen-incidencia></resumen-incidencia>
            </template>

            <template v-if="menu==6">
                <grafico-lineal></grafico-lineal>
            </template>
            
            
            <template v-if="menu==7">
                <record-intervenciones></record-intervenciones>
            </template>
            
            <template v-if="menu==8">
                <record-personal></record-personal>
            </template>

            <template v-if="menu==9">
                <generico></generico>
            </template>

            <template v-if="menu==10">
                <incidente></incidente>
            </template>

            <template v-if="menu==11">
                <tipo-incidente></tipo-incidente>
            </template>
            
            <template v-if="menu==12">
                <zona></zona>
            </template>
            
            <template v-if="menu==13">
                <ubicacion></ubicacion>
            </template>
            
            <template v-if="menu==14">
                <unidad-movil></unidad-movil>
            </template>
            
            <template v-if="menu==15">
                <fuente-imagen></fuente-imagen>
            </template>

            <template v-if="menu==16">
                <cargo></cargo>
            </template>
            
            <template v-if="menu==17">
                <personal-servicio></personal-servicio>
            </template>
            
            <template v-if="menu==18">
                <entidad></entidad>
            </template>
            
            <template v-if="menu==19">
                <organizacion></organizacion>
            </template>

            <template v-if="menu==20">
                <user></user>
            </template>

            <template v-if="menu==21">
                <rol></rol>
            </template>
            
            <template v-if="menu==50">
                <perfil></perfil>
            </template>
            
        @elseif(Auth::user()->rol_id==2)
            
        @elseif(Auth::user()->rol_id==3)
            
        @else
        @endif
    @endif
@endsection
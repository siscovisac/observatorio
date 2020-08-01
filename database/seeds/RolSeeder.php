<?php

use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rols')->insert(['nombre'=>'ADMINISTRADOR',       'descripcion'=> 'Autorizado en la configuración, autorización y administración de permisos en la aplicación']);
        DB::table('rols')->insert(['nombre'=>'OPERADOR',          'descripcion'=>	'Encargado de controlar el registro de ocurrencias']);   
        DB::table('rols')->insert(['nombre'=>'INVITADO',            'descripcion'=>	'Usuario que verifica avances y fotos de ocurrencias']);   
    }
}

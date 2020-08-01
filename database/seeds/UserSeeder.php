<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            ['name'=>'ISRAEL AGUIRRE',
                'email'=> 'eaguirreblas@gmail.com',
                'email_verified_at' => now(),
                'password' =>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
                'rol_id'=>'1'
            ]);
    }
}

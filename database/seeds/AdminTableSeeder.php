<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Models\Admin', 1)->create([
            'name' => '超级管理员',
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);
    }
}

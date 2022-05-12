<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Admin::create(['name'=>'User','email'=>'user12@gmail.com','password'=>md5(md5('user@123')),'image'=>'admin.jpg']); 
    }
}

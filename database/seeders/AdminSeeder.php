<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        Admin::create(['name'=>'Admin','email'=>'admin12@gmail.com','password'=>md5(md5('admin@123')),'image'=>'admin.jpg']);  
    }

}

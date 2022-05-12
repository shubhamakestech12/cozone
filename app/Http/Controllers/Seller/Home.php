<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Home extends Controller
{
   public function index(){
    
    $data['title'] = 'Seller dashboard';
  
    $data['content'] = view('seller/dashboard')->render();

    return view('sellerview',$data);

   }
}

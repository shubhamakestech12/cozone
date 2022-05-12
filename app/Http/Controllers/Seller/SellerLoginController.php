<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use Validator;


class SellerLoginController extends Controller
{
    public function index(){
        
        return view('seller/login');

    }
    public function sellerLogin(Request $request){

        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',           
        ]);
     
        if($validator->passes()) {
            $mobile = $request->post('mobile');
            $password = md5(md5($request->password));
            $data = Seller::where(['mob_no'=>$mobile,'password'=>$password,'is_active'=>1,'is_deleted'=>1])->first(['id','mob_no','name']); 
            if(is_null($data) or empty($data)){
                return response()->json(['status'=>3]);
            }else{
                session([
                    'is_login' => TRUE,
                    'session_id'=>  md5((md5($data->mob_no))),
                    'seller_type' => 'seller',
                    'seller_data' => $data
                ]);
                return response()->json(['status'=>1]);
            }
        }   
        return response()->json(['error'=>$validator->errors()->all(),'status'=>2]);      
       
    }
    // logout
   public function logout(){
       session()->flush();
       return redirect("seller-login");
   }
}

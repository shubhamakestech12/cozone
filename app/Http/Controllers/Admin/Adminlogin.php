<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Admin;

class Adminlogin extends Controller
{
    public function index(){
        
        return view('admin/login');

    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',           
        ]);

        if($validator->passes()) {
            $username = $request->post('username');
            $password = md5(md5($request->password));
            $data = Admin::where(['email'=>$username,'password'=>$password,'is_active'=>1,'is_deleted'=>1])->first(['id','email','name']);
            if(is_null($data) or empty($data)){
                return response()->json(['status'=>3]);
            }else{
                session([
                    'is_login' => TRUE,
                    'session_id'=>  md5((md5($data->email))),
                    'user_type' => 'admin',
                    'admin_data' => $data
                ]);
                return response()->json(['status'=>1]);
            }
        }   
        return response()->json(['error'=>$validator->errors()->all(),'status'=>2]);      
       
    }


    // LogOut Function
    public function logout(){
        session()->flush();
        return redirect('admin-login');
    }// End of Function

    
}

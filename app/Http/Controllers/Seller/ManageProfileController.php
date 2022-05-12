<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Gallery;
use App\Models\ProductImage;
use App\Models\SellerConfiguration;
use Validator;

class ManageProfileController extends Controller
{
    function __construct(){

    }// end of construct
    public function index(){
        $data['title'] = "Manage Profile";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css','css/dropzone.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/manage_profile.js','js/ckeditor5/build/ckeditor.js',);
        $page_data['countries'] = Country::where('is_active',1)->get(['id','name']);
        $page_data['all_img'] = Gallery::where(['galleries.is_active'=>1,'galleries.seller_id'=>session('seller_data')->id])->get(['id','file_path']);
        $data['content'] = view('seller.manageprofile',$page_data)->render();
        return view('sellerview',$data);
    }
    public function getState(Request $request){
        $data['states'] = State::where("country_id",$request->country_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
    public function getCity(Request $request){
        $data['cities'] = City::where("state_id",$request->state_id)
                    ->get(["name","id"]);
        return response()->json($data);
    }
    public function setSellerdata(Request $request){
        $data = Seller::where('id',session('seller_data')['id'])->first();
        return response()->json($data);
    }

    public function setSellerConfig(Request $request){
        $data = SellerConfiguration::where('id',session('seller_data')['id'])->first();
        return response()->json($data);
    }

    //edit seller config function
    public function editSellerConfig(Request $request){
            $formdata = array();
            $id = $request->id;
                $formdata['min_ord_value'] = $request->minovalue;
                $formdata['expe_delivery'] = $request->expe_del;
                if(!empty($id)){
                    $res = SellerConfiguration::where('id',$id)->update($formdata);
                    if(!empty($res)){
                        return response()->json(['msg'=>'Updated Successfully !','status'=>1]);
                    }else{
                        return response()->json(['msg'=>"Can't Updated Seller Profile !",'status'=>2]);
                    }
                }
        
    }//end of function

  // manage Profile
    public function editSellerprofile(Request $request){
        $formdata = array();
        $id = $request->id;
        
            $formdata['name']   = $request->name;
            $formdata['address']   = $request->address;
            $formdata['country']   = $request->country;
            $formdata['state']   = $request->state;
            $formdata['city']   = $request->city;
            $formdata['pin']   = $request->p_number;
            $formdata['reg_no']   = $request->reg;
            $formdata['business_name']   = $request->business;
            $formdata['is_active']  = 1;
            $formdata['is_deleted'] = 1;

            if(!empty($request->file('certificate'))){
                $filename = uniqid().'.'.$request->file('certificate')->getClientOriginalExtension(); 
                $request->file('certificate')->move(public_path('uploads'), $filename);
                $formdata['certificate'] = url('uploads').'/'.$filename;
            }
            if(!empty($request->file('logo'))){
                $filename = uniqid().'.'.$request->file('logo')->getClientOriginalExtension(); 
                $request->file('logo')->move(public_path('uploads'), $filename);
                $formdata['logo'] = url('uploads').'/'.$filename;
            }

            if(!empty($id) and !is_null($id)){
                $formdata['updated_by'] = $id;
                $res = Seller::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'Seller Profile Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Seller Profile !",'status'=>2]);
                }
            }
    }//end of function

    // manage description
    public function manageDese(Request $request){ 
        $formdata = array();
        $validator = Validator::make($request->all(),[
            'description' => 'required',
        ]);
        if($validator->passes()){
            $formdata['description']   = $request->description;
            if(!empty($id) and !is_null($id)){
                $formdata['updated_by'] = $id;
                $res = Seller::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'Description Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Description !",'status'=>2]);
                }
            }
        }else{
            return response()->json(['error'=>$validator->errors()->all(),'status'=>9]);
        }   
    }//End Of Functionss
    
 //Save Gallery img   
    public function saveGallery(Request $request){
          $formdata = array();
          $id = $request->id;
          $validator = Validator::make($request->all(),[
              'files' => 'required'
          ]);
          if($validator->passes()){
             $formdata['seller_id'] = session('seller_data')->id;
             $formdata['created_by'] = session('seller_data')->id;
             $formdata['is_active']  = 1;
             $formdata['is_deleted'] = 1;
             $all_img = $request->file('files');
             $resp = array();
            if($all_img){
                foreach($all_img as $key => $value){
                     $filename = uniqid().'.'.$value->getClientOriginalExtension();
                     $value->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                     $formdata['file_path'] = url('uploads',[date('Y'),date('m'),$filename]);
                     $resp[] = Gallery::insertGetId($formdata);
                }
                if(count($resp) > 0){
                     if(!empty($resp)){
                         return response()->json(['msg'=>'Gallery Saved Successfully !','status' => 1]);
                     }else{
                         return response()->json(['msg'=>"Can't Saved Gallery !",'status' => 2]);
                     }         
                }
            }
        }else{
            return response()->json(['error'=>$validator->errors()->all(),'status'=>9]); 
        }    
    }//End Of Function

    public function deleteGallery(Request $request){
        if($request->flag){
            $row = ProductImage::where('id',$request->id)->delete();
        }else{
            $row = Gallery::where('id',$request->id)->delete();
        }
        if(empty($row)){
            return response()->json(['msg'=>"Can't Deleted !",'status'=>2]);
        }else{
          return response()->json(['msg'=>"Deleted Successfully !",'status'=>1]);
        }

    }//End if Function


}



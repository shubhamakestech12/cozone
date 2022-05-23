<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Country;
use App\Models\City;
use DataTables;
Use Str;
use Validator;
class CityController extends Controller
{
    function __construct(){

    }// end of construct

    public function index(){
        $data['title'] = 'City';
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/city.js');
        $country = Country::where(['is_deleted'=>1])->get(['id','name']);
        $pagedata['countries']  = $country;
        $data['content'] = view('admin.customlist',$pagedata)->render();
        return view('template',$data);
    }//end of function
    
     // show data
     public function saveCity(Request $request){ 

        $formdata = array();

        $id = $request->id;

        $validator = Validator::make($request->all(), [
                'city_name'      => 'required',
                'country'      => 'required',
                'image'      => 'required',
            ]);

        if($validator->passes()){
            $formdata['location']   = $request->city_name;
            $formdata['country_id']   = $request->country;
            if(!empty($request->file('image'))){
                $filename = uniqid().'.'.$request->file('image')->getClientOriginalExtension(); 
                $request->file('image')->move(public_path('uploads'), $filename);
                $formdata['image'] = url('uploads').'/'.$filename;
            }

            if(!empty(@$id) and !is_null(@$id)){
                
                $res = City::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'City Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated City !",'status'=>2]);
                }
            }else{
                $res = City::insertGetId($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>' Save Successfully !','status' => 1]);
                }else{
                    return response()->json(['msg'=>"Can't Save !",'status' => 2]);
                }
            }
        }else{
            return response()->json(['error'=>$validator->errors()->all(),'status'=>9]);
        }

    }//End Of Functionss

   // show data
    public function showCity(Request $request){
        if($request->ajax()) {
           $data = City::where(['is_deleted'=>1])
            ->get(['cities.location as name','cities.is_active','cities.id as id','cities.is_deleted']);
            return Datatables::of($data)
                ->addIndexColumn()
                
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']),$request->get('search'))){
                                return true;
                            }else if (Str::contains(Str::lower($row['id']), Str::lower($request->get('search')))) {
                                return true;
                            }

                            return false;
                        });
                    }

                })
                ->addColumn('status', function($row){
                    if($row->is_active == 1){
                        return '<div class="userDatatable-content d-inline-block" data-toggle="tooltip" data-placement="top" title="Click To Deactive" rel="tooltip" title="Change Status" onclick="status_seller('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Active</span> </div>';
                    }else{
                        return '<div class="userDatatable-content d-inline-block" data-toggle="tooltip" data-placement="top" title="Click To Active" rel="tooltip" title="Change Status" onclick="status_seller('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function($row){

                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li onclick="edit_seller('.$row->id.')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li onclick="delete_seller('.$row->id.')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                   
                })

                
                ->rawColumns(['status','action'])
                ->make(true);
        }

    }//End of Function


    //  update status
    public function statusCity(Request $request){
        $id = $request->id;
        $status = $request->status;
        if($status == 1){
            $data['is_active'] = 2;
        }
        else if($status == 2){
            $data['is_active'] = 1;
        }
        $row = City::where('id',$id)->update($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Status Updated !",'status'=>2]);
        }else{
            return response()->json(['msg'=>'Status Updated !','status'=>1]);
        }
    }//End of function



 //Update Function
    public function editCity(Request $request){
        $id = $request->id;
        $data = City::where('id',$id)->first();
        return response()->json($data);
    }//End of Function



    // Delete fucntion
    public function deleteCity(Request $request){
        $id = $request->id;
        if(!empty($id))
        {
            $data['is_deleted'] = 2;
        }
        $row = City::where('id',$id)->delete();

        if(empty($row)){
            return response()->json(['msg'=>"Can't Deleted City !",'status'=>2]);
        }else{

          return response()->json(['msg'=>"City Deleted Successfully !",'status'=>1]);
        }
    }//End if Function

}

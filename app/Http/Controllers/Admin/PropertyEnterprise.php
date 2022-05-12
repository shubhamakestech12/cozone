<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Validator;
use DataTables;
class PropertyEnterprise extends Controller
{
    //
     //
     public function index(){
        $data['title'] = "Property EnterPrise";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/property_enterprise.js');
        $pageData['plans'] = DB::table('plans_enterprise')->where(['is_deleted'=>1])->get(['id','plan_name','is_deleted','is_active']);
        $pageData['properties'] =DB::table('property_details')->where(['is_deleted'=>1])->get(['id','title','is_deleted','is_active']);
        $pageData['amenties'] =DB::table('amenties')->where(['is_deleted'=>1])->get(['id','name','is_deleted','is_active']);
        $data['content'] = view('admin.property_enterprise',$pageData)->render();
        return view('template',$data);
    }//end of function


    public function savePropertyEnterprise(Request $request){ 

        $formdata = array();

        $id = $request->id;

        $validator = Validator::make($request->all(), [
                'property'      => 'required',
                'plan'      => 'required',
                'price'=>'required',
            ]);

        if($validator->passes()){
            $formdata['property_id']   = $request->property;
            $formdata['plan_id']   = $request->plan;
            $formdata['amenties']   = implode(',',$request->amenties);
            $formdata['price']   = $request->price;
            if(!empty(@$id) and !is_null(@$id)){
                
                $res = DB::table('property_enterprise')->where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'Property Enterprise Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Property Enterprise !",'status'=>2]);
                }
            }else{
                $res = DB::table('property_enterprise')->insertGetId($formdata);
                
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
    public function showPropertyEnterprise(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('property_enterprise')->where('property_enterprise.is_deleted', 1)->join('plans_enterprise','property_enterprise.plan_id','=','plans_enterprise.id')
            ->join('property_details','property_details.id','=','property_enterprise.property_id')
                ->get(['property_enterprise.id as id', 'property_details.title as title','plans_enterprise.plan_name as plan_name',DB::raw('CONCAT("₹ ",property_enterprise.price) as price'),'property_enterprise.amenties as amenties','property_enterprise.is_active as is_active','property_enterprise.is_deleted as is_deleted']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Deactive" rel="tooltip" title="Change Status" onclick="status_property_memebership(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Active</span> </div>';
                    } else {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Active" rel="tooltip" title="Change Status" onclick="status_property_memebership(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li rel="tooltip" title="Edit" onclick="edit_property_enterprise(' . $row->id . ')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li rel="tooltip" title="Delete" onclick="delete_property_enterprise(' . $row->id . ')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['title']), $request->get('search'))) {
                                return true;
                            }

                            return false;
                        });
                    }
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    } //End of Function

    public function statusPropertyEnterprise(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == 1) {
            $data['is_active'] = 2;
            $data['updated_at'] = 2;
        } else if ($status == 2) {
            $data['is_active'] = 1;
            $data['updated_at'] = 2;
        }
        $row = DB::table('property_enterprise')->where('id', $id)->update($data);
        if (empty($row)) {
            return response()->json(['msg' => "Can't Status Updated !", 'status' => 2]);
        } else {
            return response()->json(['msg' => 'Status Updated !', 'status' => 1]);
        }
    } //End of function

    //Update Function
    public function editPropertyEnterprise(Request $request)
    {
        $id = $request->id;
        $data = DB::table('property_enterprise')->where('id', $id)->first();
        return response()->json($data);
    } //End of Function

    // Delete fucntion
    public function deletePropertyEnterprise(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $data['is_deleted'] = 2;
        }
        $row = DB::table('property_enterprise')->where('id', $id)->delete();
        if (empty($row)) {
            return response()->json(['msg' => "Can't delete Property EnterPrise  !", 'status' => 2]);
        } else {

            return response()->json(['msg' => "Property EnterPrise deleted Successfully !", 'status' => 1]);
        }
    } //End if Function

    public function getSpaces(Request $req)
    {
        $id = $req->id;

        $data = AddSpace::where('city_id',$id)->get(['id','space_name']);

        if(!empty($data)){
            return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
            return array('status'=>false,'code'=>201,'data'=>'data not found');
            
        }
    }//end of function
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SpaceType;
use App\Models\City;
use App\Models\AddSpace;
use App\Models\TopCoworking;
use Validator;
use DataTables;
class TopCoWorkingController extends Controller
{
    //
    public function index(){
        $data['title'] = "Top Coworkings";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/top_coworking.js');
        $pageData['space_types'] = SpaceType::where(['is_deleted'=>1])->get(['id','name','is_deleted','is_active']);
        $pageData['cities'] = City::where(['is_deleted'=>1])->get(['id','location','is_deleted','is_active']);
        $data['content'] = view('admin.topcoworking',$pageData)->render();
        return view('template',$data);
    }//end of function


    public function saveTopCoworking(Request $request){ 

        $formdata = array();

        $id = $request->id;

        $validator = Validator::make($request->all(), [
                'space_types'      => 'required',
                'city'      => 'required',
                'spaces'=>'required'
            ]);

        if($validator->passes()){
            $formdata['space_type']   = $request->space_types;
            $formdata['city']   = $request->city;
            $formdata['space_names']   = implode(',',$request->spaces);
            if(!empty(@$id) and !is_null(@$id)){
                
                $res = TopCoworking::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'plan Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Plan !",'status'=>2]);
                }
            }else{
                $res = TopCoworking::insertGetId($formdata);
                
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
    public function showTopCoworking(Request $request)
    {
        if ($request->ajax()) {
            $data = TopCoworking::where('top_coworkings.is_deleted', 1)->join('space_types','space_types.id','=','top_coworkings.space_type')
            ->join('cities','top_coworkings.city','=','cities.id')
                ->get(['top_coworkings.id as id', 'top_coworkings.space_names as space_names','space_types.name as space_type_name','cities.location as location','top_coworkings.is_active as is_active','top_coworkings.is_deleted as is_deleted']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Deactive" rel="tooltip" title="Change Status" onclick="status_top_coworking(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Active</span> </div>';
                    } else {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Active" rel="tooltip" title="Change Status" onclick="status_top_coworking(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li rel="tooltip" title="Edit" onclick="edit_topCoworking(' . $row->id . ')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li rel="tooltip" title="Delete" onclick="delete_top_coworking(' . $row->id . ')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']), $request->get('search'))) {
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

    public function statusTopCoworking(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == 1) {
            $data['is_active'] = 2;
        } else if ($status == 2) {
            $data['is_active'] = 1;
        }
        $row = TopCoworking::where('id', $id)->update($data);
        if (empty($row)) {
            return response()->json(['msg' => "Can't Status Updated !", 'status' => 2]);
        } else {
            return response()->json(['msg' => 'Status Updated !', 'status' => 1]);
        }
    } //End of function

    //Update Function
    public function editTopCoworking(Request $request)
    {
        $id = $request->id;
        $data = TopCoworking::where('id', $id)->first();
        return response()->json($data);
    } //End of Function

    // Delete fucntion
    public function deleteTopCoworking(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $data['is_deleted'] = 2;
        }
        $row = TopCoworking::where('id', $id)->delete();
        if (empty($row)) {
            return response()->json(['msg' => "Can't delete Top Spaces  !", 'status' => 2]);
        } else {

            return response()->json(['msg' => "Plan deleted Successfully !", 'status' => 1]);
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
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
Use Str;
use App\Models\AddSpace;
use App\Models\SpaceType;
use App\Models\City;
use App\Models\Amenty;
Use DB;
use Validator;

class AddSpaceController extends Controller
{
    //
    public function index(){
        $data['title'] = "Add Space";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/add_space.js');
        $pagedata['cities'] = City::where('is_deleted',1)->get(['id','location']);
        $pagedata['space_types'] = SpaceType::where('is_deleted',1)->get(['id','name']);
        $pageData1['amenties'] = Amenty::where('is_deleted',1)->get(['id','name']);
        $pagedata['space_for'] = DB::table('space_for')->where('is_deleted',1)->get(['id','name']);
        $data['content'] = view('admin.addspace',$pagedata, $pageData1)->render();
        return view('template',$data);
    }//end of function


    public function saveAddSpace(Request $request){ 

        $formdata = array();

        $id = $request->id;

        $validator = Validator::make($request->all(), [
                'space_name'      => 'required',
                'space_type'      => 'required',
                'address'      => 'required',
                'city'      => 'required',
                'seat_capacity'      => 'required',
                'area_type'      => 'required',
                'email'      => 'required',
                'mobile'      => 'required|numeric|regex:/^[6-9]{1}[0-9]{9}$/',
                'starting_price'      => 'required|numeric',
                'amenties'      => 'required',
                'space_for'      => 'required',
                'image'      => 'required|max:5000',
            ]);

        if($validator->passes()){
            $formdata['space_name']   = $request->space_name;
            $formdata['space_type']   = $request->space_type;
            $formdata['address']   = $request->address;
            $formdata['city_id']   = $request->city;
            $formdata['seat_capacity']   = $request->seat_capacity;
            $formdata['area']   = $request->area_type;
            $formdata['email']   = $request->email;
            $formdata['mobile']   = $request->mobile;
            $formdata['starting_price']   = $request->starting_price;
            $formdata['amenties']   = implode(',',$request->amenties);
            $formdata['space_for']   = $request->space_for;
            
            if(!empty(@$id) and !is_null(@$id)){
                $res = AddSpace::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'Space Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Space !",'status'=>2]);
                }
            }else{
                $res = AddSpace::insertGetId($formdata);
                $all_img = $request->file('image');
                $resp = array();
                if(!empty($all_img) and count($all_img) > 0 ){
                foreach($all_img as $key => $value){
                    $filename = uniqid().'.'.$value->getClientOriginalExtension();
                    $value->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                    $formdataa['image'] = url('uploads',[date('Y'),date('m'),$filename]);
                    $formdataa['space_id'] = $res;
                    $resp[] = DB::table('property_image')->insertGetId($formdataa);
                }
            }
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
    public function showAddSpace(Request $request)
    {
        if ($request->ajax()) {
            $data = AddSpace::where('add_spaces.is_deleted', 1)->join('cities','cities.id','=','add_spaces.city_id')
                    ->join('space_types','space_types.id','=','add_spaces.space_type')
                ->get(['add_spaces.id as id', 'add_spaces.space_name as name',DB::raw('CONCAT("₹ ",add_spaces.starting_price) as price'),'space_types.name as space_name','add_spaces.address as address','cities.location as city_name','add_spaces.seat_capacity as seat_capacity','add_spaces.area as area','add_spaces.is_active as is_active','add_spaces.is_deleted as is_deleted']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Deactive" rel="tooltip" title="Change Status" onclick="status_add_space(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Active</span> </div>';
                    } else {
                        return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Active" rel="tooltip" title="Change Status" onclick="status_add_space(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li rel="tooltip" title="Edit" onclick="edit_add_space(' . $row->id . ')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li rel="tooltip" title="Delete" onclick="delete_add_space(' . $row->id . ')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
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

    public function statusAddSpace(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == 1) {
            $data['is_active'] = 2;
        } else if ($status == 2) {
            $data['is_active'] = 1;
        }
        $row = AddSpace::where('id', $id)->update($data);
        if (empty($row)) {
            return response()->json(['msg' => "Can't Status Updated !", 'status' => 2]);
        } else {
            return response()->json(['msg' => 'Status Updated !', 'status' => 1]);
        }
    } //End of function

    //Update Function
    public function editAddSpace(Request $request)
    {
        $id = $request->id;
        $data = AddSpace::where('id', $id)->first();
        return response()->json($data);
    } //End of Function

    // Delete fucntion
    public function deleteAddSpace(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $data['is_deleted'] = 2;
        }
        $row = AddSpace::where('id', $id)->delete();
        if (empty($row)) {
            return response()->json(['msg' => "Can't Deleted Add Space !", 'status' => 2]);
        } else {

            return response()->json(['msg' => "Add Space  Deleted Successfully !", 'status' => 1]);
        }
    } //End if Function
}

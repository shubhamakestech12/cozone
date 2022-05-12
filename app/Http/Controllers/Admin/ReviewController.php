<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DataTables;
use DB;
use Str;
class ReviewController extends Controller
{
    //
     //
     public function index(){
        $data['title'] = "Reviews";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','admin/custome_js/review.js');
        
        $data['content'] = view('admin.review')->render();
        return view('template',$data);
    }
    public function showReview(Request $request)
    {
        $data = DB::table('reviews')->where('reviews.is_deleted', 1)
                ->join('property_details','reviews.property_id','=','property_details.id')
            ->get(['reviews.id as id', 'property_details.title as title','reviews.user_id as user_id','reviews.review as review','reviews.is_active as is_active','reviews.is_deleted as is_deleted']);
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {
                if ($row->is_active == 1) {
                    return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Deactive" rel="tooltip" title="Change Status" onclick="status_review(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Active</span> </div>';
                } else {
                    return '<div class="userDatatable-content d-inline-block " data-toggle="tooltip" data-placement="top" title="Click To Active" rel="tooltip" title="Change Status" onclick="status_review(' . $row->id . ',' . $row->is_active . ')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status' . $row->id . '">Deactive</span> </div>';
                }
            })
            ->addColumn('action', function ($row) {
                return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"><li rel="tooltip" title="Delete" onclick="delete_review(' . $row->id . ')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
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
        
    } //End of Function
    
    //delete function
    public function deleteReview(Request $request)
    {
        $id = $request->id;
        if (!empty($id)) {
            $data['is_deleted'] = 2;
        }
        $row = DB::table('reviews')->where('id', $id)->delete();
        if (empty($row)) {
            return response()->json(['msg' => "Can't Delete Review !", 'status' => 2]);
        } else {

            return response()->json(['msg' => "Review Deleted Successfully !", 'status' => 1]);
        }
    } //End if Function

    public function statusReview(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        if ($status == 1) {
            $data['is_active'] = 2;
        } else if ($status == 2) {
            $data['is_active'] = 1;
        }
        $row = DB::table('reviews')->where('id', $id)->update($data);
        if (empty($row)) {
            return response()->json(['msg' => "Can't Status Updated !", 'status' => 2]);
        } else {
            return response()->json(['msg' => 'Status Updated !', 'status' => 1]);
        }
    } //End of function
}//end of class

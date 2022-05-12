<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity_type;
use App\Models\Commodity;
use DB;
use DataTables;
use Validator;
use Str;

class CommodityRatesController extends Controller
{
    function __construct(){

    }// end of construct

    public function index(){
        $data['title'] = "Commodity Rate";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/commodity_rates.js');
        $page_data1['commodity_type'] = Commodity_type::get(['id','title']);
        $data['content'] = view('seller.commodity_rates',$page_data1)->render();
        return view('sellerview',$data);
    }

    
    // insertion
    public function saveCommodityrates(Request $request){
        //dd($request->all());
        $formdata = array();
        $id = $request->id;
        $validator = Validator::make($request->all(), [
                'commodity'      => 'required_if:id,=,null',  
                'price'      => 'required',  
                'date' => 'required_if:id,=,null',  
            ]);
        if($validator->passes()){
            
            $formdata['price']   = $request->price;
            $formdata['seller_id'] = session('seller_data')->id;
            $date = date("Y-m-d", strtotime(str_replace('/', '-',$request->date)));
            if(!$id){
                $formdata['date']  = $date;
            }
            $formdata['is_active']  = 1;
            $formdata['is_deleted'] = 1;

            if(!empty($id) and !is_null($id)){
                $formdata['updated_by'] = $id;
                $data = Commodity::where(['date'=>$date,'commodity_type'=>$request->commodity,'price'=>$request->price])->first();
                if(empty($data)){
                    $res = Commodity::where('id',$id)->update($formdata);
                    if(!empty($res)){
                        return response()->json(['msg'=>'Commodity Updated Successfully !','status'=>1]);
                    }else{
                        return response()->json(['msg'=>"Can't Updated Commodity !",'status'=>2]);
                    }
                }else{
                    return response()->json(['msg'=>"This date entry already exists !",'status'=>2]);
                }
            }else{
            $formdata['commodity_type']   = $request->commodity;
            $formdata['created_by'] = session('seller_data')->id;
            $data = Commodity::where(['date'=>$formdata['date'],'commodity_type'=>$request->commodity])->first();
            if(empty($data) and is_null($data)){
                    $res = Commodity::insertGetId($formdata);
                    if(!empty($res)){
                        return response()->json(['msg'=>' Save Successfully !','status' => 1]);
                    }else{
                        return response()->json(['msg'=>"Can't Save !",'status' => 2]);
                    }
                }else{
                    return response()->json(['msg'=>"This date entry already exists !",'status'=>2]);
                }
            }
        }else{
            return response()->json(['error'=>$validator->errors()->all(),'status'=>9]);
        }

    }//End Of Functions  

         // show data
    public function showCommodity(Request $request){
        if($request->ajax()) {
            $data = Commodity::join('commodity_types', 'commodities.commodity_type', '=', 'commodity_types.id')
            ->where(['commodities.is_deleted'=>1,'commodities.seller_id'=>session('seller_data')->id])
            ->get(['commodities.id','commodity_types.title','commodities.commodity_type','commodities.price',DB::raw("DATE_FORMAT(commodities.date, '%d-%m-%Y') AS date"),'commodities.is_active']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    if($row->is_active == 1){
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="status_commodity('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Active</span> </div>';
                    }else{
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="status_commodity('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function($row){
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li rel="tooltip" title="Edit" onclick="edit_commodity('.$row->id.')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li rel="tooltip" title="Delete" onclick="delete_commodity('.$row->id.')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                   
            })
                ->filter(function ($instance) use ($request) { 
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['title']),$request->get('search'))){
                                return true;
                            }

                            return false;
                        });
                    }

                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

    }//End of Function

    public function statusCommodity(Request $request){
        $id = $request->id;   
        $status = $request->status;
        if($status == 1){
            $data['is_active'] = 2;
            $data['updated_by'] = 1;
        }
        else if($status == 2){
            $data['is_active'] = 1;
            $data['updated_by'] = 1;
        }
        $row = Commodity::where('id',$id)->update($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Status Updated !",'status'=>2]);
        }else{
            return response()->json(['msg'=>'Status Updated !','status'=>1]);
        }
    }//End of function

    //Update Function
    public function editCommodity(Request $request){
        $id = $request->id;
        $data = Commodity::where('id',$id)->first();
        return response()->json($data);
    }//End of Function

     // Delete fucntion
    public function deleteCommodity(Request $request){
        $id = $request->id;
        if(!empty($id))
        {
            $data['is_deleted'] = 2;
        }
        $row = Commodity::where('id',$id)->update($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Deleted Commodity !",'status'=>2]);
        }else{

          return response()->json(['msg'=>"Commodity Deleted Successfully !",'status'=>1]);
        }
    }//End if Function

}

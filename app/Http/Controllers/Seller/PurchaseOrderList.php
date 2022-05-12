<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use DataTables;
use DB;


class PurchaseOrderList extends Controller
{
    public function index(){
        $data['title'] = "Purchase Order List";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/purchaselist.js');
        $data['content'] = view('seller.purchase_list')->render();
        return view('sellerview',$data);
    }//end of function

    // show data
    public function showData(Request $request){
        if($request->ajax()) {
            $data = PurchaseOrder::join('sellers', 'sellers.id', '=', 'purchase_orders.seller_id')
            ->join('users', 'users.id', '=', 'purchase_orders.customer_id')
            ->join('commodity_types', 'commodity_types.id', '=', 'purchase_orders.commodity_id')
            ->join('payments','payments.order_id', '=','purchase_orders.id' )
            ->where('purchase_orders.is_active',1)
            ->where('sellers.id',session('seller_data')->id)
            ->get(['purchase_orders.id','purchase_orders.weight',DB::raw("CONCAT('₹',purchase_orders.price) as price"),'purchase_orders.order_status',DB::raw("DATE_FORMAT(purchase_orders.date, '%d-%m-%Y') AS date"),'sellers.name','users.name as customer_name','commodity_types.title','commodity_types.unit','payments.payment_status']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('weight', function($row){
                    if($row->weight){
                        return $row->weight.' '.$row->unit;
                    } 
                })
                ->addColumn('order_status', function($row){
                    if($row->order_status == '1'){
                       return '<select onchange="change_order_status(this);" data-id="'.$row->id.'" class="nav-link dropdown-toggle"><option value"0">Pending</option><option value"0">Approved</option><option value"0">Cancelled</option></select>';
                    }else if($row->order_status == '3'){
                        return '<span class="media-badge color-white bg-success">Confirmed</span>';
                    }else if($row->order_status == '9'){
                        return '<span class="media-badge color-white bg-danger">Cancelled</span>';
                    }
                })
                ->addcolumn('payment_status',function($row){    
                    if($row->payment_status == 'completed'){
                        return '<span class="media-badge color-white bg-success">Completed</span>';
                    }elseif($row->payment_status == 'cancelled'){
                        return '<span class="media-badge color-white bg-danger">Cancelled</span>';
                    }
                })

                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('name', 'LIKE', "%$search%");
                        });
                    }
                })
                    ->rawColumns(['order_status','payment_status'])
                ->make(true);
        }

    }//end of function

    public function changeOrderstatus(Request $request){
            $id = $request->update_id;
            $status = $request->status_id;
            $data['order_status'] = $status;
            $row = PurchaseOrder::where('id',$id)->update($data);
            if(empty($row)){
                return response()->json(['msg'=>"Can't changed status !",'status'=>2]);
            }else{
                return response()->json(['msg'=>'Order Status Updated !','status'=>1]);
            }
    }//End of function

   
}

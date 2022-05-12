<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManageOrder;
use App\Models\User;
use App\Models\ProductModel;
use DB;
use DataTables;
use Shuffle;

class ManageOrderController extends Controller
{
    public function index(){
        $data['title'] = "Manage Order";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css','css/dropzone.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/manage_order.js','js/ckeditor5/build/ckeditor.js',);
        $data['content'] = view('seller.manage_order')->render();
        return view('sellerview',$data);
    }//end of function

    // Show data
    public function showOrderlist(Request $request){
        if($request->ajax()) {
            $data = ManageOrder::join('users', 'users.id', '=', 'manage_orders.customer_id')
            ->join('order_statuses as ord_status','ord_status.id', '=','manage_orders.order_status' )
            ->join('order_statuses as pmt_status','pmt_status.id', '=', 'manage_orders.payment_status')
            ->where('manage_orders.is_active',1)
            ->get(['manage_orders.id','manage_orders.order_id',DB::raw("CONCAT('₹',manage_orders.amount) as amt"),DB::raw("DATE_FORMAT(manage_orders.order_date, '%d-%m-%Y') AS ord_date"),DB::raw("DATE_FORMAT(manage_orders.expe_delivery_date, '%d-%m-%Y') AS del_date"),'users.name as customer_name','users.mobile','ord_status.status_title as order_status','pmt_status.status_title as payment_status']);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('order_details', function($row){
                    if($row->order_id){
                        return '<a href="'.url('show_order_details'.'/'.$row->order_id).'"><span class="media-badge color-white bg-secondary mb-1">Order Id:'.$row->order_id.'</span><span class="media-badge color-white bg-primary mb-1">Order date:'.$row->ord_date.'</span><span class="media-badge color-white bg-info">Delivery date:'.$row->del_date.'</span></a>';
                    } 
                })
                ->addColumn('customer_info', function($row){
                    if($row->customer_name){
                   return '<span>Name:'.$row->customer_name.'<br/>Mobile: '.$row->mobile.'</span>';
                    }
                })
                ->addColumn('amt', function($row){
                    if($row->amt){
                    return '<span class="media-badge color-white bg-primary">'.$row->amt.'</span>';
                    } 
                })
                ->addColumn('order_status', function($row){
                    if($row->order_status){
                        $color = array("success","info","warning","primary","danger","secondary");
                        shuffle($color);
                        return '<span class="media-badge color-white bg-'.$color[0].'">'.$row->order_status.'</span>';
                    } 
                })
                ->addColumn('payment_status', function($row){
                    if($row->payment_status){
                        $color1 = array("success","info","warning","primary","danger","secondary");
                        shuffle($color1);
                    return '<span class="media-badge color-white bg-'.$color1[0].'">'.$row->payment_status.'</span>';
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
                    ->rawColumns(['order_details','order_status','amt','payment_status','customer_info'])
                ->make(true);
        }

    }//end of function

    // show order details
    public function showOrderdetails($id = ''){
        if(!empty($id)){
            $data['title'] = "Order Details";
            $order_data = array();
            $datas = ManageOrder::join('users', 'users.id', '=', 'manage_orders.customer_id')
            ->join('order_statuses as ord_status','ord_status.id', '=','manage_orders.order_status' )
            ->join('order_statuses as pmt_status','pmt_status.id', '=', 'manage_orders.payment_status')
            ->where(['manage_orders.is_active'=>1,'manage_orders.order_id'=>$id])
            ->first(['manage_orders.id','manage_orders.order_id',DB::raw("CONCAT('₹',manage_orders.amount) as amt"),DB::raw("DATE_FORMAT(manage_orders.order_date, '%d-%m-%Y') AS ord_date"),DB::raw("DATE_FORMAT(manage_orders.expe_delivery_date, '%d-%m-%Y') AS del_date"),'users.name as customer_name','users.mobile','users.mobile','users.address','manage_orders.payment_status','ord_status.status_title as order_status','pmt_status.status_title as payment_status']);
            $product_data = ManageOrder::join('item_lists','manage_orders.order_id','=','item_lists.order_id')
            ->join('products','item_lists.product_id','=','products.id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->join('commodity_types', 'products.commodity_type', '=', 'commodity_types.id')
            ->join('commodities', 'commodities.commodity_type', '=', 'commodity_types.id')
            ->where('product_images.is_featured','yes')
            ->where('item_lists.order_id',$id)
            ->get(['products.id','products.title','product_images.file_path','products.c_weight','products.o_weight','commodity_types.weight','commodities.price','products.make_charge','products.o_charge']);
            $order_data['product_details'] = $product_data;
            $order_data['order_details'] = $datas;
            $data['content'] = view('seller.orderdetails',$order_data)->render();
            return view('sellerview',$data);
        }else{
            return view('seller.manage_order');
        }
    }// End of Function


}



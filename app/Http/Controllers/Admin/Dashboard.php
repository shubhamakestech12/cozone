<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;

class Dashboard extends Controller
{
    public function index(){
        $data['title'] = "Dashboard";
        // $data['tamtcollection'] = PurchaseOrder::join('payments','purchase_orders.id', '=' ,'payments.order_id')
        // ->where(['purchase_orders.order_status'=>'approved','payments.payment_status'=>'completed'])->sum('order_amt');
        // $data['tgoldcollection'] = PurchaseOrder::join('payments','purchase_orders.id','=','payments.order_id')
        // ->where(['purchase_orders.order_status'=>'approved','payments.payment_status'=>'completed','purchase_orders.commodity_id'=>1])->sum('weight');
        $data['content'] = view('admin/dashboard',$data)->render();
        return view('template',$data); 
    }
    // Show Amt and Gold Collection
}


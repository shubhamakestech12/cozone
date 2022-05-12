<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Commodity_type;
use App\Models\ProductModel;
use App\Models\ProductImage;
use App\Models\ProductCategory;
use App\Models\ProductSubcat;
use App\Models\Commodity;
use Validator;
use DataTables;
use DB;
use Str;
use Carbon\Carbon;
class ProductController extends Controller
{
    function __construct(){

    }// end of construct

    public function index(){
        $data['title'] = "Manage Product";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css','css/dropzone.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/manage_product.js','seller/customejs/product_category.js','seller/customejs/subcategory.js','js/ckeditor5/build/ckeditor.js');
        $page_data1['commodity_type'] = Commodity_type::get(['id','title']);
        $page_data1['all_category'] = ProductCategory::get(['id','title']);
        $data['content'] = view('seller.product',$page_data1)->render();
        return view('sellerview',$data);
    }//end of function

    public function getSubcategory(Request $request){
        $data['all_subcat'] = ProductSubcat::where("category_id",$request->category_id)
                    ->get(['id','title']);
        return response()->json($data);
    }

    //Inert Product
    public function saveProduct(Request $request){
        $formdata = array();
        $id = $request->id;
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'commodity' => 'required',
            'category' => 'required',
            'subcat' => 'required',
            'c_weight' => 'required',
            'o_weight' => 'required',
            'make_charge' => 'required',
            'o_charge'  => 'required',
        ]);
        if($validator->passes()){
            $formdata['title']   = $request->title;
            $formdata['seller_id']   = session('seller_data')->id;
            $formdata['cat_id']   = $request->category;
            $formdata['subcat_id']   = $request->subcat;
            $formdata['commodity_type']   = $request->commodity;
            $formdata['description']   = $request->description;
            $formdata['c_weight']   = $request->c_weight;
            $formdata['o_weight']   = $request->o_weight;
            $formdata['make_charge']  = $request->make_charge;
            $formdata['o_charge']   = $request->o_charge;
            $formdata['is_active']  = 1;
            $formdata['is_deleted'] = 1;
            $formdata['created_by'] = session('seller_data')->id;
            $all_img = $request->file('files');
            $resp = array();
            if(!empty($id)){
                $formdata['updated_by'] =session('seller_data')->id;
                if($all_img){ 
                    foreach($all_img as $key => $value){
                         $filename = uniqid().'.'.$value->getClientOriginalExtension();
                         $value->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                         $formdata1['file_path'] = url('uploads',[date('Y'),date('m'),$filename]);
                         $formdata1['created_by'] = session('seller_data')->id;
                         $formdata1['is_featured'] = 'no';
                         $resp[] = ProductImage::insertGetId($formdata1);
                    } 
                    $filename = uniqid().'.'.$request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                    $formdata2['file_path'] = url('uploads',[date('Y'),date('m'),$filename]);
                    $formdata2['created_by'] = session('seller_data')->id;
                    $formdata2['is_featured'] = 'yes';
                    $resp[] = ProductImage::insertGetId($formdata2);
                    if(count($resp) > 0){
                         if(!empty($resp)){
                             return response()->json(['msg'=>'Saved Successfully !','status' => 1]);
                         }else{
                             return response()->json(['msg'=>"Can't Saved !",'status' => 2]);
                         }         
                    }
                }
                $res = ProductModel::where('id',$id)->update($formdata);
                if(!empty($res)){
                    return response()->json(['msg'=>'Product Updated Successfully !','status'=>1]);
                }else{
                    return response()->json(['msg'=>"Can't Updated Product !",'status'=>2]);
                }
            }else{
                $product_id = ProductModel::insertGetId($formdata);
                if($all_img){ 
                    foreach($all_img as $key => $value){
                         $filename = uniqid().'.'.$value->getClientOriginalExtension();
                         $value->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                         $formdata1['file_path'] = url('uploads',[date('Y'),date('m'),$filename]);
                         $formdata1['product_id'] = $product_id;
                         $formdata1['created_by'] = session('seller_data')->id;
                         $formdata1['is_featured'] = 'no';
                         $resp[] = ProductImage::insertGetId($formdata1);
                    } 
                    $filename = uniqid().'.'.$request->file('image')->getClientOriginalExtension();
                    $request->file('image')->move(public_path('uploads/'.date('Y').'/'.date('m')), $filename);
                    $formdata2['file_path'] = url('uploads',[date('Y'),date('m'),$filename]);
                    $formdata2['product_id'] = $product_id;
                    $formdata2['created_by'] = session('seller_data')->id;
                    $formdata2['is_featured'] = 'yes';
                    $resp[] = ProductImage::insertGetId($formdata2);
                    if(count($resp) > 0){
                         if(!empty($resp)){
                             return response()->json(['msg'=>'Saved Successfully !','status' => 1]);
                         }else{
                             return response()->json(['msg'=>"Can't Saved !",'status' => 2]);
                         }         
                    }
                }
            }
        }else{
            return response()->json(['error'=>$validator->errors()->all(),'status'=>9]);
        }

    }//End Of Functions

    // show data
    public function showProduct(Request $request){
        if($request->ajax()) { 
           $data = ProductModel::join('sellers', 'products.seller_id', '=', 'sellers.id')
            ->join('commodity_types', 'products.commodity_type', '=', 'commodity_types.id')
            ->join('commodities', 'commodities.commodity_type', '=', 'commodity_types.id')
            ->join('product_categories', 'products.cat_id', '=', 'product_categories.id')
            ->join('product_subcats', 'products.subcat_id', '=', 'product_subcats.id')
            ->where(['products.is_deleted'=>1,'products.seller_id'=>session('seller_data')->id])
            ->groupBy('products.id')
            // ->orWhere(DB::raw('DATE_FORMAT(NOW(),"%Y-%m-%d")'), '=', DB::raw('DATE_FORMAT(commodities.date, "%Y-%m-%d")'))
            // DB::raw('products.c_weight*(commodity_types.weight*commodities.price/100)+products.make_charge + products.o_charge AS total')
            // ->whereRaw('DATE_FORMAT(NOW(),"%Y-%m-%d") = DATE_FORMAT(commodities.date, "%Y-%m-%d")')
            ->get(['products.id','products.is_active','sellers.name','products.title','products.cat_id','products.subcat_id','commodity_types.title as commodity_name','product_categories.title as catname', 'product_subcats.title as subcatname','products.make_charge','products.o_charge','commodity_types.weight','products.c_weight','commodities.price','commodities.price']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('products', function($row){
                    if($row->title){
                        $sum = intval($row->c_weight)*(intval($row->weight)*intval($row->price)/100)+intval($row->make_charge)+intval($row->o_charge);
                        
                        return $row->title.'('.$row->commodity_name.') '.$row->catname.', '.$row->subcatname.' '.number_format($sum,2);
                    } 
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
                ->addColumn('images',function($row){
                    return '<a href="javascript:;" class="media-badge color-white bg-primary" onclick="showIamge('.$row->id.')">See Img</a>';
                })
                ->addColumn('status', function($row){
                    if($row->is_active == 1){
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="statusProduct('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Active</span> </div>';
                    }else{
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="statusProduct('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function($row){
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li onclick="edit_product('.$row->id.')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li onclick="deleteProduct('.$row->id.')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                   
                })
                ->rawColumns(['images','status','action'])
                ->make(true);
        }

    }//End of Function

    // update status
    public function projetStatus(Request $request){
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
        $row = ProductModel::where('id',$id)->update($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Status Updated !",'status'=>2]);
        }else{
            return response()->json(['msg'=>'Status Updated !','status'=>1]);
        }
    }//End of function

    //Update Function
    public function editProduct(Request $request){
        $id = $request->id;
        $data = ProductModel::join('product_images' , 'products.id' ,'=' ,'product_images.product_id')->where('products.id',$id)->groupBy('product_images.product_id')->first([DB::raw('GROUP_CONCAT(CASE WHEN product_images.is_featured = "no" THEN product_images.file_path ELSE NULL END) AS images'),DB::raw('GROUP_CONCAT(CASE WHEN product_images.is_featured = "no" THEN product_images.id ELSE NULL END) AS images_id'),'products.*','product_images.is_featured',DB::raw('GROUP_CONCAT(CASE WHEN product_images.is_featured = "yes" THEN product_images.file_path ELSE NULL END) AS featured_image')]);
        return response()->json($data);
    }//End of Function


    // Delete fucntion
    public function deleteProduct(Request $request){
        $id = $request->id;
        if(!empty($id))
        {
            $data['is_deleted'] = 2;
        }
        $row = ProductModel::where('id',$id)->delete($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Deleted Category !",'status'=>2]);
        }else{
          return response()->json(['msg'=>"Category Deleted Successfully !",'status'=>1]);
        }
    }//End if Function

    //show  function
    public function showImg(Request $request){   
        $img = ProductImage::where('product_id',$request->id)->get(['id','file_path','is_featured']);
        if(count($img) > 0 ){
            return response()->json(['status'=>1,'data'=>$img]);
        }else{
            return response()->json(['status'=>2,'data'=>[]]);
        }
    }//end of function


}

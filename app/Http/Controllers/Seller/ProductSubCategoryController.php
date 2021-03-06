<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductSubcat;
use App\Models\ProductCategory;
use Validator;
use Str;
use DataTables;

class ProductSubCategoryController extends Controller
{
    public function index(){
        $data['title'] = "Product Subcategory";
        $data['css_files'] = array('plugins\datatables\dataTables.bootstrap4.min.css','plugins\datatables\buttons.bootstrap4.min.css','plugins\datatables\responsive.bootstrap4.min.css','css/dropzone.css');
        $data['js_files'] = array('plugins\datatables\jquery.dataTables.min.js','plugins\datatables\dataTables.bootstrap4.min.js','plugins\datatables\dataTables.responsive.min.js','plugins\datatables\responsive.bootstrap4.min.js','plugins\datatables\dataTables.buttons.min.js','plugins\datatables\buttons.bootstrap4.min.js','seller/customejs/subcategory.js','js/ckeditor5/build/ckeditor.js',);
        $page_data1['all_cat'] = ProductCategory::get(['id','title']);
        $data['content'] = view('seller.subcategory',$page_data1)->render();
        return view('sellerview',$data);
    }//end of function

    //Save Category
    public function saveSubcategory(Request $request){
            $formdata = array();
            $id = $request->id;
            $validator = Validator::make($request->all(), [
                    'title'      => 'required',
                ]);
    
            if($validator->passes()){
                $formdata['title']   = $request->title;
                $formdata['category_id']   = $request->category;
                $slug = strtolower(Str::slug($request->title, '-'));
                $resp = $this->checkSlug($slug);
                if($resp != false){
                    $formdata['slug']   = strtolower($resp);
                }else{
                    $formdata['slug']   = strtolower(Str::slug($request->title, '-'));
                }
                $formdata['is_active']  = 1;
                $formdata['is_deleted'] = 1;
                $formdata['updated_by'] = 1;
                if(!empty($id) and !is_null($id)){
                    $formdata['updated_by'] = session('seller_data')->id;
                    $res = ProductSubcat::where('id',$id)->update($formdata);
                    if(!empty($res)){
    
                            return response()->json(['msg'=>'Category Updated Successfully !','status'=>1]);
                    }else{
    
                            return response()->json(['msg'=>"Can't Updated Category !",'status'=>2]);
                    }
    
                }else{
                    $formdata['created_by'] = session('seller_data')->id;
                    $res = ProductSubcat::insertGetId($formdata);
                    if(!empty($res)){
                            return response()->json(['msg'=>'Category Added Successfully !','status' => 1]);
                    }else{
                            return response()->json(['msg'=>"Can't Added Category !",'status' => 2]);
                    }
                }
            }else{
    
                return response()->json(['error'=>$validator->errors()->all(),'status'=>9]);
    
            }
    
    }//End Of Function

     // show data
    public function showSubcategory(Request $request){
        if($request->ajax()) {
           $data = ProductSubcat::join('product_categories','product_subcats.category_id', '=', 'product_categories.id')
           ->where('product_subcats.is_deleted',1)
           ->get(['product_subcats.id','product_subcats.is_active','product_subcats.title','product_categories.title as cat_name']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('address', function($row){
                    if($row->address){
                        return $row->address.', '.$row->city_name.', '.$row->state_name.', '.$row->country_name.', '.$row->pin;
                    } 
                })

                ->filter(function ($instance) use ($request) { 
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($request) {
                            if (Str::contains(Str::lower($row['name']),$request->get('search'))){
                                return true;
                            }else if (Str::contains(Str::lower($row['mob_no']), Str::lower($request->get('search')))) {
                                return true;
                            }

                            return false;
                        });
                    }

                })
                ->addColumn('status', function($row){
                    if($row->is_active == 1){
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="statusSubCategory('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-success  color-success rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Active</span> </div>';
                    }else{
                        return '<div class="userDatatable-content d-inline-block" rel="tooltip" title="Change Status" onclick="statusSubCategory('.$row->id.','.$row->is_active.')"> <span class="btn bg-opacity-warning  color-warning rounded-pill userDatatable-content-status active" id="status'.$row->id.'">Deactive</span> </div>';
                    }
                })
                ->addColumn('action', function($row){
                    return '<ul class="orderDatatable_actions mb-0 d-flex flex-wrap" style="min-width:90px;justify-content:unset;"> <li onclick="editSubCategory('.$row->id.')"> <a href="#" class="edit"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a> </li> <li onclick="deleteSubCategory('.$row->id.')"> <a href="#" class="remove"> <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a> </li> </ul>';
                   
                })   
                ->rawColumns(['status','action'])
                ->make(true);
        }

    }//End of Function


    //edit category
    public function editSubategory(Request $request){
        $id = $request->id;
        $data = ProductSubcat::where('id',$id)->first();
        return response()->json($data);
    }//End of Function

     // Delete fucntion
    public function deleteSubcategory(Request $request){
        $id = $request->id;
        if(!empty($id))
        {
            $data['is_deleted'] = 2;
        }
        $row = ProductSubcat::where('id',$id)->delete($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Deleted Seller !",'status'=>2]);
        }else{
          return response()->json(['msg'=>"Seller Deleted Successfully !",'status'=>1]);
        }
    }//End if Function

     //  update status
    public function statusSubcategory(Request $request){
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
        $row = ProductSubcat::where('id',$id)->update($data);
        if(empty($row)){
            return response()->json(['msg'=>"Can't Status Updated !",'status'=>2]);
        }else{
            return response()->json(['msg'=>'Status Updated !','status'=>1]);
        }
    }//End of function


    //create Slug
    public function checkSlug($slug=''){
        if(!empty($slug)){
            $resp = ProductSubcat::where('slug',$slug)->first(['slug']);
            if(!empty($resp) && is_object($resp)){
                $token = openssl_random_pseudo_bytes(5);
                $token = bin2hex($token);
                return $resp->slug.'-'.$token;
            }else{
                return false;    
            }
        }else{
            return false;
        }
    }// End of Function

}

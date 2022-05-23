<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use \App\Models\User;
use Validator;
use \Illuminate\Support\Facades\Auth;
use \App\Models\Country;
use App\Models\SpaceType;
use \Illuminate\Support\Facades\DB;
use \Illuminate\Support\Carbon;
use \App\Models\Amenty;
use \App\Models\Feature;
use \App\Models\AddSpace;
use \App\Models\Enquiry;
use \App\Models\TopCoworking;
use \App\Models\MembershipPlan;
use Illuminate\Support\Facades\Date;

class UserController extends Controller
{
    //function for User Registration

    function saveEnquiry(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'mobile_no' => 'required|numeric|regex:/^[6-9]{1}[0-9]{9}$/',
                'email' => 'required',
                'space_type' => 'required',
                'persons' => 'required|numeric',
                'property_id' => 'required|numeric',
                
            ],
            [
                'mobile.regex' => 'Mobile no must be 10 digits and start with 6-9 only'
            ]
        );

        if ($validate->passes()) {

            // $user = new Enquiry();
            $formdata['name'] = $request->name;
            $formdata['mobile_no'] = $request->mobile_no;
            $formdata['email'] = $request->email;
            $formdata['space_type'] = $request->space_type;
            $formdata['persons'] = $request->persons;
            $formdata['property_id'] = $request->property_id;
            

            $result = DB::table('enquiries')->InsertGetId($formdata);

            if ($result) {
                return array(
                    "status" => true,
                    "code" => 200,
                    "message" => "Enquiry Submitted successfully",
                );
            } else {
                return array(
                    "status" => false,
                    "code" => 201,
                    "message" => 'Something went wrong',
                );
            }
        } else {
            return array(
                "status" => false,
                "code" => 201,
                "message" => $validate->errors()->all(),
            );
        }
    }
    // function end here 


    // function for User  login
    function userLogin(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'mobile' => 'required|numeric|regex:/^[6-9]{1}[0-9]{9}$/',
            'password' => 'required|min:8',
        ]);
        if ($validate->passes()) {
            $user = Auth::attempt([
                'mobile' => $request->mobile,
                'password' => $request->password
            ]);
            if ($user) {
                return array(
                    'status' => true,
                    'code' => 200,
                    'message' => "Login Successfully",
                    'token' => Auth::user()->createToken('token')->accessToken,
                    'data' => ['name' => Auth::user()->name, 'mobile' => Auth::user()->mobile, 'Address' => Auth::user()->address]
                );
            } else {
                return array(
                    'status' => false,
                    'code' => 201,
                    'message' => "Incorrect mobile or password",
                );
            }
        } else {
            return array(
                'status' => false,
                'code' => 201,
                'message' => $validate->errors()->all()
            );
        }
    }
    // end of function 

    //function  for Get seller data 
    function getAmenties()
    {

        $amenty = Amenty::where(['is_active' => 1, 'is_deleted' => 1])->get(['id', 'name', 'image']);

        if (!empty($amenty) and count($amenty)>0) {
            return array('status' => true, 'code' => 200, 'data' => $amenty);
        } else {
            return array('status' => false, 'code' => 201, 'data' => [],'message'=>'data not found');
        }
    }
    // end of function 
    function getFeatures()
    {
        $feature = Feature::where(['is_active' => 1, 'is_deleted' => 1])->get(['id', 'name', 'image']);

        if (!empty($feature) and count($feature)>0) {
            return array('status' => true, 'code' => 200, 'data' => $feature);
        } else {
            return array('status' => false, 'code' => 201, 'data' => [],'message'=>'data not  found');
        }
    }
    // end of function 
    function showAddspaces(){
        $data = AddSpace::where(['add_spaces.is_deleted'=>1])->join('cities','cities.id','=','add_spaces.city_id')
                    ->join('space_types','space_types.id','=','add_spaces.space_type')
                    ->join('property_image','property_image.space_id','=','add_spaces.id')
                    ->groupBy('property_image.space_id')
                    ->get(['add_spaces.id as id', 'add_spaces.space_name as name',DB::RAW('GROUP_CONCAT(property_image.image)as image'),'space_types.name as space_name','add_spaces.address as address','cities.location as city_name','add_spaces.seat_capacity as seat_capacity','add_spaces.area as area','add_spaces.email','add_spaces.mobile',DB::raw('CONCAT("₹ ",add_spaces.starting_price) as starting_price')]);
                if(!empty($data) and count($data)>0){
                    return array('status'=>true,'code'=>200,'data'=>$data);
                }else{
                    return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
                }
    }//end of function

    function getPlans(){
        $data = MembershipPlan::where(['membership_plans.is_deleted'=>1,'membership_plans.is_active'=>1])
        ->get(['membership_plans.id as id', 'membership_plans.plan_name as name','membership_plans.price as price','membership_plans.description as description']);
        if(!empty($data) and count($data)>0){
            return array('status'=>true,'code'=>200,'data'=>[$data]);
        }else{
            return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
        }
    }


    // fucntion for edit_user 
    function edit_user($id)
    {
        $user = User::where(['id' => $id, 'is_active' => 1, 'is_deleted' => 1])->get(['name', 'mobile', 'address', 'country', 'state', 'city']);

        if ($user) {

            return array('status' => true, 'code' => 200, 'data' => $user);
        } else {

            return array('status' => false, 'code' => 201, 'message' => 'Record not found');
        }
    }
    // end of function 

    function update_user(Request $req)
    {

        $id = Auth::user()->id;

        $validate = Validator::make($req->all(), [
            'name' => 'required',
            'address' => 'required',
            'country' => 'required|numeric',
            'state' => 'required|numeric',
            'city' => 'required|numeric',
        ]);
        if ($validate->passes()) {
            $dataArray['name'] = $req->name;
            $dataArray['address'] = $req->address;
            $dataArray['country'] = $req->country;
            $dataArray['state'] = $req->state;
            $dataArray['city'] = $req->city;
            $result = User::where('id', $id)->update($dataArray);
            if ($result) {
                return array('status' => true, 'code' => 200, 'message' => 'Data Updated successfully');
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'Something went wrong');
            }
        } else {
            return array(
                'status' => false,
                'code' => 201,
                'message' => $validate->errors()->all()
            );
        }
    }
    // function for update password 
    function update_password(Request $req)
    {
        $validate = Validator::make(
            $req->all(),
            [
                'current_password' => 'required|password',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|same:new_password'
            ]
        );
        if ($validate->passes()) {

            $id = Auth::user()->id;
            $result = User::where('id', $id)->update(['password' => bcrypt($req->new_password)]);

            if ($result) {
                return array('status' => true, 'code' => 200, 'message' => 'Password updated Successfully');
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'something went Wrong');
            }
        } else {
            return array(
                'status' => false,
                'code' => 201,
                'message' => $validate->errors()->all()
            );
        }
    }
    //  end function 

    // function for reset password 

    function resetPassword(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'mobile' => 'required|numeric|exists:users'
        ]);
        if ($validate->passes()) {
            $arr['time'] = date('y-m-d-h-i-s');
            $arr['mobile'] = $req->mobile;
            $token =  encryptData(json_encode($arr), env('APP_KEY'));
            // return $token;

            $response = DB::table('password_resets')->insert([
                'mobile' => $req->mobile,
                'token' => $token,
            ]);
            if ($response) {
                $url = url('api/set_password', [$token]);
                return array('status' => true, 'code' => 200, 'reset_url' => $url);
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'Something went wrong');
            }
        } else {
            return array('status' => false, 'code' => 200, 'message' => $validate->errors()->all());
        }
        // end of function 

        // function for submit password 



    }
    // end of function 

    // function for submit password 
    function checkResetToken($token)
    {

        $decrypt_data = decryptData($token, env('APP_KEY'));
        if ($decrypt_data) {
            $data =  json_decode($decrypt_data);
            $where['mobile'] =  $data->mobile;
            $where['token'] =  $token;
            $response = DB::table('password_resets')->where($where)->first();
            if ($response) {
                return array('status' => true, 'code' => 200, 'token' => $token);
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'invalid url');
            }
        } else {
            return array('status' => false, 'code' => 201, 'message' => 'invalid url');
        }
    }
    // end of function 

    //  function for update password 
    function submitNewPassword(Request $req)
    {
        $validate = validator::make($req->all(), [
            'token' => 'required|exists:password_resets',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validate->passes()) {
            $decrypt_data = decryptData($req->token, env('APP_KEY'));
            $data =  json_decode($decrypt_data);
            $result = User::where('mobile', $data->mobile)->update(['password' => bcrypt($req->new_password)]);
            if ($result) {
                DB::table('password_resets')->where('mobile', $data->mobile)->delete();
                return array('status' => true, 'code' => 200, 'message' => 'Password reset succeessfully');
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'Something went wrong]');
            }
        } else {
            return array('status' => false, 'code' => 201, 'message' => $validate->errors()->all());
        }
    }
    // end of function 


    //***  function for relation of  country code to country name **/

    function getCountry()
    {
        $country = DB::table('countries')->where(['is_deleted'=>1])->get(['id','name','flag']);
        if(!empty($country) and count($country)>0){
            return array('status'=>true,'code'=>200,'data'=>$country);
        }else{
            return array('status'=>false,'code'=>201,'data'=>[],'message'=>'no data found');
        }
        
        
    }


    // **** function for realationship table in country and states **// 
    function spaceType()
    {
        $space = SpaceType::where('is_deleted',1)->get(['id','name','location','image']);
        if(!empty($space) and count($space)>0){
            return  array('status'=>true,'code'=>200,'data'=>$space);
        }else{
            return  array('status'=>false,'code'=>201,'data'=>'','message'=>'data not found');
        }
        
    }
    //end function


    //** function for relationship in state and cities ***/
    function getCity($id='')
    {
        $city = City::where(['country_id'=>$id,'is_deleted'=>1])->get(['id','location','image','country_id']);
        if(empty($id)){
            $city = City::where(['country_id'=>1,'is_deleted'=>1])->get(['id','location','image','country_id']);
            return  array('status'=>true,'code'=>200,'data'=>$city);
        }else{
            if(!empty($city) and count($city)>0){
                return array('status'=>true,'code'=>200,'data'=>$city);
            }else{
                return array('status'=>false,'code'=>201,'data'=>[],'message'=>'Data not found');
            }
        }
        
    }

    //** end of function ***/
    function getCommodities()
    {

        $data = Commodity::join('commodity_types', 'commodities.commodity_type', '=', 'commodity_types.id')
            ->where('commodities.is_active', 1)
            ->where('commodity_types.is_active', 1)
            ->where('date', '>=', Carbon::now()->subDay(5))

            ->get(['commodities.id', 'commodities.price', 'commodities.date', 'commodity_types.title as commodity_type']);

        return $data;
    }
    // api for purchase orders 

    function purchaseOrder(Request $req)
    {

        $validate = Validator::make(
            $req->all(),

            [
                'customer_id' => 'required',
                'seller_id' => 'required',
                'commodity_id' => 'required',
                'weight' => 'required',
                'price' => 'required'
            ]
        );
        if ($validate->passes()) {
            $customer_id = $req->customer_id;
            $commodity_id = $req->commodity_id;
            $seller_id = $req->seller_id;
            $user_weight = $req->weight;
            $price  = $req->price;

            $commodity_type = Commodity::join('commodity_types', 'commodities.commodity_type', '=', 'commodity_types.id')
                ->where('commodities.is_active', 1)
                ->where('commodity_types.is_active', 1)
                ->where('date', '>=', Carbon::now()->subDay(1))
                ->where('commodities.commodity_type', $commodity_id)
                ->first(['commodities.id', 'commodities.price', 'commodities.date', 'commodity_types.title as commodity_type', 'commodity_types.weight', 'commodity_types.unit']);
                
                if ($commodity_type) {
                    $gm_price = ($commodity_type->price * 1) / $commodity_type->weight;
                    $user_price = $user_weight * $gm_price;

                if ($user_price == $price) {
                    $data['customer_id'] = $customer_id;
                    $data['commodity_id'] = $commodity_id;
                    $data['seller_id'] = $seller_id;
                    $data['price'] = $user_price;
                    $data['date'] = Date('y-m-d');
                    $data['created_by'] = $customer_id;
                    $data['weight'] = $user_weight;
                    $response = PurchaseOrder::insertGetId($data);

                    if ($response) {
                        return array('status' => true, 'code' => 200, 'message' => 'order created successfully');
                    }
                } else {
                    return array('status' => false, 'code' => 201, 'message' => 'Something weint wrong');
                }
            } else {
                return array('status' => false, 'code' => 201, 'message' => 'Something weint wrong');
            }
        } else {
            return array('status' => false, 'code' => 201, 'message' => $validate->errors()->all());
        }
    }
    // end of function
    // function for order_details 
    function order_details()
    {
        $id = Auth::user()->id;
        $records = PurchaseOrder::join('users', 'purchase_orders.id', '=', 'users.id')
            ->join('sellers','purchase_orders.seller_id','=','sellers.id')
            ->join('commodity_types','purchase_orders.commodity_id','=','commodity_types.id')
            ->where('id', $id)

            ->first(['users.name', 'purchase_orders.weight', 'purchase_orders.price','sellers.name as seller_name','commodity_types.title as commodity_type','purchase_orders.date as order_date']);

        return $records;
    }

    public function TopCoworking()
    {
        $data = TopCoworking::where('top_coworkings.is_deleted', 1)->join('space_types','space_types.id','=','top_coworkings.space_type')
        ->join('cities','top_coworkings.city','=','cities.id')
            ->get(['top_coworkings.id as id', 'top_coworkings.space_names as space_names','space_types.name as space_type_name','cities.location as location']);

            if(!empty($data)){
                return array('status'=>true,'code'=>200,'data'=>$data);
            }else{
                return array('status'=>false,'code'=>201,'data'=>[],'message'=>'Data not found');
            }
    }
    public function saveReview(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'review' => 'required',
                'user_id' => 'required',
                'property_id'=>'required',

                
            ]);
            if ($validate->passes()) {

                // $user = new Enquiry();
                $formdata['review'] = $request->review;
                $formdata['user_id'] = $request->user_id;
                $formdata['property_id'] = $request->property_id;
    
                $result = DB::table('reviews')->InsertGetId($formdata);
    
                if ($result) {
                    return array(
                        "status" => true,
                        "code" => 200,
                        "message" => "Review Submitted successfully",
                    );
                } else {
                    return array(
                        "status" => false,
                        "code" => 201,
                        "message" => 'Something went wrong',
                    );
                }
            } else {
                return array(
                    "status" => false,
                    "code" => 201,
                    "message" => $validate->errors()->all(),
                );
            }
    }//end of function

    public function getPropertyDetails($id)
    {
        $enterprise = DB::table('plans_enterprise')
        ->where(['plans_enterprise.is_deleted'=>1])
        ->get(['plans_enterprise.id','plans_enterprise.plan_name','plans_enterprise.description']);
        $membership = DB::table('membership_plans')->join('plans_detail','membership_plans.id','=','plans_detail.membership_plan_id')
                      ->where('membership_plans.is_deleted',1)
                      ->get(['membership_plans.plan_name','membership_plans.plan_duration','membership_plans.description','membership_plans.image as icon',]);
        $data = DB::table('property_details')->join('add_spaces','add_spaces.id','=','property_details.property_id')
        ->where('property_details.is_deleted', 1)
        ->Where('add_spaces.id',$id)
            ->get(['property_details.id as id','add_spaces.city_id','add_spaces.space_name as property_name','add_spaces.address as address','property_details.area as area','property_details.about','property_details.open_time as open_time','property_details.close_time as close_time','add_spaces.amenties']);
        if(!empty($data)){
            foreach ($data as $key => $value) {
                $amenties = Amenty::whereIn("id",explode(",",$value->amenties))->get(['id','name','image']);
                $value->amenties = $amenties;
                $value->membership_plans = $membership;
                $value->enterprise = $enterprise;
            }
            return array('status'=>true,'code'=>200,'data'=>$data[0]);
        }else{
            return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
        }


    }//end of function

    public function getEnterpriseplans()
    {
        $data = DB::table('plans_enterprise')->where('plans_enterprise.is_deleted', 1)
                ->get(['plans_enterprise.id as id', 'plans_enterprise.plan_name as name','plans_enterprise.description as description','plans_enterprise.is_active as is_active','plans_enterprise.is_deleted as is_deleted']);
        if(!empty($data) and count($data)>0){
                 return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
                    return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
                }
    }//end of function

    public function getPropertyEnterprise()
    {
            $data = DB::table('property_enterprise')->where('property_enterprise.is_deleted', 1)->join('plans_enterprise','property_enterprise.plan_id','=','plans_enterprise.id')
            ->join('add_spaces','add_spaces.id','=','property_enterprise.property_id')
                ->get(['property_enterprise.id as id', 'add_spaces.space_name as title','plans_enterprise.plan_name as plan_name',DB::raw('CONCAT("₹ ",property_enterprise.price) as price'),'property_enterprise.amenties as amenties','property_enterprise.is_active as is_active','property_enterprise.is_deleted as is_deleted']);
            if(!empty($data) and count($data)>0){
                    return array('status'=>true,'code'=>200,'data'=>$data);
           }else{
                       return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
                   }
    }//end of function

    function getPropertyMembership()
    {
        $data = DB::table('property_membership')->where('property_membership.is_deleted', 1)->join('membership_plans','property_membership.plan_id','=','membership_plans.id')
            ->join('property_details','property_details.id','=','property_membership.property_id')
                ->get(['property_membership.id as id', 'property_details.title as title','membership_plans.plan_name as plan_name',DB::raw('CONCAT("₹ ",property_membership.price) as price'),'property_membership.amenties as amenties','property_membership.is_active as is_active','property_membership.is_deleted as is_deleted']);
        if(!empty($data) and count($data)>0){
            return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
              return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
                   }
    }//end of function

    public function getSimilarproperties($city_id)
    {
        $data = AddSpace::where(['add_spaces.is_deleted'=>1])->join('cities','cities.id','=','add_spaces.city_id')
        ->join('space_types','space_types.id','=','add_spaces.space_type')
        ->join('property_image','property_image.space_id','=','add_spaces.id')
        ->where('add_spaces.city_id',$city_id)
        ->groupBy('property_image.space_id')
        ->get(['add_spaces.id as id','add_spaces.city_id','add_spaces.space_name as name',DB::RAW('GROUP_CONCAT(property_image.image)as image'),'space_types.name as space_name','add_spaces.address as address','cities.location as city_name','add_spaces.seat_capacity as seat_capacity','add_spaces.area as area','add_spaces.email','add_spaces.mobile',DB::raw('CONCAT("₹ ",add_spaces.starting_price) as starting_price'),]);
        if(!empty($data) and count($data)>0){
            return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
            return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
        }
    }//end of function

   
    public function propertiesByCitiesSpace($city_id,$space_for)
    {
        $data = AddSpace::where(['add_spaces.is_deleted'=>1])->join('cities','cities.id','=','add_spaces.city_id')
        ->join('space_types','space_types.id','=','add_spaces.space_type')
        ->join('property_image','property_image.space_id','=','add_spaces.id')
        ->where('add_spaces.city_id',$city_id)
        ->where('add_spaces.space_for',$space_for)
        ->groupBy('property_image.space_id')
        ->get(['add_spaces.id as id', 'add_spaces.space_name as name','add_spaces.space_for',DB::RAW('GROUP_CONCAT(property_image.image)as image'),'space_types.name as space_name','add_spaces.address as address','cities.location as city_name','add_spaces.seat_capacity as seat_capacity','add_spaces.area as area','add_spaces.email','add_spaces.mobile',DB::raw('CONCAT("₹ ",add_spaces.starting_price) as starting_price'),]);
        if(!empty($data) and count($data)>0){
            return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
            return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
        }
    }//end of function

    public function spaceFor()
    {
        $data = DB::table('space_for')->where('space_for.is_deleted', 1)
                ->get(['space_for.id as id', 'space_for.name as name']);
        if(!empty($data) and count($data)>0){
                 return array('status'=>true,'code'=>200,'data'=>$data);
        }else{
                    return array('status'=>false,'code'=>201,'data'=>[],'message'=>'data not found');
                }
    }
}
// end of class 
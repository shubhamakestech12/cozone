<?php


use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use \App\Http\Controllers\Api\DbController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function(){
    Route::get("edit_user/{id}",[UserController::class,"edit_user"]);
    Route::post("update_user",[UserController::class,"update_user"]);
    Route::post("update_password",[UserController::class,"update_password"]);
    Route::post('purchase_order',[UserController::class,'purchaseOrder']);
    Route::get('order',[UserController::class,'order_details']);
});

Route::post("save_enquiry",[UserController::class,"saveEnquiry"]);
Route::post("save_review",[UserController::class,"saveReview"]);
// Route::post("login",[UserController::class,"userLogin"]);
Route::get('get_space_type',[UserController::class,'spaceType']);
Route::get('get_cities/{id?}',[UserController::class,'getCity']);
Route::get('get_country',[UserController::class,'getCountry']);
Route::get('get_amenties',[UserController::class,'getAmenties']);
Route::get('get_features',[UserController::class,'getFeatures']);
Route::get('get_add_spaces',[UserController::class,'showAddspaces']);
Route::get('get_plans',[UserController::class,'getPlans']);
Route::get('get_top_coworking',[UserController::class,'TopCoworking']);
Route::get('get_property_details/{id}',[UserController::class,'getPropertyDetails']);
Route::get('get_enterprise_plans',[UserController::class,'getEnterpriseplans']);
Route::get('get_property_enterprise',[UserController::class,'getPropertyEnterprise']);
Route::get('get_property_membership',[UserController::class,'getPropertyMembership']);
Route::get('get_similar_property/{location}',[UserController::class,'getSimilarproperties']);
Route::get('get_cities_properties_spaces/{city_id}/{space_for}',[UserController::class,'propertiesByCitiesSpace']);
Route::get('get_space_for',[UserController::class,'spaceFor']);
// Route::get('get_location_filter/{area}',[UserController::class,'locationFilter']);
// Route::get('set_password/{hash}',[UserController::class,'checkResetToken']);
// Route::post('submit_reset_password',[UserController::class,'submitNewPassword']);  
// Route::post('reset_password',[UserController::class,'resetPassword']);
// Route::get("getSeller",[UserController::class,"getSellers"]);


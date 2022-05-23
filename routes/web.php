<?php

    use Illuminate\Support\Facades\Route;

    use App\Http\Controllers\Admin\Dashboard;
    use App\Http\Controllers\Admin\Adminlogin;
    use App\Http\Controllers\Admin\CityController;
    use App\Http\Controllers\Admin\CountryController;
    use App\Http\Controllers\Admin\CustomListController;
    use App\Http\Controllers\Admin\SpaceController;
    use App\Http\Controllers\Admin\AmentyController;
    use App\Http\Controllers\Admin\FeatureController;
    use App\Http\Controllers\Admin\AddSpaceController;
    use App\Http\Controllers\Admin\MemberShipController;
    use App\Http\Controllers\Admin\EnquiryController;
    use App\Http\Controllers\Admin\TopCoWorkingController;
    use App\Http\Controllers\Admin\EnterPriseController;
    use App\Http\Controllers\Admin\ReviewController;
    use App\Http\Controllers\Admin\PropertyDetailsController;
    use App\Http\Controllers\Admin\PropertyMemberShipController;
    use App\Http\Controllers\Admin\PropertyEnterprise;
    use App\Http\Controllers\Admin\ContactController;
   
    
  
    
    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    Route::get('/404', function () {
        return view('404');
    });

    Route::get('/',[Adminlogin::class , 'index']);
    Route::get('/admin-login',[Adminlogin::class , 'index']);

    //admin controller start
    Route::get('/admin-logout',[Adminlogin::class , 'logout']);
    Route::post('/validate',[Adminlogin::class,'login']);

/*Admin Middleware*/
    Route::middleware('admin_login')->group(function(){
   // after login 
// for admin 
Route::get('/dashboard',[Dashboard::class , 'index']);
//Dropdown function

//Country Controller Start
Route::get('/country',[CountryController::class , 'index']);
Route::post('/save_country',[CountryController::class , 'saveCountry']);
Route::get('/show_country',[CountryController::class , 'showCountry']);
Route::get('status_country',[CountryController::class , 'statusCountry']);
Route::get('/edit_country',[CountryController::class , 'editCountry']);
Route::post('/delete_country',[CountryController::class , 'deleteCountry']);

//city master
Route::get('/city',[CityController::class , 'index']);
Route::get('/show_city_list',[CityController::class , 'showCity']);
Route::post('/save-city',[CityController::class , 'saveCity']);
Route::get('/status-city',[CityController::class , 'statusCity']);
Route::post('/delete-city',[CityController::class , 'deleteCity']);
Route::get('/edit-city',[CityController::class , 'editCity']);

//space function
Route::get('/space_type',[SpaceController::class,'index']);
Route::post('/save_space',[SpaceController::class , 'saveSpace']);
Route::get('show_spacetype',[SpaceController::class , 'showSpace']);
Route::get('status_space',[SpaceController::class , 'statusSpace']);
Route::get('edit_space',[SpaceController::class , 'editSpace']);
Route::post('delete_space',[SpaceController::class , 'deleteSpace']);

//Ammenties master
Route::get('/amenties',[AmentyController::class,'index']);
Route::post('/save_amenties',[AmentyController::class , 'saveAmenties']);
Route::get('/show_amenties',[AmentyController::class , 'showAmenty']);
Route::get('/status_amenties',[AmentyController::class , 'statusAmenty']);
Route::post('/delete_amenties',[AmentyController::class , 'deleteAmenty']);
Route::get('/edit_amenties',[AmentyController::class , 'editAmenty']);

//Feature master
Route::get('/features',[FeatureController::class,'index']);
Route::post('/save_features',[FeatureController::class , 'saveFeatures']);
Route::get('/show_features',[FeatureController::class , 'showFeatures']);
Route::get('/status_features',[FeatureController::class , 'statusFeature']);
Route::post('/delete_feature',[FeatureController::class , 'deleteFeature']);
Route::get('/edit_feature',[FeatureController::class , 'editFeature']);

// add space

Route::get('/add_space',[AddSpaceController::class,'index']);
Route::post('/save_add_space',[AddSpaceController::class , 'saveAddSpace']);
Route::get('/show_add_space',[AddSpaceController::class , 'showAddSpace']);
Route::get('/status_add_space',[AddSpaceController::class , 'statusAddSpace']);
Route::post('/delete_add_space',[AddSpaceController::class , 'deleteAddSpace']);
Route::get('/edit_add_space',[AddSpaceController::class , 'editAddSpace']);
//Membership controller
Route::get('/member_ship',[MemberShipController::class,'index']);
Route::post('/save_plan',[MemberShipController::class , 'savePlan']);
Route::get('/show_plans',[MemberShipController::class , 'showPlans']);
Route::get('/status_plan',[MemberShipController::class , 'statusPlan']);
Route::post('/delete_plan',[MemberShipController::class , 'deletePlan']);
Route::get('/edit_plan',[MemberShipController::class , 'editPlan']);

// Enquiry route 
Route::get('/enquiry_list',[EnquiryController::class , 'index']);
Route::get('/show_enquiry',[EnquiryController::class , 'showEnquiry']);

// route for top coworking
Route::get('/top_coworking',[TopCoWorkingController::class , 'index']);
Route::post('/get_spaces',[TopCoWorkingController::class , 'getSpaces']);
Route::post('/save_top_coworking',[TopCoWorkingController::class , 'saveTopCoworking']);
Route::get('/show_top_coworking',[TopCoWorkingController::class , 'showTopCoworking']);
Route::get('/status_top_coworking',[TopCoWorkingController::class , 'statusTopCoworking']);
Route::post('/delete_top_coworking',[TopCoWorkingController::class , 'deleteTopCoworking']);
Route::get('/edit_top_coworking',[TopCoWorkingController::class , 'editTopCoworking']);

// property details 

Route::get('/property_details',[PropertyDetailsController::class , 'index']);
Route::get('/get_plans',[PropertyDetailsController::class , 'get_plans']);
Route::post('/save_property_details',[PropertyDetailsController::class , 'savePropertyDetails']);
Route::get('/show_property_details',[PropertyDetailsController::class , 'showPropertydetails']);
Route::get('/status_property_details',[PropertyDetailsController::class , 'statusPropertyDetails']);
Route::post('/delete_property_details',[PropertyDetailsController::class , 'deletePropertyDetails']);
Route::post('/edit_property_details',[PropertyDetailsController::class , 'editpropertydetails']);


// EnterPrise masters 
Route::get('/enterprise-plans',[EnterPriseController::class , 'index']);
Route::post('/save-enterprise-plans',[EnterPriseController::class , 'saveEnterprise']);
Route::get('/show-enterprise-plans',[EnterPriseController::class , 'showEnterPrise']);
Route::get('/status-enterprise-plans',[EnterPriseController::class , 'statusEnterprise']);
Route::post('/delete-enterprise-plans',[EnterPriseController::class , 'deleteEnterprise']);
Route::get('/edit-enterprise-plans',[EnterPriseController::class , 'editEnterPrise']);



// review list 
Route::get('/reviews',[ReviewController::class , 'index']);
Route::get('/show-review',[ReviewController::class , 'showReview']);
Route::get('/status-review',[ReviewController::class , 'statusReview']);
Route::post('/delete-review',[ReviewController::class , 'deleteReview']);

//property membership 

Route::get('/property_membership',[PropertyMemberShipController::class , 'index']);
Route::post('/save_property_membership',[PropertyMemberShipController::class , 'savePropertyMembership']);
Route::get('/show_property_membership',[PropertyMemberShipController::class , 'showPropertyMembership']);
Route::get('/status_property_membership',[PropertyMemberShipController::class , 'statusPropertyMembership']);
Route::post('/delete_property_membership',[PropertyMemberShipController::class , 'deletePropertyMembership']);
Route::get('/edit_property_membership',[PropertyMemberShipController::class , 'editPropertyMembership']);

//property enterprise

Route::get('/property_enterprise',[PropertyEnterprise::class , 'index']);
Route::post('/save_property_enterprise',[PropertyEnterprise::class , 'savePropertyEnterprise']);
Route::get('/show_property_enterprise',[PropertyEnterprise::class , 'showPropertyEnterprise']);
Route::get('/status_property_enterprise',[PropertyEnterprise::class , 'statusPropertyEnterprise']);
Route::post('/delete_property_enterprise',[PropertyEnterprise::class , 'deletePropertyEnterprise']);
Route::get('/edit_property_enterprise',[PropertyEnterprise::class , 'editPropertyEnterprise']);


});    



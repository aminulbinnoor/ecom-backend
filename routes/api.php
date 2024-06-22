<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
include __DIR__.'/site/api.php';
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

  Route::prefix('category')->group(function () {
      Route::get('get','Admin\CategoryController@get');
      Route::post('create', 'Admin\CategoryController@create');
      Route::post('update','Admin\CategoryController@update');
      Route::get('package','Admin\CategoryController@packages');
  });

  Route::prefix('v1/admin')->group(function () {
      Route::post('login', 'Admin\LoginController@login');
      Route::get('dummy-sp', 'Admin\LoginController@dummySp');
  });

  Route::prefix('v1/admin')->group(function () {
      Route::get('auth-id',function(){
          return response()->json(auth_id());
       });
       Route::prefix('report')->group(function(){
           Route::get('order','Admin\ReportController@orderReport');
       });
      Route::prefix('order')->group(function () {
          Route::get('get','Admin\OrderController@get');
          Route::post('set-status','Admin\OrderController@updateStatus');
          Route::get('invoice/{id}','Admin\OrderController@getInvoice');
      });

      Route::prefix('product')->group(function () {
          Route::get('get','Admin\ProductController@get');
          Route::post('create', 'Admin\ProductController@create');
          Route::post('update','Admin\ProductController@update');
          Route::post('set-variations','Admin\ProductController@setVariation');
          Route::get('get-product-variations','Admin\ProductController@getVariation');

          Route::post('set-specification-dimensions','Admin\ProductController@setSpecificationDimensions');
          Route::get('get-specification-dimensions','Admin\ProductController@getSpecificationDimensions');

          Route::post('set-specification-details','Admin\ProductController@setSpecificationDetails');
          Route::get('get-specification-details','Admin\ProductController@getSpecificationDetails');

          Route::post('delete','Admin\ProductController@delete');
      });

      Route::prefix('category')->group(function () {
          Route::get('get','Admin\CategoryController@get');
          Route::post('create', 'Admin\CategoryController@create');
          Route::post('update','Admin\CategoryController@update');
          Route::post('delete','Admin\CategoryController@delete');
          Route::get('get/{id}/theme','Admin\CategoryController@getCategoryWiseTheme');
      });

      Route::prefix('theme')->group(function () {
          Route::get('get','Admin\ThemeController@get');
          Route::post('create', 'Admin\ThemeController@create');
          Route::post('update','Admin\ThemeController@update');
          Route::post('delete','Admin\ThemeController@delete');
          Route::get('get/{id}/composition','Admin\ThemeController@getThemeWiseComposition');
      });

      Route::prefix('composition')->group(function () {
          Route::get('get','Admin\CompositionController@get');
          Route::post('create', 'Admin\CompositionController@create');
          Route::post('update','Admin\CompositionController@update');
          Route::post('delete','Admin\CompositionController@delete');
          Route::get('get-theme','Admin\CompositionController@getTheme');
          Route::get('get/{id}/room','Admin\CompositionController@getCompositionWiseRoom');
      });

      Route::prefix('room')->group(function () {
          Route::get('get','Admin\RoomController@get');
          Route::post('create', 'Admin\RoomController@create');
          Route::post('update','Admin\RoomController@update');
          Route::post('delete','Admin\RoomController@delete');
          Route::get('products','Admin\RoomController@products');
          Route::post('create-room-product', 'Admin\RoomController@createRoomProduct');
          Route::post('delete-room-product','Admin\RoomController@deleteRoomProduct');
          Route::get('room-product', 'Admin\RoomController@getProduct');
      });

      Route::prefix('room-tag')->group(function () {
          Route::get('get','Admin\RoomTagController@get');
          Route::post('create', 'Admin\RoomTagController@create');
          Route::post('update','Admin\RoomTagController@update');
          Route::post('delete','Admin\RoomTagController@delete');
      });

      Route::prefix('building-category')->group(function () {
          Route::get('get','Admin\BuildingCategoryController@get');
          Route::post('create', 'Admin\BuildingCategoryController@create');
          Route::post('update','Admin\BuildingCategoryController@update');
          Route::post('delete','Admin\BuildingCategoryController@delete');
      });

      Route::prefix('building')->group(function () {
          Route::get('get','Admin\BuildingController@get');
          Route::post('create', 'Admin\BuildingController@create');
          Route::post('update','Admin\BuildingController@update');
          Route::post('delete','Admin\BuildingController@delete');
      });

      Route::prefix('product-category')->group(function () {
          Route::get('get','Admin\ProductCategoryController@get');
          Route::post('create', 'Admin\ProductCategoryController@create');
          Route::post('update','Admin\ProductCategoryController@update');
          Route::post('delete','Admin\ProductCategoryController@delete');
          Route::get('get/{id}/product-subcategory','Admin\ProductCategoryController@getProductCategoryWiseSubCategory');
      });

      Route::prefix('product-subcategory')->group(function () {
          Route::get('get','Admin\ProductSubcategoryController@get');
          Route::post('create', 'Admin\ProductSubcategoryController@create');
          Route::post('update','Admin\ProductSubcategoryController@update');
          Route::post('delete','Admin\ProductSubcategoryController@delete');
      });

      Route::prefix('employee')->group(function () {
          Route::get('get','Admin\EmployeeController@get');
          Route::post('create', 'Admin\EmployeeController@create');
          Route::post('update','Admin\EmployeeController@update');
          Route::post('delete','Admin\EmployeeController@delete');
          Route::get('permissions','Admin\EmployeeController@permissions');

      });


      Route::prefix('role')->group(function () {
          Route::get('get','Admin\RoleController@get');
          Route::post('create', 'Admin\RoleController@create');
          Route::post('update','Admin\RoleController@update');
          Route::post('delete','Admin\RoleController@delete');
          Route::post('set-permission','Admin\RoleController@setPermission');
          Route::get('get-permission','Admin\RoleController@getPermission');
      });

      Route::prefix('permission')->group(function () {
          Route::get('get','Admin\PermissionController@get');
          Route::get('create', 'Admin\PermissionController@create');
          Route::post('update','Admin\PermissionController@update');
          Route::post('delete','Admin\PermissionController@delete');
      });

      Route::prefix('appriciation')->group(function () {
          Route::get('get','Admin\AppriciationController@get');
          Route::post('create', 'Admin\AppriciationController@create');
          Route::post('update','Admin\AppriciationController@update');
          Route::post('delete','Admin\AppriciationController@delete');
      });

      Route::prefix('delivery-system')->group(function () {
          Route::get('get','Admin\DeliverySystemController@get');
          Route::post('create', 'Admin\DeliverySystemController@create');
          Route::post('update','Admin\DeliverySystemController@update');
          Route::post('delete','Admin\DeliverySystemController@delete');
      });

      Route::prefix('subscribe-customer')->group(function () {
          Route::get('get','Admin\SubscriptionCustomerController@get');
          Route::post('create', 'Admin\SubscriptionCustomerController@create');
      });

      Route::prefix('appointment')->group(function () {
          Route::get('get','Admin\AppointmentController@get');
          Route::get('assign-stuff-lists','Admin\AppointmentController@assignStuffLists');
          Route::get('get-assign-stuff','Admin\AppointmentController@getAssignStuff');
          Route::post('set-assign-stuff','Admin\AppointmentController@setAssignStuff');
          Route::post('create', 'Admin\AppointmentController@create');
          Route::post('create-by-admin', 'Admin\AppointmentController@createByAdmin');
          Route::post('update','Admin\AppointmentController@update');
          Route::post('delete','Admin\AppointmentController@delete');
          Route::post('delete-customer-feedback','Admin\AppointmentController@deleteCustomerFeedback');
          Route::post('delete-assign-stuff','Admin\AppointmentController@deleteAssignStuff');
          Route::post('create-customer-feedback','Admin\AppointmentController@customerFeedback');
          Route::get('get-customer-feedback','Admin\AppointmentController@getCustomerFeedback');
      });

      Route::prefix('brand-partner')->group(function () {
          Route::get('get','Admin\BrandPartnerController@get');
          Route::post('create', 'Admin\BrandPartnerController@create');
          Route::post('update','Admin\BrandPartnerController@update');
          Route::post('delete','Admin\BrandPartnerController@delete');
      });

      Route::prefix('hero-slider')->group(function () {
          Route::get('get','Admin\HeroSliderController@get');
          Route::post('create', 'Admin\HeroSliderController@create');
          Route::post('update','Admin\HeroSliderController@update');
          Route::post('delete','Admin\HeroSliderController@delete');
      });

      Route::prefix('customer-story')->group(function () {
          Route::get('get','Admin\CustomerStoryController@get');
          Route::post('create', 'Admin\CustomerStoryController@create');
          Route::post('update','Admin\CustomerStoryController@update');
          Route::post('delete','Admin\CustomerStoryController@delete');
      });

      Route::get('get-data-config',function(){
          if(request('type')){
              $msg = ['message' => 'config', 'status' => 'info', 'success' => true];
              return response()->json(output($msg,config('p2p')[request('type')]),200);
          }
          return response()->json(config('p2p'));
      });
  });

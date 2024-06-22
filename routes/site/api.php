<?php


Route::prefix('v1')->group(function () {
    Route::get('room/products', 'Site\RoomController@roomWiseProduct');
    Route::get('room/similar-products', 'Site\RoomController@similarProduct');
    Route::get('room/tags', 'Site\RoomController@roomTags');
    Route::prefix('product')->group(function () {
        Route::get('get', 'Site\ProductController@get');
        Route::get('get-all-data', 'Site\ProductController@getAllData');
        Route::get('get-single', 'Site\ProductController@getSingle');
        Route::get('category-list', 'Site\ProductController@getCategoryList');

        Route::get('get-category', 'Site\ProductCategoryController@get');
    });
    Route::prefix('order')->group(function () {
        Route::post('submit', 'Site\OrderController@createOrder');
    });

    Route::prefix('payment')->group(function () {
          Route::prefix('ssl')->group(function () {
            Route::post('success', 'Site\PaymentController@sslSuccess')->name('');

        });

    });

    Route::prefix('signup')->group(function () {
        Route::post('otp', 'Site\LoginController@signupOtp');
        Route::post('otp-check', 'Site\LoginController@signupOtpCheck');
    });
    Route::post('sign-in', 'Site\LoginController@login');

    Route::get('checkout/products','Site\CheckoutController@products');
    Route::get('category/theme', 'Site\CategoryController@getTheme');
    Route::get('theme/composition', 'Site\ThemeController@getComposition');
    Route::get('composition/room', 'Site\CompositionController@getRoom');
    Route::get('brand-partner/lists', 'Site\BrandPartnerController@lists');
    Route::get('hero-slider/lists', 'Site\HeroSliderController@lists');
    Route::get('customer-story/lists', 'Site\CustomerStoryController@lists');

    Route::post('subscribe-customer/create', 'Site\SubscribeCustomerController@create');

    Route::get('appriciation/lists', 'Site\AppriciationController@lists');

    Route::post('create-appointment', 'Site\AppointmentController@createAppointment');

    Route::get('appointment-get', 'Site\AppointmentController@get');
    Route::get('order-get', 'Site\OrderController@get');


    Route::get('top-feataure/looks', 'Site\TopFeatureController@looks');
    Route::get('top-feataure/products', 'Site\TopFeatureController@products');

    Route::get('wishlists', 'Site\WishListController@get');
    Route::post('add-to-wishlists', 'Site\WishListController@addToWishLists');
    Route::get('recent-views', 'Site\RecentViewController@get');
    Route::post('add-to-recentviews', 'Site\RecentViewController@addToRecentView');
    Route::get('popular-products', 'Site\PopularProductController@get');

    Route::prefix('building')->group(function () {
        Route::get('category-list','Site\BuildingCategoryController@getCategory');
        Route::get('get-building','Site\BuildingController@getBuilding');
        Route::get('get-building-details','Site\BuildingController@buildingDetail');

    });
});

<?php

if (!function_exists('permissions')) {
  function permissions()
  {
    $data = [
      // main
      'staff-management' => [
          // employee menu
          'employee-menu',
          'employee-list',
          'employee-create',
          'employee-update',
      ],
      'role-management' => [
          // role menu
          'role-menu',
          'role-list',
          'role-create',
          'role-update',
          'role-set-permission',

         // permission menu
          'permission-menu',
          'permission-list',
          'permission-create',
          'permission-update',
      ],
      'category-management' => [
          'category-menu' ,
          'category-list',
          'category-create',
          'category-update',
          'category-delete',

          'theme-menu',
          'theme-list',
          'theme-create',
          'theme-update',
          'theme-delete',

          'composition-menu',
          'composition-list',
          'composition-create',
          'composition-update',
          'composition-delete',

          'room-menu',
          'room-list',
          'room-create',
          'room-update',
          'room-delete',

          'roomtag-menu',
          'roomtag-list',
          'roomtag-create',
          'roomtag-update',
          'roomtag-delete',

          'building-category-menu',
          'building-category-list',
          'building-category-create',
          'building-category-update',
          'building-category-delete',
        ],

      'inventory-management'=> [
          'product-category-menu',
          'product-category-list',
          'product-category-create',
          'product-category-update',
          'product-category-delete',

          'product-subcategory-menu',
          'product-subcategory-list',
          'product-subcategory-create',
          'product-subcategory-update',
          'product-subcategory-delete',

          'product-menu',
          'product-list',
          'product-create',
          'product-update',
          'product-delete',

          'order-menu',
          'new-order',
          'processing-order',
          'delivered-order',
          'cancel-order',

        ],
      'basic-setup' =>[
         'appriciation-video-menu',
         'appriciation-video-list',
         'appriciation-video-create',
         'appriciation-video-update',
         'appriciation-video-delete',

         'delivery-system-menu',
         'delivery-system-list',
         'delivery-system-create',
         'delivery-system-update',
         'delivery-system-delete',

         'subscribe-customer-menu',
         'subscribe-customer-list',
        ],

      'appointment-management' => [
         'appointment-menu',
         'appointment-list',
         'appointment-create',
         'appointment-update',
         'appointment-delete',
      ],
      'brand-partner-management' => [
         'brand-partner-menu',
         'brand-partner-list',
         'brand-partner-create',
         'brand-partner-update',
         'brand-partner-delete',
      ],

      'hero-slider-management' => [
         'hero-slider-menu',
         'hero-slider-list',
         'hero-slider-create',
         'hero-slider-update',
         'hero-slider-delete',
      ],

      'customer-story-management' => [
         'customer-story-menu',
         'customer-story-list',
         'customer-story-create',
         'customer-story-update',
         'customer-story-delete',
      ],

    ];

    return $data;
  }
}

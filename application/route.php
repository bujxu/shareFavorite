<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------



use think\Route;

// Route::rule('xubuju', 'index/Index/getBanner');

//Route::rule('banner/:id', 'api/Banner/getBanner');

// Route::get('api/v1/banner/:id', 'api/v1.Banner/getBanner');
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');


Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');   


Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
Route::get('api/:version/product/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);

// Route::group('api/:version/product', 
// function ()
// {
//     Route::get('/recent', 'api/:version.Product/getRecent');
//     Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']); 
//     Route::get('/by_category', 'api/:version.Product/getAllInCategory');
// }
// );


Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

Route::post('api/:version/token/user', 'api/:version.Token/getToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

Route::post('api/:version/order', 'api/:version.Order/placeOrder');
// Route::get('api/:version/Address/second', 'api/:version.Address/second');
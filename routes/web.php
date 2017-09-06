<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/','users@index');

Route::get('client_order_view_all','users@client_orders_all')->middleware('client');

Route::post('set_pass','users@set_pass');

Route::get('open_client_bids','users@open_client_bids')->middleware('client');
Route::get('closed_client_bids','users@closed_client_bids')->middleware('client');
Route::get('client_order_view/{order_id}','users@client_order');
Route::get('mypage','users@client_myapage')->middleware('auth');
Route::post('update_reg_details','users@update_reg_details')->middleware('auth');
Route::post('update_password','users@update_password')->middleware('auth');
Route::get('cancel_order/{order_id}','users@cancel_order')->middleware('client');
Route::post('choose_company','users@choose_company')->middleware('client');

Route::post('check-messages','users@check_messages')->middleware('auth');

Route::get('new_order_check', function () {
    return view('new_order_check');
})->middleware('client');

Route::get('/new_order','orders@new')->middleware('client');
Route::post('/new_order','orders@new_order_confirm')->middleware('client');
Route::get('/save_new_order','orders@create')->middleware('client');

Route::post('/registering','users@create');
Route::get('registering', 'users@registering');

Route::get('register_confirmation', function () {
    return view('register_confirmation');
});


Route::get('company_order_view_all','orders@all_orders')->middleware('company');
Route::post('bid','orders@bid')->middleware('company');
Route::post('bid_with_message','orders@bid_with_message')->middleware('company');
Route::get('open-bids', 'orders@open_bids')->middleware('company');
Route::get('closed-bids', 'orders@closed_bids')->middleware('company');
Route::get('company_order_view/{order_id}','orders@view_order')->middleware('company');
Route::get('bid-with-comment/{order_id}/{bid_price}','orders@open_bid_comment')->middleware('company');

Auth::routes();
Route::get('register/verify/{token}','Auth\RegisterController@verify');


Route::get('/home', 'HomeController@index');

Route::get('admin','admin@company_accounts')->middleware('admin');
Route::get('admin-company-accounts','admin@company_accounts')->middleware('admin');
Route::get('admin-client-accounts','admin@client_accounts')->middleware('admin');
Route::post('search-company','admin@search_company')->middleware('admin');
Route::post('update_company_record','admin@update_company_record')->middleware('admin');
Route::post('delete_company_record','admin@delete_company_record')->middleware('admin');

Route::post('update_client_record','admin@update_client_record')->middleware('admin');
Route::post('delete_client_record','admin@delete_client_record')->middleware('admin');

Route::get('company-account-details/{company_id}','admin@company_details')->middleware('admin');
Route::get('client-account-details/{company_id}','admin@client_details')->middleware('admin');

Route::get('admin-orders','admin@admin_orders')->middleware('admin');
Route::get('admin-order-details/{order_id}', 'admin@order_details')->middleware('admin');

Route::get('admin-transactions', 'admin@transactions')->middleware('admin');

Route::get('admin-transactions-details/{order_id}','admin@transaction_details')->middleware('admin');

Route::get('admin-trash', 'admin@deleted_companies')->middleware('admin');

Route::get('admin-trash-details/{user_id}','admin@deleted_company_details')->middleware('admin');

Route::post('restore_company_record','admin@restore_company_record')->middleware('admin');

Route::post('delete_record_permanently','admin@delete_record_permanently')->middleware('admin');


Route::get('admin-message-hist', function () {
    return view('admin.message-hist');
})->middleware('admin');


Route::get('admin-message-details', function () {
    return view('admin.message-details');
})->middleware('admin');

Route::get('admin-client-accounts-details', function () {
    return view('admin.client-accounts-details');
})->middleware('admin');

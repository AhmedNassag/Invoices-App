<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
Route::get('/', function () { return view('auth.login'); });

Auth::routes();
// Auth::routes(['register'=>false]);

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
});

Route::get('/{page}', 'AdminController@index');


Route::resource('invoice','InvoiceController');
Route::resource('section','SectionController');
Route::resource('product','ProductController');
Route::resource('Archive', 'InvoiceAchiveController');


Route::get('/sections/{id}','InvoiceController@getProducts');
Route::get('/invoice_edit/{id}','InvoiceController@edit');
Route::get('/status_show/{id}', 'InvoiceController@show')->name('status_show');
Route::post('/status_update/{id}', 'InvoiceController@status_update')->name('status_update');
Route::get('/Invoice_Paid','InvoiceController@Invoice_Paid');
Route::get('/Invoice_UnPaid','InvoiceController@Invoice_UnPaid');
Route::get('/Invoice_Partial','InvoiceController@Invoice_Partial');
Route::get('Print_invoice/{id}','InvoiceController@Print_invoice');
Route::get('export_invoices', 'InvoiceController@export');


Route::get('MarkAsRead_all', 'InvoiceController@MarkAsRead_all')->name('MarkAsRead_all');
Route::get('invoices_report', 'Invoices_ReportController@index');
Route::post('Search_invoices', 'Invoices_ReportController@Search_invoices');
Route::get('customers_report', 'Customers_ReportController@index')->name("customers_report");
Route::post('Search_customers', 'Customers_ReportController@Search_customers');


Route::get('/invoice_details/{id}','InvoiceDetailsController@edit');
Route::get('/view_file/{invoice_number}/{file_name}','InvoiceDetailsController@viewFile');
Route::get('/download_file/{invoice_number}/{file_name}','InvoiceDetailsController@downloadFile');
Route::post('/delete_file','InvoiceDetailsController@destroy');

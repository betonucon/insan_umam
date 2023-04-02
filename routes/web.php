<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\CostController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Auth\LogoutController;
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


Route::group(['prefix' => 'employe','middleware'    => 'auth'],function(){
    Route::get('/',[EmployeController::class, 'index']);
    Route::get('/view',[EmployeController::class, 'view_data']);
    Route::get('/getdata',[EmployeController::class, 'get_data']);
    Route::get('/getdatapm',[EmployeController::class, 'get_data_pm']);
    Route::get('/getrole',[EmployeController::class, 'get_role']);
    Route::get('/delete',[EmployeController::class, 'delete']);
    Route::get('/switch_to',[EmployeController::class, 'switch_to']);
    Route::get('/create',[EmployeController::class, 'create']);
    Route::get('/modal',[EmployeController::class, 'modal']);
    Route::post('/',[EmployeController::class, 'store']);
});

Route::group(['prefix' => 'cost','middleware'    => 'auth'],function(){
    Route::get('/',[CostController::class, 'index']);
    Route::get('/view',[CostController::class, 'view_data']);
    Route::get('/getdata',[CostController::class, 'get_data']);
    Route::get('/create',[CostController::class, 'create']);
    Route::get('/delete',[CostController::class, 'delete']);
    Route::get('/modal',[CostController::class, 'modal']);
    Route::post('/',[CostController::class, 'store']);
});

Route::group(['prefix' => 'customer','middleware'    => 'auth'],function(){
    Route::get('/',[CustomerController::class, 'index']);
    Route::get('/view',[CustomerController::class, 'view_data']);
    Route::get('/getdata',[CustomerController::class, 'get_data']);
    Route::get('/create',[CustomerController::class, 'create']);
    Route::get('/delete',[CustomerController::class, 'delete']);
    Route::get('/modal',[CustomerController::class, 'modal']);
    Route::post('/',[CustomerController::class, 'store']);
});
Route::group(['prefix' => 'master','middleware'    => 'auth'],function(){
    Route::get('/dokumen/',[MasterController::class, 'index']);
    Route::get('/dokumen/view',[MasterController::class, 'view_data']);
    Route::get('/dokumen/getdata',[MasterController::class, 'get_data']);
    Route::get('/dokumen/delete',[MasterController::class, 'delete']);
    Route::post('/dokumen/',[MasterController::class, 'store']);

    Route::get('/lemari/',[MasterController::class, 'index_lemari']);
    Route::get('/lemari/view',[MasterController::class, 'view_data_lemari']);
    Route::get('/lemari/getdata',[MasterController::class, 'get_data_lemari']);
    Route::get('/lemari/delete',[MasterController::class, 'delete_lemari']);
    Route::post('/lemari/',[MasterController::class, 'store_lemari']);

    Route::get('/rak/',[MasterController::class, 'index_rak']);
    Route::get('/rak/view',[MasterController::class, 'view_data_rak']);
    Route::get('/rak/getdata',[MasterController::class, 'get_data_rak']);
    Route::get('/rak/delete',[MasterController::class, 'delete_rak']);
    Route::post('/rak/',[MasterController::class, 'store_rak']);
});

Route::group(['prefix' => 'arsip','middleware'    => 'auth'],function(){
    Route::get('/',[PengajuanController::class, 'index_arsip']);
    Route::get('/proses_dokumen',[PengajuanController::class, 'proses_dokumen']);
    Route::get('/out',[PengajuanController::class, 'index_arsip_keluar']);
    Route::get('/getdata',[PengajuanController::class, 'get_data_arsip']);
    
});
Route::group(['prefix' => 'pengajuan','middleware'    => 'auth'],function(){
    Route::get('/',[PengajuanController::class, 'index']);
    Route::get('/riwayat',[PengajuanController::class, 'index_log']);
    Route::get('/view',[PengajuanController::class, 'view_data']);
    Route::get('/getdata',[PengajuanController::class, 'get_data']);
    Route::get('/getdatadashboard',[PengajuanController::class, 'get_data_dashboard']);
    Route::get('/tampil_dokumen',[PengajuanController::class, 'tampil_dokumen']);
    Route::get('/rak',[PengajuanController::class, 'rak']);
    Route::get('/tampil_form',[PengajuanController::class, 'tampil_form']);
    Route::get('/tampil_perpanjang',[PengajuanController::class, 'tampil_perpanjang']);
    Route::get('/tampil_log',[PengajuanController::class, 'tampil_log']);
    Route::get('/delete',[PengajuanController::class, 'delete']);
    Route::post('/store',[PengajuanController::class, 'store']);
    Route::post('/selesai_data',[PengajuanController::class, 'selesai_data']);
    Route::post('/proses_data',[PengajuanController::class, 'proses_data']);
    Route::post('/perpanjang_data',[PengajuanController::class, 'perpanjang_data']);
    Route::post('/store_dokumen',[PengajuanController::class, 'store_dokumen']);
   
});
Route::group(['prefix' => 'projectcontrol','middleware'    => 'auth'],function(){
    Route::get('/',[KontrakController::class, 'index_control']);
});
Route::group(['prefix' => 'kontrak','middleware'    => 'auth'],function(){
    Route::get('/',[KontrakController::class, 'index']);
    Route::get('/view',[KontrakController::class, 'view_data']);
    Route::get('/form_send',[KontrakController::class, 'form_send']);
    Route::get('/timeline',[KontrakController::class, 'timeline']);
    Route::get('/getdata',[KontrakController::class, 'get_data']);
    Route::get('/modal_material',[KontrakController::class, 'modal_material']);
    Route::get('/getdatacontrol',[KontrakController::class, 'get_data_control']);
    Route::get('/get_status_data',[KontrakController::class, 'get_status_data']);
    Route::get('/getdatamaterial',[KontrakController::class, 'getdatamaterial']);
    Route::get('/create',[KontrakController::class, 'create']);
    Route::get('/total_item',[KontrakController::class, 'total_item']);
    Route::get('/tampil_personal_periode',[KontrakController::class, 'tampil_personal_periode']);
    Route::get('/tampil_operasional_periode',[KontrakController::class, 'tampil_operasional_periode']);
    Route::get('/tampil_material',[KontrakController::class, 'tampil_material']);
    Route::get('/total_qty',[KontrakController::class, 'total_qty']);
    Route::get('/delete',[KontrakController::class, 'delete']);
    Route::get('/tampil_periode',[KontrakController::class, 'tampil_periode']);
    Route::get('/tampil_personal',[KontrakController::class, 'tampil_personal']);
    Route::get('/tampil_operasional',[KontrakController::class, 'tampil_operasional']);
    Route::get('/tampil_pengeluaran',[KontrakController::class, 'tampil_pengeluaran']);
    Route::get('/tampil_risiko_view',[KontrakController::class, 'tampil_risiko_view']);
    Route::get('/delete_personal',[KontrakController::class, 'delete_personal']);
    Route::get('/delete_operasional',[KontrakController::class, 'delete_operasional']);
    Route::get('/delete_material',[KontrakController::class, 'delete_material']);
    Route::get('/modal',[KontrakController::class, 'modal']);
    Route::post('/',[KontrakController::class, 'store']);
    Route::get('/kirim_kadis_komersil',[KontrakController::class, 'kirim_kadis_komersil']);
    Route::post('/kembali_komersil',[KontrakController::class, 'kembali_komersil']);
    Route::post('/approve_kadis_komersil',[KontrakController::class, 'approve_kadis_komersil']);
    Route::post('/approve_kadis_operasional',[KontrakController::class, 'approve_kadis_operasional']);
    Route::post('/approve_mgr_operasional',[KontrakController::class, 'approve_mgr_operasional']);
    Route::post('/approve_direktur_operasional',[KontrakController::class, 'approve_direktur_operasional']);
    Route::get('/kirim_procurement',[KontrakController::class, 'kirim_procurement']);
    Route::post('/store_material',[KontrakController::class, 'store_material']);
    Route::post('/store_personal',[KontrakController::class, 'store_personal']);
    Route::post('/store_operasional',[KontrakController::class, 'store_operasional']);
    Route::post('/store_pengadaan',[KontrakController::class, 'store_pengadaan']);
    Route::post('/store_periode_personal',[KontrakController::class, 'store_periode_personal']);
    Route::post('/store_periode_operasional',[KontrakController::class, 'store_periode_operasional']);
    Route::post('/store_negosiasi',[KontrakController::class, 'store_negosiasi']);
});

Route::group(['middleware' => 'auth'], function() {
    /**
    * Logout Route
    */
    Route::get('/logout-perform', [LogoutController::class, 'perform'])->name('logout.perform');
 });

Route::group(['prefix' => 'user'],function(){
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('/create', [UserController::class, 'create']);
    Route::get('/get_data', [UserController::class, 'get_data']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

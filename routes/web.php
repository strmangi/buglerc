<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//=============admin route start============
	Route::view('/blank', 'admin\template\blank');
	Route::view('/form', 'admin\template\form');
	Route::view('/data-table', 'admin\template\data-table');
	// Route::get('/forgot_password', 'Security\ForgetPassword@forget');


	Route::resource('/admin/users', 'Admin\UserController' ,['except'=>['show','create','store']]);

	Route::namespace('Admin')->prefix('admin')->name('admin.')->middleware('can:manage-user')->group(function(){
		//--------users---------
		Route::resource('/users', 'UserController' ,['except'=>['show']]);
		
		//--------coaches--------
		Route::resource('/coach', 'CoachControlle');
		
		//--------coach-type--------
		Route::resource('/type', 'CoachTypeController');
		
		//--------drivers--------
		Route::get('/drivers', 'UserController@userDetail');

		//---------dashboard------
		Route::get('/', 'UserController@index');
		//----dashboard ajax search by date----
		Route::post('/', 'UserController@getTripByDate')->name('trip.data');
		Route::post('/dutybydate', 'UserController@getDutyByDate')->name('duty.data');
		Route::post('/duty-filter-date', 'InquiryController@getDutyBydate')->name('duty.bydata');

		Route::post('/inquiry-filter-date', 'InquiryController@getInquiryBydate')->name('duty.bydata1');
		
		//--------customers--------
		Route::resource('/customer', 'CustomerController');
		Route::resource('/school', 'SchoolController');

		
		//--------inquiry--------
		Route::resource('/inquiry', 'InquiryController');
		Route::get('/inquiry_school', 'InquiryController@school');
		Route::get('/delete_enquery/{id}', 'InquiryController@delete_Inquery');
		Route::get('/inquiry_school/edit/{id}', 'InquiryController@school_edit');
		Route::post('/listinquery', 'InquiryController@inquirieslist')->name('inquery.listinquery');
		Route::post('/school_inquiry/{id}', 'InquiryController@school_update');
		Route::post('/school_listinquery', 'InquiryController@schoolinquirieslist')->name('inquiry_school.school_listinquery');

		Route::get('/inquiry/clone/{id}', 'InquiryController@cloneInquiry')->name('inquiry.clone');
		Route::get('/inquiry/conform/{id}', 'InquiryController@confirmInquiry')->name('inquiry.conform');
		Route::get('/inquiry/print/{id}', 'InquiryController@printInquiry')->name('inquiry.print');
		Route::get('/inquiry/sendconfirmmail/{id}', 'InquiryController@sendConfirmMail')->name('inquiry.sendConfirmMail');
		Route::get('/inquiry/sendemailenquery/{id}', 'InquiryController@SendEmailEnquery')->name('inquiry.SendEmailEnquery');
		Route::post('/inquiry/deleteCustomeRecord', 'InquiryController@deleteCustomeRecord')->name('inquiry.deleteCustomeRecord');

		//--------driver assign to trip--------
		Route::get('/trip/assign/', 'InquiryController@dutyIndex')->name('assign.trip');
		Route::get('/mail/instruction/{id}/{sheetID}', 'InquiryController@sendmailInstruction')->name('instruction.mail');
		Route::get('/driver/assign/{id}', 'InquiryController@fechTripAndDriver')->name('driver.assign');
		Route::post('/driver/assign', 'InquiryController@assignDriver')->name('driver.assign.store');
		Route::post('/drivers', 'InquiryController@getdriverlist')->name('driver.list');
		Route::post('/coachlist', 'InquiryController@coachlistlist')->name('coach.list');

		Route::post('/update/invoice', 'InquiryController@updateinvoice');
		
		//--------get trip detail------
		Route::post('/get_trip/{id}', 'InquiryController@getTrip');

		//--------get ------
		Route::post('/get_coach/{id}', 'InquiryController@getCoaches');

		//--------get coach type costs ------
		Route::post('/get_cost/{id}', 'InquiryController@getCost');

		//----driver instruction sheet download-----
		Route::get('/download-sheet/{id}','InquiryController@driverInstructionSheetDownload')->name('instructionsheet.download');
		Route::get('/view-sheet/{id}','InquiryController@driverInstructionSheetView')->name('instructionsheet.view');
		Route::get('/driver_instructionsheet/{id}','InquiryController@driverInstructionSendMail')->name('driver_instructionsheet');
		Route::get('/edit-sheet/{id}','InquiryController@editDriverInstructionSheet')->name('instructionsheet.edit');
		Route::match(['put', 'patch'],'/update-sheet/{id}', 'InquiryController@updateDriverSheet')->name('instructionsheet.update');

		//-------purchase_order----
		Route::get('/purchase-order/create', 'InquiryController@purchaseOrderCreate')->name('purchase_order.create');
		Route::post('/purchase-order/save', 'InquiryController@purchaseOrderStore')->name('purchase_order.store');
		Route::get('/purchase-order', 'InquiryController@purchaseOrderIndex')->name('purchase_order.index');
		Route::get('/purchase-order/{id}', 'InquiryController@purchaseOrderView')->name('purchase_order.view');

		Route::get('/purchase-order/supplier/details', 'InquiryController@supplierDetail')->name('purchase_order.supplier');
		//-------defect_notice----
		Route::view('/defect-notice', 'admin.template.Inquiry.defect_notice')->name('defect_notice');

		//--------csv import---
		Route::post('import', 'CustomerController@import')->name('import');
		Route::post('import_school', 'SchoolController@import')->name('import_school');
		Route::get('export', 'CustomerController@export')->name('export');
	
		Route::get('inquiry/import/inqury/{id?}', 'InquiryController@import')->name('trip.import');
		Route::get('inquiry/import/school', 'InquiryController@import')->name('trip.import_school');
		Route::post('inquiry/import/save', 'InquiryController@saveImport')->name('trip.import.save');

	});
       

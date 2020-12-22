<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
if (env('APP_ENV') === 'production') {
    URL::forceSchema('https');
}

Route::group(['middleware' => ['web','input_sanitizer_middleware']], function () {
		Route::auth();

		//Route::get('/test/{id}', 'BillController@addSales');
		Route::get('/test2', 'BillController@fixDropChargeSales');
		Route::get('/removedup', 'BillController@removeDuplicateInventory');
		Route::get('/addsale/{id}', 'BillController@addSale');
		Route::get('/showbatch/{id}', 'BillController@showBatch');

		Route::get('/home', function() {
				//return view('welcome');
				if (Auth::check()) {
					return redirect('/landing');
				} else {
					return redirect('/login');
				}
		});

		Route::get('/', function(Request $request) {
				if (Auth::check()) {
					return redirect('/landing');
				} else {
					return redirect('/login');
				}
		});

		Route::get('/order_sheet/cancel/{id}/edit', 'OrderSheetController@cancel_edit');
		Route::get('/order_sheet/cancel/{id}', 'OrderSheetController@cancel');
		Route::get('/order_sheet/delete/{id}', 'OrderSheetController@cancel_delete');
		Route::post('/order_sheet/cancel/{id}', 'OrderSheetController@cancel_post');
		Route::get('/order_sheet/{id}', 'OrderSheetController@index');
		Route::post('/order_sheet/{id}', 'OrderSheetController@update');
		Route::delete('/order_sheet/{id}', 'OrderSheetController@cancel_destroy');

		Route::resource('product_category_parents', 'ProductParentController');
		Route::get('/product_category_parents/id/{id}', 'ProductParentController@searchById');
		Route::post('/product_category_parent/search', 'ProductParentController@search');
		Route::get('/product_category_parent/search', 'ProductParentController@search');
		Route::get('/product_category_parents/delete/{id}', 'ProductParentController@delete');

		Route::resource('discount_rules', 'DiscountRuleController');
		Route::get('/discount_rules/id/{id}', 'DiscountRuleController@searchById');
		Route::post('/discount_rule/search', 'DiscountRuleController@search');
		Route::get('/discount_rule/search', 'DiscountRuleController@search');
		Route::get('/discount_rules/delete/{id}', 'DiscountRuleController@delete');

		Route::resource('discharge_summaries', 'DischargeSummaryController');
		Route::get('/discharge_summaries/id/{id}', 'DischargeSummaryController@searchById');
		Route::post('/discharge_summary/search', 'DischargeSummaryController@search');
		Route::get('/discharge_summary/search', 'DischargeSummaryController@search');
		Route::get('/discharge_summary/reset/{id}', 'DischargeSummaryController@reset');
		Route::get('/discharge_summaries/delete/{id}', 'DischargeSummaryController@delete');

		Route::get('/lock_orders/{id}', 'EncounterController@lockOrders');
		Route::get('/convert/{id}', 'EncounterController@convertPatientOrders');

		Route::get('/imaging', 'OrderImagingController@imaging');
		Route::post('/imaging', 'OrderImagingController@addImaging');
		Route::get('/imaging/delete/{id}', 'OrderImagingController@deleteImaging');

		Route::resource('order_imaging', 'OrderImagingController');
		Route::get('/order_imaging/id/{id}', 'OrderImagingController@searchById');
		Route::post('/order_imaging/search', 'OrderImagingController@search');
		Route::get('/order_imaging/search', 'OrderImagingController@search');
		Route::get('/order_imaging/delete/{id}', 'OrderImagingController@delete');
		

		Route::resource('fee_schedules', 'FeeScheduleController');
		Route::get('/fee_schedules/id/{id}', 'FeeScheduleController@searchById');
		Route::post('/fee_schedule/search', 'FeeScheduleController@search');
		Route::get('/fee_schedule/search', 'FeeScheduleController@search');
		Route::get('/fee_schedules/delete/{id}', 'FeeScheduleController@delete');
		

		Route::resource('consultation_histories', 'ConsultationHistoryController');
		Route::post('/consultation_history', 'ConsultationHistoryController@save');
		Route::get('/consultation_histories/id/{id}', 'ConsultationHistoryController@searchById');
		Route::post('/consultation_history/search', 'ConsultationHistoryController@search');
		Route::get('/consultation_history/search', 'ConsultationHistoryController@search');
		Route::get('/consultation_histories/delete/{id}', 'ConsultationHistoryController@delete');
		Route::post('/consultation_history', 'ConsultationHistoryController@post')->name('history.post');
		

		Route::resource('histories', 'HistoryController');
		Route::get('/histories/id/{id}', 'HistoryController@searchById');
		Route::post('/history/search', 'HistoryController@search');
		Route::get('/history/search', 'HistoryController@search');
		Route::get('/histories/delete/{id}', 'HistoryController@delete');

		Route::resource('patient_mrns', 'PatientMrnController');
		Route::get('/patient_mrns/id/{id}', 'PatientMrnController@searchById');
		Route::post('/patient_mrn/search', 'PatientMrnController@search');
		Route::get('/patient_mrn/search', 'PatientMrnController@search');
		Route::get('/patient_mrns/delete/{id}', 'PatientMrnController@delete');
		
		Route::get('/patient/adjust', 'PatientController@adjustMRN');

		Route::resource('bill_totals', 'BillTotalController');
		Route::get('/bill_totals/id/{id}', 'BillTotalController@searchById');
		Route::post('/bill_total/search', 'BillTotalController@search');
		Route::get('/bill_total/search', 'BillTotalController@search');
		Route::get('/bill_totals/delete/{id}', 'BillTotalController@delete');

		Route::resource('purchase_request_statuses', 'PurchaseRequestStatusController');
		Route::get('/purchase_request_statuses/id/{id}', 'PurchaseRequestStatusController@searchById');
		Route::post('/purchase_request_status/search', 'PurchaseRequestStatusController@search');
		Route::get('/purchase_request_status/search', 'PurchaseRequestStatusController@search');
		Route::get('/purchase_request_statuses/delete/{id}', 'PurchaseRequestStatusController@delete');

		Route::post('/procedures/find', 'ConsultationProcedureController@find')->name('procedures.find');
		Route::post('/procedures/add', 'ConsultationProcedureController@addProcedure')->name('procedures.add');
		Route::post('/procedures/list', 'ConsultationProcedureController@listProcedure')->name('procedures.list');
		Route::post('/procedures/remove', 'ConsultationProcedureController@removeProcedure')->name('procedures.remove');
		Route::post('/procedures/update', 'ConsultationProcedureController@updateProcedure')->name('procedures.update');

		Route::post('/medications/find', 'OrderDrugController@find')->name('medications.find');
		Route::post('/medications/table', 'OrderDrugController@medicationTable')->name('medications.table');
		Route::post('/medications/add', 'OrderDrugController@addDrug')->name('medications.add');
		Route::post('/medications/remove', 'OrderDrugController@removeDrug')->name('medications.remove');
		Route::post('/medications/update', 'OrderDrugController@updateDrug')->name('medications.update');
		Route::post('/medications/history', 'OrderDrugController@drugHistory')->name('medications.history');
		Route::post('/medications/renew', 'OrderDrugController@renewDrug')->name('medications.renew');
		Route::get('/medications', 'OrderDrugController@medications');
		//Route::get('/medications/add/{id}', 'OrderDrugController@addDrug');
		
		Route::post('/orders/update', 'OrderController@updateOrder')->name('orders.update_order');
		Route::post('/orders/add', 'OrderController@addOrder')->name('orders.add');
		Route::post('/orders/remove', 'OrderController@removeOrder')->name('orders.remove');
		
		Route::resource('stock_tags', 'StockTagController');
		Route::get('/stock_tags/id/{id}', 'StockTagController@searchById');
		Route::post('/stock_tag/search', 'StockTagController@search');
		Route::get('/stock_tag/search', 'StockTagController@search');
		Route::get('/stock_tags/delete/{id}', 'StockTagController@delete');
		
		Route::resource('inventory_batches', 'InventoryBatchController');
		Route::get('/inventory_batches/id/{id}', 'InventoryBatchController@searchById');
		Route::get('/inventory_batches/product/{id}', 'InventoryBatchController@index_product');
		Route::get('/inventory_batches/add/{id}', 'InventoryBatchController@add');
		Route::post('/inventory_batch/search', 'InventoryBatchController@search');
		Route::get('/inventory_batch/search', 'InventoryBatchController@search');
		Route::get('/inventory_batches/delete/{id}', 'InventoryBatchController@delete');
		
		Route::resource('inventory_movements', 'InventoryMovementController');
		Route::get('/inventory_movements/id/{id}', 'InventoryMovementController@searchById');
		Route::get('/inventory_movements/show/{id}', 'InventoryMovementController@show');
		Route::post('/inventory_movement/search', 'InventoryMovementController@search');
		Route::post('/inventory_movement/search_document', 'InventoryMovementController@searchDocument');
		Route::get('/inventory_movement/search_document', 'InventoryMovementController@searchDocument');
		Route::post('/inventory_movement/post_item', 'InventoryMovementController@postItem');
		Route::get('/inventory_movement/search', 'InventoryMovementController@search');
		Route::post('/inventory_movement/search_item', 'InventoryMovementController@searchItem');
		Route::get('/inventory_movements/delete/{id}', 'InventoryMovementController@delete');
		Route::get('/inventory_movements/add/{move_id}/{product_code}', 'InventoryMovementController@add');
		Route::get('/inventory_movements/master_document/{id}', 'InventoryMovementController@masterDocument');
		Route::get('/inventory_movements/master_item/{id}/{document_id?}', 'InventoryMovementController@masterItem');
		Route::get('/inventory_movement/convert/{from}/{to}', 'InventoryMovementController@convert');
		
		Route::post('/schema/destroy', 'SchemaController@destroy');
		Route::get('/schema/index/{table}', 'SchemaController@index');
		Route::get('/schema/form/{table}/{id?}', 'SchemaController@form');
		Route::get('/schema/delete/{table}/{id?}', 'SchemaController@delete');
		Route::get('/schema/{table}', 'SchemaController@schema');
		Route::post('/schema/post', 'SchemaController@post');

		Route::resource('inventories', 'InventoryController');
		Route::get('/inventories/id/{id}', 'InventoryController@searchById');
		Route::post('/inventory/search', 'InventoryController@search');
		Route::get('/inventory/search', 'InventoryController@search');
		Route::get('/inventories/delete/{id}', 'InventoryController@delete');
		Route::get('/inventories/detail/{id}', 'InventoryController@detail');
		Route::post('/inventories/submit/{move_id}', 'InventoryController@submit');
		Route::get('/inventories/confirm/{move_id}', 'InventoryController@confirm');
		Route::post('/inventories/post/{move_id}', 'InventoryController@post');
		Route::get('/inventory/enquiry', 'InventoryController@enquiry');
		Route::post('/inventory/enquiry', 'InventoryController@enquiry');
		
		Route::resource('purchase_documents', 'PurchaseDocumentController');
		Route::get('/purchase_documents/id/{id}', 'PurchaseDocumentController@searchById');
		Route::post('/purchase_document/search', 'PurchaseDocumentController@search');
		Route::get('/purchase_document/search', 'PurchaseDocumentController@search');
		Route::get('/purchase_documents/delete/{id}', 'PurchaseDocumentController@delete');
		
		Route::resource('purchase_lines', 'PurchaseLineController');
		Route::get('/purchase_line/enquiry', 'PurchaseLineController@enquiry');
		Route::post('/purchase_line/enquiry', 'PurchaseLineController@enquiry');
		Route::get('/purchase_line/backorder', 'PurchaseLineController@backOrder');
		Route::post('/purchase_line/backorder', 'PurchaseLineController@backOrder');
		Route::get('/purchase_lines/add/{purchase_id}/{product_code}/{type?}', 'PurchaseLineController@add');
		Route::get('/purchase_lines/add_reorder/{purchase_id}', 'PurchaseLineController@addReorder');
		Route::get('/purchase_lines/show/{id}', 'PurchaseLineController@show');
		Route::get('/purchase_lines/detail/{id}', 'PurchaseLineController@detail');
		Route::get('/purchase_lines/id/{id}', 'PurchaseLineController@searchById');
		Route::post('/purchase_line/search', 'PurchaseLineController@search');
		Route::get('/purchase_line/search', 'PurchaseLineController@search');
		Route::get('/purchase_lines/delete/{id}', 'PurchaseLineController@delete');
		Route::get('/purchase_lines/convert/{from}/{to}', 'PurchaseLineController@convert');
		Route::post('/purchase_lines/multiple', 'PurchaseLineController@multiple');
		Route::post('/purchase_lines/delete_selection/{id}', 'PurchaseLineController@deleteSelection');
		Route::get('/purchase_lines/master_item/{id?}/{document_id?}', 'PurchaseLineController@masterItem');
		Route::post('/purchase_lines/post/{id}', 'PurchaseLineController@post');
		Route::get('/purchase_lines/post_confirmation/{id}', 'PurchaseLineController@post_confirmation');
		Route::post('/purchase_lines/close/{id}', 'PurchaseLineController@close');
		
		Route::get('/purchases/master_document', 'PurchaseController@masterDocument');
		Route::post('/purchase/master_search', 'PurchaseController@masterSearch');
		Route::get('/purchase/master_search', 'PurchaseController@masterSearch');
		Route::resource('purchases', 'PurchaseController');
		Route::get('/purchases/id/{id}', 'PurchaseController@searchById');
		Route::post('/purchase/search', 'PurchaseController@search');
		Route::get('/purchase/search', 'PurchaseController@search');
		Route::get('/purchases/delete/{id}', 'PurchaseController@delete');
		

		Route::resource('product_uoms', 'ProductUomController');
		Route::get('/product_uoms/id/{id}', 'ProductUomController@searchById');
		Route::post('/product_uom/search', 'ProductUomController@search');
		Route::get('/product_uom/search', 'ProductUomController@search');
		Route::get('/product_uoms/delete/{id}', 'ProductUomController@delete');
		Route::get('/product/uom/{id}', 'ProductUomController@uom');

		Route::resource('entitlements', 'EntitlementController');
		Route::get('/entitlements/id/{id}', 'EntitlementController@searchById');
		Route::post('/entitlement/search', 'EntitlementController@search');
		Route::get('/entitlement/search', 'EntitlementController@search');
		Route::get('/entitlements/delete/{id}', 'EntitlementController@delete');
		
		Route::get('/reports/aggregate', 'ReportController@aggregate');

		/** pdftk **/
		Route::get('/pdf/{patient_id}/{form_name}', 'PDFController@encounter');

		/** Rest API **/
		Route::get('/api/patient/{mrn}', 'APIController@patient');
		Route::get('/api/dependant/{mrn}', 'APIController@dependant');
		Route::get('/api/allergy/{mrn}', 'APIController@allergy');
		Route::get('/api/appointment/{mrn}', 'APIController@appointment');
		Route::get('/api/discharge/{mrn}', 'APIController@discharge');
		Route::get('/api/medication/{mrn}', 'APIController@medication');

		Route::resource('order_stops', 'OrderStopController',['except'=>['create']]);
		Route::get('/order_stop/create/{id}', 'OrderStopController@create');
		Route::get('/order_stops/id/{id}', 'OrderStopController@searchById');
		Route::post('/order_stop/search', 'OrderStopController@search');
		Route::get('/order_stop/search', 'OrderStopController@search');
		Route::get('/order_stops/delete/{id}', 'OrderStopController@delete');
		
		Route::resource('consultation_annotations', 'ConsultationAnnotationController');
		Route::get('/consultation_annotations/id/{id}', 'ConsultationAnnotationController@searchById');
		Route::post('/consultation_annotation/search', 'ConsultationAnnotationController@search');
		Route::get('/consultation_annotation/search', 'ConsultationAnnotationController@search');
		Route::get('/consultation_annotations/delete/{id}', 'ConsultationAnnotationController@delete');
		Route::get('/consultation_annotations/clear/{consultation_id}/{annotation_image}', 'ConsultationAnnotationController@clear');
		Route::get('/consultation_annotations/get/{consulation_id}/{annotation_image}', 'ConsultationAnnotationController@getAnnotation');

		Route::resource('medication_records', 'MedicationRecordController');
		Route::get('/medication_records/id/{id}', 'MedicationRecordController@searchById');
		Route::post('/medication_record/search', 'MedicationRecordController@search');
		Route::get('/medication_record/search', 'MedicationRecordController@search');
		Route::get('/medication_records/delete/{id}', 'MedicationRecordController@delete');
		Route::get('/medication_record/mar/{encounter_id?}', 'MedicationRecordController@medicationAdministrationRecord');
		Route::get('/medication_record/record/{order_id}/{index}/{slot}', 'MedicationRecordController@marRecord');
		Route::get('/medication_record/verify/{order_id}/{index}/{slot}', 'MedicationRecordController@marVerify');
		Route::get('/medication_record/datetime/{id}', 'MedicationRecordController@datetime');

		Route::resource('bed_charges', 'BedChargeController');
		Route::get('/bed_charges/id/{id}', 'BedChargeController@searchById');
		Route::post('/bed_charge/search', 'BedChargeController@search');
		Route::get('/bed_charge/search', 'BedChargeController@search');
		Route::get('/bed_charges/delete/{id}', 'BedChargeController@delete');

		Route::resource('bill_discounts', 'BillDiscountController', ['except'=>['create']]);
		Route::get('/bill_discounts/create/{encounter_id}', 'BillDiscountController@create');
		Route::get('/bill_discounts/id/{id}', 'BillDiscountController@searchById');
		Route::post('/bill_discount/search', 'BillDiscountController@search');
		Route::get('/bill_discount/search', 'BillDiscountController@search');
		Route::get('/bill_discounts/delete/{id}', 'BillDiscountController@delete');


		Route::post('/order/enquiry', 'OrderController@enquiry');
		Route::get('/order/enquiry', 'OrderController@enquiry');
		Route::get('/order/drop/{id}', 'OrderController@drop');

		Route::resource('order_routes', 'OrderRouteController');
		Route::get('/order_routes/id/{id}', 'OrderRouteController@searchById');
		Route::post('/order_route/search', 'OrderRouteController@search');
		Route::get('/order_route/search', 'OrderRouteController@search');
		Route::get('/order_routes/delete/{id}', 'OrderRouteController@delete');

		Route::get('/amqp', 'OrderController@amqp');

		Route::resource('bill_agings', 'BillAgingController');
		Route::get('/bill_agings/id/{id}', 'BillAgingController@searchById');
		Route::post('/bill_aging/search', 'BillAgingController@search');
		Route::get('/bill_aging/search', 'BillAgingController@search');
		Route::get('/bill_agings/delete/{id}', 'BillAgingController@delete');
		Route::post('/bill_aging/enquiry', 'BillAgingController@enquiry');
		Route::get('/bill_aging/enquiry', 'BillAgingController@enquiry');
		


		Route::resource('patient_classifications', 'PatientClassificationController');
		Route::get('/patient_classifications/id/{id}', 'PatientClassificationController@searchById');
		Route::post('/patient_classification/search', 'PatientClassificationController@search');
		Route::get('/patient_classification/search', 'PatientClassificationController@search');
		Route::get('/patient_classifications/delete/{id}', 'PatientClassificationController@delete');
		Route::get('/admission/classification', 'AdmissionController@classification');
		Route::post('/admission/classification', 'AdmissionController@updateClassification');

		Route::resource('refunds', 'RefundController',['except'=>['create']]);
		Route::get('/refunds/create/{patient_id}', 'RefundController@create');
		Route::get('/refunds/id/{id}', 'RefundController@searchById');
		Route::post('/refund/search', 'RefundController@search');
		Route::get('/refund/search', 'RefundController@search');
		Route::get('/refunds/delete/{id}', 'RefundController@delete');
		Route::get('/refund/transactions/{patient_id}', 'RefundController@transactions');
		Route::post('/refund/enquiry', 'RefundController@enquiry');
		Route::get('/refund/enquiry', 'RefundController@enquiry');

		Route::resource('diet_censuses', 'DietCensusController');
		Route::get('/diet_censuses/id/{id}', 'DietCensusController@searchById');
		Route::post('/diet_census/search', 'DietCensusController@search');
		Route::get('/diet_census/search', 'DietCensusController@search');
		Route::get('/diet_censuses/delete/{id}', 'DietCensusController@delete');
		Route::post('/diet_census/enquiry', 'DietCensusController@enquiry');
		Route::get('/diet_census/enquiry', 'DietCensusController@enquiry');

		Route::resource('payment_credits', 'PaymentCreditController');
		Route::get('/payment_credits/id/{id}', 'PaymentCreditController@searchById');
		Route::post('/payment_credit/search', 'PaymentCreditController@search');
		Route::get('/payment_credit/search', 'PaymentCreditController@search');
		Route::get('/payment_credits/delete/{id}', 'PaymentCreditController@delete');

		Route::resource('credit_cards', 'CreditCardController');
		Route::get('/credit_cards/id/{id}', 'CreditCardController@searchById');
		Route::post('/credit_card/search', 'CreditCardController@search');
		Route::get('/credit_card/search', 'CreditCardController@search');
		Route::get('/credit_cards/delete/{id}', 'CreditCardController@delete');

		Route::resource('product_price_tiers', 'ProductPriceTierController');
		Route::get('/product_price_tiers/id/{id}', 'ProductPriceTierController@searchById');
		Route::post('/product_price_tier/search', 'ProductPriceTierController@search');
		Route::get('/product_price_tier/search', 'ProductPriceTierController@search');
		Route::get('/product_price_tiers/delete/{id}', 'ProductPriceTierController@delete');

		Route::resource('product_charges', 'ProductChargeController');
		Route::get('/product_charges/id/{id}', 'ProductChargeController@searchById');
		Route::post('/product_charge/search', 'ProductChargeController@search');
		Route::get('/product_charge/search', 'ProductChargeController@search');
		Route::get('/product_charges/delete/{id}', 'ProductChargeController@delete');

		Route::resource('loan_types', 'LoanTypeController');
		Route::get('/loan_types/id/{id}', 'LoanTypeController@searchById');
		Route::post('/loan_type/search', 'LoanTypeController@search');
		Route::get('/loan_type/search', 'LoanTypeController@search');
		Route::get('/loan_types/delete/{id}', 'LoanTypeController@delete');
		
		Route::resource('bed_transactions', 'BedTransactionController');
		Route::get('/bed_transactions/id/{id}', 'BedTransactionController@searchById');
		Route::post('/bed_transaction/search', 'BedTransactionController@search');
		Route::get('/bed_transaction/search', 'BedTransactionController@search');
		Route::get('/bed_transactions/delete/{id}', 'BedTransactionController@delete');

		Route::resource('priorities', 'PriorityController');
		Route::get('/priorities/id/{id}', 'PriorityController@searchById');
		Route::post('/priority/search', 'PriorityController@search');
		Route::get('/priority/search', 'PriorityController@search');
		Route::get('/priorities/delete/{id}', 'PriorityController@delete');
		
		Route::post('/preadmission/enquiry', 'BedBookingController@enquiry');
		Route::get('/preadmission/enquiry', 'BedBookingController@enquiry');

		Route::resource('bed_movements', 'BedMovementController');
		Route::get('/bed_movements/id/{id}', 'BedMovementController@searchById');
		Route::post('/bed_movement/search', 'BedMovementController@search');
		Route::get('/bed_movement/search', 'BedMovementController@search');
		Route::get('/bed_movements/delete/{id}', 'BedMovementController@delete');
		Route::post('/bed_movement/enquiry', 'BedMovementController@enquiry');
		Route::get('/bed_movement/enquiry', 'BedMovementController@enquiry');
				
		Route::resource('product_groups', 'ProductGroupController');
		Route::get('/product_groups/id/{id}', 'ProductGroupController@searchById');
		Route::post('/product_group/search', 'ProductGroupController@search');
		Route::get('/product_group/search', 'ProductGroupController@search');
		Route::get('/product_groups/delete/{id}', 'ProductGroupController@delete');
		

		Route::resource('product_subcategories', 'ProductSubcategoryController');
		Route::get('/product_subcategories/id/{id}', 'ProductSubcategoryController@searchById');
		Route::post('/product_subcategory/search', 'ProductSubcategoryController@search');
		Route::get('/product_subcategory/search', 'ProductSubcategoryController@search');
		Route::get('/product_subcategories/delete/{id}', 'ProductSubcategoryController@delete');
		

		Route::resource('stock_receives', 'StockReceiveController');
		Route::get('/stock_receives/id/{id}', 'StockReceiveController@searchById');
		Route::post('/stock_receive/search', 'StockReceiveController@search');
		Route::get('/stock_receive/search', 'StockReceiveController@search');
		Route::get('/stock_receives/delete/{id}', 'StockReceiveController@delete');

		Route::resource('stock_input_batches', 'StockInputBatchController');
		Route::get('/stock_input_batches/id/{id}', 'StockInputBatchController@searchById');
		Route::post('/stock_input_batch/search', 'StockInputBatchController@search');
		Route::post('/stock_input_batch/add', 'StockInputBatchController@batchAdd');
		Route::get('/stock_input_batch/search', 'StockInputBatchController@search');
		Route::get('/stock_input_batches/delete/{id}', 'StockInputBatchController@delete');
		Route::get('/stock_input_batches/batch/{line_id}', 'StockInputBatchController@batch');

		Route::resource('drug_diseases', 'DrugDiseaseController');
		Route::get('/drug_diseases/id/{id}', 'DrugDiseaseController@searchById');
		Route::post('/drug_disease/search', 'DrugDiseaseController@search');
		Route::get('/drug_disease/search', 'DrugDiseaseController@search');
		Route::get('/drug_diseases/delete/{id}', 'DrugDiseaseController@delete');
		
		Route::resource('drug_prescriptions', 'DrugPrescriptionController');
		Route::get('/drug_prescriptions/id/{id}', 'DrugPrescriptionController@searchById');
		Route::post('/drug_prescription/search', 'DrugPrescriptionController@search');
		Route::get('/drug_prescription/search', 'DrugPrescriptionController@search');
		Route::get('/drug_prescriptions/delete/{id}', 'DrugPrescriptionController@delete');
		
		Route::resource('drug_special_instructions', 'DrugSpecialInstructionController');
		Route::get('/drug_special_instructions/id/{id}', 'DrugSpecialInstructionController@searchById');
		Route::post('/drug_special_instruction/search', 'DrugSpecialInstructionController@search');
		Route::get('/drug_special_instruction/search', 'DrugSpecialInstructionController@search');
		Route::get('/drug_special_instructions/delete/{id}', 'DrugSpecialInstructionController@delete');
		
		Route::resource('drug_instructions', 'DrugInstructionController');
		Route::get('/drug_instructions/id/{id}', 'DrugInstructionController@searchById');
		Route::post('/drug_instruction/search', 'DrugInstructionController@search');
		Route::get('/drug_instruction/search', 'DrugInstructionController@search');
		Route::get('/drug_instructions/delete/{id}', 'DrugInstructionController@delete');
		

		Route::resource('drug_indications', 'DrugIndicationController');
		Route::get('/drug_indications/id/{id}', 'DrugIndicationController@searchById');
		Route::post('/drug_indication/search', 'DrugIndicationController@search');
		Route::get('/drug_indication/search', 'DrugIndicationController@search');
		Route::get('/drug_indications/delete/{id}', 'DrugIndicationController@delete');

		Route::resource('drug_cautions', 'DrugCautionController');
		Route::get('/drug_cautions/id/{id}', 'DrugCautionController@searchById');
		Route::post('/drug_caution/search', 'DrugCautionController@search');
		Route::get('/drug_caution/search', 'DrugCautionController@search');
		Route::get('/drug_cautions/delete/{id}', 'DrugCautionController@delete');

		Route::resource('stock_input_lines', 'StockInputLineController');
		Route::get('/stock_input_lines/id/{id}', 'StockInputLineController@searchById');
		Route::post('/stock_input_line/search', 'StockInputLineController@search');
		Route::get('/stock_input_line/search', 'StockInputLineController@search');
		Route::get('/stock_input_lines/delete/{id}', 'StockInputLineController@delete');

		Route::resource('stock_inputs', 'StockInputController');
		Route::get('/stock_inputs/id/{id}', 'StockInputController@searchById');
		Route::post('/stock_input/search', 'StockInputController@search');
		Route::get('/stock_input/search', 'StockInputController@search');
		Route::get('/stock_inputs/delete/{id}', 'StockInputController@delete');
		Route::get('/stock_inputs/input/{id}/{product_code?}', 'StockInputController@input');
		Route::get('/stock_inputs/show/{id}/{product_code?}', 'StockInputController@show');
		Route::get('/stock_input/post/{id}', 'StockInputController@post');
		//Route::post('/stock_input/input', 'StockInputController@input_post');
		Route::post('/stock_input/save/{id}', 'StockInputController@save');
		Route::get('/stock_input/close/{id}', 'StockInputController@input_close');
		Route::post('/stock_input/post/{id}', 'StockInputController@post');
		Route::get('/stock_input/indent/{id}', 'StockInputController@indent');

		Route::resource('stock_limits', 'StockLimitController');
		Route::get('/stock_limits/id/{id}', 'StockLimitController@searchById');
		Route::post('/stock_limit/search', 'StockLimitController@search');
		Route::get('/stock_limit/search', 'StockLimitController@search');
		Route::get('/stock_limit/{id}', 'StockLimitController@product');
		Route::post('/stock_limit/{id}', 'StockLimitController@updateLimit');
		Route::get('/stock_limits/delete/{id}', 'StockLimitController@delete');

		Route::resource('admission_therapeutics', 'AdmissionTherapeuticController');
		Route::get('/admission_therapeutics/id/{id}', 'AdmissionTherapeuticController@searchById');
		Route::post('/admission_therapeutic/search', 'AdmissionTherapeuticController@search');
		Route::get('/admission_therapeutic/search', 'AdmissionTherapeuticController@search');
		Route::get('/admission_therapeutics/delete/{id}', 'AdmissionTherapeuticController@delete');

		Route::resource('diet_therapeutics', 'DietTherapeuticController');
		Route::get('/diet_therapeutics/id/{id}', 'DietTherapeuticController@searchById');
		Route::post('/diet_therapeutic/search', 'DietTherapeuticController@search');
		Route::get('/diet_therapeutic/search', 'DietTherapeuticController@search');
		Route::get('/diet_therapeutics/delete/{id}', 'DietTherapeuticController@delete');
		
		Route::resource('team_members', 'TeamMemberController');
		Route::get('/team_members/id/{id}', 'TeamMemberController@searchById');
		Route::post('/team_member/search', 'TeamMemberController@search');
		Route::get('/team_member/search', 'TeamMemberController@search');
		Route::get('/team_members/delete/{id}', 'TeamMemberController@delete');
		
		Route::get('/chart/line/{form_code}/{encounter_id}', 'ChartController@line');
		Route::get('/partograph/{encounter_id}', 'ChartController@partograph');
		Route::get('/vital_signs/{encounter_id}', 'ChartController@vitalSign');
		Route::get('/growth_chart/{encounter_id}', 'ChartController@growthChart');
		Route::get('/graph/{form_code}/{encounter_id}', 'ChartController@graph');

		Route::get('/team/add/{id}/{team_code}', 'TeamController@addMember');
		Route::resource('teams', 'TeamController');
		Route::get('/teams/id/{id}', 'TeamController@searchById');
		Route::post('/team/search', 'TeamController@search');
		Route::get('/team/search', 'TeamController@search');
		Route::post('/team/search_member/{id}', 'TeamController@searchMember');
		Route::get('/teams/delete/{id}', 'TeamController@delete');
		
		Route::resource('form_systems', 'FormSystemController');
		Route::get('/form_systems/id/{id}', 'FormSystemController@searchById');
		Route::post('/form_system/search', 'FormSystemController@search');
		Route::get('/form_system/search', 'FormSystemController@search');
		Route::get('/form_systems/delete/{id}', 'FormSystemController@delete');

		Route::resource('general_ledgers', 'GeneralLedgerController');
		Route::get('/general_ledgers/id/{id}', 'GeneralLedgerController@searchById');
		Route::post('/general_ledger/search', 'GeneralLedgerController@search');
		Route::get('/general_ledger/search', 'GeneralLedgerController@search');
		Route::get('/general_ledgers/delete/{id}', 'GeneralLedgerController@delete');

		Route::resource('tax_types', 'TaxTypeController');
		Route::get('/tax_types/id/{id}', 'TaxTypeController@searchById');
		Route::post('/tax_type/search', 'TaxTypeController@search');
		Route::get('/tax_type/search', 'TaxTypeController@search');
		Route::get('/tax_types/delete/{id}', 'TaxTypeController@delete');
		
		Route::get('/form/table/{form_code}/{encounter_id}', 'FormController@table');
		Route::get('/form/results/{encounter_id}', 'FormValueController@results');
		Route::get('/form/entry/{id}', 'FormValueController@edit');
		Route::get('/form/delete/{id}', 'FormValueController@delete');
		Route::delete('/form/{id}', 'FormValueController@destroy');
		Route::get('/form/{form_code}/{encounter_id}', 'FormValueController@show');
		Route::get('/form/{form_code}/{patient_id}/create', 'FormValueController@create');
		Route::post('/form/entry', 'FormValueController@store');
		Route::post('/form/search_form/{encounter_id}', 'FormValueController@results');

		Route::get('/futures', 'FutureController@index');
		Route::post('/future/search', 'FutureController@search');
		Route::get('/future/search', 'FutureController@search');

		Route::resource('product_authorizations', 'ProductAuthorizationController');
		Route::get('/product_authorizations/id/{id}', 'ProductAuthorizationController@searchById');
		Route::post('/product_authorization/search', 'ProductAuthorizationController@search');
		Route::get('/product_authorization/search', 'ProductAuthorizationController@search');
		Route::get('/product_authorizations/delete/{id}', 'ProductAuthorizationController@delete');
		
		Route::resource('store_authorizations', 'StoreAuthorizationController');
		Route::get('/store_authorizations/id/{id}', 'StoreAuthorizationController@searchById');
		Route::post('/store_authorization/search', 'StoreAuthorizationController@search');
		Route::get('/store_authorization/search', 'StoreAuthorizationController@search');
		Route::get('/store_authorizations/delete/{id}', 'StoreAuthorizationController@delete');

		Route::post('/admission_task/status', 'AdmissionTaskController@status');
		Route::resource('admission_tasks', 'AdmissionTaskController');
		Route::get('/admission_tasks/id/{id}', 'AdmissionTaskController@searchById');
		Route::post('/admission_task/search', 'AdmissionTaskController@search');
		Route::get('/admission_task/search', 'AdmissionTaskController@search');
		Route::get('/admission_tasks/delete/{id}', 'AdmissionTaskController@delete');

		Route::resource('task_cancellations', 'TaskCancellationController',['except'=>['create']]);
		Route::get('/task_cancellations/create/{id}', 'TaskCancellationController@create');
		Route::get('/task_cancellations/id/{id}', 'TaskCancellationController@searchById');
		Route::post('/task_cancellation/search', 'TaskCancellationController@search');
		Route::get('/task_cancellation/search', 'TaskCancellationController@search');
		Route::get('/task_cancellations/delete/{id}', 'TaskCancellationController@delete');

		Route::resource('sponsors', 'SponsorController');
		Route::get('/sponsors/id/{id}', 'SponsorController@searchById');
		Route::post('/sponsor/search', 'SponsorController@search');
		Route::get('/sponsor/search', 'SponsorController@search');
		Route::get('/sponsors/delete/{id}', 'SponsorController@delete');

		Route::resource('employees', 'EmployeeController');
		Route::get('/employees/id/{id}', 'EmployeeController@searchById');
		Route::post('/employee/search', 'EmployeeController@search');
		Route::get('/employee/search', 'EmployeeController@search');
		Route::get('/employees/delete/{id}', 'EmployeeController@delete');
		Route::get('/employees/create_user/{id}', 'EmployeeController@createUser');
		
		Route::get('/order_investigations/{id}/edit_date', 'OrderInvestigationController@editDate');
		Route::post('/order_investigations/edit_date', 'OrderInvestigationController@updateDate');

		Route::get('/patient/image/{id}', [
				'uses'=>'PatientController@getImage',
				'as'=>'patient.image'
		]);

		Route::get('/unauthorized', function() {
				return view('common.403');
		});

		Route::group(['middleware' => 'landing'], function () {
				Route::get('/landing', function() {
						return "Landing page";
				});
		});

		Route::get('/class_methods/{class_name}', 'SpyController@getMethods');
		Route::get('/integration', 'IntegrationController@test');
		Route::get('/reports', 'ReportController@index');
		Route::get('/report_aggregate', 'ReportController@aggregate');
		Route::post('/reports', 'ReportController@generate');
		
		Route::get('/obstetric', 'ObstetricController@history');
		Route::post('/obstetric', 'ObstetricController@update');

		Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
		Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
		Route::post('password/reset','Auth\PasswordController@reset');
		Route::get('/change_password', 'UserController@changePassword');
		Route::post('/change_password', 'UserController@updatePassword');
		
		//Route::get('auth/logout', 'Auth\AuthCOntroller@getLogout');

		Route::get('/user_profile', 'UserController@editProfile');
		Route::put('/user_profile', 'UserController@updateProfile');

		Route::resource('documents', 'DocumentController');
		Route::get('/documents/id/{id}', 'DocumentController@searchById');
		Route::post('/document/search', 'DocumentController@search');
		Route::get('/document/search', 'DocumentController@search');
		Route::get('/documents/delete/{id}', 'DocumentController@delete');
		Route::get('/documents/file/{id}', 'DocumentController@file');

		Route::get('/queue_locations/set/{id}', 'QueueLocationController@setLocation');
		Route::get('/queue_locations/forget', 'QueueLocationController@forgetCookie');
		Route::get('/queue_locations/get', 'QueueLocationController@getLocation');
		Route::resource('queue_locations', 'QueueLocationController');
		Route::get('/queue_locations/id/{id}', 'QueueLocationController@searchById');
		Route::post('/queue_location/search', 'QueueLocationController@search');
		Route::get('/queue_location/search', 'QueueLocationController@search');
		Route::get('/queue_locations/delete/{id}', 'QueueLocationController@delete');

		Route::resource('admission_beds', 'AdmissionBedController');
		Route::get('/admission_beds/{id}/{ward_code}', 'AdmissionBedController@searchById');
		Route::post('/admission_bed/search', 'AdmissionBedController@search');
		Route::get('/admission_bed/search', 'AdmissionBedController@search');
		Route::get('/admission_beds/delete/{id}', 'AdmissionBedController@delete');
		Route::post('/admission_beds/move/{admission_id}/{bed_code}', 'AdmissionBedController@move');
		Route::get('/admission_beds/confirm/{admission_id}/{bed_code}', 'AdmissionBedController@confirm');

		Route::resource('bed_bookings', 'BedBookingController',['except'=>['create']]);
		Route::get('/bed_bookings/create/{patient_id}/{admission_id?}', 'BedBookingController@create');
		Route::get('/bed_bookings/id/{id}', 'BedBookingController@searchById');
		Route::post('/bed_booking/search', 'BedBookingController@index');
		Route::get('/bed_booking/search', 'BedBookingController@search');
		Route::get('/bed_bookings/delete/{id}', 'BedBookingController@delete');

		Route::resource('beds', 'BedController');
		Route::get('/beds/id/{id}', 'BedController@searchById');
		Route::post('/bed/search', 'BedController@search');
		Route::get('/bed/search', 'BedController@search');
		Route::get('/beds/delete/{id}', 'BedController@delete');
		Route::get('/bed/enquiry', 'BedController@enquiry');
		Route::post('/bed/enquiry', 'BedController@enquiry');
		Route::get('/bed/generate', 'BedController@generate');

		Route::post('/order_task/status', 'OrderTaskController@status');
		Route::get('/order_task/reopen/{id}', 'OrderTaskController@reopen');
		Route::get('/order_tasks/task/{consultation_id}/{location_code?}', 'OrderTaskController@task');
		Route::resource('order_tasks', 'OrderTaskController');
		Route::get('/order_tasks/id/{id}', 'OrderTaskController@searchById');
		Route::post('/order_task/search', 'OrderTaskController@search');
		Route::get('/order_task/search', 'OrderTaskController@search');
		Route::get('/order_tasks/delete/{id}', 'OrderTaskController@delete');

		Route::get('/wards/set/{id}', 'WardController@setWard');
		Route::get('/wards/forget', 'WardController@forgetCookie');
		Route::resource('wards', 'WardController');
		Route::get('/wards/id/{id}', 'WardController@searchById');
		Route::post('/ward/search', 'WardController@search');
		Route::get('/ward/search', 'WardController@search');
		Route::get('/wards/delete/{id}', 'WardController@delete');

		Route::get('/stores/set/{id}', 'StoreController@setStore');
		Route::get('/stores/forget', 'StoreController@forgetCookie');
		Route::resource('stores', 'StoreController');
		Route::get('/stores/id/{id}', 'StoreController@searchById');
		Route::post('/store/search', 'StoreController@search');
		Route::get('/store/search', 'StoreController@search');
		Route::get('/stores/delete/{id}', 'StoreController@delete');
		Route::get('/store/generate', 'StoreController@generate');

		Route::get('/loans/request/{id}', 'LoanController@request');
		Route::get('/loans/request/{id}/edit', 'LoanController@requestEdit');
		Route::post('/loans/request/{id}/edit', 'LoanController@requestUpdate');
		Route::post('/loans/request/{id}', 'LoanController@requestSubmit');
		Route::get('/loans/request/{id}/delete', 'LoanController@requestDelete');
		Route::delete('/loans/request/delete/{id}', 'LoanController@requestDestroy');
		Route::get('/loans/exchange/{id}', 'LoanController@requestExchange');
		Route::post('/loans/exchange/{id}', 'LoanController@exchangePost');
		
		Route::get('/admission/export', 'AdmissionController@export');
		Route::resource('admissions', 'AdmissionController');
		Route::get('/admissions/id/{id}', 'AdmissionController@searchById');
		Route::post('/admission/search', 'AdmissionController@search');
		Route::get('/admission/search', 'AdmissionController@search');
		Route::get('/admissions/delete/{id}', 'AdmissionController@delete');
		Route::post('/admission/enquiry', 'AdmissionController@enquiry');
		Route::get('/admission/enquiry', 'AdmissionController@enquiry');
		Route::get('/admission/progress/{patient_id}', 'AdmissionController@progress');
		Route::post('/admission/diet_enquiry', 'AdmissionController@diet_enquiry');
		Route::get('/admission/diet_enquiry', 'AdmissionController@diet_enquiry');
		Route::get('/admission/consultation/{encounter_id}', 'AdmissionController@consultation');

		Route::group(['middleware' => 'diet_middleware'], function () {

				Route::get('/diet_bom', 'DietMenuController@bom');
				Route::post('/diet_bom', 'DietMenuController@bom');

				Route::get('/diet_menus', 'DietMenuController@menu');
				Route::post('/diet_menus', 'DietMenuController@menu');

				Route::get('/diet_orders', 'DietMenuController@order');
				Route::post('/diet_orders', 'DietMenuController@order');
				Route::get('/diet_order/census', 'DietMenuController@census');

				Route::get('/diet_distribution', 'DietMenuController@distribution');
				Route::post('/diet_distribution', 'DietMenuController@distribution');

				Route::get('/diet_cooklist', 'DietMenuController@cooklist');
				Route::post('/diet_cooklist', 'DietMenuController@cooklist');

				Route::get('/diet_workorder', 'DietMenuController@workorder');
				Route::post('/diet_workorder', 'DietMenuController@workorder');

				Route::get('/diet_menus/{class}/{period}/{week}/{day}/{diet_code}', 'DietMenuController@create');
				Route::get('/diet_menus/menu/{class}/{period}/{week}/{day}/{diet_code}', 'DietMenuController@index');
				Route::get('/diet_menus/delete/{id}', 'DietMenuController@delete');
				Route::delete('/diet_menus/delete/{id}', 'DietMenuController@destroy');

				Route::resource('diet_complains', 'DietComplainController');
				Route::get('/diet_complains/id/{id}', 'DietComplainController@searchById');
				Route::post('/diet_complain/search', 'DietComplainController@search');
				Route::get('/diet_complain/search', 'DietComplainController@search');
				Route::get('/diet_complains/delete/{id}', 'DietComplainController@delete');

				Route::resource('diet_wastages', 'DietWastageController');
				Route::get('/diet_wastages/id/{id}', 'DietWastageController@searchById');
				Route::post('/diet_wastage/search', 'DietWastageController@search');
				Route::get('/diet_wastage/search', 'DietWastageController@search');
				Route::get('/diet_wastages/delete/{id}', 'DietWastageController@delete');
				
				Route::resource('diet_qualities', 'DietQualityController');
				Route::get('/diet_qualities/id/{id}', 'DietQualityController@searchById');
				Route::post('/diet_quality/search', 'DietQualityController@search');
				Route::get('/diet_quality/search', 'DietQualityController@search');
				Route::get('/diet_qualities/delete/{id}', 'DietQualityController@delete');

				Route::get('/diet_po', 'PurchaseOrderController@diet');
				
		});

		Route::group(['middleware' => 'medical_record'], function () {

				/*
				Route::resource('documents', 'DocumentController');
				Route::get('/documents/id/{id}', 'DocumentController@searchById');
				Route::post('/document/search', 'DocumentController@search');
				Route::get('/document/search', 'DocumentController@search');
				Route::get('/documents/delete/{id}', 'DocumentController@delete');
				Route::get('/documents/file/{id}', 'DocumentController@file');
				 */
		});

		Route::group(['middleware' => 'loan_function_middleware'], function () {

				Route::get('/loans/ward', 'LoanController@ward');
				Route::post('/loan/index', 'LoanController@index');
				Route::resource('loans', 'LoanController');
				Route::get('/loans/id/{id}', 'LoanController@searchById');
				Route::get('/loan/search', 'LoanController@search');
				Route::post('/loan/search', 'LoanController@search');
				Route::post('/loan/request_search', 'LoanController@request_search');
				Route::get('/loans/delete/{id}', 'LoanController@delete');
				Route::get('/loan/enquiry', 'LoanController@enquiry');
				Route::post('/loan/enquiry', 'LoanController@enquiry');
				Route::get('/loan/workload', 'LoanController@workload');
				Route::post('/loan/workload', 'LoanController@workload');

		});

				Route::get('/order_queues/report', 'OrderQueueController@report');
				Route::post('/order_queues/report', 'OrderQueueController@report');
				Route::get('/order_queues/setup', 'OrderQueueController@setting');
				Route::post('/order_queues/setup', 'OrderQueueController@setup');
				Route::resource('order_queues', 'OrderQueueController');
				Route::get('/order_queues/id/{id}', 'OrderQueueController@searchById');
				Route::post('/order_queue/search', 'OrderQueueController@search');
				Route::get('/order_queue/search', 'OrderQueueController@search');
				Route::get('/order_queues/delete/{id}', 'OrderQueueController@delete');

		Route::group(['middleware' => 'support'], function () {

		});

				Route::resource('deposits', 'DepositController',['except'=>['create','index']]);
				Route::get('/deposits/index/{id}', 'DepositController@index');
				Route::get('/deposits/create/{id}', 'DepositController@create');
				Route::get('/deposits/id/{id}', 'DepositController@searchById');
				Route::post('/deposit/search', 'DepositController@search');
				Route::get('/deposit/search', 'DepositController@search');
				Route::get('/deposits/delete/{id}', 'DepositController@delete');

		Route::group(['middleware' => 'discharge'], function () {

				Route::post('/deposit/enquiry', 'DepositController@enquiry');
				Route::get('/deposit/enquiry', 'DepositController@enquiry');

				Route::resource('payments', 'PaymentController',['except'=>['index','show','create']]);
				Route::get('/payments/{id?}', 'PaymentController@index');
				Route::get('/payments/create/{patient_id?}/{encounter_id?}/{non_claimable?}', 'PaymentController@create');
				Route::get('/payments/id/{id}', 'PaymentController@searchById');
				Route::post('/payment/search', 'PaymentController@search');
				Route::get('/payment/search', 'PaymentController@search');
				Route::get('/payments/delete/{id}', 'PaymentController@delete');
				Route::post('/payment/enquiry', 'PaymentController@enquiry');
				Route::get('/payment/enquiry', 'PaymentController@enquiry');

				Route::get('/bill_items/delete/{id}', 'BillItemController@delete');
				Route::get('/bill_items/reload/{id}', 'BillItemController@reload');
				Route::get('/bill_items/generate/{id}', 'BillItemController@generate');
				Route::resource('bill_items', 'BillItemController',['except'=>['index','show']]);
				Route::get('/bill_items/{id}/{non_claimable?}', 'BillItemController@index');
				Route::get('/bill_items/id/{id}', 'BillItemController@searchById');
				Route::get('/bill_items/{id}/json', 'BillItemController@json');
				Route::get('/bill_items/close/{id}', 'BillItemController@close');

				Route::resource('bills', 'BillController');
				Route::get('/bills/id/{id}', 'BillController@searchById');
				Route::get('/bills/{id}/json', 'BillController@json');
				Route::post('/bill/search', 'BillController@search');
				Route::get('/bill/search', 'BillController@search');
				Route::get('/bills/delete/{id}', 'BillController@delete');
				Route::post('/bill/enquiry', 'BillController@enquiry');
				Route::get('/bill/enquiry', 'BillController@enquiry');
				Route::get('/bill/report', 'BillController@report');
				Route::get('/bill/aging', 'BillController@aging');
				Route::get('/bill/bill_edit/{id}', 'BillController@billEdit');
				Route::post('/bill/bill_update/{id}', 'BillController@billUpdate');
				Route::post('/bill/sponsor_outstanding', 'BillController@sponsorOutstanding');
				Route::get('/bill/sponsor_outstanding', 'BillController@sponsorOutstanding');
		});

				Route::post('/bill_item/enquiry', 'BillItemController@enquiry');
				Route::get('/bill_item/enquiry', 'BillItemController@enquiry');

		Route::group(['middleware' => 'patient_list_middleware'], function () {
				Route::post('/discharge/discharge_count', 'DischargeController@dischargeCount');
				Route::get('/discharge/discharge_count', 'DischargeController@dischargeCount');

				Route::post('/discharge/enquiry', 'DischargeController@enquiry');
				Route::get('/discharge/enquiry', 'DischargeController@enquiry');
				Route::get('/discharges', 'DischargeController@index');
				
				Route::resource('patients', 'PatientController');
				Route::get('/patients/id/{id}', 'PatientController@searchById');
				//Route::get('/patients/{id}/json', 'PatientController@json');
				Route::get('/patients/dependants/{id}', 'PatientController@dependants');
				Route::get('/patients/dependant_list/{id}', 'PatientController@dependantList');
				Route::get('/patient/dependants', 'PatientController@find');
				Route::post('/patient/search', 'PatientController@search');
				Route::get('/patient/search', 'PatientController@search');
				Route::get('/patients/delete/{id}', 'PatientController@delete');
				Route::get('/patients/encounter/{id}', 'PatientController@activeEncounter');
				Route::post('/patient/enquiry', 'PatientController@enquiry');
				Route::get('/patient/enquiry', 'PatientController@enquiry');
				Route::get('/patient/merge/{id}', 'PatientController@merge');
				Route::post('/patient/merge/{id}', 'PatientController@merge');
				Route::get('/patient/prints/{id}', 'PatientController@prints');

				Route::resource('dependants', 'DependantController', ['except'=>['create','show']]);
				Route::get('/dependants/create/{id}', 'DependantController@create');
				Route::get('/dependants/id/{id}', 'DependantController@searchById');
				Route::post('/dependant/search', 'DependantController@search');
				Route::get('/dependant/search', 'DependantController@search');
				Route::get('/dependants/delete/{id}', 'DependantController@delete');
				Route::get('/dependants/add/{dependant_id}/{patient_id}', 'DependantController@add');


				Route::resource('patient_dependants', 'PatientDependantController');
				Route::get('/patient_dependants/id/{id}', 'PatientDependantController@searchById');
				Route::post('/patient_dependant/search', 'PatientDependantController@search');
				Route::get('/patient_dependant/search', 'PatientDependantController@search');
				Route::get('/patient_dependants/delete/{id}', 'PatientDependantController@delete');
		});

		Route::group(['middleware' => 'appointment_function_middleware'], function () {

				Route::resource('appointments', 'AppointmentController',['except'=>['create','edit']]);
				Route::get('/appointments/create/{patient_id}/{service_id}/{slot}/{admission_id?}', 'AppointmentController@create');
				Route::get('/appointments/{id}/edit/{appointment_slot}', 'AppointmentController@edit');
				Route::get('/appointments/id/{id}', 'AppointmentController@searchById');
				Route::post('/appointment/search', 'AppointmentController@search');
				Route::get('/appointment/search', 'AppointmentController@search');
				Route::get('/appointments/delete/{id}', 'AppointmentController@delete');
				Route::post('/appointments/multiple_delete', 'AppointmentController@bulkDelete');
				Route::get('/appointment/enquiry', 'AppointmentController@enquiry');
				Route::post('/appointment/enquiry', 'AppointmentController@enquiry');

				Route::get('/appointment_services/forget', 'AppointmentServiceController@forgetCookie');
				Route::get('/appointment_services/set/{id}', 'AppointmentServiceController@setAppointmentBook');
				Route::get('/appointment_services/delete/{id}', 'AppointmentServiceController@delete');
				Route::resource('appointment_services', 'AppointmentServiceController', ['except'=>['show']]);
				Route::get('/appointment_services/id/{id}', 'AppointmentServiceController@searchById');
				Route::get('/appointment_services/{id}/{selected_week}/{service_id?}/{appointment_id?}', 'AppointmentServiceController@show');
				Route::post('/appointment_services/{id}/{selected_week}/{service_id?}/{appointment_id?}', 'AppointmentServiceController@show');
				Route::post('/appointment_service/search', 'AppointmentServiceController@search');
				Route::get('/appointment_service/search', 'AppointmentServiceController@search');
		});

				Route::resource('encounters', 'EncounterController');
				Route::get('/encounters/id/{id}', 'EncounterController@searchById');
				Route::post('/encounter/search', 'EncounterController@search');
				Route::get('/encounter/search', 'EncounterController@search');
				Route::get('/encounters/delete/{id}', 'EncounterController@delete');

		Route::group(['middleware' => 'patient'], function () {


				/*
				Route::resource('queues', 'QueueController');
				Route::get('/queues/id/{id}', 'QueueController@searchById');
				Route::post('/queue/search', 'QueueController@search');
				Route::get('/queue/search', 'QueueController@search');
				Route::get('/queues/delete/{id}', 'QueueController@delete');


				Route::resource('deposits', 'DepositController',['except'=>['create','index']]);
				Route::get('/deposits/index/{id}', 'DepositController@index');
				Route::get('/deposits/create/{id}', 'DepositController@create');
				Route::get('/deposits/id/{id}', 'DepositController@searchById');
				Route::post('/deposit/search', 'DepositController@search');
				Route::get('/deposit/search', 'DepositController@search');
				Route::get('/deposits/delete/{id}', 'DepositController@delete');
				 */
		
				//Route::resource('deposits', 'DepositController');
				//Route::get('/deposits/id/{id}', 'DepositController@searchById');
				//Route::post('/deposit/search', 'DepositController@search');
				//Route::get('/deposit/search', 'DepositController@search');
				//Route::get('/deposits/delete/{id}', 'DepositController@delete');
				



		});
				Route::resource('preadmissions', 'BedBookingController',['except'=>['create']]);
				Route::get('/preadmissions/create/{patient_id}/{admission_id?}', 'BedBookingController@create');
				Route::get('/preadmissions/id/{id}', 'BedBookingController@searchById');
				Route::post('/preadmission/search', 'BedBookingController@search');
				Route::get('/preadmission/search', 'BedBookingController@search');
				Route::get('/preadmissions/delete/{id}', 'BedBookingController@delete');

				Route::resource('queues', 'QueueController');
				Route::get('/queues/id/{id}', 'QueueController@searchById');
				Route::post('/queue/search', 'QueueController@search');
				Route::get('/queue/search', 'QueueController@search');
				Route::get('/queues/delete/{id}', 'QueueController@delete');
				Route::post('/queue/enquiry', 'QueueController@enquiry');
				Route::get('/queue/enquiry', 'QueueController@enquiry');

				Route::get('/discharge/search', 'DischargeController@search');
				Route::post('/discharge/search', 'DischargeController@search');

		Route::group(['middleware' => 'order'], function () {
				Route::get('/order/index', 'OrderController@index');
				Route::get('/orders/single/{product_code}', 'OrderController@single');
				Route::get('/orders/task', 'OrderController@task');
				Route::get('/orders/make', 'OrderController@make');
				Route::get('/orders/plan', 'OrderController@plan');
				Route::get('/orders/medication', 'OrderController@medication');
				Route::get('/orders/discussion', 'OrderController@discussion');
				Route::post('/orders/discussion', 'OrderController@postPlan')->name('order.post_plan');
				Route::get('/orders/procedure', 'OrderController@procedure');
				Route::post('/orders/multiple', 'OrderController@multiple');
				Route::resource('orders', 'OrderController', ['except'=>[ 'create', 'show']]);
				Route::get('/orders/{id}/show', 'OrderController@show');
				Route::get('/orders/create/{product_code}', 'OrderController@create');
				Route::get('/orders/id/{id}', 'OrderController@searchById');
				Route::post('/order/index', 'OrderController@index');
				Route::get('/order/search', 'OrderController@search');
				Route::get('/orders/delete/{id}', 'OrderController@delete');
				Route::post('/orders/diagnostic_report/{id}', 'OrderController@updateDiagnosticReport');
				Route::get('/orders/cancel_single/{id}', 'OrderController@cancelSingle');

				Route::resource('order_investigations', 'OrderInvestigationController');
				Route::get('/order_investigations/create/{code}', 'OrderInvestigationController@create');
				Route::get('/order_investigations/id/{id}', 'OrderInvestigationController@searchById');
				Route::post('/order_investigation/search', 'OrderInvestigationController@search');
				Route::get('/order_investigation/search', 'OrderInvestigationController@search');
				Route::get('/order_investigations/delete/{id}', 'OrderInvestigationController@delete');

				Route::resource('order_products', 'OrderProductController');
				Route::get('/order_products/{id}', 'OrderProductController@index');
				Route::get('/order_products/id/{id}', 'OrderProductController@searchById');
				Route::post('/order_product/search', 'OrderProductController@search');
				Route::get('/order_product/search', 'OrderProductController@search');
				Route::get('/order_product/drug', 'OrderProductController@drugHistory');
				Route::get('/order_products/delete/{id}', 'OrderProductController@delete');

				Route::resource('order_drugs', 'OrderDrugController',['except'=>['create']]);
				Route::get('/order_drugs/id/{id}', 'OrderDrugController@searchById');
				Route::get('/order_drugs/create/{product_code}', 'OrderDrugController@create');
				Route::post('/order_drug/search', 'OrderDrugController@search');
				Route::get('/order_drug/search', 'OrderDrugController@search');
				Route::get('/order_drugs/delete/{id}', 'OrderDrugController@delete');
				Route::get('/order_drugs/post/{id}', 'OrderDrugController@post');

				Route::resource('order_cancellations', 'OrderCancellationController', ['except'=>['create']]);
				Route::get('/order_cancellations/create/{id}', 'OrderCancellationController@create');
				Route::get('/order_cancellations/id/{id}', 'OrderCancellationController@searchById');
				Route::post('/order_cancellation/search', 'OrderCancellationController@search');
				Route::get('/order_cancellation/search', 'OrderCancellationController@search');
				Route::get('/order_cancellations/delete/{id}', 'OrderCancellationController@delete');
		});

		Route::group(['middleware' => 'consultation'], function () {

				Route::post('/discharge/update_summary/{id}', 'DischargeController@update_summary');
				Route::get('/discharges/ward/{id}', 'DischargeController@ward');
				Route::resource('discharges', 'DischargeController', ['except'=>['index','show']]);
				Route::get('/discharges/create', 'DischargeController@create');
				Route::get('/discharges/id/{id}', 'DischargeController@searchById');
				Route::get('/discharges/delete/{id}', 'DischargeController@delete');
				Route::get('/discharge/summary/{id}', 'DischargeController@summary');

				Route::resource('patient_lists', 'PatientListController');
				Route::get('/patient_lists/id/{id}', 'PatientListController@searchById');
				Route::post('/patient_list/search', 'PatientListController@search');
				Route::get('/patient_list/search', 'PatientListController@search');
				Route::get('/patient_lists/delete/{id}', 'PatientListController@delete');

				Route::get('/consultation/get', 'ConsultationController@getConsultation');
				Route::get('/consultation/set', 'ConsultationController@setConsultation');
				Route::get('/consultations/close', 'ConsultationController@close');
				Route::get('/consultations/summary', 'ConsultationController@summary');
				Route::get('/consultations/progress2/{consultation_id}/{target_encounter?}/{target_consultation?}', 'ConsultationController@progress2');
				Route::get('/consultations/progress3/{consultation_id}/{target_encounter?}/{target_consultation?}', 'ConsultationController@progress3');
				Route::get('/consultations/progress/{consultation_id}/{encounter_id}', 'ConsultationController@progress');
				Route::resource('consultations', 'ConsultationController');
				Route::get('/consultations/id/{id}', 'ConsultationController@searchById');
				Route::get('/consultations/confirm/{id}', 'ConsultationController@confirm');
				Route::post('/consultation/search', 'ConsultationController@search');
				Route::get('/consultation/search', 'ConsultationController@search');
				Route::get('/consultations/delete/{id}', 'ConsultationController@delete');

				Route::get('/clinical_pathway', 'ClinicalPathwayController@index');

				Route::post('/consultation_diagnosis/drop', 'ConsultationDiagnosisController@drop')->name('diagnosis.drop');
				Route::get('/consultation_diagnoses/encounter', 'ConsultationDiagnosisController@getDiagnoses');
				Route::resource('consultation_diagnoses', 'ConsultationDiagnosisController');
				Route::get('/consultation_diagnoses/id/{id}', 'ConsultationDiagnosisController@searchById');
				Route::post('/consultation_diagnosis/search', 'ConsultationDiagnosisController@search');
				Route::get('/consultation_diagnosis/search', 'ConsultationDiagnosisController@search');
				Route::get('/consultation_diagnoses/delete/{id}', 'ConsultationDiagnosisController@delete');

				Route::get('/consultation_procedures/encounter', 'ConsultationProcedureController@getProcedures');
				Route::resource('consultation_procedures', 'ConsultationProcedureController');
				Route::get('/consultation_procedures/id/{id}', 'ConsultationProcedureController@searchById');
				Route::post('/consultation_procedure/search', 'ConsultationProcedureController@search');
				Route::get('/consultation_procedure/search', 'ConsultationProcedureController@search');
				Route::get('/consultation_procedures/delete/{id}', 'ConsultationProcedureController@delete');


				Route::resource('medical_alerts', 'MedicalAlertController');
				Route::get('/medical_alerts/{id}', 'MedicalAlertController@index');
				Route::get('/medical_alerts/id/{id}', 'MedicalAlertController@searchById');
				Route::post('/medical_alert/search', 'MedicalAlertController@search');
				Route::get('/medical_alert/search', 'MedicalAlertController@search');
				Route::get('/medical_alerts/delete/{id}', 'MedicalAlertController@delete');

				Route::resource('medical_certificates', 'MedicalCertificateController');
				Route::get('/medical_certificates/id/{id}', 'MedicalCertificateController@searchById');
				Route::post('/medical_certificate/search', 'MedicalCertificateController@search');
				Route::get('/medical_certificate/search', 'MedicalCertificateController@search');
				Route::get('/medical_certificates/delete/{id}', 'MedicalCertificateController@delete');

				Route::resource('newborns', 'NewbornController');
				Route::get('/newborns/id/{id}', 'NewbornController@searchById');
				Route::post('/newborn/search', 'NewbornController@search');
				Route::get('/newborn/search', 'NewbornController@search');
				Route::get('/newborns/delete/{id}', 'NewbornController@delete');

				Route::get('/diet', 'AdmissionController@diet');
				Route::get('/nbm/remove/{id}', 'AdmissionController@removeNilByMouth');

		});

		Route::group(['middleware' => 'product_list_middleware'], function () {

				Route::post('/products/reorder', 'ProductController@reorder');
				Route::get('/products/reorder', 'ProductController@reorder');
				Route::post('/products/on_hand', 'ProductController@onHandEnquiry');
				Route::get('/products/on_hand', 'ProductController@onHandEnquiry');
				Route::post('/products/enquiry', 'ProductController@enquiry');
				Route::get('/products/enquiry', 'ProductController@enquiry');
				Route::get('/products/{id}/option', 'ProductController@option');
				Route::get('/products/{id}/json', 'ProductController@json');
				Route::resource('products', 'ProductController');
				Route::get('/products/id/{id}', 'ProductController@searchById');
				Route::post('/product/search', 'ProductController@search');
				Route::get('/product/search', 'ProductController@search');
				Route::get('/products/delete/{id}', 'ProductController@delete');
				
		});

		Route::group(['middleware' => 'inventory'], function () {
				Route::post('/products/reorder', 'ProductController@reorder');
				Route::get('/products/reorder', 'ProductController@reorder');
				Route::post('/products/on_hand', 'ProductController@onHandEnquiry');
				Route::get('/products/on_hand', 'ProductController@onHandEnquiry');
				Route::post('/products/enquiry', 'ProductController@enquiry');
				Route::get('/products/enquiry', 'ProductController@enquiry');
				Route::get('/products/{id}/option', 'ProductController@option');
				Route::get('/products/{id}/json', 'ProductController@json');
				Route::resource('products', 'ProductController');
				Route::get('/products/id/{id}', 'ProductController@searchById');
				Route::post('/product/search', 'ProductController@search');
				Route::get('/product/search', 'ProductController@search');
				Route::get('/products/delete/{id}', 'ProductController@delete');

				Route::resource('product_maintenances', 'ProductMaintenanceController');
				Route::get('/product_maintenances/id/{id}', 'ProductMaintenanceController@searchById');
				Route::post('/product_maintenance/search', 'ProductMaintenanceController@search');
				Route::get('/product_maintenance/search', 'ProductMaintenanceController@search');
				Route::get('/product_maintenances/delete/{id}', 'ProductMaintenanceController@delete');

				Route::resource('bill_materials', 'BillMaterialController',['except'=>['index', 'show']]);
				Route::get('/bill_materials/{product_code}', 'BillMaterialController@show');
				Route::get('/bill_materials/index/{product_code}', 'BillMaterialController@index');
				Route::get('/bill_materials/id/{id}', 'BillMaterialController@searchById');
				Route::post('/bill_material/search', 'BillMaterialController@search');
				Route::get('/bill_material/search', 'BillMaterialController@search');
				Route::get('/bill_materials/delete/{id}', 'BillMaterialController@delete');
				
				Route::resource('stock_movements', 'StockMovementController');
				Route::get('/stock_movements/id/{id}', 'StockMovementController@searchById');
				Route::post('/stock_movements/search', 'StockMovementController@search');
				Route::get('/stock_movement/search', 'StockMovementController@search');
				Route::get('/stock_movements/delete/{id}', 'StockMovementController@delete');

				Route::get('/build_assembly/{id}', 'AssemblyController@index');
				Route::post('/build_assembly/refresh/{id}', 'AssemblyController@index');
				Route::post('/build_assembly/{id}', 'AssemblyController@build');
				Route::get('/explode_assembly/{id}', 'AssemblyController@explode');
				Route::post('/explode_assembly/{id}', 'AssemblyController@destroy');
				Route::post('/explode_assembly/refresh/{id}', 'AssemblyController@explode');
				
				Route::resource('urgencies', 'UrgencyController');
				Route::get('/urgencies/id/{id}', 'UrgencyController@searchById');
				Route::post('/urgency/search', 'UrgencyController@search');
				Route::get('/urgency/search', 'UrgencyController@search');
				Route::get('/urgencies/delete/{id}', 'UrgencyController@delete');

				Route::resource('suppliers', 'SupplierController');
				Route::get('/suppliers/id/{id}', 'SupplierController@searchById');
				Route::post('/supplier/search', 'SupplierController@search');
				Route::get('/supplier/search', 'SupplierController@search');
				Route::get('/suppliers/delete/{id}', 'SupplierController@delete');

				Route::get('/purchase_orders/{id}/json', 'PurchaseOrderController@json');
				Route::resource('purchase_orders', 'PurchaseOrderController');
				Route::get('/purchase_orders/id/{id}', 'PurchaseOrderController@searchById');
				Route::post('/purchase_order/search', 'PurchaseOrderController@search');
				Route::get('/purchase_order/search', 'PurchaseOrderController@search');
				Route::get('/purchase_order/post', 'PurchaseOrderController@post');
				Route::post('/purchase_order/posts', 'PurchaseOrderController@posts');
				Route::post('/purchase_order/verify', 'PurchaseOrderController@postVerify');
				Route::get('/purchase_orders/delete/{id}', 'PurchaseOrderController@delete');
				Route::get('/purchase_order/diet/{id}', 'PurchaseOrderController@diet');

				Route::resource('drugs', 'DrugController');
				Route::get('/drugs/id/{id}', 'DrugController@searchById');
				Route::post('/drug/search', 'DrugController@search');
				Route::get('/drug/search', 'DrugController@search');
				Route::get('/drugs/delete/{id}', 'DrugController@delete');

				Route::resource('sets', 'SetController', ['except'=>['show']]);
				Route::get('/sets/{set_code}', 'SetController@show');
				Route::get('/sets/id/{id}', 'SetController@searchById');
				Route::post('/set/search', 'SetController@search');
				Route::get('/set/search', 'SetController@search');
				Route::get('/sets/delete/{id}', 'SetController@delete');

				Route::get('/product_searches/menu/{class_code}/{period_code}/{week}/{day}/{product_code}/{diet_code}', 'ProductSearchController@menu');
				Route::resource('product_searches', 'ProductSearchController');
				Route::get('/product_searches/id/{id}', 'ProductSearchController@searchById');
				Route::get('/product_searches/add/{purchase_id}/{id}', 'ProductSearchController@add');
				Route::get('/product_searches/bom/{product_code}/{bom_product_code}', 'ProductSearchController@bom');
				Route::get('/product_searches/asset/{set_code}/{product_code}', 'ProductSearchController@asset');
				Route::get('/product_searches/bulk/{input_id}/{product_code}', 'ProductSearchController@bulk');
				Route::post('/product_search/search', 'ProductSearchController@search');
				Route::get('/product_search/search', 'ProductSearchController@search');
				Route::get('/product_searches/delete/{id}', 'ProductSearchController@delete');

				Route::resource('order_sets', 'OrderSetController', ['except'=>['index']]);
				Route::get('/order_sets/index/{set_code}', 'OrderSetController@index');
				Route::get('/order_sets/id/{id}', 'OrderSetController@searchById');
				Route::post('/order_set/search', 'OrderSetController@search');
				Route::get('/order_set/search', 'OrderSetController@search');
				Route::get('/order_sets/delete/{id}', 'OrderSetController@delete');

		});
		
		Route::group(['middleware' => 'ward'], function () {
		
				Route::get('/admission/dietUpdate', 'AdmissionController@dietUpdate');

		
				Route::get('/ward_arrivals/create/{id}', 'WardArrivalController@create');
				Route::resource('ward_arrivals', 'WardArrivalController', ['except'=>['create']]);
				Route::get('/ward_arrivals/id/{id}', 'WardArrivalController@searchById');
				Route::post('/ward_arrival/search', 'WardArrivalController@search');
				Route::get('/ward_arrival/search', 'WardArrivalController@search');
				Route::get('/ward_arrivals/delete/{id}', 'WardArrivalController@delete');
				
				Route::get('/ward_discharges/create/{id}', 'WardDischargeController@create');
				Route::resource('ward_discharges', 'WardDischargeController',['except'=>['create']]);
				Route::get('/ward_discharges/id/{id}', 'WardDischargeController@searchById');
				Route::post('/ward_discharge/search', 'WardDischargeController@search');
				Route::get('/ward_discharge/search', 'WardDischargeController@search');
				Route::get('/ward_discharges/delete/{id}', 'WardDischargeController@delete');

				Route::resource('move_beds', 'AdmissionBedController');
				Route::get('/move_beds/id/{id}', 'AdmissionBedController@searchById');
				Route::post('/move_bed/search', 'AdmissionBedController@search');
				Route::get('/move_bed/search', 'AdmissionBedController@search');
				Route::get('/move_beds/delete/{id}', 'AdmissionBedController@delete');
				Route::get('/move_beds/move/{admission_id}/{bed_code}', 'AdmissionBedController@move');
		});
		
				Route::resource('unit_measures', 'UnitMeasureController');
				Route::get('/unit_measures/id/{id}', 'UnitMeasureController@searchById');
				Route::post('/unit_measure/search', 'UnitMeasureController@search');
				Route::get('/unit_measure/search', 'UnitMeasureController@search');
				Route::get('/unit_measures/delete/{id}', 'UnitMeasureController@delete');
				
		Route::group(['middleware' => 'admin'], function () {

				Route::get('/maintenance', function() {
						return view('maintenance.maintenance');
				});
				
				Route::resource('patient_billings', 'PatientBillingController');
				Route::get('/patient_billings/id/{id}', 'PatientBillingController@searchById');
				Route::post('/patient_billing/search', 'PatientBillingController@search');
				Route::get('/patient_billing/search', 'PatientBillingController@search');
				Route::get('/patient_billings/delete/{id}', 'PatientBillingController@delete');
				
				Route::resource('tax_codes', 'TaxCodeController');
				Route::get('/tax_codes/id/{id}', 'TaxCodeController@searchById');
				Route::post('/tax_code/search', 'TaxCodeController@search');
				Route::get('/tax_code/search', 'TaxCodeController@search');
				Route::get('/tax_codes/delete/{id}', 'TaxCodeController@delete');
				
				Route::resource('payment_methods', 'PaymentMethodController');
				Route::get('/payment_methods/id/{id}', 'PaymentMethodController@searchById');
				Route::post('/payment_method/search', 'PaymentMethodController@search');
				Route::get('/payment_method/search', 'PaymentMethodController@search');
				Route::get('/payment_methods/delete/{id}', 'PaymentMethodController@delete');
				
				Route::resource('patient_flags', 'PatientFlagController');
				Route::get('/patient_flags/id/{id}', 'PatientFlagController@searchById');
				Route::post('/patient_flag/search', 'PatientFlagController@search');
				Route::get('/patient_flag/search', 'PatientFlagController@search');
				Route::get('/patient_flags/delete/{id}', 'PatientFlagController@delete');
				
				Route::resource('product_statuses', 'ProductStatusController');
				Route::get('/product_statuses/id/{id}', 'ProductStatusController@searchById');
				Route::post('/product_status/search', 'ProductStatusController@search');
				Route::get('/product_status/search', 'ProductStatusController@search');
				Route::get('/product_statuses/delete/{id}', 'ProductStatusController@delete');
				
				Route::resource('user_authorizations', 'UserAuthorizationController');
				Route::get('/user_authorizations/id/{id}', 'UserAuthorizationController@searchById');
				Route::post('/user_authorization/search', 'UserAuthorizationController@search');
				Route::get('/user_authorization/search', 'UserAuthorizationController@search');
				Route::get('/user_authorizations/delete/{id}', 'UserAuthorizationController@delete');
				
				Route::resource('discharge_types', 'DischargeTypeController');
				Route::get('/discharge_types/id/{id}', 'DischargeTypeController@searchById');
				Route::post('/discharge_type/search', 'DischargeTypeController@search');
				Route::get('/discharge_type/search', 'DischargeTypeController@search');
				Route::get('/discharge_types/delete/{id}', 'DischargeTypeController@delete');
				
				Route::resource('consultation_orders', 'ConsultationOrderController',['except'=>['index','show']]);
				Route::get('/consultation_orders/{id}', 'ConsultationOrderController@index');
				Route::get('/consultation_orders/create/{consultation_id}/{product_code}', 'ConsultationOrderController@create');
				Route::get('/consultation_orders/id/{id}', 'ConsultationOrderController@searchById');
				Route::post('/consultation_order/search', 'ConsultationOrderController@search');
				Route::get('/consultation_order/search', 'ConsultationOrderController@search');
				Route::get('/consultation_orders/delete/{id}', 'ConsultationOrderController@delete');
				
				Route::resource('encounter_types', 'EncounterTypeController');
				Route::get('/encounter_types/id/{id}', 'EncounterTypeController@searchById');
				Route::post('/encounter_type/search', 'EncounterTypeController@search');
				Route::get('/encounter_type/search', 'EncounterTypeController@search');
				Route::get('/encounter_types/delete/{id}', 'EncounterTypeController@delete');
				
				Route::resource('block_dates', 'BlockDateController');
				Route::get('/block_dates/id/{id}', 'BlockDateController@searchById');
				Route::post('/block_date/search', 'BlockDateController@search');
				Route::get('/block_date/search', 'BlockDateController@search');
				Route::get('/block_dates/delete/{id}', 'BlockDateController@delete');
				
				
				
				Route::resource('care_organisations', 'CareOrganisationController');
				Route::get('/care_organisations/id/{id}', 'CareOrganisationController@searchById');
				Route::post('/care_organisation/search', 'CareOrganisationController@search');
				Route::get('/care_organisation/search', 'CareOrganisationController@search');
				Route::get('/care_organisations/delete/{id}', 'CareOrganisationController@delete');
				
				Route::resource('ward_classes', 'WardClassController');
				Route::get('/ward_classes/id/{id}', 'WardClassController@searchById');
				Route::post('/ward_class/search', 'WardClassController@search');
				Route::get('/ward_class/search', 'WardClassController@search');
				Route::get('/ward_classes/delete/{id}', 'WardClassController@delete');
				
				Route::resource('patient_types', 'PatientTypeController');
				Route::get('/patient_types/id/{id}', 'PatientTypeController@searchById');
				Route::post('/patient_type/search', 'PatientTypeController@search');
				Route::get('/patient_type/search', 'PatientTypeController@search');
				Route::get('/patient_types/delete/{id}', 'PatientTypeController@delete');
				
				Route::resource('referrals', 'ReferralController');
				Route::get('/referrals/id/{id}', 'ReferralController@searchById');
				Route::post('/referral/search', 'ReferralController@search');
				Route::get('/referral/search', 'ReferralController@search');
				Route::get('/referrals/delete/{id}', 'ReferralController@delete');
				
				Route::resource('relationships', 'RelationshipController');
				Route::get('/relationships/id/{id}', 'RelationshipController@searchById');
				Route::post('/relationship/search', 'RelationshipController@search');
				Route::get('/relationship/search', 'RelationshipController@search');
				Route::get('/relationships/delete/{id}', 'RelationshipController@delete');
				
				Route::resource('marital_statuses', 'MaritalStatusController');
				Route::get('/marital_statuses/id/{id}', 'MaritalStatusController@searchById');
				Route::post('/marital_status/search', 'MaritalStatusController@search');
				Route::get('/marital_status/search', 'MaritalStatusController@search');
				Route::get('/marital_statuses/delete/{id}', 'MaritalStatusController@delete');
				
				Route::resource('care_levels', 'CareLevelController');
				Route::get('/care_levels/id/{id}', 'CareLevelController@searchById');
				Route::post('/care_level/search', 'CareLevelController@search');
				Route::get('/care_level/search', 'CareLevelController@search');
				Route::get('/care_levels/delete/{id}', 'CareLevelController@delete');
				
				Route::resource('frequencies', 'FrequencyController');
				Route::get('/frequencies/id/{id}', 'FrequencyController@searchById');
				Route::post('/frequency/search', 'FrequencyController@search');
				Route::get('/frequency/search', 'FrequencyController@search');
				Route::get('/frequencies/delete/{id}', 'FrequencyController@delete');
				
				Route::resource('birth_complications', 'BirthComplicationController');
				Route::get('/birth_complications/id/{id}', 'BirthComplicationController@searchById');
				Route::post('/birth_complication/search', 'BirthComplicationController@search');
				Route::get('/birth_complication/search', 'BirthComplicationController@search');
				Route::get('/birth_complications/delete/{id}', 'BirthComplicationController@delete');
				
				Route::resource('birth_types', 'BirthTypeController');
				Route::get('/birth_types/id/{id}', 'BirthTypeController@searchById');
				Route::post('/birth_type/search', 'BirthTypeController@search');
				Route::get('/birth_type/search', 'BirthTypeController@search');
				Route::get('/birth_types/delete/{id}', 'BirthTypeController@delete');
				
				Route::resource('delivery_modes', 'DeliveryModeController');
				Route::get('/delivery_modes/id/{id}', 'DeliveryModeController@searchById');
				Route::post('/delivery_mode/search', 'DeliveryModeController@search');
				Route::get('/delivery_mode/search', 'DeliveryModeController@search');
				Route::get('/delivery_modes/delete/{id}', 'DeliveryModeController@delete');
				
				Route::resource('periods', 'PeriodController');
				Route::get('/periods/id/{id}', 'PeriodController@searchById');
				Route::post('/period/search', 'PeriodController@search');
				Route::get('/period/search', 'PeriodController@search');
				Route::get('/periods/delete/{id}', 'PeriodController@delete');
				
				Route::resource('drug_routes', 'DrugRouteController');
				Route::get('/drug_routes/id/{id}', 'DrugRouteController@searchById');
				Route::post('/drug_route/search', 'DrugRouteController@search');
				Route::get('/drug_route/search', 'DrugRouteController@search');
				Route::get('/drug_routes/delete/{id}', 'DrugRouteController@delete');
				
				Route::resource('drug_frequencies', 'DrugFrequencyController');
				Route::get('/drug_frequencies/id/{id}', 'DrugFrequencyController@searchById');
				Route::post('/drug_frequency/search', 'DrugFrequencyController@search');
				Route::get('/drug_frequency/search', 'DrugFrequencyController@search');
				Route::get('/drug_frequencies/delete/{id}', 'DrugFrequencyController@delete');
				
				Route::resource('drug_dosages', 'DrugDosageController');
				Route::get('/drug_dosages/id/{id}', 'DrugDosageController@searchById');
				Route::post('/drug_dosage/search', 'DrugDosageController@search');
				Route::get('/drug_dosage/search', 'DrugDosageController@search');
				Route::get('/drug_dosages/delete/{id}', 'DrugDosageController@delete');
				
				Route::resource('drug_systems', 'DrugSystemController');
				Route::get('/drug_systems/id/{id}', 'DrugSystemController@searchById');
				Route::post('/drug_system/search', 'DrugSystemController@search');
				Route::get('/drug_system/search', 'DrugSystemController@search');
				Route::get('/drug_systems/delete/{id}', 'DrugSystemController@delete');
				
				Route::resource('drug_categories', 'DrugCategoryController');
				Route::get('/drug_categories/id/{id}', 'DrugCategoryController@searchById');
				Route::post('/drug_category/search', 'DrugCategoryController@search');
				Route::get('/drug_category/search', 'DrugCategoryController@search');
				Route::get('/drug_categories/delete/{id}', 'DrugCategoryController@delete');
				
				Route::resource('races', 'RaceController');
				Route::get('/races/id/{race_code}', 'RaceController@searchById');
				Route::post('/race/search', 'RaceController@search');
				Route::get('/race/search', 'RaceController@search');
				Route::get('/races/delete/{id}', 'RaceController@delete');

				Route::resource('religions', 'ReligionController');
				Route::get('/religions/id/{id}', 'ReligionController@searchById');
				Route::post('/religion/search', 'ReligionController@search');
				Route::get('/religion/search', 'ReligionController@search');
				Route::get('/religions/delete/{id}', 'ReligionController@delete');

				Route::resource('genders', 'GenderController');
				Route::get('/genders/id/{id}', 'GenderController@searchById');
				Route::post('/gender/search', 'GenderController@search');
				Route::get('/gender/search', 'GenderController@search');
				Route::get('/genders/delete/{id}', 'GenderController@delete');		

				Route::resource('cities', 'CityController');
				Route::get('/cities/id/{id}', 'CityController@searchById');
				Route::post('/city/search', 'CityController@search');
				Route::get('/city/search', 'CityController@search');
				Route::get('/cities/delete/{id}', 'CityController@delete');

				Route::resource('states', 'StateController');
				Route::get('/states/id/{id}', 'StateController@searchById');
				Route::post('/state/search', 'StateController@search');
				Route::get('/state/search', 'StateController@search');
				Route::get('/states/delete/{id}', 'StateController@delete');

				Route::resource('nations', 'NationController');
				Route::get('/nations/id/{id}', 'NationController@searchById');
				Route::post('/nation/search', 'NationController@search');
				Route::get('/nation/search', 'NationController@search');
				Route::get('/nations/delete/{id}', 'NationController@delete');
			
				Route::resource('occupations', 'OccupationController');
				Route::get('/occupations/id/{id}', 'OccupationController@searchById');
				Route::post('/occupation/search', 'OccupationController@search');
				Route::get('/occupation/search', 'OccupationController@search');
				Route::get('/occupations/delete/{id}', 'OccupationController@delete');

				Route::resource('tourists', 'TouristController');
				Route::get('/tourists/id/{id}', 'TouristController@searchById');
				Route::post('/tourist/search', 'TouristController@search');
				Route::get('/tourist/search', 'TouristController@search');
				Route::get('/tourists/delete/{id}', 'TouristController@delete');

				Route::resource('titles', 'TitleController');
				Route::get('/titles/id/{id}', 'TitleController@searchById');
				Route::post('/title/search', 'TitleController@search');
				Route::get('/title/search', 'TitleController@search');
				Route::get('/titles/delete/{id}', 'TitleController@delete');
			
				Route::resource('registrations', 'RegistrationController');
				Route::get('/registrations/id/{id}', 'RegistrationController@searchById');
				Route::post('/registration/search', 'RegistrationController@search');
				Route::get('/registration/search', 'RegistrationController@search');
				Route::get('/registrations/delete/{id}', 'RegistrationController@delete');

				Route::resource('employers', 'EmployerController');
				Route::get('/employers/id/{id}', 'EmployerController@searchById');
				Route::post('/employer/search', 'EmployerController@search');
				Route::get('/employer/search', 'EmployerController@search');
				Route::get('/employers/delete/{id}', 'EmployerController@delete');

				Route::resource('departments', 'DepartmentController');
				Route::get('/departments/id/{id}', 'DepartmentController@searchById');
				Route::post('/department/search', 'DepartmentController@search');
				Route::get('/department/search', 'DepartmentController@search');
				Route::get('/departments/delete/{id}', 'DepartmentController@delete');

				Route::resource('rooms', 'RoomController');
				Route::get('/rooms/id/{id}', 'RoomController@searchById');
				Route::post('/room/search', 'RoomController@search');
				Route::get('/room/search', 'RoomController@search');
				Route::get('/rooms/delete/{id}', 'RoomController@delete');

				Route::resource('bed_statuses', 'BedStatusController');
				Route::get('/bed_statuses/id/{id}', 'BedStatusController@searchById');
				Route::post('/bed_status/search', 'BedStatusController@search');
				Route::get('/bed_status/search', 'BedStatusController@search');
				Route::get('/bed_statuses/delete/{id}', 'BedStatusController@delete');

				Route::resource('product_categories', 'ProductCategoryController');
				Route::get('/product_categories/id/{id}', 'ProductCategoryController@searchById');
				Route::post('/product_category/search', 'ProductCategoryController@search');
				Route::get('/product_category/search', 'ProductCategoryController@search');
				Route::get('/product_categories/delete/{id}', 'ProductCategoryController@delete');

				Route::resource('forms', 'FormController');
				Route::get('/forms/id/{id}', 'FormController@searchById');
				Route::post('/form/search', 'FormController@search');
				Route::get('/form/search', 'FormController@search');
				Route::get('/forms/delete/{id}', 'FormController@delete');

				Route::resource('order_forms', 'OrderFormController');
				Route::get('/order_forms/id/{id}', 'OrderFormController@searchById');
				Route::post('/order_form/search', 'OrderFormController@search');
				Route::get('/order_form/search', 'OrderFormController@search');
				Route::get('/order_forms/delete/{id}', 'OrderFormController@delete');

				Route::resource('form_properties', 'FormPropertyController');
				Route::get('/form_properties/id/{id}', 'FormPropertyController@searchById');
				Route::post('/form_property/search', 'FormPropertyController@search');
				Route::get('/form_property/search', 'FormPropertyController@search');
				Route::get('/form_property/add/{form_code}/{property_code}', 'FormPropertyController@add');
				Route::get('/form_property/select', 'FormPropertyController@propertySelect');
				Route::get('/form_properties/delete/{id}', 'FormPropertyController@delete');

				Route::resource('form_positions', 'FormPositionController');
				Route::get('/form_positions/id/{id}', 'FormPositionController@searchById');
				Route::post('/form_position/search', 'FormPositionController@search');
				Route::get('/form_position/search', 'FormPositionController@search');
				Route::get('/form_positions/delete/{id}', 'FormPositionController@delete');

				Route::resource('diets', 'DietController');
				Route::get('/diets/id/{id}', 'DietController@searchById');
				Route::post('/diet/search', 'DietController@search');
				Route::get('/diet/search', 'DietController@search');
				Route::get('/diets/delete/{id}', 'DietController@delete');

				Route::resource('admission_types', 'AdmissionTypeController');
				Route::get('/admission_types/id/{id}', 'AdmissionTypeController@searchById');
				Route::post('/admission_type/search', 'AdmissionTypeController@search');
				Route::get('/admission_type/search', 'AdmissionTypeController@search');
				Route::get('/admission_types/delete/{id}', 'AdmissionTypeController@delete');

				Route::resource('diet_classes', 'DietClassController');
				Route::get('/diet_classes/id/{id}', 'DietClassController@searchById');
				Route::post('/diet_class/search', 'DietClassController@search');
				Route::get('/diet_class/search', 'DietClassController@search');
				Route::get('/diet_classes/delete/{id}', 'DietClassController@delete');

				Route::resource('diet_contaminations', 'DietContaminationController');
				Route::get('/diet_contaminations/id/{id}', 'DietContaminationController@searchById');
				Route::post('/diet_contamination/search', 'DietContaminationController@search');
				Route::get('/diet_contamination/search', 'DietContaminationController@search');
				Route::get('/diet_contaminations/delete/{id}', 'DietContaminationController@delete');

				Route::resource('diet_enterals', 'DietEnteralController');
				Route::get('/diet_enterals/id/{id}', 'DietEnteralController@searchById');
				Route::post('/diet_enteral/search', 'DietEnteralController@search');
				Route::get('/diet_enteral/search', 'DietEnteralController@search');
				Route::get('/diet_enterals/delete/{id}', 'DietEnteralController@delete');

				Route::resource('diet_meals', 'DietMealController');
				Route::get('/diet_meals/id/{id}', 'DietMealController@searchById');
				Route::post('/diet_meal/search', 'DietMealController@search');
				Route::get('/diet_meal/search', 'DietMealController@search');
				Route::get('/diet_meals/delete/{id}', 'DietMealController@delete');

				Route::resource('diet_ratings', 'DietRatingController');
				Route::get('/diet_ratings/id/{id}', 'DietRatingController@searchById');
				Route::post('/diet_rating/search', 'DietRatingController@search');
				Route::get('/diet_rating/search', 'DietRatingController@search');
				Route::get('/diet_ratings/delete/{id}', 'DietRatingController@delete');

				Route::resource('diet_periods', 'DietPeriodController');
				Route::get('/diet_periods/id/{id}', 'DietPeriodController@searchById');
				Route::post('/diet_period/search', 'DietPeriodController@search');
				Route::get('/diet_period/search', 'DietPeriodController@search');
				Route::get('/diet_periods/delete/{id}', 'DietPeriodController@delete');

				Route::resource('diet_textures', 'DietTextureController');
				Route::get('/diet_textures/id/{id}', 'DietTextureController@searchById');
				Route::post('/diet_texture/search', 'DietTextureController@search');
				Route::get('/diet_texture/search', 'DietTextureController@search');
				Route::get('/diet_textures/delete/{id}', 'DietTextureController@delete');

				Route::resource('triages', 'TriageController');
				Route::get('/triages/id/{id}', 'TriageController@searchById');
				Route::post('/triage/search', 'TriageController@search');
				Route::get('/triage/search', 'TriageController@search');
				Route::get('/triages/delete/{id}', 'TriageController@delete');

				Route::resource('diagnosis_types', 'DiagnosisTypeController');
				Route::get('/diagnosis_types/id/{id}', 'DiagnosisTypeController@searchById');
				Route::post('/diagnosis_type/search', 'DiagnosisTypeController@search');
				Route::get('/diagnosis_type/search', 'DiagnosisTypeController@search');
				Route::get('/diagnosis_types/delete/{id}', 'DiagnosisTypeController@delete');

				Route::resource('document_statuses', 'DocumentStatusController');
				Route::get('/document_statuses/id/{id}', 'DocumentStatusController@searchById');
				Route::post('/document_status/search', 'DocumentStatusController@search');
				Route::get('/document_status/search', 'DocumentStatusController@search');
				Route::get('/document_statuses/delete/{id}', 'DocumentStatusController@delete');

				Route::resource('document_types', 'DocumentTypeController');
				Route::get('/document_types/id/{id}', 'DocumentTypeController@searchById');
				Route::post('/document_type/search', 'DocumentTypeController@search');
				Route::get('/document_type/search', 'DocumentTypeController@search');
				Route::get('/document_types/delete/{id}', 'DocumentTypeController@delete');
				
				Route::resource('loan_statuses', 'LoanStatusController');
				Route::get('/loan_statuses/id/{id}', 'LoanStatusController@searchById');
				Route::post('/loan_status/search', 'LoanStatusController@search');
				Route::get('/loan_status/search', 'LoanStatusController@search');
				Route::get('/loan_statuses/delete/{id}', 'LoanStatusController@delete');

				Route::resource('maintenance_reasons', 'MaintenanceReasonController');
				Route::get('/maintenance_reasons/id/{id}', 'MaintenanceReasonController@searchById');
				Route::post('/maintenance_reason/search', 'MaintenanceReasonController@search');
				Route::get('/maintenance_reason/search', 'MaintenanceReasonController@search');
				Route::get('/maintenance_reasons/delete/{id}', 'MaintenanceReasonController@delete');

				Route::resource('users', 'UserController');
				Route::get('/users/id/{id}', 'UserController@searchById');
				Route::post('/user/search', 'UserController@search');
				Route::get('/user/search', 'UserController@search');
				Route::get('/users/delete/{id}', 'UserController@delete');

				Route::resource('order_maintenances', 'OrderMaintenanceController');
				Route::get('/order_maintenances/id/{id}', 'OrderMaintenanceController@searchById');
				Route::post('/order_maintenance/search', 'OrderMaintenanceController@search');
				Route::get('/order_maintenance/search', 'OrderMaintenanceController@search');
				Route::get('/order_maintenances/delete/{id}', 'OrderMaintenanceController@delete');

		});

		
});

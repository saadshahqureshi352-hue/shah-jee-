<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PickupAddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\SmartToolsController;
use App\Http\Controllers\TrackingPublicController;
use App\Http\Controllers\AdminShipperController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Authentication Routes
require __DIR__.'/auth.php';

// === SAAD BHAI KA ADMIN LOGIN BYPASS ROUTE ===
Route::get('/force-admin-login', function () {
    // 1. Check karein agar naya email maujood hai
    $user = DB::table('users')->where('email', 'shahjeecourier@gmail.com')->first();
    
    // 2. Agar user nahi mila to aapka bataya hua email aur password insert karein
    if (!$user) {
        DB::table('users')->insert([
            'name' => 'Admin Shah Jee',
            'email' => 'shahjeecourier@gmail.com',
            'password' => bcrypt('1122334455'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $user = DB::table('users')->where('email', 'shahjeecourier@gmail.com')->first();
    }
    
    // 3. Direct bagair password error ke session login karein
    Auth::loginUsingId($user->id);
    
    // 4. Seedha dashboard par redirect
    return redirect('/dashboard')->with('success', 'Logged in successfully!');
});
// ============================================

Route::get('/track/{tracking_number}', [TrackingPublicController::class, 'show'])->name('track.show');

// Protected Routes
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');

    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    $status = Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bookings', [OrderController::class, 'index'])->name('bookings');
    Route::get('/bookings/loadsheets', [BookingController::class, 'loadsheets'])->name('bookings.loadsheets');

    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/book-shipment', [BookingController::class, 'store'])->name('shipment.book');
    Route::get('/bookings/{booking}/slip', [BookingController::class, 'slip'])->name('bookings.slip');

    // === BULK PRINT LABELS SYSTEM ROUTES ===
    // Modal ka data lane ke liye endpoint
    Route::get('/api/bulk-print-orders', [BookingController::class, 'getBulkOrders']);
    // Selected orders ko akatha print nikalne ka generator
    Route::get('/bookings/bulk-print', [BookingController::class, 'bulkPrintLabels'])->name('bookings.bulk-print');
    // =======================================

    Route::get('/pickup-addresses', [PickupAddressController::class, 'index'])->name('pickup-addresses.index');
    Route::post('/pickup-addresses', [PickupAddressController::class, 'store'])->name('pickup-addresses.store');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance');

    Route::get('/payments', fn () => redirect()->route('payments.overall-sales'))->name('payments');
    Route::get('/payments/overall-sales', [PaymentsController::class, 'overallSales'])->name('payments.overall-sales');
    Route::get('/payments/invoices', [PaymentsController::class, 'invoices'])->name('payments.invoices');
    Route::post('/payments/invoices/{invoice}/mark-paid', [PaymentsController::class, 'markInvoicePaid'])->name('payments.invoices.mark-paid');
    Route::get('/payments/non-cod', [PaymentsController::class, 'nonCod'])->name('payments.non-cod');
    Route::post('/payments/non-cod', [PaymentsController::class, 'nonCodStore'])->name('payments.non-cod.store');

    Route::get('/smart-tools', [SmartToolsController::class, 'hub'])->name('smart-tools');
    Route::get('/smart-tools/tracking', [SmartToolsController::class, 'tracking'])->name('smart-tools.tracking');
    Route::get('/smart-tools/whatsapp-gateway', [SmartToolsController::class, 'whatsAppGateway'])->name('smart-tools.whatsapp-gateway');
    Route::post('/smart-tools/whatsapp-profiles', [SmartToolsController::class, 'storeWhatsAppProfile'])->name('smart-tools.profiles.store');
    Route::post('/smart-tools/consignee-activate', [SmartToolsController::class, 'activateConsigneeAlert'])->name('smart-tools.profiles.activate');
    Route::post('/smart-tools/consignee-disconnect', [SmartToolsController::class, 'deactivateConsigneeAlert'])->name('smart-tools.profiles.disconnect');
    Route::get('/smart-tools/alert-templates', [SmartToolsController::class, 'alertTemplates'])->name('smart-tools.alert-templates');
    Route::post('/smart-tools/alert-templates', [SmartToolsController::class, 'saveAlertTemplates'])->name('smart-tools.alert-templates.save');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/shippers', [AdminShipperController::class, 'index'])->name('shippers.index');
        Route::get('/shippers/{user}/edit', [AdminShipperController::class, 'edit'])->name('shippers.edit');
        Route::patch('/shippers/{user}', [AdminShipperController::class, 'update'])->name('shippers.update');
        Route::post('/shippers/{user}/approve', [AdminShipperController::class, 'approve'])->name('shippers.approve');
        Route::post('/shippers/{user}/reject', [AdminShipperController::class, 'reject'])->name('shippers.reject');
    });

    Route::get('/integrations', function () {
        return view('integrations');
    })->name('integrations');
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
});
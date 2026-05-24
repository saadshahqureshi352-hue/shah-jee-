# 3PL Admin Panel - Feature Documentation

**Version:** 1.0  
**Last Updated:** May 23, 2026  
**Status:** Operational Efficiency Phase Complete

---

## 📋 Overview

The Shah Jee Courier 3PL Admin Panel is a comprehensive management system built with **Laravel 13.8** and **Filament 5.6**, designed to streamline daily operations, manage merchants, handle financials, and track shipments efficiently.

---

## 🎯 Core Features

### 1. **Power Dashboard** ✅
**Location:** Admin Panel Home  
**Features:**
- **Today vs Yesterday Comparison:** Real-time shipment statistics with percentage changes
- **Revenue & Profit Tracking:** Total delivery charges and commission calculations
- **System Alerts:** Pending pickups, delayed shipments, failed deliveries, and returned shipments
- **Courier Performance:** Visual breakdown of deliveries by courier company

**Files:**
- `app/Filament/Widgets/StatsOverviewWidget_Enhanced.php`
- `app/Filament/Widgets/RevenueVsProfitWidget.php`
- `app/Filament/Widgets/CourierPerformanceWidget_Enhanced.php`
- `app/Filament/Widgets/AlertsWidget.php`

---

### 2. **Merchant & User Management** ✅
**Location:** Admin → Merchant Management → Merchants  
**Features:**
- **Account Approval:** Manually approve/reject new merchant registrations
- **Wallet Control:** View, update, and manage merchant wallet balances
- **Wallet Status:** Activate, block, or suspend merchant accounts
- **Pricing Plans:** Assign different pricing plans to merchants

**Files:**
- `app/Filament/Resources/WalletResource.php`
- `app/Services/WalletService.php`

**Database Tables:**
- `wallets` - Merchant wallet balances and history

**API:**
```php
// WalletService Usage
$walletService = new WalletService();
$walletService->creditWallet($user, 5000, 'Payment received');
$walletService->debitWallet($user, 1000, 'Commission deducted');
$walletService->blockWallet($user, 'Suspicious activity');
```

---

### 3. **Courier & API Management Hub** ✅
**Location:** Admin → Courier Management

#### a) **API Key Management**
**Location:** Admin → Courier Management → API Keys  
**Features:**
- Securely store and manage courier API credentials (encrypted)
- Support for Production and Testing environments
- Last usage tracking
- Toggle API keys on/off

**Files:**
- `app/Filament/Resources/APIKeyResource.php`
- `app/Models/APIKey.php` (Auto-encryption for sensitive fields)

**Database Tables:**
- `api_keys` - Encrypted courier API credentials

**Security:** All API keys, secrets, and account IDs are encrypted using Laravel's encryption before storage.

#### b) **Rate Matrix Management**
**Location:** Admin → Courier Management → Rate Matrices  
**Features:**
- Define delivery rates by courier, city, and weight category
- Support for 5 weight categories: 0-500g, 501-1kg, 1-2kg, 2-5kg, 5+kg
- Enable/disable rates dynamically
- Weight categories for: Karachi, Lahore, Islamabad, etc.

**Files:**
- `app/Filament/Resources/RateMatrixResource.php`
- `app/Models/RateMatrix.php`

**Database Tables:**
- `rate_matrices` - Dynamic rate configuration

**API:**
```php
// Get rate for a specific shipment
$rate = RateMatrix::getRate($courierId, 'Karachi', '1-2kg');
```

---

### 4. **Shipment/Order Management** ✅
**Location:** Admin → Shipments → Bookings  
**Features:**
- **Advanced Filters:**
  - Filter by status (Pending, In Transit, Delivered, Failed, etc.)
  - Filter by courier company
  - Filter by merchant/user
  - Date range filtering
  - Destination city filtering

- **Bulk Operations:**
  - Bulk status updates
  - Bulk label downloads
  - Bulk export to CSV/Excel

- **Tracking History:**
  - Complete shipment journey from origin to destination
  - Status timeline
  - Courier tracking updates

**Files:**
- `app/Filament/Resources/Bookings/BookingResource.php` (Enhanced)
- `app/Services/ExportService.php`

**Database Tables:**
- `bookings` - Order data
- `tracking_history` - Movement history

---

### 5. **Financials & Payouts Management** ✅
**Location:** Admin → Financial Management

#### a) **COD Reconciliation**
**Location:** Admin → Financial Management → COD Reconciliation  
**Features:**
- Track cash collected vs. transferred to bank
- Identify discrepancies automatically
- Historical reconciliation reports
- Status tracking: Pending, Verified, Discrepancy, Resolved

**Files:**
- `app/Filament/Resources/CODReconciliationResource.php`
- `app/Models/CODReconciliation.php`

**Database Tables:**
- `cod_reconciliations` - Cash tracking and reconciliation

#### b) **Payout Management**
**Location:** Admin → Financial Management → Payouts  
**Features:**
- Automatic payout generation based on period and commission percentage
- Payout reference generation (PAYOUT-YYYY-XXXXX)
- Payment method tracking (Bank Transfer, Check, Mobile Wallet)
- Status tracking: Pending, Processing, Completed, Failed, Cancelled
- Commission and fee tracking

**Files:**
- `app/Filament/Resources/PayoutResource.php`
- `app/Models/Payout.php`
- `app/Services/PayoutService.php`

**Database Tables:**
- `payouts` - Payout records and status

**API:**
```php
// Generate payout for merchant
$payoutService = new PayoutService();
$payout = $payoutService->generatePayout($user, 5.0, $startDate, $endDate);
$payoutService->markAsPaid($payout);

// Get report
$report = $payoutService->generatePayoutReport($startDate, $endDate);
```

#### c) **Profit Calculator**
**Features:**
- Automatic per-order commission calculation
- Profit tracking by period, courier, and merchant
- Customizable commission percentage
- Profit breakdown reports

**Files:**
- `app/Services/ProfitCalculatorService.php`

**API:**
```php
// Calculate profit for a booking
$calculator = new ProfitCalculatorService();
$commission = $calculator->calculateBookingCommission($booking, 5.0);

// Get profit by date range
$profit = $calculator->calculateProfitByDateRange($startDate, $endDate, 5.0);

// Get breakdown by courier
$breakdown = $calculator->getProfitByCourier($startDate, $endDate, 5.0);
```

---

### 6. **Operational Features** ✅

#### a) **Dark Mode**
**Location:** User Settings (Profile Icon → Top Right)  
**Features:**
- Built-in dark/light mode toggle
- Automatically persists user preference
- Optimized colors for both modes

#### b) **Activity Logs**
**Location:** Admin → Audit → Activity Logs  
**Features:**
- Track all admin operations
- Log user, timestamp, action, and changes
- Support for resource modifications (status changes, rate updates, wallet operations)
- Old and new values comparison

**Database Tables:**
- `activity_logs` - Audit trail

#### c) **Excel Export**
**Locations:** Any list page (Bookings, Merchants, Payouts)  
**Features:**
- Export filtered data to CSV format
- Download button on list pages
- Formatted output for spreadsheet applications

**Files:**
- `app/Services/ExportService.php`

**API:**
```php
// Export bookings
$exporter = new ExportService();
$csv = $exporter->exportBookingsToCSV($bookings);
return $exporter->downloadCSV($csv, 'bookings.csv');
```

---

## 📊 Database Schema

### New Tables Created

#### wallets
```
id, user_id (FK), balance, total_credited, total_debited, status, notes, timestamps
```

#### api_keys
```
id, courier_integration_id (FK), key_name, api_key (encrypted), api_secret (encrypted),
account_id (encrypted), account_title (encrypted), environment, is_active, 
notes, last_used_at, timestamps
```

#### rate_matrices
```
id, courier_integration_id (FK), city_zone, weight_category, rate, is_active, 
notes, timestamps
```

#### cod_reconciliations
```
id, courier_integration_id (FK), reconciliation_date, reported_cash, transferred_cash,
variance, total_cod_shipments, successful_deliveries, status, notes, timestamps
```

#### payouts
```
id, user_id (FK), payout_reference (unique), gross_amount, commissions_deducted,
other_charges, net_amount, period_start, period_end, status, payment_method,
paid_at, remarks, timestamps
```

---

## 🔐 Security Features

1. **API Key Encryption:** All sensitive credentials stored encrypted using Laravel's `Crypt` facade
2. **Activity Logging:** Every admin action is logged for audit purposes
3. **Role-Based Access:** Resources inherit Filament's role-based permissions
4. **Wallet Blocking:** Merchants can be blocked to prevent unauthorized transactions
5. **Secure Password Storage:** User passwords hashed with bcrypt

---

## 📈 Key Metrics & Reports

### Dashboard Metrics
- Daily shipment comparison (Today vs Yesterday)
- Total revenue from delivery charges
- Total COD value in pipeline
- Active merchant count
- Courier delivery performance
- System alerts and warnings

### Financial Reports
- Profit by period (customizable date range)
- Revenue by courier
- Commission tracking
- Payout history and status
- COD reconciliation reports

### Merchant Analytics
- Merchant wallet balance and history
- Payout history
- Commission calculations
- Account status tracking

---

## 🚀 Usage Examples

### Create New API Key
1. Go to Admin → Courier Management → API Keys
2. Click "Create"
3. Select Courier
4. Enter Key Name (e.g., "Trax Production")
5. Enter API Key, Secret, Account ID (auto-encrypted)
6. Choose Environment (Production/Testing)
7. Save

### Generate Merchant Payout
```php
$payoutService = new PayoutService();
$payout = $payoutService->generatePayout(
    user: $merchant,
    commissionPercent: 5.0,
    periodStart: now()->startOfMonth(),
    periodEnd: now()->endOfMonth()
);
```

### Export Shipments to Excel
1. Go to Admin → Shipments → Bookings
2. Apply filters as needed
3. Click "Export" button
4. Download CSV file

### Track Merchant Wallet
1. Go to Admin → Merchant Management → Wallets
2. View balance, credits, and debits
3. Click merchant name to edit
4. Update balance, block/unblock account

---

## 🛠️ Configuration

### Commission Percentage
Default: **5%**  
Update in `PayoutService::generatePayout()` or environment variables

### Supported Cities/Zones
- Karachi
- Lahore  
- Islamabad
- Rawalpindi
- Multan
- (Add more in RateMatrix resource)

### Weight Categories
- 0-500g
- 501-1kg
- 1-2kg
- 2-5kg
- 5+kg

---

## 📝 Notes

- All monetary values are in **PKR (Pakistani Rupees)**
- Filament version: **5.6** (includes built-in dark mode)
- Laravel version: **13.8**
- PHP requirement: **8.3+**
- Activity logging captures all admin changes automatically
- Excel exports use CSV format for maximum compatibility

---

## ✅ Complete Implementation Checklist

- ✅ Dashboard with real-time stats and comparisons
- ✅ Merchant wallet management with blocking capability
- ✅ Secure API key management (encrypted)
- ✅ Dynamic rate matrix configuration
- ✅ Advanced booking filters and bulk operations
- ✅ COD reconciliation tracking
- ✅ Automatic payout generation and invoicing
- ✅ Per-order profit calculator
- ✅ Dark mode support
- ✅ Enhanced activity logging
- ✅ Excel export for all major tables

---

## 🔄 Next Steps (Optional Enhancements)

1. **SMS Notifications:** Alert merchants when payouts are processed
2. **Email Reports:** Auto-send financial reports to merchants
3. **Mobile App:** Companion mobile app for field operations
4. **Payment Gateway Integration:** Direct bank transfers via API
5. **Advanced Analytics:** Predictive analytics and trend analysis

---

**For support or questions, contact:** `shahjeecourier@gmail.com`

# Admin Panel Comprehensive Overhaul - Todo List

## Phase 1: Backend Data Layer (AdminDashboard.php)
- [x] Already has most data structures in getViewData()
- [ ] Update Company Live Position: Total COD (all statuses)
- [ ] Fix Merchant Payables: Total COD - (Delivery Charges + 4% Tax)
- [ ] Fix Courier Receivable: Total COD Sent - (Courier Fees + 2% Tax)
- [ ] Fix Available Cash: Merchant Delivery Charges - Courier Delivery Charges (no tax)
- [ ] Add Total Returned count
- [ ] Add Ready to Return / Return Confirmed proper tracking
- [ ] Update Gross Profit: on dispatched orders (Merchant Rate - Courier Rate)
- [ ] Update Net Profit: on delivered orders (actual profit after courier paid)
- [ ] Add date_to_date period filter
- [ ] Add calendar/date range to backend

## Phase 2: Dashboard View (admin-dashboard.blade.php)
- [ ] Complete rewrite with all new sections
- [ ] Add ready_to_return and return_confirmed filter buttons
- [ ] Add search box for orders
- [ ] Add calendar box for date filtering
- [ ] Add Total Returned card
- [ ] Fix financial formulas display

## Phase 3: Orders Section Overhaul
- [ ] Add Ready to Return / Return Confirmed filter buttons
- [ ] Add search box (by name/tracking number)
- [ ] Add calendar date picker
- [ ] Fix filtered data loading

## Phase 4: COD Settlement Section
- [ ] Fix Total COD to Pay formula
- [ ] Fix Courier Receivable formula  
- [ ] Add per-merchant edit functionality
- [ ] Add status dropdown (pending/paid/unpaid)
- [ ] Add calendar box

## Phase 5: Invoice Management
- [ ] Convert from 3-day to Daily Invoice system
- [ ] Add auto-scheduler logic (evening batch)
- [ ] Add manual Generate Invoice button
- [ ] Add View orders in invoice
- [ ] Add Edit invoice functionality
- [ ] Add Pay / Mark as Paid button
- [ ] Add Today Pay aggregation button

## Phase 6: Merchants Section
- [ ] Pending approval with Approve/Reject
- [ ] Active merchants financial summary
- [ ] Custom return charges per merchant
- [ ] Plan change dropdown
- [ ] Search box (name/phone)
- [ ] Calendar date filter

## Phase 7: Couriers Section
- [ ] Courier rate management with profit display
- [ ] ON/OFF toggle
- [ ] Add courier button
- [ ] Display logo
- [ ] Gross profit (dispatched) vs Net profit (delivered)

## Phase 8: Pricing Plans
- [ ] Convert to Basic, Standard, VIP
- [ ] Editable rates: Different City, Same City, Additional KG, Return
- [ ] Merchant count per plan
- [ ] Plan switching for merchants
- [ ] Filter tabs: All, Basic, Standard, VIP

## Phase 9: Overall Sales
- [ ] Per courier breakdown
- [ ] Total Delivered, Total COD, Gross Profit, 4% Tax
- [ ] Net Profit Summary
- [ ] Courier 2% and Our 2% tax breakdown

## Phase 10: JavaScript / AJAX Integration
- [ ] All filter callbacks
- [ ] Merchant approve/reject
- [ ] Courier toggle/save
- [ ] Invoice generate/mark-paid
- [ ] Plan saving
- [ ] Search functionality
- [ ] Calendar/date picker
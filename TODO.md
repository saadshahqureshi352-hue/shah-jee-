# Shah Jee Courier — Implementation TODO

## Finance + Invoices (Daily Cycle)
- [ ] Update/extend DB schema for daily invoices (11:59pm), overdue logic, and delivered_at linkage.
- [ ] Add courier settlement ledger (2% courier tax + courier receivable/remittance).
- [ ] Add consolidated tax holding + profit fields needed for dashboard.
- [ ] Replace existing weekly/3-day SellerInvoiceSyncService with daily scheduler job + manual Generate Invoice endpoint.
- [ ] Implement Delivered-only finance calculations:
  - [ ] Merchant tax (4% of COD)
  - [ ] Courier tax (2% of COD)
  - [ ] Company profit without tax (delivery margin + our 2% tax margin)
  - [ ] Gross profit on dispatched orders (merchant_rate - courier_rate)
- [ ] Same-city auto-detection and correct rate application on booking hook.

## Courier ON/OFF
- [ ] Ensure courier ON/OFF impacts client portal booking availability (backend validation too).

## Filament Dashboard + Orders Filters
- [ ] Implement dashboard widgets:
  - [ ] Date filters: Today, Yesterday, 3 Days, This Week, This Month, Date-to-Date
  - [ ] Top cards: Live Balance, Merchant Payables, Courier Receivable, Tax Held (4%), Available Cash (Profit without tax), Book Today, Dispatched, Delivered, In Progress, Issue, Ready to Return, Return Confirmed, Total Returned, Gross Profit, Net Profit.
- [ ] Orders sidebar:
  - [ ] Tabs for statuses + global search by name/tracking + calendar/day filter
  - [ ] Correct mapping to “In Progress / Issue / Ready to Return / Return Confirmed / Returned” rules
- [ ] Return workflow states UI + backend state transitions.

## Settlements UI
- [ ] COD Settlement tables:
  - [ ] Total COD to Pay, Net payable, courier receivable, pending settlements, per-merchant settlement, edit/save with recompute.
- [ ] Courier COD Received breakdown:
  - [ ] courier-wise delivery charges & courier tax (2%) deducted, remitted amount, courier status active/off.

## Completion / Validation
- [ ] Migrate DB + run unit checks
- [ ] Validate scheduler: deliver orders → invoice auto-generated daily → statuses pending/paid/overdue
- [ ] Verify dashboard counters match settlement totals

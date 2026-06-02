<style>
/* ============================================================
   SHAH JEE COURIER — PROFESSIONAL ADMIN DASHBOARD
   ============================================================ */
:root {
  --erp-bg: #f0f2f5;
  --erp-card-bg: #ffffff;
  --erp-border: #e5e7eb;
  --erp-border-light: #f3f4f6;
  --erp-primary: #4f46e5;
  --erp-primary-light: #eef2ff;
  --erp-primary-soft: #e0e7ff;
  --erp-primary-dark: #3730a3;
  --erp-success: #059669;
  --erp-success-light: #ecfdf5;
  --erp-success-soft: #d1fae5;
  --erp-warning: #d97706;
  --erp-warning-light: #fffbeb;
  --erp-warning-soft: #fef3c7;
  --erp-danger: #dc2626;
  --erp-danger-light: #fef2f2;
  --erp-danger-soft: #fee2e2;
  --erp-info: #2563eb;
  --erp-info-light: #eff6ff;
  --erp-info-soft: #dbeafe;
  --erp-text: #111827;
  --erp-text-secondary: #6b7280;
  --erp-text-muted: #9ca3af;
  --erp-text-light: #d1d5db;
  --erp-radius: 12px;
  --erp-radius-sm: 8px;
  --erp-radius-xs: 6px;
  --erp-shadow-sm: 0 1px 2px 0 rgba(0,0,0,.05);
  --erp-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px -1px rgba(0,0,0,.1);
  --erp-shadow-md: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -2px rgba(0,0,0,.1);
  --erp-shadow-lg: 0 10px 15px -3px rgba(0,0,0,.1), 0 4px 6px -4px rgba(0,0,0,.1);
  --erp-transition: all .2s cubic-bezier(.4,0,.2,1);
  --erp-font: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
}

* { box-sizing: border-box; }

#erp-admin-wrap {
  font-family: var(--erp-font);
  font-size: 14px;
  line-height: 1.6;
  color: var(--erp-text);
  background: var(--erp-bg);
  position: fixed;
  top: 0; left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 999;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* ==================== LAYOUT ==================== */
.erp-layout { display: flex; min-height: 100vh; }

/* ---- Sidebar ---- */
.erp-sidebar {
  width: 250px;
  min-width: 250px;
  background: linear-gradient(180deg, #1e1b4b 0%, #312e81 50%, #3730a3 100%);
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
  z-index: 20;
  box-shadow: 2px 0 12px rgba(0,0,0,.08);
}

.erp-sidebar-logo {
  padding: 22px 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  border-bottom: 1px solid rgba(255,255,255,.08);
}

.erp-logo-icon {
  width: 42px; height: 42px;
  background: linear-gradient(135deg, #818cf8, #6366f1);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  font-size: 19px;
  font-weight: 700;
  flex-shrink: 0;
  box-shadow: 0 4px 14px rgba(99,102,241,.35);
}

.erp-logo-text { display: flex; flex-direction: column; gap: 1px; }
.erp-logo-name { font-size: 16px; font-weight: 700; color: #f9fafb; letter-spacing: -.3px; line-height: 1.2; }
.erp-logo-sub { font-size: 11px; color: #a5b4fc; font-weight: 400; }

.erp-nav-group { padding: 10px 0; }
.erp-nav-label {
  font-size: 10px;
  font-weight: 700;
  color: #818cf8;
  padding: 10px 20px 4px;
  letter-spacing: 1.2px;
  text-transform: uppercase;
}

.erp-nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 11px 18px;
  margin: 2px 10px;
  font-size: 13.5px;
  color: #c7d2fe;
  cursor: pointer;
  border-radius: 8px;
  transition: var(--erp-transition);
  font-weight: 500;
  text-decoration: none;
}

.erp-nav-item:hover {
  background: rgba(255,255,255,.08);
  color: #e0e7ff;
}

.erp-nav-item.active {
  background: rgba(99,102,241,.25);
  color: #fff;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(0,0,0,.15);
}

.erp-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: .8; }
.erp-nav-item.active svg { opacity: 1; }
.erp-nav-item .erp-badge { margin-left: auto; }

/* ---- Main Content ---- */
.erp-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

.erp-topbar {
  background: var(--erp-card-bg);
  border-bottom: 1px solid var(--erp-border);
  padding: 14px 28px;
  display: flex;
  align-items: center;
  gap: 16px;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: var(--erp-shadow-sm);
}

.erp-topbar-title {
  font-size: 20px;
  font-weight: 700;
  flex: 1;
  color: var(--erp-text);
  letter-spacing: -.3px;
}

.erp-topbar-avatar {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: linear-gradient(135deg, #6366f1, #8b5cf6);
  color: #fff;
  font-size: 14px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(99,102,241,.35);
}

.erp-content {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px 32px;
}

.erp-page { display: none; animation: erpFadeIn .3s ease; }
.erp-page.active { display: block; }

@keyframes erpFadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==================== GRID SYSTEM ==================== */
.erp-grid { display: grid; gap: 16px; }
.erp-grid-6 { grid-template-columns: repeat(6, 1fr); }
.erp-grid-4 { grid-template-columns: repeat(4, 1fr); }
.erp-grid-3 { grid-template-columns: repeat(3, 1fr); }
.erp-grid-2 { grid-template-columns: 1fr 1fr; }

/* ==================== STAT CARDS ==================== */
.erp-stat {
  background: var(--erp-card-bg);
  border-radius: var(--erp-radius);
  padding: 20px 22px;
  border: 1px solid var(--erp-border);
  box-shadow: var(--erp-shadow-sm);
  transition: var(--erp-transition);
  position: relative;
  overflow: hidden;
}

.erp-stat:hover {
  box-shadow: var(--erp-shadow-md);
  transform: translateY(-2px);
  border-color: #c7d2fe;
}

.erp-stat-icon {
  width: 44px; height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  margin-bottom: 14px;
}

.erp-stat-label {
  font-size: 12.5px;
  color: var(--erp-text-secondary);
  margin-bottom: 6px;
  display: flex;
  align-items: center;
  gap: 6px;
  font-weight: 500;
  letter-spacing: .1px;
}

.erp-stat-value {
  font-size: 26px;
  font-weight: 700;
  color: var(--erp-text);
  line-height: 1.2;
  letter-spacing: -.5px;
}

.erp-stat-sub {
  font-size: 11.5px;
  color: var(--erp-text-muted);
  margin-top: 6px;
  font-weight: 400;
}

/* ==================== FINANCIAL WIDGET ==================== */
.erp-fin-widget {
  background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
  border: 1px solid #c7d2fe;
  border-radius: var(--erp-radius);
  padding: 22px 26px;
  margin-bottom: 18px;
  box-shadow: var(--erp-shadow-sm);
}

.erp-fin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.erp-fin-title {
  font-size: 13.5px;
  font-weight: 700;
  color: var(--erp-text-secondary);
  display: flex;
  align-items: center;
  gap: 8px;
  text-transform: uppercase;
  letter-spacing: .5px;
}

.erp-fin-live {
  font-size: 11px;
  color: var(--erp-success);
  display: flex;
  align-items: center;
  gap: 5px;
  font-weight: 600;
  background: var(--erp-success-light);
  padding: 4px 12px;
  border-radius: 99px;
}

.erp-fin-live-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: var(--erp-success);
  animation: erpPulse 1.5s ease infinite;
}

@keyframes erpPulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50% { opacity: .5; transform: scale(1.3); }
}

.erp-fin-main {
  font-size: 32px;
  font-weight: 800;
  color: var(--erp-primary-dark);
  margin: 8px 0 20px;
  letter-spacing: -.8px;
}

.erp-fin-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 13px;
  padding: 8px 0;
  border-bottom: 1px solid rgba(0,0,0,.05);
  color: var(--erp-text-secondary);
}

.erp-fin-row:last-child { border-bottom: none; }
.erp-fin-row span:last-child { font-weight: 500; color: var(--erp-text); }

/* ==================== CARDS ==================== */
.erp-card {
  background: var(--erp-card-bg);
  border: 1px solid var(--erp-border);
  border-radius: var(--erp-radius);
  box-shadow: var(--erp-shadow-sm);
  overflow: hidden;
  margin-bottom: 18px;
}

.erp-card-header {
  padding: 15px 20px;
  border-bottom: 1px solid var(--erp-border-light);
  background: #fafbfc;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.erp-card-header-title {
  font-size: 14px;
  font-weight: 700;
  color: var(--erp-text);
  display: flex;
  align-items: center;
  gap: 8px;
}

.erp-card-body { padding: 0; }

/* ==================== TABLES ==================== */
.erp-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 13px;
}

.erp-table th {
  padding: 13px 16px;
  text-align: left;
  font-weight: 600;
  color: var(--erp-text-secondary);
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: .5px;
  border-bottom: 2px solid var(--erp-border);
  background: #f9fafb;
  white-space: nowrap;
}

.erp-table td {
  padding: 13px 16px;
  color: var(--erp-text);
  border-bottom: 1px solid var(--erp-border-light);
  vertical-align: middle;
}

.erp-table tbody tr:last-child td { border-bottom: none; }
.erp-table tbody tr { transition: background .15s ease; }
.erp-table tbody tr:hover td { background: #f8fafc; }

/* ==================== BADGES ==================== */
.erp-badge {
  font-size: 11px;
  padding: 4px 12px;
  border-radius: 99px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  white-space: nowrap;
  letter-spacing: .1px;
}

.erp-badge-success { background: var(--erp-success-soft); color: #065f46; }
.erp-badge-warning { background: var(--erp-warning-soft); color: #92400e; }
.erp-badge-danger { background: var(--erp-danger-soft); color: #991b1b; }
.erp-badge-info { background: var(--erp-info-soft); color: #1e40af; }
.erp-badge-neutral { background: #f3f4f6; color: #6b7280; }

/* ==================== BUTTONS ==================== */
.erp-btn {
  font-size: 12px;
  padding: 8px 16px;
  border-radius: var(--erp-radius-xs);
  border: 1px solid var(--erp-border);
  background: var(--erp-card-bg);
  color: var(--erp-text-secondary);
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-weight: 500;
  transition: var(--erp-transition);
  white-space: nowrap;
  line-height: 1;
}

.erp-btn:hover { background: #f9fafb; border-color: #d1d5db; color: var(--erp-text); }

.erp-btn-primary {
  background: var(--erp-primary);
  color: #fff;
  border-color: transparent;
  box-shadow: 0 1px 3px rgba(79,70,229,.3);
}

.erp-btn-primary:hover { background: #4338ca; color: #fff; box-shadow: 0 2px 8px rgba(79,70,229,.4); }

.erp-btn-success {
  background: var(--erp-success);
  color: #fff;
  border-color: transparent;
  box-shadow: 0 1px 3px rgba(5,150,105,.3);
}

.erp-btn-success:hover { background: #047857; color: #fff; box-shadow: 0 2px 8px rgba(5,150,105,.4); }

.erp-btn-danger {
  background: var(--erp-danger);
  color: #fff;
  border-color: transparent;
  box-shadow: 0 1px 3px rgba(220,38,38,.3);
}

.erp-btn-danger:hover { background: #b91c1c; color: #fff; box-shadow: 0 2px 8px rgba(220,38,38,.4); }

.erp-btn-warning {
  background: var(--erp-warning);
  color: #fff;
  border-color: transparent;
  box-shadow: 0 1px 3px rgba(217,119,6,.3);
}

.erp-btn-warning:hover { background: #b45309; color: #fff; }

.erp-btn-sm { font-size: 11px; padding: 6px 12px; border-radius: 5px; }

/* ==================== FILTER BAR ==================== */
.erp-filter-bar { display: flex; gap: 6px; margin-bottom: 16px; flex-wrap: wrap; }

.erp-filter-btn {
  font-size: 12px;
  padding: 7px 16px;
  border-radius: 99px;
  border: 1px solid var(--erp-border);
  background: var(--erp-card-bg);
  color: var(--erp-text-secondary);
  cursor: pointer;
  font-weight: 500;
  transition: var(--erp-transition);
}

.erp-filter-btn:hover { border-color: var(--erp-primary); color: var(--erp-primary); background: var(--erp-primary-light); }

.erp-filter-btn.active {
  background: linear-gradient(135deg, var(--erp-primary), #6366f1);
  color: #fff;
  border-color: transparent;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(79,70,229,.35);
}

/* ==================== TOGGLE ==================== */
.erp-toggle {
  width: 48px; height: 26px;
  border-radius: 13px;
  border: none;
  cursor: pointer;
  position: relative;
  transition: background .25s ease;
  flex-shrink: 0;
}

.erp-toggle.on { background: var(--erp-success); box-shadow: 0 0 0 2px rgba(5,150,105,.2); }
.erp-toggle.off { background: #d1d5db; }

.erp-toggle::after {
  content: '';
  position: absolute;
  top: 2px;
  width: 22px; height: 22px;
  border-radius: 50%;
  background: #fff;
  transition: left .25s ease;
  box-shadow: 0 1px 3px rgba(0,0,0,.2);
}

.erp-toggle.on::after { left: 24px; }
.erp-toggle.off::after { left: 2px; }

/* ==================== INPUTS ==================== */
.erp-input {
  border: 1px solid var(--erp-border);
  border-radius: var(--erp-radius-xs);
  padding: 8px 12px;
  font-size: 13px;
  background: var(--erp-card-bg);
  color: var(--erp-text);
  width: 96px;
  text-align: right;
  transition: var(--erp-transition);
}

.erp-input:focus {
  outline: none;
  border-color: var(--erp-primary);
  box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}

/* ==================== TOAST ==================== */
.erp-toast {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 9999;
  font-size: 13px;
  padding: 14px 22px;
  border-radius: var(--erp-radius-sm);
  font-weight: 600;
  box-shadow: 0 12px 32px rgba(0,0,0,.18);
  display: none;
  animation: erpSlideIn .3s ease;
  min-width: 200px;
}

.erp-toast.show { display: block; }
.erp-toast-success { background: #059669; color: #fff; }
.erp-toast-error { background: #dc2626; color: #fff; }

@keyframes erpSlideIn {
  from { opacity: 0; transform: translateY(-14px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ==================== SECTION TITLE ==================== */
.erp-section-title {
  font-size: 15px;
  font-weight: 700;
  color: var(--erp-text);
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
  letter-spacing: -.2px;
}

/* ==================== INFO BAR ==================== */
.erp-info-bar {
  font-size: 12.5px;
  padding: 12px 18px;
  border-radius: var(--erp-radius-sm);
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  line-height: 1.5;
}

.erp-info-bar-info { background: var(--erp-info-light); color: #1e40af; border: 1px solid var(--erp-info-soft); }
.erp-info-bar-warning { background: var(--erp-warning-light); color: #92400e; border: 1px solid var(--erp-warning-soft); }

/* ==================== PLAN CARDS ==================== */
.erp-plan-card {
  background: var(--erp-card-bg);
  border: 1px solid var(--erp-border);
  border-radius: var(--erp-radius);
  padding: 20px;
  transition: var(--erp-transition);
}

.erp-plan-card:hover { box-shadow: var(--erp-shadow-md); transform: translateY(-2px); }
.erp-plan-card.vip { border-color: #a5b4fc; background: linear-gradient(180deg, #eef2ff, #fff); }

.erp-plan-header {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.erp-plan-table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 8px; }
.erp-plan-table th {
  background: #f9fafb;
  padding: 8px 10px;
  font-size: 10.5px;
  font-weight: 600;
  color: var(--erp-text-secondary);
  border-bottom: 1px solid var(--erp-border);
}
.erp-plan-table td { padding: 8px 10px; border-bottom: 1px solid var(--erp-border-light); }
.erp-plan-table tr:last-child td { border-bottom: none; }

/* ==================== FORM GROUP ==================== */
.erp-form-group { display: flex; flex-direction: column; gap: 5px; }
.erp-form-group label { font-size: 12px; font-weight: 600; color: var(--erp-text-secondary); }
.erp-form-group select,
.erp-form-group textarea,
.erp-form-group input[type="text"],
.erp-form-group input[type="number"] {
  width: 100%;
  font-size: 13px;
  padding: 10px 14px;
  border: 1px solid var(--erp-border);
  border-radius: var(--erp-radius-sm);
  background: var(--erp-card-bg);
  color: var(--erp-text);
  transition: var(--erp-transition);
  font-family: var(--erp-font);
}

.erp-form-group select:focus,
.erp-form-group textarea:focus,
.erp-form-group input:focus {
  outline: none;
  border-color: var(--erp-primary);
  box-shadow: 0 0 0 3px rgba(79,70,229,.1);
}

.erp-form-group textarea { resize: vertical; min-height: 90px; }

/* ==================== UTILITY ==================== */
.pos { color: #059669; font-weight: 700; }
.neg { color: #dc2626; font-weight: 700; }

/* ==================== RESPONSIVE ==================== */
@media (max-width: 1400px) {
  .erp-grid-6 { grid-template-columns: repeat(3, 1fr); }
  .erp-grid-4 { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 1100px) {
  .erp-sidebar { width: 210px; min-width: 210px; }
  .erp-grid-2, .erp-grid-3 { grid-template-columns: 1fr; }
  .erp-grid-6 { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  .erp-layout { flex-direction: column; }
  .erp-sidebar {
    width: 100%;
    min-width: unset;
    height: auto;
    position: relative;
    flex-direction: row;
    flex-wrap: wrap;
    overflow-x: auto;
    background: #312e81;
  }
  .erp-sidebar-logo { display: none; }
  .erp-nav-group { display: none; }
  .erp-grid-6, .erp-grid-4, .erp-grid-3, .erp-grid-2 { grid-template-columns: 1fr; }
  .erp-fin-main { font-size: 24px; }
  .erp-stat-value { font-size: 22px; }
  .erp-content { padding: 16px; }
  .erp-topbar { padding: 12px 16px; }
}
</style>

<div id="erp-admin-wrap">
  <div class="erp-layout">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="erp-sidebar">
      <div class="erp-sidebar-logo">
        <div class="erp-logo-icon">SJ</div>
        <div class="erp-logo-text">
          <div class="erp-logo-name">Shah Jee Courier</div>
          <div class="erp-logo-sub">Admin Control Panel</div>
        </div>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">Main</div>
        <a class="erp-nav-item active" onclick="erpNavigate('dashboard', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
          Dashboard
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('orders', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
          Orders
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('cod', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          COD & Settlement
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('invoices', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Invoices
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">People</div>
        <a class="erp-nav-item" onclick="erpNavigate('merchants', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
          Merchants
          <span class="erp-badge erp-badge-warning" style="font-size:10px;padding:2px 7px">{{ number_format($financialCards['pendingMerchants'] ?? 0) }}</span>
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('couriers', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
          Couriers
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">Finance</div>
        <a class="erp-nav-item" onclick="erpNavigate('overall-sales', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Overall Sales
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('pricing', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M8 12h8"/></svg>
          Pricing Plans
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('profit', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          Profit Report
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('tax', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="15" x2="15.01" y2="15"/></svg>
          Tax Engine
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">Tools</div>
        <a class="erp-nav-item" onclick="erpNavigate('notif', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          Notifications
        </a>
      </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="erp-main">
      <div class="erp-topbar">
        <div class="erp-topbar-title" id="erpPageTitle">Dashboard</div>
        <div class="erp-filter-bar" style="margin:0" id="erpTimeFilters">
          <button class="erp-filter-btn active" onclick="erpSetFilter('today', this)">Today</button>
          <button class="erp-filter-btn" onclick="erpSetFilter('yesterday', this)">Yesterday</button>
          <button class="erp-filter-btn" onclick="erpSetFilter('3days', this)">3 Days</button>
          <button class="erp-filter-btn" onclick="erpSetFilter('week', this)">This Week</button>
          <button class="erp-filter-btn" onclick="erpSetFilter('month', this)">This Month</button>
        </div>
        <div class="erp-topbar-avatar">SA</div>
      </div>

      <div class="erp-content">

        <!-- ==================== DASHBOARD ==================== -->
        <div class="erp-page active" id="erp-page-dashboard">

          <!-- Company Live Position -->
          <div class="erp-fin-widget">
            <div class="erp-fin-header">
              <div class="erp-fin-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg> Company Live Position</div>
              <div class="erp-fin-live"><span class="erp-fin-live-dot"></span>Live</div>
            </div>
            <div class="erp-fin-main">Rs {{ number_format($companyPosition['availableCash'] ?? 0) }}</div>
            <div class="erp-fin-row"><span>Bank Balance</span><span>Rs {{ number_format($companyPosition['bankBalance'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Merchant Payables</span><span class="neg">− Rs {{ number_format($companyPosition['merchantPayables'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Courier Receivables</span><span class="pos">+ Rs {{ number_format($companyPosition['courierReceivables'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Tax Collected (4%) Held</span><span class="neg">− Rs {{ number_format($companyPosition['taxHeld'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span style="font-weight:700;color:var(--erp-primary-dark)">Available Cash</span><span class="pos" style="font-size:16px">Rs {{ number_format($companyPosition['availableCash'] ?? 0) }}</span></div>
          </div>

          <!-- Operational Stats -->
          <div class="erp-grid erp-grid-6" style="margin-bottom:18px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-primary-light);color:var(--erp-primary)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
              <div class="erp-stat-label">Booked Today</div>
              <div class="erp-stat-value">{{ number_format($operationalCards['bookedToday'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['bookedTodayCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-info-light);color:var(--erp-info)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
              <div class="erp-stat-label">Dispatched</div>
              <div class="erp-stat-value" style="color:var(--erp-info)">{{ number_format($operationalCards['dispatched'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['dispatchedCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
              <div class="erp-stat-label">Delivered</div>
              <div class="erp-stat-value" style="color:var(--erp-success)">{{ number_format($operationalCards['delivered'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['deliveredCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
              <div class="erp-stat-label">In Progress</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($operationalCards['inProgress'] ?? 0) }}</div>
              <div class="erp-stat-sub">In transit</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 9 9 4 15 10 21 4"/><path d="M21 4v16H3"/></svg></div>
              <div class="erp-stat-label">Returned</div>
              <div class="erp-stat-value" style="color:var(--erp-danger)">{{ number_format($operationalCards['returned'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['returnedCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
              <div class="erp-stat-label">Issue Orders</div>
              <div class="erp-stat-value" style="color:var(--erp-danger)">{{ number_format($operationalCards['issueOrders'] ?? 0) }}</div>
              <div class="erp-stat-sub">Action needed</div>
            </div>
          </div>

          <!-- Financial Stats -->
          <div class="erp-grid erp-grid-6" style="margin-bottom:18px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="6" x2="12" y2="12"/><line x1="12" y1="12" x2="16" y2="14"/></svg></div>
              <div class="erp-stat-label">Gross Profit</div>
              <div class="erp-stat-value pos">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</div>
              <div class="erp-stat-sub">Dispatched orders</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div>
              <div class="erp-stat-label">Net Profit</div>
              <div class="erp-stat-value pos">Rs {{ number_format($financialCards['netProfit'] ?? 0) }}</div>
              <div class="erp-stat-sub">After 2% tax diff</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/></svg></div>
              <div class="erp-stat-label">Tax Collected 4%</div>
              <div class="erp-stat-value">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div>
              <div class="erp-stat-sub">On delivered COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg></div>
              <div class="erp-stat-label">Courier 2% Tax</div>
              <div class="erp-stat-value neg">Rs {{ number_format($financialCards['courierTax2'] ?? 0) }}</div>
              <div class="erp-stat-sub">Deducted by courier</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div>
              <div class="erp-stat-label">Our 2% Tax Balance</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</div>
              <div class="erp-stat-sub">Our liability</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-primary-light);color:var(--erp-primary)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
              <div class="erp-stat-label">Active Merchants</div>
              <div class="erp-stat-value">{{ number_format($financialCards['activeMerchants'] ?? 0) }}</div>
              <div class="erp-stat-sub">{{ number_format($financialCards['pendingMerchants'] ?? 0) }} pending</div>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg> Recent Orders</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Order #</th><th>Merchant</th><th>Courier</th><th>COD</th><th>4% Tax</th><th>Courier 2%</th><th>Our 2%</th><th>Charges</th><th>Profit</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($recentOrders as $order)
                  <tr>
                    <td><b>#{{ $order->id }}</b></td>
                    <td>{{ $order->user->brand_name ?? $order->user->name ?? '—' }}</td>
                    <td>{{ $order->courier_integration->courier_name ?? '—' }}</td>
                    <td>Rs {{ number_format($order->cod_amount ?? 0) }}</td>
                    <td>Rs {{ number_format(round(($order->cod_amount ?? 0) * 0.04)) }}</td>
                    <td>Rs {{ number_format(round(($order->delivery_charges ?? 0) * 0.02)) }}</td>
                    <td>Rs {{ number_format(round(($order->cod_amount ?? 0) * 0.02)) }}</td>
                    <td>Rs {{ number_format($order->delivery_charges ?? 0) }}</td>
                    <td class="{{ ($order->profit ?? 0) >= 0 ? 'pos' : 'neg' }}">{{ ($order->profit ?? 0) != 0 ? 'Rs '.number_format($order->profit) : '—' }}</td>
                    <td><span class="erp-badge erp-badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'returned' ? 'danger' : ($order->status === 'dispatched' ? 'info' : 'warning')) }}">{{ \App\Models\Booking::getStatusLabel($order->status) }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="10" style="text-align:center;color:var(--erp-text-muted);padding:28px">No recent orders found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== ORDERS ==================== -->
        <div class="erp-page" id="erp-page-orders">
          <div class="erp-filter-bar">
            <button class="erp-filter-btn active" onclick="erpFilterOrders('all', this)">All</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('pending', this)">Booked</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('dispatched', this)">Dispatched</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('delivered', this)">Delivered</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('in_transit', this)">In Transit</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('returned', this)">Returned</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('issue', this)">Issue</button>
          </div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Order #</th><th>Merchant</th><th>City</th><th>Courier</th><th>COD</th><th>4% Tax</th><th>Courier 2%</th><th>Our 2%</th><th>Delivery</th><th>Profit</th><th>Status</th></tr></thead>
                <tbody id="erpOrdersTableBody">
                  @forelse($allOrders as $o)
                  <tr>
                    <td><b>#{{ $o['id'] }}</b></td>
                    <td>{{ $o['merchant'] }}</td>
                    <td>{{ $o['city'] }}</td>
                    <td>{{ $o['courier'] }}</td>
                    <td>Rs {{ number_format($o['cod_amount']) }}</td>
                    <td>Rs {{ number_format($o['tax_4percent']) }}</td>
                    <td>Rs {{ number_format($o['courier_2percent']) }}</td>
                    <td>Rs {{ number_format($o['our_2percent']) }}</td>
                    <td>Rs {{ number_format($o['delivery_charge']) }}</td>
                    <td class="{{ $o['profit'] >= 0 ? 'pos' : 'neg' }}">{{ $o['profit'] != 0 ? 'Rs '.number_format($o['profit']) : '—' }}</td>
                    <td><span class="erp-badge erp-badge-{{ str_contains($o['status_class'] ?? '', 'bg-s') ? 'success' : (str_contains($o['status_class'] ?? '', 'bg-d') ? 'danger' : (str_contains($o['status_class'] ?? '', 'bg-i') ? 'info' : 'warning')) }}">{{ $o['status_label'] }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="11" style="text-align:center;color:var(--erp-text-muted);padding:28px">No orders found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== COD & SETTLEMENT ==================== -->
        <div class="erp-page" id="erp-page-cod">
          <div class="erp-grid erp-grid-3" style="margin-bottom:18px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/></svg></div>
              <div class="erp-stat-label">Total COD to Pay</div>
              <div class="erp-stat-value neg">Rs {{ number_format($companyPosition['merchantPayables'] ?? 0) }}</div>
              <div class="erp-stat-sub">To merchants</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div>
              <div class="erp-stat-label">Courier Receivable</div>
              <div class="erp-stat-value pos">Rs {{ number_format($companyPosition['courierReceivables'] ?? 0) }}</div>
              <div class="erp-stat-sub">COD from couriers</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 21"/><line x1="16" y1="3" x2="16" y2="21"/></svg></div>
              <div class="erp-stat-label">Pending Settlements</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($financialCards['pendingSettlements'] ?? 0) }}</div>
              <div class="erp-stat-sub">Merchants</div>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/></svg> Merchant COD Settlement</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">Per Merchant — COD Payable</div></div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Delivered</th><th>Total COD</th><th>Delivery Charges</th><th>4% Tax</th><th>Net Payable</th><th>Courier Paid Us</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                  @forelse($activeMerchantsList as $m)
                  <tr>
                    <td><b>{{ $m['name'] }}</b></td>
                    <td>{{ number_format($m['delivered']) }}</td>
                    <td>Rs {{ number_format($m['total_cod']) }}</td>
                    <td>Rs {{ number_format($m['delivery_charges']) }}</td>
                    <td>Rs {{ number_format($m['tax_4percent']) }}</td>
                    <td class="pos">Rs {{ number_format($m['net_payable']) }}</td>
                    <td>Rs {{ number_format(round($m['total_cod'] * 0.98)) }}</td>
                    <td><span class="erp-badge erp-badge-warning">Pending</span></td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpPayMerchant({{ $m['id'] }})">Pay Now</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No merchant data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/></svg> Courier COD Received</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Courier</th><th>Delivered</th><th>Total COD Collected</th><th>Charges Deducted</th><th>2% Tax Deducted</th><th>Amount Remitted</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($couriers as $c)
                  @php
                    $cDelivered = \App\Models\Booking::where('courier_integration_id', $c->id)->where('status', \App\Models\Booking::STATUS_DELIVERED);
                    $cCod = $cDelivered->sum('cod_amount');
                    $cCharges = $cDelivered->sum('delivery_charges');
                    $cTax = round($cCod * 0.02);
                    $cRemitted = $cCod - $cCharges - $cTax;
                  @endphp
                  <tr>
                    <td><b>{{ $c->courier_name }}</b></td>
                    <td>{{ $cDelivered->count() }}</td>
                    <td>Rs {{ number_format($cCod) }}</td>
                    <td>Rs {{ number_format($cCharges) }}</td>
                    <td>Rs {{ number_format($cTax) }}</td>
                    <td class="pos">Rs {{ number_format(max(0, $cRemitted)) }}</td>
                    <td><span class="erp-badge {{ $c->is_active ? 'erp-badge-success' : 'erp-badge-neutral' }}">{{ $c->is_active ? 'Active' : 'Inactive' }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No courier data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== INVOICES ==================== -->
        <div class="erp-page" id="erp-page-invoices">
          <div class="erp-grid erp-grid-4" style="margin-bottom:18px">
            <div class="erp-stat"><div class="erp-stat-label">Total Invoices</div><div class="erp-stat-value">{{ number_format($invoiceStats['total'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Pending</div><div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($invoiceStats['pending'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Paid</div><div class="erp-stat-value pos">{{ number_format($invoiceStats['paid'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Overdue</div><div class="erp-stat-value neg">{{ number_format($invoiceStats['overdue'] ?? 0) }}</div></div>
          </div>

          <div class="erp-info-bar erp-info-bar-info">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Invoice cycle: every 3 days — sirf delivered orders included hote hain. Formula: COD − Delivery Charges − 4% Tax = Net Payable.
          </div>

          <div class="erp-card">
            <div class="erp-card-header">
              <div class="erp-card-header-title">Invoice List — 3-Day Cycle per Merchant</div>
              <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpGenerateInvoice()"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Generate Invoice</button>
            </div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Invoice #</th><th>Merchant</th><th>Period</th><th>Delivered</th><th>COD</th><th>Charges</th><th>4% Tax</th><th>Net Payable</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                  @forelse($invoices as $inv)
                  <tr>
                    <td><b>{{ $inv['invoice_number'] }}</b></td>
                    <td>{{ $inv['merchant'] }}</td>
                    <td>{{ $inv['period_start'] }}–{{ $inv['period_end'] }}</td>
                    <td>{{ number_format($inv['delivered_count']) }}</td>
                    <td>Rs {{ number_format($inv['total_cod']) }}</td>
                    <td>Rs {{ number_format($inv['delivery_charges']) }}</td>
                    <td>Rs {{ number_format($inv['tax_4percent']) }}</td>
                    <td class="pos"><b>Rs {{ number_format($inv['net_payable']) }}</b></td>
                    <td><span class="erp-badge {{ $inv['status'] === 'paid' ? 'erp-badge-success' : ($inv['status'] === 'unpaid' ? 'erp-badge-warning' : 'erp-badge-danger') }}">{{ ucfirst($inv['status']) }}</span></td>
                    <td>
                      <button class="erp-btn erp-btn-sm" onclick="erpToast('PDF downloaded!')"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> PDF</button>
                      <button class="erp-btn erp-btn-success erp-btn-sm" onclick="erpToast('WhatsApp message bheja!')"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> WA</button>
                      @if($inv['status'] !== 'paid')
                      <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpMarkInvoicePaid({{ $inv['id'] }})">Mark Paid</button>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="10" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No invoices found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== MERCHANTS ==================== -->
        <div class="erp-page" id="erp-page-merchants">
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Pending Approval</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">New Merchant Requests <span class="erp-badge erp-badge-warning" style="margin-left:6px">{{ count($pendingMerchantsList) }} pending</span></div></div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Business</th><th>City</th><th>Plan</th><th>Phone</th><th>Joined</th><th>Actions</th></tr></thead>
                <tbody id="erpPendingMerchants">
                  @forelse($pendingMerchantsList as $m)
                  <tr id="merchant-row-{{ $m['id'] }}">
                    <td><b>{{ $m['name'] }}</b></td>
                    <td>{{ $m['business'] }}</td>
                    <td>{{ $m['city'] }}</td>
                    <td><span class="erp-badge erp-badge-warning">{{ $m['plan'] }}</span></td>
                    <td>{{ $m['phone'] }}</td>
                    <td style="color:var(--erp-text-secondary)">{{ $m['joined'] }}</td>
                    <td>
                      <button class="erp-btn erp-btn-success erp-btn-sm" onclick="erpApproveMerchant({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Approve</button>
                      <button class="erp-btn erp-btn-danger erp-btn-sm" onclick="erpRejectMerchant({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Reject</button>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No pending merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Active Merchants — Finance Summary</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Plan</th><th>Dispatched</th><th>Delivered</th><th>Returned</th><th>Total COD</th><th>Charges</th><th>4% Tax</th><th>Net Payable</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="erpActiveMerchants">
                  @forelse($activeMerchantsList as $m)
                  <tr id="active-merchant-{{ $m['id'] }}">
                    <td><b>{{ $m['name'] }}</b></td>
                    <td><span class="erp-badge erp-badge-info">{{ $m['plan'] }}</span></td>
                    <td>{{ number_format($m['dispatched']) }}</td>
                    <td class="pos">{{ number_format($m['delivered']) }}</td>
                    <td class="neg">{{ number_format($m['returned']) }}</td>
                    <td>Rs {{ number_format($m['total_cod']) }}</td>
                    <td>Rs {{ number_format($m['delivery_charges']) }}</td>
                    <td>Rs {{ number_format($m['tax_4percent']) }}</td>
                    <td class="pos">Rs {{ number_format($m['net_payable']) }}</td>
                    <td><span class="erp-badge {{ $m['is_suspended'] ? 'erp-badge-danger' : 'erp-badge-success' }}">{{ $m['is_suspended'] ? 'Suspended' : 'Active' }}</span></td>
                    <td>
                      <button class="erp-btn erp-btn-sm" onclick="erpToast('View merchant')">View</button>
                      <button class="erp-btn {{ $m['is_suspended'] ? 'erp-btn-success' : 'erp-btn-danger' }} erp-btn-sm" onclick="erpSuspendMerchant({{ $m['id'] }})">{{ $m['is_suspended'] ? 'Reactivate' : 'Suspend' }}</button>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="11" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No active merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg> Custom Return Charges</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">Per Merchant Return Rate <span style="font-size:11px;color:var(--erp-text-muted);font-weight:400">— Default: Basic=120, Gold=110, VIP=90</span></div></div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Plan</th><th>Default Return Rate</th><th>Custom Rate (Rs)</th><th>Action</th></tr></thead>
                <tbody id="erpReturnCharges">
                  @forelse($activeMerchantsList as $m)
                  @php $defaultReturn = $m['plan'] === 'VIP' ? 90 : ($m['plan'] === 'Gold' ? 110 : 120); @endphp
                  <tr>
                    <td><b>{{ $m['name'] }}</b></td>
                    <td><span class="erp-badge erp-badge-info">{{ $m['plan'] }}</span></td>
                    <td>Rs {{ $defaultReturn }}</td>
                    <td><input type="number" class="erp-input" id="return-input-{{ $m['id'] }}" value="{{ $defaultReturn }}" style="width:96px"></td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSaveReturnCharge({{ $m['id'] }})">Save</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="5" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== COURIERS ==================== -->
        <div class="erp-page" id="erp-page-couriers">
          <div class="erp-info-bar erp-info-bar-warning">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            Profit = Merchant rate − Courier rate · Sirf dispatched orders pe profit count hoga · Courier 2% tax + delivery charges kat ke COD bheji ga
          </div>

          <div class="erp-card">
            <div class="erp-card-header">
              <div class="erp-card-header-title">Courier Management — Rate Edit & Profit</div>
              <span id="erpCourierSaved" style="display:none;font-size:12px;color:var(--erp-success);font-weight:600"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Saved!</span>
            </div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Courier</th><th>Status</th><th>Courier Rate (Rs)</th><th>Merchant Rate (Rs)</th><th>Profit/Order</th><th>Dispatched</th><th>Total Profit</th><th>ON/OFF</th><th>Save</th></tr></thead>
                <tbody id="erpCourierTable">
                  @forelse($courierData as $c)
                  @php
                    $cRate = floatval($c['courier_rate'] ?? 0);
                    $mRate = floatval($c['merchant_rate'] ?? 0);
                    $profitPer = $mRate - $cRate;
                    $totalP = $c['is_active'] ? ($profitPer * $c['dispatched']) : 0;
                  @endphp
                  <tr id="courier-row-{{ $c['id'] }}">
                    <td><b>{{ $c['name'] }}</b></td>
                    <td><span class="erp-badge {{ $c['is_active'] ? 'erp-badge-success' : 'erp-badge-neutral' }}">{{ $c['is_active'] ? 'Active' : 'Off' }}</span></td>
                    <td><input type="number" step="5" class="erp-input" id="crate-{{ $c['id'] }}" value="{{ $cRate }}" style="width:96px"></td>
                    <td><input type="number" step="5" class="erp-input" id="mrate-{{ $c['id'] }}" value="{{ $mRate }}" style="width:96px"></td>
                    <td><span class="erp-badge {{ $profitPer >= 0 ? 'erp-badge-success' : 'erp-badge-danger' }}">{{ $profitPer >= 0 ? '+' : '' }}Rs {{ number_format($profitPer) }}</span></td>
                    <td><b>{{ number_format($c['dispatched']) }}</b> <span style="font-size:11px;color:var(--erp-text-muted)">dispatched</span></td>
                    <td class="{{ $totalP >= 0 ? 'pos' : 'neg' }}">{{ $c['is_active'] ? 'Rs '.number_format($totalP) : '—' }}</td>
                    <td>
                      <button class="erp-toggle {{ $c['is_active'] ? 'on' : 'off' }}" onclick="erpToggleCourier({{ $c['id'] }})" aria-label="Toggle"></button>
                    </td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSaveCourier({{ $c['id'] }})">Save</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No couriers configured</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== OVERALL SALES ==================== -->
        <div class="erp-page" id="erp-page-overall-sales">
          <div class="erp-info-bar erp-info-bar-info">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <b>Overall Sales</b> — Sabhi couriers ke delivered orders ka summary. Formula: COD − Delivery Charges − 4% Tax = Net Payable. Profit = Delivery Charges − Courier Cost.
          </div>

          <!-- Summary Cards -->
          <div class="erp-grid erp-grid-4" style="margin-bottom:18px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
              <div class="erp-stat-label">Total Delivered Orders</div>
              <div class="erp-stat-value" style="color:var(--erp-success)">{{ number_format($overallSalesSummary['delivered_count'] ?? 0) }}</div>
              <div class="erp-stat-sub">All couriers combined</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-primary-light);color:var(--erp-primary)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
              <div class="erp-stat-label">Total COD Amount</div>
              <div class="erp-stat-value">Rs {{ number_format($overallSalesSummary['delivered_amount'] ?? 0) }}</div>
              <div class="erp-stat-sub">Delivered COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div>
              <div class="erp-stat-label">Gross Profit</div>
              <div class="erp-stat-value pos">Rs {{ number_format($overallSalesSummary['gross_profit'] ?? 0) }}</div>
              <div class="erp-stat-sub">Charges − Cost</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/></svg></div>
              <div class="erp-stat-label">4% Tax</div>
              <div class="erp-stat-value">Rs {{ number_format($overallSalesSummary['tax_4percent'] ?? 0) }}</div>
              <div class="erp-stat-sub">On delivered COD</div>
            </div>
          </div>

          <!-- Per Courier Breakdown -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/></svg> Per Courier — Delivered Orders Breakdown</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Courier</th><th>Delivered</th><th>Total COD</th><th>Delivery Charges</th><th>Courier Cost</th><th>Gross Profit</th><th>4% Tax</th><th>Courier 2%</th><th>Our 2%</th><th>Net Payable</th></tr></thead>
                <tbody>
                  @forelse($overallCourierCounts as $oc)
                  <tr>
                    <td><b>{{ $oc['name'] }}</b></td>
                    <td class="pos">{{ number_format($oc['delivered']) }}</td>
                    <td>Rs {{ number_format($oc['cod_amount']) }}</td>
                    <td>Rs {{ number_format($oc['delivery_charges']) }}</td>
                    <td>Rs {{ number_format($oc['courier_cost']) }}</td>
                    <td class="{{ $oc['gross_profit'] >= 0 ? 'pos' : 'neg' }}">Rs {{ number_format($oc['gross_profit']) }}</td>
                    <td>Rs {{ number_format($oc['tax_4percent']) }}</td>
                    <td>Rs {{ number_format($oc['courier_2percent']) }}</td>
                    <td>Rs {{ number_format($oc['our_2percent']) }}</td>
                    <td class="pos"><b>Rs {{ number_format($oc['net_payable']) }}</b></td>
                  </tr>
                  @empty
                  <tr><td colspan="10" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No overall sales data available</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Net Profit Summary -->
          <div class="erp-fin-widget" style="margin-top:18px">
            <div class="erp-fin-title" style="margin-bottom:12px">Overall Sales — Net Profit Summary</div>
            <div class="erp-fin-row"><span>Total Delivery Charges Collected</span><span>Rs {{ number_format($overallSalesSummary['delivery_charges'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Total Courier Cost</span><span class="neg">− Rs {{ number_format($overallSalesSummary['courier_cost'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span style="font-weight:700">Gross Profit</span><span class="pos">Rs {{ number_format($overallSalesSummary['gross_profit'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Our 2% Tax (Remaining)</span><span class="neg">− Rs {{ number_format(round(($overallSalesSummary['tax_4percent'] ?? 0) / 2)) }}</span></div>
            <div class="erp-fin-row"><span style="font-weight:700;color:var(--erp-primary-dark)">Net Profit</span><span class="pos" style="font-size:17px">Rs {{ number_format($overallSalesSummary['net_profit'] ?? 0) }}</span></div>
          </div>
        </div>

        <!-- ==================== PRICING PLANS ==================== -->
        <div class="erp-page" id="erp-page-pricing">
          <div class="erp-grid-3" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:18px">
            @foreach($pricingPlans as $plan)
            <div class="erp-plan-card {{ strtolower($plan['name']) === 'vip' ? 'vip' : '' }}">
              <div class="erp-plan-header">
                {{ $plan['name'] }} Plan
                @if(strtolower($plan['name']) === 'basic')
                  <span class="erp-badge erp-badge-success">Default</span>
                @elseif(strtolower($plan['name']) === 'vip')
                  <span class="erp-badge erp-badge-info">Premium</span>
                @else
                  <span class="erp-badge erp-badge-warning">Custom</span>
                @endif
              </div>
              <table class="erp-plan-table">
                <thead><tr><th>City</th><th>Forward Rate (Rs)</th><th>Return Rate (Rs)</th></tr></thead>
                <tbody>
                  <tr><td>Karachi</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-khi-f" value="{{ $plan['base_delivery_charge'] ?? 180 }}" style="width:85px"></td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-khi-r" value="{{ ($plan['base_delivery_charge'] ?? 180) - 60 }}" style="width:85px"></td></tr>
                  <tr><td>Lahore</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-lhr-f" value="{{ ($plan['base_delivery_charge'] ?? 220) + 40 }}" style="width:85px"></td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-lhr-r" value="{{ ($plan['base_delivery_charge'] ?? 220) - 70 }}" style="width:85px"></td></tr>
                  <tr><td>Islamabad</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-isb-f" value="{{ ($plan['base_delivery_charge'] ?? 240) + 60 }}" style="width:85px"></td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-isb-r" value="{{ ($plan['base_delivery_charge'] ?? 240) - 80 }}" style="width:85px"></td></tr>
                  <tr><td>Other</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-oth-f" value="{{ ($plan['base_delivery_charge'] ?? 260) + 80 }}" style="width:85px"></td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-oth-r" value="{{ ($plan['base_delivery_charge'] ?? 260) - 90 }}" style="width:85px"></td></tr>
                </tbody>
              </table>
              <button class="erp-btn erp-btn-primary" onclick="erpSavePlan({{ $plan['id'] }})" style="margin-top:14px;width:100%;justify-content:center">Save {{ $plan['name'] }} Plan</button>
            </div>
            @endforeach
          </div>
        </div>

        <!-- ==================== PROFIT REPORT ==================== -->
        <div class="erp-page" id="erp-page-profit">
          <div class="erp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
            <!-- Profit Engine -->
            <div class="erp-fin-widget" style="margin:0">
              <div class="erp-fin-title" style="margin-bottom:12px">Profit Engine</div>
              <div class="erp-fin-row"><span style="color:var(--erp-text-secondary)">Merchant Delivery Revenue</span><span>Rs {{ number_format(($financialCards['grossProfit'] ?? 0) + ($financialCards['courierTax2'] ?? 0)) }}</span></div>
              <div class="erp-fin-row"><span style="color:var(--erp-text-secondary)">Courier Cost (Actual)</span><span class="neg">− Rs {{ number_format(($financialCards['courierTax2'] ?? 0)) }}</span></div>
              <div class="erp-fin-row"><span style="font-weight:700">Gross Profit</span><span class="pos">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</span></div>
              <div class="erp-fin-row"><span style="color:var(--erp-text-secondary)">Our 2% Tax (Remaining)</span><span class="neg">− Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</span></div>
              <div class="erp-fin-row"><span style="font-weight:700">Net Profit</span><span class="pos" style="font-size:17px">Rs {{ number_format($financialCards['netProfit'] ?? 0) }}</span></div>
            </div>

            <!-- Per Courier Profit -->
            <div class="erp-fin-widget" style="margin:0">
              <div class="erp-fin-title" style="margin-bottom:12px">Per Courier Profit</div>
              @foreach($courierProfits as $cp)
              <div class="erp-fin-row"><span>{{ $cp['name'] }} ({{ $cp['dispatched'] }} dispatched)</span><span class="pos">Rs {{ number_format($cp['profit']) }}</span></div>
              @endforeach
              <div class="erp-fin-row"><span style="font-weight:700">Total</span><span class="pos" style="font-size:16px">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</span></div>
            </div>
          </div>

          <!-- Per Merchant Profit -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Per Merchant Profit Analysis</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Plan</th><th>Dispatched</th><th>Total COD</th><th>4% Tax</th><th>Courier 2%</th><th>Our 2%</th><th>Delivery Charges</th><th>Net Profit</th></tr></thead>
                <tbody>
                  @forelse($merchantProfitData as $mp)
                  <tr>
                    <td><b>{{ $mp['name'] }}</b></td>
                    <td><span class="erp-badge erp-badge-info">{{ $mp['plan'] }}</span></td>
                    <td>{{ number_format($mp['dispatched']) }}</td>
                    <td>Rs {{ number_format($mp['total_cod']) }}</td>
                    <td>Rs {{ number_format($mp['tax_4percent']) }}</td>
                    <td>Rs {{ number_format($mp['courier_2percent']) }}</td>
                    <td>Rs {{ number_format($mp['our_2percent']) }}</td>
                    <td>Rs {{ number_format($mp['delivery_charges']) }}</td>
                    <td class="pos">Rs {{ number_format($mp['net_profit']) }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No profit data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== TAX ENGINE ==================== -->
        <div class="erp-page" id="erp-page-tax">
          <div class="erp-info-bar erp-info-bar-info">
            <b>Tax Formula:</b> COD × 4% = Total Tax · Courier deducts 2% before remitting · We collect 4% from merchant but courier already took 2% — our remaining liability = 2%
          </div>

          <div class="erp-grid-4" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:18px">
            <div class="erp-stat"><div class="erp-stat-label">4% Collected (Merchants)</div><div class="erp-stat-value">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">2% Courier Deducted</div><div class="erp-stat-value neg">Rs {{ number_format($financialCards['courierTax2'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Our 2% Balance</div><div class="erp-stat-value" style="color:var(--erp-warning)">Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Govt Payable</div><div class="erp-stat-value neg">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div></div>
          </div>

          <!-- Tax Calculator -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/></svg> Live Tax Calculator</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">Enter COD Amount — Auto Calculate</div></div>
            <div class="erp-card-body" style="padding:18px">
              <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <div class="erp-form-group" style="width:auto"><label>COD Amount (Rs)</label><input type="number" id="erpCodInput" value="1000" style="width:130px" oninput="erpCalcTax()"></div>
                <div style="display:flex;gap:24px;flex-wrap:wrap;font-size:13px;align-items:center">
                  <span>4% Tax: <b id="erpTax4" style="color:var(--erp-warning)">Rs 40</b></span>
                  <span>Courier 2%: <b id="erpTax2Courier" class="neg">Rs 20</b></span>
                  <span>Our 2%: <b id="erpTax2Our" style="color:var(--erp-warning)">Rs 20</b></span>
                  <span>Net to Merchant: <b id="erpTaxNet" class="pos">Rs 960</b></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Tax Register -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M6 8h4"/><path d="M6 12h4"/><path d="M6 16h4"/></svg> Tax Register — Per Order</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Order #</th><th>Merchant</th><th>COD</th><th>4% Tax</th><th>Courier 2%</th><th>Our 2%</th><th>Paid to Govt</th></tr></thead>
                <tbody>
                  @forelse($taxRegister as $t)
                  <tr>
                    <td><b>#{{ $t['id'] }}</b></td>
                    <td>{{ $t['merchant'] }}</td>
                    <td>Rs {{ number_format($t['cod']) }}</td>
                    <td>Rs {{ number_format($t['tax_4percent']) }}</td>
                    <td>Rs {{ number_format($t['courier_2percent']) }}</td>
                    <td>Rs {{ number_format($t['our_2percent']) }}</td>
                    <td><span class="erp-badge {{ $t['paid_to_govt'] ? 'erp-badge-success' : 'erp-badge-warning' }}">{{ $t['paid_to_govt'] ? 'Yes' : 'No' }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No tax data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== NOTIFICATIONS ==================== -->
        <div class="erp-page" id="erp-page-notif">
          <div class="erp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
            <!-- WhatsApp -->
            <div class="erp-card" style="margin:0">
              <div class="erp-card-header"><div class="erp-card-header-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--erp-success)" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> WhatsApp</div></div>
              <div class="erp-card-body" style="padding:16px;display:flex;flex-direction:column;gap:10px">
                <div class="erp-form-group"><label>Merchant</label><select id="erpWaMerchant"><option value="all">All Merchants</option>@foreach($allMerchants as $m)<option value="{{ $m->id }}">{{ $m->brand_name ?? $m->name }}</option>@endforeach</select></div>
                <div class="erp-form-group"><label>Type</label><select id="erpWaType"><option>Invoice Generated</option><option>Settlement Paid</option><option>Order Delivered</option><option>Custom Message</option></select></div>
                <div class="erp-form-group"><label>Message</label><textarea id="erpWaMessage">Assalamualaikum,\n\nAapka invoice generate ho gaya hai.\n\nNet payable: Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}\n\nShukriya 🙏</textarea></div>
                <button class="erp-btn erp-btn-success" onclick="erpSendNotif('whatsapp')">📱 Send WhatsApp</button>
              </div>
            </div>

            <!-- Website Notification -->
            <div class="erp-card" style="margin:0">
              <div class="erp-card-header"><div class="erp-card-header-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--erp-primary)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> Website Notification</div></div>
              <div class="erp-card-body" style="padding:16px;display:flex;flex-direction:column;gap:10px">
                <div class="erp-form-group"><label>Merchant</label><select id="erpWebMerchant"><option value="all">All Merchants</option>@foreach($allMerchants as $m)<option value="{{ $m->id }}">{{ $m->brand_name ?? $m->name }}</option>@endforeach</select></div>
                <div class="erp-form-group"><label>Type</label><select id="erpWebType"><option>New Invoice</option><option>Settlement Paid</option><option>Order Update</option><option>Issue Created</option><option>Custom</option></select></div>
                <div class="erp-form-group"><label>Message</label><textarea id="erpWebMessage" placeholder="Notification text likhein..."></textarea></div>
                <button class="erp-btn erp-btn-primary" onclick="erpSendNotif('website')">📢 Send Notification</button>
              </div>
            </div>
          </div>

          <!-- Notification History -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Notification History</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Time</th><th>Merchant</th><th>Type</th><th>Message</th><th>Channel</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($notifHistory as $n)
                  <tr>
                    <td style="white-space:nowrap">{{ isset($n['time']) ? date('M d, H:i', strtotime($n['time'])) : '—' }}</td>
                    <td>{{ $n['merchant'] }}</td>
                    <td>{{ $n['type'] }}</td>
                    <td>{{ $n['message'] }}</td>
                    <td><span class="erp-badge {{ $n['channel'] === 'WhatsApp' ? 'erp-badge-success' : 'erp-badge-info' }}">{{ $n['channel'] }}</span></td>
                    <td><span class="erp-badge {{ $n['status'] === 'sent' ? 'erp-badge-success' : ($n['status'] === 'Seen' ? 'erp-badge-info' : 'erp-badge-warning') }}">{{ ucfirst($n['status']) }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="6" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No notification history</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Toast Notification -->
  <div class="erp-toast" id="erpToast"></div>
</div>

<script>
// ==================== NAVIGATION ====================
function erpNavigate(page, el) {
  document.querySelectorAll('.erp-page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.erp-nav-item').forEach(n => n.classList.remove('active'));
  const target = document.getElementById('erp-page-' + page);
  if (target) target.classList.add('active');
  if (el) el.classList.add('active');

  const titles = {
    'dashboard': 'Dashboard', 'orders': 'Orders', 'cod': 'COD & Settlement',
    'invoices': 'Invoices', 'merchants': 'Merchants', 'couriers': 'Couriers',
    'overall-sales': 'Overall Sales', 'pricing': 'Pricing Plans', 'profit': 'Profit Report', 'tax': 'Tax Engine', 'notif': 'Notifications
  };
  document.getElementById('erpPageTitle').textContent = titles[page] || page;

  // Show/hide time filters (only for dashboard)
  const filters = document.getElementById('erpTimeFilters');
  if (filters) filters.style.display = page === 'dashboard' ? '' : 'none';

  if (page === 'tax') setTimeout(() => erpCalcTax(), 100);
}

function erpSetFilter(f, el) {
  el.closest('.erp-filter-bar').querySelectorAll('.erp-filter-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
}

// ==================== TOAST ====================
function erpToast(msg, type) {
  const t = document.getElementById('erpToast');
  t.textContent = msg;
  t.className = 'erp-toast show ' + (type === 'error' ? 'erp-toast-error' : 'erp-toast-success');
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 2500);
}

// ==================== ORDERS FILTER ====================
function erpFilterOrders(status, el) {
  el.closest('.erp-filter-bar').querySelectorAll('.erp-filter-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');

  fetch('/api/admin/orders/filter?status=' + status)
    .then(r => r.json())
    .then(d => {
      if (!d.success || !d.data || d.data.length === 0) {
        document.getElementById('erpOrdersTableBody').innerHTML = '<tr><td colspan="11" style="text-align:center;padding:28px;color:var(--erp-text-muted)">No orders found</td></tr>';
        return;
      }
      document.getElementById('erpOrdersTableBody').innerHTML = d.data.map(o => {
        const statusBadgeClass = o.status === 'delivered' ? 'erp-badge-success' : (o.status === 'returned' ? 'erp-badge-danger' : (o.status === 'dispatched' ? 'erp-badge-info' : 'erp-badge-warning'));
        const profitClass = o.profit >= 0 ? 'pos' : 'neg';
        const profitVal = o.profit != 0 ? 'Rs ' + Number(o.profit).toLocaleString() : '—';
        return `<tr>
          <td><b>#${o.id}</b></td><td>${o.merchant}</td><td>${o.city}</td><td>${o.courier}</td>
          <td>Rs ${Number(o.cod_amount).toLocaleString()}</td>
          <td>Rs ${Number(o.tax_4percent).toLocaleString()}</td>
          <td>Rs ${Number(o.courier_2percent).toLocaleString()}</td>
          <td>Rs ${Number(o.our_2percent).toLocaleString()}</td>
          <td>Rs ${Number(o.delivery_charge).toLocaleString()}</td>
          <td class="${profitClass}">${profitVal}</td>
          <td><span class="erp-badge ${statusBadgeClass}">${o.status_label}</span></td>
        </tr>`;
      }).join('');
    })
    .catch(() => erpToast('Error loading orders', 'error'));
}

// ==================== MERCHANT ACTIONS ====================
function erpApproveMerchant(id) {
  fetch('/api/admin/merchant/approve', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        const row = document.getElementById('merchant-row-' + id);
        if (row) row.remove();
        erpToast(d.message);
      } else erpToast(d.message || 'Error', 'error');
    })
    .catch(() => erpToast('Error approving merchant', 'error'));
}

function erpRejectMerchant(id) {
  fetch('/api/admin/merchant/reject', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        const row = document.getElementById('merchant-row-' + id);
        if (row) row.remove();
        erpToast(d.message);
      }
    })
    .catch(() => erpToast('Error rejecting merchant', 'error'));
}

function erpSuspendMerchant(id) {
  fetch('/api/admin/merchant/suspend', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        erpToast(d.message);
        setTimeout(() => location.reload(), 800);
      }
    })
    .catch(() => erpToast('Error updating merchant', 'error'));
}

function erpSaveReturnCharge(id) {
  const val = document.getElementById('return-input-' + id).value;
  fetch('/api/admin/merchant/return-charge', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id, return_charge: val })
  })
    .then(r => r.json())
    .then(d => { if (d.success) erpToast(d.message); })
    .catch(() => erpToast('Error saving', 'error'));
}

// ==================== COURIER ACTIONS ====================
function erpToggleCourier(id) {
  fetch('/api/admin/courier/toggle', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        erpToast(d.message);
        setTimeout(() => location.href = location.href, 800);
      }
    })
    .catch(() => erpToast('Error toggling courier', 'error'));
}

function erpSaveCourier(id) {
  const crate = document.getElementById('crate-' + id).value;
  const mrate = document.getElementById('mrate-' + id).value;
  fetch('/api/admin/courier/save-rates', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id, courier_rate: crate, merchant_rate: mrate })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        const el = document.getElementById('erpCourierSaved');
        el.style.display = 'inline-block';
        erpToast(d.message);
        setTimeout(() => el.style.display = 'none', 2000);
      }
    })
    .catch(() => erpToast('Error saving rates', 'error'));
}

// ==================== SETTLEMENT / PAYMENT ====================
function erpPayMerchant(id) {
  fetch('/api/admin/settlement/pay', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ user_id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) erpToast(d.message);
      else erpToast(d.message, 'error');
    })
    .catch(() => erpToast('Error processing payment', 'error'));
}

// ==================== INVOICE ACTIONS ====================
function erpGenerateInvoice() {
  const uid = prompt('Enter Merchant ID for invoice generation:');
  if (!uid) return;
  const start = prompt('Period Start Date (YYYY-MM-DD):', '{{ \Carbon\Carbon::now()->subDays(3)->toDateString() }}');
  const end = prompt('Period End Date (YYYY-MM-DD):', '{{ \Carbon\Carbon::now()->toDateString() }}');
  fetch('/api/admin/invoice/generate', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ user_id: parseInt(uid), period_start: start, period_end: end })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 1000); }
      else erpToast(d.message, 'error');
    })
    .catch(() => erpToast('Error generating invoice', 'error'));
}

function erpMarkInvoicePaid(id) {
  fetch('/api/admin/invoice/mark-paid', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id: id })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 800); }
    })
    .catch(() => erpToast('Error marking paid', 'error'));
}

// ==================== PRICING PLANS ====================
function erpSavePlan(planId) {
  fetch('/api/admin/pricing/save', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ plan_id: planId })
  })
    .then(r => r.json())
    .then(d => { if (d.success) erpToast(d.message); })
    .catch(() => erpToast('Error saving plan', 'error'));
}

// ==================== NOTIFICATIONS ====================
function erpSendNotif(channel) {
  const merchantEl = channel === 'whatsapp' ? document.getElementById('erpWaMerchant') : document.getElementById('erpWebMerchant');
  const typeEl = channel === 'whatsapp' ? document.getElementById('erpWaType') : document.getElementById('erpWebType');
  const msgEl = channel === 'whatsapp' ? document.getElementById('erpWaMessage') : document.getElementById('erpWebMessage');

  const msg = (msgEl.value || '').trim();
  if (!msg) { erpToast('Please enter a message', 'error'); return; }

  fetch('/api/admin/notification/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({
      send_to: merchantEl.value,
      channel: channel,
      type: typeEl.value,
      notification_type: typeEl.value,
      subject: typeEl.value,
      message: msg
    })
  })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        erpToast(d.message);
        msgEl.value = '';
        setTimeout(() => location.reload(), 1500);
      } else erpToast(d.message || 'Error', 'error');
    })
    .catch(() => erpToast('Error sending notification', 'error'));
}

// ==================== TAX CALCULATOR ====================
function erpCalcTax() {
  const cod = +(document.getElementById('erpCodInput').value) || 0;
  const t4 = Math.round(cod * 0.04);
  const t2c = Math.round(t4 / 2);
  const t2o = t4 - t2c;
  const net = cod - t4;
  document.getElementById('erpTax4').textContent = 'Rs ' + t4.toLocaleString();
  document.getElementById('erpTax2Courier').textContent = 'Rs ' + t2c.toLocaleString();
  document.getElementById('erpTax2Our').textContent = 'Rs ' + t2o.toLocaleString();
  document.getElementById('erpTaxNet').textContent = 'Rs ' + net.toLocaleString();
}

// Initialize
document.addEventListener('DOMContentLoaded', () => erpCalcTax());
</script>

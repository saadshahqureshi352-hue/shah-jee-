<style>
/* ============================================================
   SHAH JEE COURIER — PROFESSIONAL ADMIN DASHBOARD v2
   ============================================================ */
:root {
  --erp-bg: #f1f5f9;
  --erp-card-bg: #ffffff;
  --erp-border: #e2e8f0;
  --erp-border-light: #f1f5f9;
  --erp-primary: #7c3aed;
  --erp-primary-light: #f5f3ff;
  --erp-primary-soft: #ede9fe;
  --erp-primary-dark: #5b21b6;
  --erp-success: #10b981;
  --erp-success-light: #ecfdf5;
  --erp-success-soft: #d1fae5;
  --erp-warning: #f59e0b;
  --erp-warning-light: #fffbeb;
  --erp-warning-soft: #fef3c7;
  --erp-danger: #ef4444;
  --erp-danger-light: #fef2f2;
  --erp-danger-soft: #fee2e2;
  --erp-info: #3b82f6;
  --erp-info-light: #eff6ff;
  --erp-info-soft: #dbeafe;
  --erp-text: #0f172a;
  --erp-text-secondary: #64748b;
  --erp-text-muted: #94a3b8;
  --erp-text-light: #cbd5e1;
  --erp-radius: 14px;
  --erp-radius-sm: 10px;
  --erp-radius-xs: 7px;
  --erp-shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --erp-shadow: 0 3px 6px rgba(0,0,0,.07), 0 1px 3px rgba(0,0,0,.05);
  --erp-shadow-md: 0 6px 12px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.05);
  --erp-shadow-lg: 0 12px 24px rgba(0,0,0,.1), 0 4px 12px rgba(0,0,0,.06);
  --erp-transition: all .25s cubic-bezier(.4,0,.2,1);
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

.erp-layout { display: flex; min-height: 100vh; }

/* ---- Sidebar ---- */
.erp-sidebar {
  width: 250px; min-width: 250px;
  background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #334155 100%);
  display: flex; flex-direction: column;
  position: sticky; top: 0; height: 100vh;
  overflow-y: auto; z-index: 20;
  box-shadow: 2px 0 15px rgba(0,0,0,.12);
}

.erp-sidebar-logo {
  padding: 22px 20px; display: flex; align-items: center; gap: 12px;
  border-bottom: 1px solid rgba(255,255,255,.06);
}

.erp-logo-icon {
  width: 42px; height: 42px;
  background: linear-gradient(135deg, #a78bfa, #7c3aed);
  border-radius: 11px; display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 19px; font-weight: 700; flex-shrink: 0;
  box-shadow: 0 4px 14px rgba(124,58,237,.4);
}

.erp-logo-text { display: flex; flex-direction: column; gap: 1px; }
.erp-logo-name { font-size: 16px; font-weight: 700; color: #f1f5f9; letter-spacing: -.3px; line-height: 1.2; }
.erp-logo-sub { font-size: 11px; color: #94a3b8; font-weight: 400; }

.erp-nav-group { padding: 8px 0; }
.erp-nav-label {
  font-size: 10px; font-weight: 700; color: #64748b;
  padding: 10px 20px 4px; letter-spacing: 1.2px; text-transform: uppercase;
}

.erp-nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 16px; margin: 2px 10px;
  font-size: 13px; color: #94a3b8; cursor: pointer;
  border-radius: 10px; transition: var(--erp-transition);
  font-weight: 500; text-decoration: none;
  border: 1px solid transparent;
}

.erp-nav-item:hover { background: rgba(255,255,255,.06); color: #e2e8f0; border-color: rgba(255,255,255,.04); }
.erp-nav-item.active { background: linear-gradient(135deg, rgba(124,58,237,.2), rgba(139,92,246,.1)); color: #c4b5fd; font-weight: 600; border-color: rgba(124,58,237,.25); box-shadow: 0 4px 12px rgba(0,0,0,.15); }
.erp-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; opacity: .7; }
.erp-nav-item.active svg { opacity: 1; color: #8b5cf6; }
.erp-nav-item .erp-badge { margin-left: auto; }

.erp-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; min-width: 0; }

.erp-topbar {
  background: var(--erp-card-bg); border-bottom: 1px solid var(--erp-border);
  padding: 12px 28px; display: flex; align-items: center; gap: 12px;
  position: sticky; top: 0; z-index: 10; flex-wrap: wrap;
  box-shadow: var(--erp-shadow-sm);
  backdrop-filter: blur(8px);
}

.erp-topbar-title { font-size: 20px; font-weight: 700; color: var(--erp-text); letter-spacing: -.3px; white-space: nowrap; background: linear-gradient(135deg, var(--erp-primary), #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.erp-topbar-avatar {
  width: 38px; height: 38px; border-radius: 50%;
  background: linear-gradient(135deg, #7c3aed, #a78bfa); color: #fff;
  font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; box-shadow: 0 2px 8px rgba(124,58,237,.35);
}

.erp-content { flex: 1; overflow-y: auto; padding: 20px 24px 28px; }
.erp-page { display: none; animation: erpFadeIn .3s ease; }
.erp-page.active { display: block; }

@keyframes erpFadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

.erp-grid { display: grid; gap: 14px; }
.erp-grid-6 { grid-template-columns: repeat(6, 1fr); }
.erp-grid-5 { grid-template-columns: repeat(5, 1fr); }
.erp-grid-4 { grid-template-columns: repeat(4, 1fr); }
.erp-grid-3 { grid-template-columns: repeat(3, 1fr); }
.erp-grid-2 { grid-template-columns: 1fr 1fr; }

.erp-stat {
  background: var(--erp-card-bg); border-radius: var(--erp-radius);
  padding: 18px 20px; border: 1px solid var(--erp-border);
  box-shadow: var(--erp-shadow-sm); transition: var(--erp-transition); position: relative; overflow: hidden;
}
.erp-stat::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, var(--erp-primary), #a78bfa); opacity: 0; transition: opacity .25s ease; }
.erp-stat:hover { box-shadow: var(--erp-shadow-md); transform: translateY(-3px); border-color: #c4b5fd; }
.erp-stat:hover::before { opacity: 1; }

.erp-stat-icon {
  width: 40px; height: 40px; border-radius: 11px;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; margin-bottom: 12px;
}
.erp-stat-label { font-size: 12px; color: var(--erp-text-secondary); margin-bottom: 4px; display: flex; align-items: center; gap: 6px; font-weight: 500; }
.erp-stat-value { font-size: 24px; font-weight: 700; color: var(--erp-text); line-height: 1.2; letter-spacing: -.5px; }
.erp-stat-sub { font-size: 11px; color: var(--erp-text-muted); margin-top: 4px; font-weight: 400; }

.erp-fin-widget {
  background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 50%, #eef2ff 100%);
  border: 1px solid #c4b5fd; border-radius: var(--erp-radius);
  padding: 20px 24px; margin-bottom: 16px; box-shadow: var(--erp-shadow-sm); position: relative; overflow: hidden;
}
.erp-fin-widget::before { content: ''; position: absolute; top: -50%; right: -20%; width: 200px; height: 200px; border-radius: 50%; background: rgba(124,58,237,.04); pointer-events: none; }

.erp-fin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
.erp-fin-title { font-size: 13px; font-weight: 700; color: var(--erp-text-secondary); display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: .5px; }

.erp-fin-live { font-size: 11px; color: var(--erp-success); display: flex; align-items: center; gap: 5px; font-weight: 600; background: var(--erp-success-light); padding: 4px 12px; border-radius: 99px; }
.erp-fin-live-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--erp-success); animation: erpPulse 1.5s ease infinite; }

@keyframes erpPulse { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: .5; transform: scale(1.3); } }

.erp-fin-main { font-size: 30px; font-weight: 800; color: var(--erp-primary-dark); margin: 6px 0 16px; letter-spacing: -.8px; }
.erp-fin-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; padding: 7px 0; border-bottom: 1px solid rgba(0,0,0,.05); color: var(--erp-text-secondary); }
.erp-fin-row:last-child { border-bottom: none; }
.erp-fin-row span:last-child { font-weight: 500; color: var(--erp-text); }

.erp-card { background: var(--erp-card-bg); border: 1px solid var(--erp-border); border-radius: var(--erp-radius); box-shadow: var(--erp-shadow-sm); overflow: hidden; margin-bottom: 16px; transition: var(--erp-transition); }
.erp-card:hover { box-shadow: var(--erp-shadow); }
.erp-card-header { padding: 14px 18px; border-bottom: 1px solid var(--erp-border-light); background: linear-gradient(135deg, #fafbfc, #f8fafc); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
.erp-card-header-title { font-size: 14px; font-weight: 700; color: var(--erp-text); display: flex; align-items: center; gap: 8px; }
.erp-card-body { padding: 0; }

.erp-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
.erp-table th {
  padding: 11px 14px; text-align: left; font-weight: 600; color: var(--erp-text-secondary);
  font-size: 10.5px; text-transform: uppercase; letter-spacing: .4px;
  border-bottom: 2px solid var(--erp-border); background: linear-gradient(135deg, #f8fafc, #f1f5f9); white-space: nowrap;
}
.erp-table td { padding: 11px 14px; color: var(--erp-text); border-bottom: 1px solid var(--erp-border-light); vertical-align: middle; }
.erp-table tbody tr:last-child td { border-bottom: none; }
.erp-table tbody tr { transition: all .15s ease; }
.erp-table tbody tr:hover td { background: #f8fafc; }

.erp-badge { font-size: 10.5px; padding: 3px 10px; border-radius: 99px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap; }
.erp-badge-success { background: var(--erp-success-soft); color: #065f46; }
.erp-badge-warning { background: var(--erp-warning-soft); color: #92400e; }
.erp-badge-danger { background: var(--erp-danger-soft); color: #991b1b; }
.erp-badge-info { background: var(--erp-info-soft); color: #1e40af; }
.erp-badge-neutral { background: #f1f5f9; color: #64748b; }

.erp-btn {
  font-size: 11.5px; padding: 7px 14px; border-radius: var(--erp-radius-xs);
  border: 1px solid var(--erp-border); background: var(--erp-card-bg);
  color: var(--erp-text-secondary); cursor: pointer; display: inline-flex;
  align-items: center; gap: 5px; font-weight: 500; transition: var(--erp-transition); white-space: nowrap; line-height: 1;
}
.erp-btn:hover { background: #f1f5f9; border-color: #94a3b8; color: var(--erp-text); transform: translateY(-1px); }
.erp-btn-primary { background: linear-gradient(135deg, var(--erp-primary), #9333ea); color: #fff; border-color: transparent; box-shadow: 0 2px 6px rgba(124,58,237,.3); }
.erp-btn-primary:hover { background: linear-gradient(135deg, #6d28d9, #7c3aed); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(124,58,237,.4); }
.erp-btn-success { background: linear-gradient(135deg, var(--erp-success), #34d399); color: #fff; border-color: transparent; box-shadow: 0 2px 6px rgba(16,185,129,.3); }
.erp-btn-success:hover { background: linear-gradient(135deg, #059669, #10b981); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.4); }
.erp-btn-danger { background: linear-gradient(135deg, var(--erp-danger), #f87171); color: #fff; border-color: transparent; box-shadow: 0 2px 6px rgba(239,68,68,.3); }
.erp-btn-danger:hover { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,.4); }
.erp-btn-warning { background: linear-gradient(135deg, var(--erp-warning), #fbbf24); color: #fff; border-color: transparent; box-shadow: 0 2px 6px rgba(245,158,11,.3); }
.erp-btn-warning:hover { background: linear-gradient(135deg, #d97706, #f59e0b); color: #fff; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,158,11,.4); }
.erp-btn-sm { font-size: 10.5px; padding: 5px 10px; border-radius: 6px; }

.erp-filter-bar { display: flex; gap: 5px; margin-bottom: 12px; flex-wrap: wrap; }
.erp-filter-btn {
  font-size: 11.5px; padding: 6px 14px; border-radius: 99px;
  border: 1px solid var(--erp-border); background: var(--erp-card-bg);
  color: var(--erp-text-secondary); cursor: pointer; font-weight: 500; transition: var(--erp-transition);
}
.erp-filter-btn:hover { border-color: var(--erp-primary); color: var(--erp-primary); background: var(--erp-primary-light); }
.erp-filter-btn.active { background: linear-gradient(135deg, var(--erp-primary), #6366f1); color: #fff; border-color: transparent; font-weight: 600; box-shadow: 0 2px 8px rgba(79,70,229,.35); }

.erp-toggle {
  width: 44px; height: 24px; border-radius: 12px; border: none; cursor: pointer;
  position: relative; transition: background .25s ease; flex-shrink: 0;
}
.erp-toggle.on { background: var(--erp-success); box-shadow: 0 0 0 2px rgba(5,150,105,.2); }
.erp-toggle.off { background: #d1d5db; }
.erp-toggle::after {
  content: ''; position: absolute; top: 2px; width: 20px; height: 20px;
  border-radius: 50%; background: #fff; transition: left .25s ease; box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.erp-toggle.on::after { left: 22px; }
.erp-toggle.off::after { left: 2px; }

.erp-input {
  border: 1px solid var(--erp-border); border-radius: var(--erp-radius-xs);
  padding: 7px 10px; font-size: 12.5px; background: var(--erp-card-bg);
  color: var(--erp-text); width: 90px; text-align: right; transition: var(--erp-transition);
}
.erp-input:focus { outline: none; border-color: var(--erp-primary); box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
.erp-input-wide { width: 160px; text-align: left; }
.erp-input-search { width: 220px; text-align: left; }
.erp-input-date { width: 140px; text-align: left; }

.erp-toast {
  position: fixed; top: 24px; right: 24px; z-index: 9999;
  font-size: 13px; padding: 14px 22px; border-radius: var(--erp-radius-sm);
  font-weight: 600; box-shadow: 0 12px 32px rgba(0,0,0,.18);
  display: none; animation: erpSlideIn .3s ease; min-width: 200px;
}
.erp-toast.show { display: block; }
.erp-toast-success { background: #059669; color: #fff; }
.erp-toast-error { background: #dc2626; color: #fff; }

@keyframes erpSlideIn { from { opacity: 0; transform: translateY(-14px); } to { opacity: 1; transform: translateY(0); } }

.erp-section-title { font-size: 15px; font-weight: 700; color: var(--erp-text); margin-bottom: 12px; display: flex; align-items: center; gap: 8px; letter-spacing: -.2px; }

.erp-info-bar { font-size: 12px; padding: 10px 16px; border-radius: var(--erp-radius-sm); margin-bottom: 14px; display: flex; align-items: center; gap: 8px; line-height: 1.5; }
.erp-info-bar-info { background: var(--erp-info-light); color: #1e40af; border: 1px solid var(--erp-info-soft); }
.erp-info-bar-warning { background: var(--erp-warning-light); color: #92400e; border: 1px solid var(--erp-warning-soft); }
.erp-info-bar-success { background: var(--erp-success-light); color: #065f46; border: 1px solid var(--erp-success-soft); }

.erp-plan-card { background: var(--erp-card-bg); border: 1px solid var(--erp-border); border-radius: var(--erp-radius); padding: 18px; transition: var(--erp-transition); }
.erp-plan-card:hover { box-shadow: var(--erp-shadow-md); transform: translateY(-2px); }
.erp-plan-card.vip { border-color: #a5b4fc; background: linear-gradient(180deg, #eef2ff, #fff); }
.erp-plan-header { font-size: 15px; font-weight: 700; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: center; }

.erp-form-group { display: flex; flex-direction: column; gap: 4px; }
.erp-form-group label { font-size: 11.5px; font-weight: 600; color: var(--erp-text-secondary); }
.erp-form-group select, .erp-form-group textarea, .erp-form-group input[type="text"], .erp-form-group input[type="number"], .erp-form-group input[type="date"] {
  width: 100%; font-size: 12.5px; padding: 8px 12px; border: 1px solid var(--erp-border);
  border-radius: var(--erp-radius-sm); background: var(--erp-card-bg); color: var(--erp-text); transition: var(--erp-transition); font-family: var(--erp-font);
}
.erp-form-group select:focus, .erp-form-group textarea:focus, .erp-form-group input:focus { outline: none; border-color: var(--erp-primary); box-shadow: 0 0 0 3px rgba(79,70,229,.1); }

.pos { color: #059669; font-weight: 700; }
.neg { color: #dc2626; font-weight: 700; }

.erp-search-box { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; margin-bottom: 12px; }

.erp-plan-count { font-size: 11px; color: var(--erp-text-muted); font-weight: 400; }
.erp-select-sm { font-size: 11.5px; padding: 4px 8px; border-radius: 5px; border: 1px solid var(--erp-border); }
.erp-status-select { font-size: 11px; padding: 3px 6px; border-radius: 4px; border: 1px solid var(--erp-border); }

.erp-modal-overlay {
  display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,.5); z-index: 9998; justify-content: center; align-items: center;
}
.erp-modal-overlay.show { display: flex; }
.erp-modal {
  background: #fff; border-radius: 12px; padding: 24px; max-width: 700px;
  width: 90%; max-height: 80vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0,0,0,.3);
}
.erp-modal-title { font-size: 17px; font-weight: 700; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center; }
.erp-modal-close { cursor: pointer; font-size: 22px; color: var(--erp-text-muted); }
.erp-modal-close:hover { color: var(--erp-text); }

@media (max-width: 1400px) { .erp-grid-6 { grid-template-columns: repeat(3, 1fr); } .erp-grid-5 { grid-template-columns: repeat(3, 1fr); } .erp-grid-4 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 1100px) { .erp-sidebar { width: 210px; min-width: 210px; } .erp-grid-2, .erp-grid-3 { grid-template-columns: 1fr; } .erp-grid-6, .erp-grid-5 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) {
  .erp-layout { flex-direction: column; }
  .erp-sidebar { width: 100%; min-width: unset; height: auto; position: relative; flex-direction: row; flex-wrap: wrap; overflow-x: auto; background: #312e81; }
  .erp-sidebar-logo { display: none; } .erp-nav-group { display: none; }
  .erp-grid-6, .erp-grid-5, .erp-grid-4, .erp-grid-3, .erp-grid-2 { grid-template-columns: 1fr; }
  .erp-content { padding: 14px; } .erp-topbar { padding: 10px 14px; }
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
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg> Dashboard
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('orders', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg> Orders
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('cod', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> COD & Settlement
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('invoices', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Invoices
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">People</div>
        <a class="erp-nav-item" onclick="erpNavigate('merchants', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> Merchants
          <span class="erp-badge erp-badge-warning" style="font-size:10px;padding:2px 7px">{{ number_format($financialCards['pendingMerchants'] ?? 0) }}</span>
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('couriers', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg> Couriers
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">Finance</div>
        <a class="erp-nav-item" onclick="erpNavigate('overall-sales', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Overall Sales
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('pricing', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M8 12h8"/></svg> Pricing Plans
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('profit', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Profit Report
        </a>
        <a class="erp-nav-item" onclick="erpNavigate('tax', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="15" x2="15.01" y2="15"/></svg> Tax Engine
        </a>
      </div>

      <div class="erp-nav-group">
        <div class="erp-nav-label">Tools</div>
        <a class="erp-nav-item" onclick="erpNavigate('notif', this)">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> Notifications
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
            <button class="erp-filter-btn" onclick="erpSetFilter('last7days', this)">Last 7 Days</button>
            <button class="erp-filter-btn" onclick="erpSetFilter('date_to_date', this)">Date to Date</button>
          </div>
          <div style="display:flex;align-items:center;gap:6px;display:none" id="erpTopbarDateInputs">
            <input type="date" class="erp-input erp-input-date" id="erpDateFrom" value="{{ $dateFrom ?? '' }}" onchange="erpApplyDateFilter()">
            <span style="color:var(--erp-text-muted);font-size:12px;">to</span>
            <input type="date" class="erp-input erp-input-date" id="erpDateTo" value="{{ $dateTo ?? '' }}" onchange="erpApplyDateFilter()">
          </div>
          <div class="erp-topbar-avatar" style="margin-left:auto">SA</div>
      </div>

      <div class="erp-content">

        <!-- ==================== DASHBOARD PAGE ==================== -->
        <div class="erp-page active" id="erp-page-dashboard">

          <!-- Company Live Position -->
          <div class="erp-fin-widget">
            <div class="erp-fin-header">
              <div class="erp-fin-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg> Company Live Position</div>
              <div class="erp-fin-live"><span class="erp-fin-live-dot"></span>Live</div>
            </div>
            <div class="erp-fin-row"><span><b>Total COD Amount</b></span><span>Rs {{ number_format($companyPosition['totalCodAll'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Merchant Payables (COD − Charges − 4% Tax)</span><span class="neg">− Rs {{ number_format($companyPosition['merchantPayables'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Courier Receivable (COD − Courier Fee − 2% Tax)</span><span class="pos">+ Rs {{ number_format($companyPosition['courierReceivables'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Tax Collected (4% Held)</span><span class="neg">− Rs {{ number_format($companyPosition['taxHeld'] ?? 0) }}</span></div>
            <div class="erp-fin-main">Rs {{ number_format($companyPosition['availableCash'] ?? 0) }}</div>
            <div class="erp-fin-row"><span style="font-weight:700;color:var(--erp-primary-dark)">Available Cash (Del Charges − Courier Cost)</span><span class="pos" style="font-size:15px">Rs {{ number_format($companyPosition['availableCash'] ?? 0) }}</span></div>
          </div>

          <!-- Operational Cards -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Operational Workflow</div>
          <div class="erp-grid erp-grid-6" style="margin-bottom:16px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-primary-light);color:var(--erp-primary)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></div>
              <div class="erp-stat-label">Booked Today</div>
              <div class="erp-stat-value">{{ number_format($operationalCards['bookedToday'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['bookedTodayCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-info-light);color:var(--erp-info)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
              <div class="erp-stat-label">Dispatched</div>
              <div class="erp-stat-value" style="color:var(--erp-info)">{{ number_format($operationalCards['dispatched'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['dispatchedCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
              <div class="erp-stat-label">Delivered</div>
              <div class="erp-stat-value" style="color:var(--erp-success)">{{ number_format($operationalCards['delivered'] ?? 0) }}</div>
              <div class="erp-stat-sub">Rs {{ number_format($operationalCards['deliveredCod'] ?? 0) }} COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
              <div class="erp-stat-label">In Progress</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($operationalCards['inProgress'] ?? 0) }}</div>
              <div class="erp-stat-sub">Dispatched not closed</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div>
              <div class="erp-stat-label">Issue Orders</div>
              <div class="erp-stat-value" style="color:var(--erp-danger)">{{ number_format($operationalCards['issueOrders'] ?? 0) }}</div>
              <div class="erp-stat-sub">Action needed</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-info-light);color:var(--erp-info)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20"/><path d="M17 5H9a3 3 0 0 0 0 6h8a3 3 0 0 1 0 6H7"/></svg></div>
              <div class="erp-stat-label">Ready to Return</div>
              <div class="erp-stat-value" style="color:var(--erp-info)">{{ number_format($operationalCards['readyToReturn'] ?? 0) }}</div>
              <div class="erp-stat-sub">Awaiting routing</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 9 9 4 15 10 21 4"/><path d="M21 4v16H3"/></svg></div>
              <div class="erp-stat-label">Return Confirmed</div>
              <div class="erp-stat-value" style="color:var(--erp-danger)">{{ number_format($operationalCards['returnConfirmed'] ?? 0) }}</div>
              <div class="erp-stat-sub">Courier confirmed</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 9 9 4 15 10 21 4"/><path d="M21 4v16H3"/></svg></div>
              <div class="erp-stat-label">Total Returned</div>
              <div class="erp-stat-value" style="color:var(--erp-danger)">{{ number_format($operationalCards['totalReturned'] ?? 0) }}</div>
              <div class="erp-stat-sub">All return cycle</div>
            </div>
          </div>

          <!-- Financial Cards -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="6" x2="12" y2="12"/><line x1="12" y1="12" x2="16" y2="14"/></svg> Profitability & Tax</div>
          <div class="erp-grid erp-grid-6" style="margin-bottom:16px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="6" x2="12" y2="12"/><line x1="12" y1="12" x2="16" y2="14"/></svg></div>
              <div class="erp-stat-label">Gross Profit (Dispatched)</div>
              <div class="erp-stat-value pos">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</div>
              <div class="erp-stat-sub">Merchant Rate − Courier Rate</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div>
              <div class="erp-stat-label">Net Profit (Delivered)</div>
              <div class="erp-stat-value pos">Rs {{ number_format($financialCards['netProfit'] ?? 0) }}</div>
              <div class="erp-stat-sub">Delivery Charges − Courier Cost</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/></svg></div>
              <div class="erp-stat-label">Tax 4% Collected</div>
              <div class="erp-stat-value">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div>
              <div class="erp-stat-sub">On delivered COD</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg></div>
              <div class="erp-stat-label">Courier 2% Tax</div>
              <div class="erp-stat-value neg">Rs {{ number_format($financialCards['courierTax2'] ?? 0) }}</div>
              <div class="erp-stat-sub">Deducted by courier</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg></div>
              <div class="erp-stat-label">Our 2% Tax Balance</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</div>
              <div class="erp-stat-sub">4% − 2% = 2% margin</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-primary-light);color:var(--erp-primary)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
              <div class="erp-stat-label">Active Merchants</div>
              <div class="erp-stat-value">{{ number_format($financialCards['activeMerchants'] ?? 0) }}</div>
              <div class="erp-stat-sub">{{ number_format($financialCards['pendingMerchants'] ?? 0) }} pending approval</div>
            </div>
          </div>

          <!-- Recent Orders -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg> Recent Orders</div>
          <div class="erp-card">
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Order #</th><th>Merchant</th><th>Courier</th><th>COD</th><th>4% Tax</th><th>Courier 2%</th><th>Delivery</th><th>Profit</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($recentOrders as $order)
                  @php
                    $cod = (float)($order->cod_amount ?? 0);
                    $tax4 = round($cod * 0.04);
                    $courier2 = round($cod * 0.02);
                    $profit = (float)($order->profit ?? (($order->delivery_charges ?? 0) - ($order->courier_cost ?? 0)));
                  @endphp
                  <tr>
                    <td><b>#{{ $order->id }}</b></td>
                    <td>{{ $order->user->brand_name ?? $order->user->name ?? '—' }}</td>
                    <td>{{ $order->courier_integration->courier_name ?? '—' }}</td>
                    <td>Rs {{ number_format($cod) }}</td>
                    <td>Rs {{ number_format($tax4) }}</td>
                    <td>Rs {{ number_format($courier2) }}</td>
                    <td>Rs {{ number_format($order->delivery_charges ?? 0) }}</td>
                    <td class="{{ $profit >= 0 ? 'pos' : 'neg' }}">{{ $profit != 0 ? 'Rs '.number_format($profit) : '—' }}</td>
                    <td><span class="erp-badge erp-badge-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'returned' || $order->status === 'issue' ? 'danger' : ($order->status === 'dispatched' || $order->status === 'picked_up' ? 'info' : 'warning')) }}">{{ \App\Models\Booking::getStatusLabel($order->status) }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;color:var(--erp-text-muted);padding:24px">No recent orders found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== ORDERS PAGE ==================== -->
        <div class="erp-page" id="erp-page-orders">
          <div class="erp-filter-bar">
            <button class="erp-filter-btn active" onclick="erpFilterOrders('all', this)">All</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('booked', this)">Booked</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('dispatched', this)">Dispatched</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('delivered', this)">Delivered</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('in_transit', this)">In Transit</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('returned', this)">Returned</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('issue', this)">Issue</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('ready_to_return', this)">Ready to Return</button>
            <button class="erp-filter-btn" onclick="erpFilterOrders('return_confirmed', this)">Return Confirmed</button>
          </div>
          <div class="erp-search-box">
            <input type="text" class="erp-input erp-input-search" id="erpOrdersSearch" placeholder="Search by name, tracking ID, phone..." onkeyup="if(event.key==='Enter')erpSearchOrders()">
            <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSearchOrders()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg> Search</button>
            <input type="date" class="erp-input erp-input-date" id="erpOrdersFrom" value="{{ $dateFrom ?? '' }}" onchange="erpSearchOrders()">
            <span style="font-size:12px;color:var(--erp-text-muted)">to</span>
            <input type="date" class="erp-input erp-input-date" id="erpOrdersTo" value="{{ $dateTo ?? '' }}" onchange="erpSearchOrders()">
          </div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
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
                  <tr><td colspan="11" style="text-align:center;color:var(--erp-text-muted);padding:24px">No orders found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== COD & SETTLEMENT PAGE ==================== -->
        <div class="erp-page" id="erp-page-cod">
          <div class="erp-grid erp-grid-3" style="margin-bottom:16px">
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-danger-light);color:var(--erp-danger)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/></svg></div>
              <div class="erp-stat-label">Total COD to Pay (Merchants)</div>
              <div class="erp-stat-value neg">Rs {{ number_format($companyPosition['merchantPayables'] ?? 0) }}</div>
              <div class="erp-stat-sub">COD − Charges − 4% Tax</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-success-light);color:var(--erp-success)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div>
              <div class="erp-stat-label">Courier Receivable (Net)</div>
              <div class="erp-stat-value pos">Rs {{ number_format($companyPosition['courierReceivables'] ?? 0) }}</div>
              <div class="erp-stat-sub">COD − Courier Fee − 2% Tax</div>
            </div>
            <div class="erp-stat">
              <div class="erp-stat-icon" style="background:var(--erp-warning-light);color:var(--erp-warning)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 21"/><line x1="16" y1="3" x2="16" y2="21"/></svg></div>
              <div class="erp-stat-label">Pending Settlements</div>
              <div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($financialCards['pendingSettlements'] ?? 0) }}</div>
              <div class="erp-stat-sub">Unpaid merchants</div>
            </div>
          </div>

          <!-- COD Settlements Search & Calendar -->
          <div class="erp-search-box" style="margin-bottom:12px">
            <input type="date" class="erp-input erp-input-date" id="erpCodFrom" value="{{ $dateFrom ?? '' }}">
            <span style="font-size:12px;color:var(--erp-text-muted)">to</span>
            <input type="date" class="erp-input erp-input-date" id="erpCodTo" value="{{ $dateTo ?? '' }}">
            <button class="erp-btn erp-btn-sm" onclick="erpCodRefresh()">Apply</button>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/></svg> Per Merchant — COD Payable</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">Merchant COD Payable with Edit/Save</div></div>
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Delivered</th><th>Total COD</th><th>Delivery Charges</th><th>4% Tax</th><th>Net Payable</th><th>Courier Paid Us</th><th>Status</th><th>Action</th></tr></thead>
                <tbody id="erpCodMerchantBody">
                  @forelse($activeMerchantsList as $m)
                  @php $courierPaid = round($m['total_cod'] * 0.98); @endphp
                  <tr id="cod-merchant-{{ $m['id'] }}">
                    <td><b>{{ $m['name'] }}</b></td>
                    <td>{{ number_format($m['delivered']) }}</td>
                    <td><span class="cod-total-cod">Rs {{ number_format($m['total_cod']) }}</span></td>
                    <td><input type="number" class="erp-input" id="cod-charges-{{ $m['id'] }}" value="{{ $m['delivery_charges'] }}" style="width:80px" onchange="erpRecalcMerchant({{ $m['id'] }})"></td>
                    <td><span class="cod-tax" id="cod-tax-{{ $m['id'] }}">Rs {{ number_format($m['tax_4percent']) }}</span></td>
                    <td class="pos"><b><span class="cod-net" id="cod-net-{{ $m['id'] }}">Rs {{ number_format($m['net_payable']) }}</span></b></td>
                    <td>Rs {{ number_format($courierPaid) }}</td>
                    <td>
                      <select class="erp-status-select" id="cod-status-{{ $m['id'] }}">
                        <option value="pending" {{ $m['account_status'] === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $m['account_status'] === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ $m['account_status'] === 'unpaid' || $m['account_status'] === 'active' ? 'selected' : '' }}>Unpaid</option>
                      </select>
                    </td>
                    <td>
                      <button class="erp-btn erp-btn-sm" onclick="erpEditMerchantPayment({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit</button>
                      <button class="erp-btn erp-btn-success erp-btn-sm" onclick="erpSaveMerchantPayment({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Save</button>
                      <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpPayMerchant({{ $m['id'] }})">Pay Now</button>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No merchant data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/></svg> Courier COD Received</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Courier</th><th>Delivered</th><th>Total COD Collected</th><th>Charges Deducted</th><th>2% Tax Deducted</th><th>Amount Remitted</th><th>Status</th></tr></thead>
                <tbody id="erpCourierCODTable">
                  @forelse($couriers as $c)
                  @php
                    $cDelivered = \App\Models\Booking::where('courier_integration_id', $c->id)->where('status', \App\Models\Booking::STATUS_DELIVERED);
                    $cCod = $cDelivered->sum('cod_amount');
                    $cCharges = $cDelivered->sum('delivery_charges');
                    $cTax = round($cCod * 0.02);
                    $cRemitted = $cCod - $cCharges - $cTax;
                  @endphp
                  <tr>
                    <td><b>{{ $c->courier_name }}</b> @if($c->logo_path)<img src="{{ $c->logo_path }}" style="height:20px;margin-left:6px">@endif</td>
                    <td>{{ $cDelivered->count() }}</td>
                    <td>Rs {{ number_format($cCod) }}</td>
                    <td>Rs {{ number_format($cCharges) }}</td>
                    <td>Rs {{ number_format($cTax) }}</td>
                    <td class="pos">Rs {{ number_format(max(0, $cRemitted)) }}</td>
                    <td><span class="erp-badge {{ $c->is_active ? 'erp-badge-success' : 'erp-badge-neutral' }}">{{ $c->is_active ? 'Active' : 'Off' }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No courier data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== INVOICES PAGE ==================== -->
        <div class="erp-page" id="erp-page-invoices">
          <div class="erp-grid erp-grid-4" style="margin-bottom:16px">
            <div class="erp-stat"><div class="erp-stat-label">Total Invoices</div><div class="erp-stat-value">{{ number_format($invoiceStats['total'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Pending</div><div class="erp-stat-value" style="color:var(--erp-warning)">{{ number_format($invoiceStats['pending'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Paid</div><div class="erp-stat-value pos">{{ number_format($invoiceStats['paid'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Overdue</div><div class="erp-stat-value neg">{{ number_format($invoiceStats['overdue'] ?? 0) }}</div></div>
          </div>

          <div class="erp-info-bar erp-info-bar-info">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <b>Daily Invoice System:</b> Har roz shaam automatic invoice generate hoti hai. Agle din payment clear karein. Formula: COD − Delivery Charges − 4% Tax = Net Payable.
          </div>

          <div class="erp-card">
            <div class="erp-card-header">
              <div class="erp-card-header-title">Daily Invoice List</div>
              <div style="display:flex;gap:6px;flex-wrap:wrap">
                <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpGenerateAllInvoices()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Generate All Daily</button>
                <button class="erp-btn erp-btn-warning erp-btn-sm" onclick="erpGenerateSingleInvoice()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Single Merchant</button>
                <button class="erp-btn erp-btn-success erp-btn-sm" onclick="erpTodayPay()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg> Today Pay</button>
              </div>
            </div>
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Invoice #</th><th>Merchant</th><th>Period</th><th>Delivered</th><th>COD</th><th>Charges</th><th>4% Tax</th><th>Net Payable</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="erpInvoiceTableBody">
                  @forelse($invoices as $inv)
                  <tr id="invoice-row-{{ $inv['id'] }}">
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
                      <button class="erp-btn erp-btn-sm" onclick="erpViewInvoiceOrders({{ $inv['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> View</button>
                      <button class="erp-btn erp-btn-sm" onclick="erpEditInvoice({{ $inv['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg> Edit</button>
                      @if($inv['status'] !== 'paid')
                      <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpMarkInvoicePaid({{ $inv['id'] }})">Mark Paid</button>
                      @endif
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="10" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No invoices found</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== MERCHANTS PAGE ==================== -->
        <div class="erp-page" id="erp-page-merchants">
          <!-- Pending Approval -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Pending Approval</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">New Merchant Requests <span class="erp-badge erp-badge-warning" style="margin-left:6px">{{ count($pendingMerchantsList) }} pending</span></div></div>
            <div class="erp-card-body">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Business</th><th>City</th><th>Plan</th><th>Phone</th><th>Joined</th><th>Actions</th></tr></thead>
                <tbody id="erpPendingMerchants">
                  @forelse($pendingMerchantsList as $m)
                  <tr id="merchant-row-{{ $m['id'] }}">
                    <td><b>{{ $m['name'] }}</b></td><td>{{ $m['business'] }}</td><td>{{ $m['city'] }}</td>
                    <td><span class="erp-badge erp-badge-warning">{{ $m['plan'] }}</span></td><td>{{ $m['phone'] }}</td>
                    <td style="color:var(--erp-text-secondary)">{{ $m['joined'] }}</td>
                    <td>
                      <button class="erp-btn erp-btn-success erp-btn-sm" onclick="erpApproveMerchant({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg> Approve</button>
                      <button class="erp-btn erp-btn-danger erp-btn-sm" onclick="erpRejectMerchant({{ $m['id'] }})"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Reject</button>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No pending merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Search Active Merchants -->
          <div class="erp-search-box" style="margin-bottom:8px">
            <input type="text" class="erp-input erp-input-search" id="erpMerchantSearch" placeholder="Search by name or phone..." onkeyup="if(event.key==='Enter')erpSearchMerchants()">
            <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSearchMerchants()">🔍 Search</button>
            <input type="date" class="erp-input erp-input-date" id="erpMerchantFrom">
            <span style="font-size:12px;color:var(--erp-text-muted)">to</span>
            <input type="date" class="erp-input erp-input-date" id="erpMerchantTo">
            <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpApplyMerchantFilter()">Apply</button>
          </div>

          <!-- Active Merchants Finance Summary -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Active Merchants — Finance Summary</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Plan</th><th>Dispatched</th><th>Delivered</th><th>Returned</th><th>Total COD</th><th>Charges</th><th>4% Tax</th><th>Net Payable</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody id="erpActiveMerchants">
                  @forelse($activeMerchantsList as $m)
                  <tr id="active-merchant-{{ $m['id'] }}">
                    <td><b>{{ $m['name'] }}</b></td>
                    <td>
                      <select class="erp-select-sm" id="plan-select-{{ $m['id'] }}" onchange="erpChangePlan({{ $m['id'] }})">
                        @foreach($pricingPlans as $pp)
                        <option value="{{ $pp['id'] }}" {{ $m['plan'] === $pp['name'] ? 'selected' : '' }}>{{ $pp['name'] }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td>{{ number_format($m['dispatched']) }}</td>
                    <td class="pos">{{ number_format($m['delivered']) }}</td>
                    <td class="neg">{{ number_format($m['returned']) }}</td>
                    <td>Rs {{ number_format($m['total_cod']) }}</td>
                    <td>Rs {{ number_format($m['delivery_charges']) }}</td>
                    <td>Rs {{ number_format($m['tax_4percent']) }}</td>
                    <td class="pos">Rs {{ number_format($m['net_payable']) }}</td>
                    <td><span class="erp-badge {{ $m['is_suspended'] ? 'erp-badge-danger' : 'erp-badge-success' }}">{{ $m['is_suspended'] ? 'Suspended' : 'Active' }}</span></td>
                    <td>
                      <button class="erp-btn erp-btn-sm" onclick="erpEditMerchantDetail({{ $m['id'] }})">View/Edit</button>
                      <button class="erp-btn {{ $m['is_suspended'] ? 'erp-btn-success' : 'erp-btn-danger' }} erp-btn-sm" onclick="erpSuspendMerchant({{ $m['id'] }})">{{ $m['is_suspended'] ? 'Reactivate' : 'Suspend' }}</button>
                    </td>
                  </tr>
                  @empty
                  <tr><td colspan="11" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No active merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Custom Return Charges -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/></svg> Custom Return Charges per Merchant</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Plan</th><th>Default Return Rate</th><th>Custom Rate (Rs)</th><th>Returned Orders</th><th>Total Return Charges</th><th>Action</th></tr></thead>
                <tbody>
                  @forelse($activeMerchantsList as $m)
                  @php
                    $planRates = collect($pricingPlans)->firstWhere('name', $m['plan']);
                    $defaultReturn = $planRates['return_charge'] ?? 120;
                    $returnedOrders = \App\Models\Booking::where('user_id', $m['id'])->where('status', \App\Models\Booking::STATUS_RETURNED)->count();
                    $totalReturnCharges = $returnedOrders * (float)($m['custom_return_charge'] ?? $defaultReturn);
                  @endphp
                  <tr>
                    <td><b>{{ $m['name'] }}</b></td>
                    <td><span class="erp-badge erp-badge-info">{{ $m['plan'] }}</span></td>
                    <td>Rs {{ number_format($defaultReturn) }}</td>
                    <td><input type="number" class="erp-input" id="return-input-{{ $m['id'] }}" value="{{ $m['custom_return_charge'] ?? $defaultReturn }}" style="width:90px"></td>
                    <td>{{ number_format($returnedOrders) }}</td>
                    <td>Rs {{ number_format($totalReturnCharges) }}</td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSaveReturnCharge({{ $m['id'] }})">Save</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== COURIERS PAGE ==================== -->
        <div class="erp-page" id="erp-page-couriers">
          <div class="erp-info-bar erp-info-bar-warning">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <b>Profit = Merchant Rate − Courier Rate.</b> Gross Profit = Dispatched orders. Net Profit = Delivered orders (actual received).
          </div>

          <div class="erp-card">
            <div class="erp-card-header">
              <div class="erp-card-header-title">Courier Management — Rates & Profit</div>
              <button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpShowAddCourier()"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Add Courier</button>
            </div>
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Courier</th><th>Status</th><th>Courier Rate</th><th>Merchant Rate</th><th>Profit/Order</th><th>Dispatched</th><th>Gross Profit</th><th>ON/OFF</th><th>Save</th></tr></thead>
                <tbody id="erpCourierTable">
                  @forelse($courierData as $c)
                  @php
                    $cRate = floatval($c['courier_rate'] ?? 0);
                    $mRate = floatval($c['merchant_rate'] ?? 0);
                    $profitPer = $mRate - $cRate;
                    $totalP = $profitPer * $c['dispatched'];
                  @endphp
                  <tr id="courier-row-{{ $c['id'] }}">
                    <td><b>{{ $c['name'] }}</b> @if($c['logo_path'])<img src="{{ $c['logo_path'] }}" style="height:22px;margin-left:6px;vertical-align:middle">@endif</td>
                    <td><span class="erp-badge {{ $c['is_active'] ? 'erp-badge-success' : 'erp-badge-neutral' }}">{{ $c['is_active'] ? 'Active' : 'Off' }}</span></td>
                    <td><input type="number" step="5" class="erp-input" id="crate-{{ $c['id'] }}" value="{{ $cRate }}" style="width:80px"></td>
                    <td><input type="number" step="5" class="erp-input" id="mrate-{{ $c['id'] }}" value="{{ $mRate }}" style="width:80px"></td>
                    <td><span class="erp-badge {{ $profitPer >= 0 ? 'erp-badge-success' : 'erp-badge-danger' }}">{{ $profitPer >= 0 ? '+' : '' }}Rs {{ number_format($profitPer) }}</span></td>
                    <td><b>{{ number_format($c['dispatched']) }}</b></td>
                    <td class="{{ $totalP >= 0 ? 'pos' : 'neg' }}">{{ $c['is_active'] ? 'Rs '.number_format($totalP) : '—' }}</td>
                    <td><button class="erp-toggle {{ $c['is_active'] ? 'on' : 'off' }}" onclick="erpToggleCourier({{ $c['id'] }})"></button></td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSaveCourier({{ $c['id'] }})">Save</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="9" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No couriers</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== OVERALL SALES PAGE ==================== -->
        <div class="erp-page" id="erp-page-overall-sales">
          <div class="erp-info-bar erp-info-bar-info">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <b>Overall Sales</b> — Sabhi couriers ke delivered orders ka summary. Profit = Delivery Charges − Courier Cost.
          </div>

          <div class="erp-grid erp-grid-4" style="margin-bottom:16px">
            <div class="erp-stat"><div class="erp-stat-label">Total Delivered Orders</div><div class="erp-stat-value" style="color:var(--erp-success)">{{ number_format($overallSalesSummary['delivered_count'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Total COD Amount</div><div class="erp-stat-value">Rs {{ number_format($overallSalesSummary['delivered_amount'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Gross Profit</div><div class="erp-stat-value pos">Rs {{ number_format($overallSalesSummary['gross_profit'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">4% Tax</div><div class="erp-stat-value">Rs {{ number_format($overallSalesSummary['tax_4percent'] ?? 0) }}</div></div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/></svg> Per Courier — Delivered Orders Breakdown</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
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
                  <tr><td colspan="10" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- Net Profit Summary -->
          <div class="erp-fin-widget" style="margin-top:4px">
            <div class="erp-fin-title" style="margin-bottom:10px">Overall Sales — Net Profit Summary</div>
            <div class="erp-fin-row"><span>Total Delivery Charges Collected</span><span>Rs {{ number_format($overallSalesSummary['delivery_charges'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Total Courier Cost</span><span class="neg">− Rs {{ number_format($overallSalesSummary['courier_cost'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span style="font-weight:700">Gross Profit</span><span class="pos">Rs {{ number_format($overallSalesSummary['gross_profit'] ?? 0) }}</span></div>
            <div class="erp-fin-row"><span>Our 2% Tax (Remaining)</span><span class="neg">− Rs {{ number_format(round(($overallSalesSummary['tax_4percent'] ?? 0) / 2)) }}</span></div>
            <div class="erp-fin-row"><span style="font-weight:700;color:var(--erp-primary-dark)">Net Profit</span><span class="pos" style="font-size:16px">Rs {{ number_format($overallSalesSummary['net_profit'] ?? 0) }}</span></div>
          </div>
        </div>

        <!-- ==================== PRICING PLANS PAGE ==================== -->
        <div class="erp-page" id="erp-page-pricing">
          <div class="erp-grid-3" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:16px">
            @foreach($pricingPlans as $plan)
            <div class="erp-plan-card {{ str_contains(strtolower($plan['name']), 'vip') ? 'vip' : '' }}">
              <div class="erp-plan-header">
                <span>{{ $plan['name'] }} Plan</span>
                <span class="erp-plan-count">{{ $plan['merchant_count'] }} merchants</span>
              </div>
              <table class="erp-plan-table" style="width:100%;border-collapse:collapse;font-size:12px">
                <tr><td style="padding:6px 4px">Different City</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-diff" value="{{ $plan['different_city_delivery'] }}" style="width:80px"></td></tr>
                <tr><td style="padding:6px 4px">Same City</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-same" value="{{ $plan['same_city_delivery'] }}" style="width:80px"></td></tr>
                <tr><td style="padding:6px 4px">Additional KG</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-kg" value="{{ $plan['additional_kg_rate'] }}" style="width:80px"></td></tr>
                <tr><td style="padding:6px 4px">Return Charge</td><td><input type="number" class="erp-input" id="plan-{{ $plan['id'] }}-return" value="{{ $plan['return_charge'] }}" style="width:80px"></td></tr>
              </table>
              <button class="erp-btn erp-btn-primary" onclick="erpSavePlan({{ $plan['id'] }})" style="margin-top:12px;width:100%;justify-content:center">Save {{ $plan['name'] }} Plan</button>
            </div>
            @endforeach
          </div>

          <!-- Merchant Plan Filter Tabs -->
          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> Merchants by Plan</div>
          <div class="erp-filter-bar">
            <button class="erp-filter-btn active" onclick="erpFilterMerchantPlan('all', this)">All ({{ count($activeMerchantsList) }})</button>
            @foreach($pricingPlans as $plan)
            <button class="erp-filter-btn" onclick="erpFilterMerchantPlan('{{ $plan['name'] }}', this)">{{ $plan['name'] }} ({{ $plan['merchant_count'] }})</button>
            @endforeach
          </div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Merchant</th><th>Current Plan</th><th>Diff City</th><th>Same City</th><th>Return</th><th>Per KG</th><th>Change Plan</th><th>Save</th></tr></thead>
                <tbody id="erpPlanMerchantBody">
                  @forelse($activeMerchantsList as $m)
                  @php $pRates = collect($pricingPlans)->firstWhere('name', $m['plan']); @endphp
                  <tr class="plan-merchant-row" data-plan="{{ $m['plan'] }}">
                    <td><b>{{ $m['name'] }}</b></td>
                    <td><span class="erp-badge erp-badge-info">{{ $m['plan'] }}</span></td>
                    <td>Rs {{ number_format($pRates['different_city_delivery'] ?? 260) }}</td>
                    <td>Rs {{ number_format($pRates['same_city_delivery'] ?? 170) }}</td>
                    <td>Rs {{ number_format($pRates['return_charge'] ?? 150) }}</td>
                    <td>Rs {{ number_format($pRates['additional_kg_rate'] ?? 150) }}</td>
                    <td>
                      <select class="erp-select-sm" id="mp-plan-{{ $m['id'] }}">
                        @foreach($pricingPlans as $pp)
                        <option value="{{ $pp['id'] }}" {{ $m['plan'] === $pp['name'] ? 'selected' : '' }}>{{ $pp['name'] }}</option>
                        @endforeach
                      </select>
                    </td>
                    <td><button class="erp-btn erp-btn-primary erp-btn-sm" onclick="erpSaveMerchantPlan({{ $m['id'] }})">Save</button></td>
                  </tr>
                  @empty
                  <tr><td colspan="8" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No merchants</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== PROFIT REPORT PAGE ==================== -->
        <div class="erp-page" id="erp-page-profit">
          <div class="erp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
            <div class="erp-fin-widget" style="margin:0">
              <div class="erp-fin-title" style="margin-bottom:10px">Profit Engine</div>
              <div class="erp-fin-row"><span>Merchant Delivery Revenue</span><span>Rs {{ number_format(($financialCards['totalDeliveryCharges'] ?? 0)) }}</span></div>
              <div class="erp-fin-row"><span>Courier Cost (Actual)</span><span class="neg">− Rs {{ number_format(($financialCards['totalCourierCost'] ?? 0)) }}</span></div>
              <div class="erp-fin-row"><span style="font-weight:700">Gross Profit (Dispatched)</span><span class="pos">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</span></div>
              <div class="erp-fin-row"><span>Our 2% Tax (Remaining)</span><span class="neg">− Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</span></div>
              <div class="erp-fin-row"><span style="font-weight:700">Net Profit (Delivered)</span><span class="pos" style="font-size:16px">Rs {{ number_format($financialCards['netProfit'] ?? 0) }}</span></div>
            </div>
            <div class="erp-fin-widget" style="margin:0">
              <div class="erp-fin-title" style="margin-bottom:10px">Per Courier Profit</div>
              @forelse($courierProfits as $cp)
              <div class="erp-fin-row"><span>{{ $cp['name'] }} ({{ $cp['dispatched'] }} dispatched)</span><span class="pos">Rs {{ number_format($cp['profit']) }}</span></div>
              @empty
              <div class="erp-fin-row"><span>No data</span></div>
              @endforelse
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg> Per Merchant Profit Analysis</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
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
                  <tr><td colspan="9" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== TAX ENGINE PAGE ==================== -->
        <div class="erp-page" id="erp-page-tax">
          <div class="erp-info-bar erp-info-bar-info">
            <b>Tax Formula:</b> Total COD × 4% = Total Tax · Courier deducts 2% before remitting · We collect 4% from merchant but courier already took 2% — our margin = 2%
          </div>

          <div class="erp-grid erp-grid-4" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:16px">
            <div class="erp-stat"><div class="erp-stat-label">4% Collected (Merchants)</div><div class="erp-stat-value">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">2% Courier Deducted</div><div class="erp-stat-value neg">Rs {{ number_format($financialCards['courierTax2'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Our 2% Balance</div><div class="erp-stat-value" style="color:var(--erp-warning)">Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</div></div>
            <div class="erp-stat"><div class="erp-stat-label">Govt Payable (4%)</div><div class="erp-stat-value neg">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div></div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="m9 9 6 6"/></svg> Live Tax Calculator</div>
          <div class="erp-card">
            <div class="erp-card-header"><div class="erp-card-header-title">Enter COD Amount — Auto Calculate</div></div>
            <div class="erp-card-body" style="padding:16px">
              <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
                <div class="erp-form-group" style="width:auto"><label>COD Amount (Rs)</label><input type="number" id="erpCodInput" value="1000" style="width:120px" oninput="erpCalcTax()"></div>
                <div style="display:flex;gap:20px;flex-wrap:wrap;font-size:13px;align-items:center">
                  <span>4% Tax: <b id="erpTax4" style="color:var(--erp-warning)">Rs 40</b></span>
                  <span>Courier 2%: <b id="erpTax2Courier" class="neg">Rs 20</b></span>
                  <span>Our 2%: <b id="erpTax2Our" style="color:var(--erp-warning)">Rs 20</b></span>
                  <span>Net to Merchant: <b id="erpTaxNet" class="pos">Rs 960</b></span>
                </div>
              </div>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/></svg> Tax Register — Per Order</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
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
                  <tr><td colspan="7" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No data</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ==================== NOTIFICATIONS PAGE ==================== -->
        <div class="erp-page" id="erp-page-notif">
          <div class="erp-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px">
            <div class="erp-card" style="margin:0">
              <div class="erp-card-header"><div class="erp-card-header-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--erp-success)" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg> WhatsApp</div></div>
              <div class="erp-card-body" style="padding:14px;display:flex;flex-direction:column;gap:8px">
                <div class="erp-form-group"><label>Merchant</label><select id="erpWaMerchant"><option value="all">All Merchants</option>@foreach($allMerchants as $m)<option value="{{ $m->id }}">{{ $m->brand_name ?? $m->name }}</option>@endforeach</select></div>
                <div class="erp-form-group"><label>Type</label><select id="erpWaType"><option>Invoice Generated</option><option>Settlement Paid</option><option>Order Delivered</option><option>Custom Message</option></select></div>
                <div class="erp-form-group"><label>Message</label><textarea id="erpWaMessage" style="min-height:70px">Assalamualaikum,\n\nAapka invoice generate ho gaya hai.\n\nShukriya 🙏</textarea></div>
                <button class="erp-btn erp-btn-success" onclick="erpSendNotif('whatsapp')">📱 Send WhatsApp</button>
              </div>
            </div>
            <div class="erp-card" style="margin:0">
              <div class="erp-card-header"><div class="erp-card-header-title"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--erp-primary)" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg> Website Notification</div></div>
              <div class="erp-card-body" style="padding:14px;display:flex;flex-direction:column;gap:8px">
                <div class="erp-form-group"><label>Merchant</label><select id="erpWebMerchant"><option value="all">All Merchants</option>@foreach($allMerchants as $m)<option value="{{ $m->id }}">{{ $m->brand_name ?? $m->name }}</option>@endforeach</select></div>
                <div class="erp-form-group"><label>Type</label><select id="erpWebType"><option>New Invoice</option><option>Settlement Paid</option><option>Order Update</option><option>Custom</option></select></div>
                <div class="erp-form-group"><label>Message</label><textarea id="erpWebMessage" style="min-height:70px" placeholder="Notification text likhein..."></textarea></div>
                <button class="erp-btn erp-btn-primary" onclick="erpSendNotif('website')">📢 Send Notification</button>
              </div>
            </div>
          </div>

          <div class="erp-section-title"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> Notification History</div>
          <div class="erp-card">
            <div class="erp-card-body" style="overflow-x:auto">
              <table class="erp-table">
                <thead><tr><th>Time</th><th>Merchant</th><th>Type</th><th>Message</th><th>Channel</th><th>Status</th></tr></thead>
                <tbody>
                  @forelse($notifHistory as $n)
                  <tr>
                    <td style="white-space:nowrap">{{ isset($n['time']) ? date('M d, H:i', strtotime($n['time'])) : '—' }}</td>
                    <td>{{ $n['merchant'] }}</td><td>{{ $n['type'] }}</td>
                    <td>{{ $n['message'] }}</td>
                    <td><span class="erp-badge {{ $n['channel'] === 'WhatsApp' ? 'erp-badge-success' : 'erp-badge-info' }}">{{ $n['channel'] }}</span></td>
                    <td><span class="erp-badge {{ $n['status'] === 'sent' ? 'erp-badge-success' : 'erp-badge-warning' }}">{{ ucfirst($n['status']) }}</span></td>
                  </tr>
                  @empty
                  <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No notification history</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ==================== MODAL ==================== -->
  <div class="erp-modal-overlay" id="erpModalOverlay">
    <div class="erp-modal">
      <div class="erp-modal-title">
        <span id="erpModalTitle">Modal</span>
        <span class="erp-modal-close" onclick="erpCloseModal()">&times;</span>
      </div>
      <div id="erpModalBody"></div>
    </div>
  </div>

  <!-- Toast -->
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
    'overall-sales': 'Overall Sales', 'pricing': 'Pricing Plans', 'profit': 'Profit Report',
    'tax': 'Tax Engine', 'notif': 'Notifications'
  };
  document.getElementById('erpPageTitle').textContent = titles[page] || page;

  // Topbar date filter: sirf Dashboard page par show karein
  const filters = document.getElementById('erpTimeFilters');
  const topbarDates = document.getElementById('erpTopbarDateInputs');
  if (filters) filters.style.display = page === 'dashboard' ? '' : 'none';
  if (topbarDates) topbarDates.style.display = page === 'dashboard' ? 'flex' : 'none';

  if (page === 'tax') setTimeout(() => erpCalcTax(), 100);
}

// ==================== TOAST ====================
function erpToast(msg, type) {
  const t = document.getElementById('erpToast');
  t.textContent = msg;
  t.className = 'erp-toast show ' + (type === 'error' ? 'erp-toast-error' : 'erp-toast-success');
  setTimeout(() => t.classList.remove('show'), 3000);
}

// ==================== MODAL ====================
function erpShowModal(title, bodyHtml) {
  document.getElementById('erpModalTitle').innerHTML = title;
  document.getElementById('erpModalBody').innerHTML = bodyHtml;
  document.getElementById('erpModalOverlay').classList.add('show');
}
function erpCloseModal() {
  document.getElementById('erpModalOverlay').classList.remove('show');
}
document.getElementById('erpModalOverlay').addEventListener('click', function(e) {
  if (e.target === this) erpCloseModal();
});

// ==================== DATE FILTERS (Dashboard only - page reload) ====================
function erpSetFilter(f, el) {
  el.closest('.erp-filter-bar').querySelectorAll('.erp-filter-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  const params = new URLSearchParams({ period: f });
  const from = document.getElementById('erpDateFrom')?.value;
  const to = document.getElementById('erpDateTo')?.value;
  if (f === 'date_to_date' && from && to) { params.set('from', from); params.set('to', to); }
  window.location.href = '?' + params.toString();
}

function erpApplyDateFilter() {
  const from = document.getElementById('erpDateFrom').value;
  const to = document.getElementById('erpDateTo').value;
  if (from && to) {
    const params = new URLSearchParams({ period: 'date_to_date', from: from, to: to });
    window.location.href = '?' + params.toString();
  }
}

// ==================== COD PAGE DATE FILTER ====================
function erpCodRefresh() {
  const from = document.getElementById('erpCodFrom').value;
  const to = document.getElementById('erpCodTo').value;
  if (!from || !to) { erpToast('Please select both from and to dates', 'error'); return; }
  // Stay on COD page, only refresh data
  const params = new URLSearchParams({ period: 'date_to_date', from: from, to: to });
  // Add #cod hash to stay on COD page after reload
  window.location.href = '?' + params.toString() + '#cod';
}

// ==================== ORDERS FILTER & SEARCH ====================
function erpFilterOrders(status, el) {
  el.closest('.erp-filter-bar').querySelectorAll('.erp-filter-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  erpFetchOrders(status);
}

function erpSearchOrders() {
  erpFetchOrders(document.querySelector('#erp-page-orders .erp-filter-btn.active')?.textContent?.toLowerCase()?.replace(/ /g,'_') || 'all');
}

function erpFetchOrders(status) {
  const search = document.getElementById('erpOrdersSearch').value;
  const from = document.getElementById('erpOrdersFrom').value;
  const to = document.getElementById('erpOrdersTo').value;
  let url = '/api/admin/orders/filter?status=' + (status || 'all');
  if (search) url += '&search=' + encodeURIComponent(search);
  if (from && to) { url += '&from=' + from + '&to=' + to; }

  fetch(url)
    .then(r => r.json())
    .then(d => {
      if (!d.success || !d.data || d.data.length === 0) {
        document.getElementById('erpOrdersTableBody').innerHTML = '<tr><td colspan="11" style="text-align:center;padding:24px;color:var(--erp-text-muted)">No orders found</td></tr>';
        return;
      }
      document.getElementById('erpOrdersTableBody').innerHTML = d.data.map(o => {
        const badgeClass = o.status === 'delivered' ? 'erp-badge-success' : (o.status === 'returned' || o.status === 'issue' ? 'erp-badge-danger' : (o.status === 'dispatched' || o.status === 'picked_up' ? 'erp-badge-info' : 'erp-badge-warning'));
        const profitClass = o.profit >= 0 ? 'pos' : 'neg';
        const profitVal = o.profit != 0 ? 'Rs ' + Number(o.profit).toLocaleString() : '—';
        return `<tr><td><b>#${o.id}</b></td><td>${o.merchant}</td><td>${o.city}</td><td>${o.courier}</td>
          <td>Rs ${Number(o.cod_amount).toLocaleString()}</td><td>Rs ${Number(o.tax_4percent).toLocaleString()}</td>
          <td>Rs ${Number(o.courier_2percent).toLocaleString()}</td><td>Rs ${Number(o.our_2percent).toLocaleString()}</td>
          <td>Rs ${Number(o.delivery_charge).toLocaleString()}</td>
          <td class="${profitClass}">${profitVal}</td>
          <td><span class="erp-badge ${badgeClass}">${o.status_label}</span></td></tr>`;
      }).join('');
    })
    .catch(e => {
      console.error('Orders fetch error:', e);
      erpToast('Error loading orders. Check console for details.', 'error');
    });
}

// ==================== MERCHANT ACTIONS ====================
function erpSearchMerchants() {
  const q = (document.getElementById('erpMerchantSearch').value || '').toLowerCase();
  document.querySelectorAll('#erpActiveMerchants tr').forEach(r => {
    const name = r.querySelector('td b')?.textContent?.toLowerCase() || '';
    const phone = r.querySelector('td:nth-child(2)')?.textContent?.toLowerCase() || '';
    const status = r.querySelector('td:nth-child(10) .erp-badge')?.textContent?.toLowerCase() || '';
    const email = r.querySelector('td:nth-child(1)')?.textContent?.toLowerCase() || '';
    r.style.display = (!q || name.includes(q) || phone.includes(q) || email.includes(q)) ? '' : 'none';
  });
}

function erpApproveMerchant(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/approve', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { document.getElementById('merchant-row-' + id)?.remove(); erpToast(d.message); }
      else erpToast(d.message || 'Error', 'error');
    }).catch(() => erpToast('Error', 'error'));
}

function erpRejectMerchant(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/reject', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { document.getElementById('merchant-row-' + id)?.remove(); erpToast(d.message); }
    }).catch(() => erpToast('Error', 'error'));
}

function erpSuspendMerchant(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/suspend', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 800); }
    }).catch(() => erpToast('Error', 'error'));
}

function erpSaveReturnCharge(id) {
  const val = document.getElementById('return-input-' + id).value;
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/return-charge', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id, return_charge: val }) })
    .then(r => r.json()).then(d => { if (d.success) erpToast(d.message); else erpToast('Error', 'error'); })
    .catch(() => erpToast('Error', 'error'));
}

function erpChangePlan(id) {
  // Auto-save on change
  erpSaveMerchantPlan(id);
}

function erpSaveMerchantPlan(id) {
  const planId = document.getElementById('mp-plan-' + id)?.value || document.getElementById('plan-select-' + id)?.value;
  if (!planId) return;
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/plan', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id, plan_id: planId }) })
    .then(r => r.json()).then(d => { if (d.success) erpToast(d.message); })
    .catch(() => erpToast('Error', 'error'));
}

function erpEditMerchantDetail(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/merchant/edit-payment', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) {
        erpShowModal('Merchant Details',
          `<div style="font-size:13px"><p><b>Total COD:</b> Rs ${Number(d.data.total_cod).toLocaleString()}</p>
          <p><b>Delivery Charges:</b> Rs ${Number(d.data.delivery_charges).toLocaleString()}</p>
          <p><b>4% Tax:</b> Rs ${Number(d.data.tax_4percent).toLocaleString()}</p>
          <p><b>Net Payable:</b> <span class="pos">Rs ${Number(d.data.net_payable).toLocaleString()}</span></p>
          <p><b>Courier Paid:</b> Rs ${Number(d.data.courier_paid).toLocaleString()}</p></div>`);
      }
    }).catch(() => erpToast('Error', 'error'));
}

function erpRecalcMerchant(id) {
  const charges = parseFloat(document.getElementById('cod-charges-' + id).value) || 0;
  const totalCodEl = document.querySelector('#cod-merchant-' + id + ' .cod-total-cod');
  const totalCod = parseFloat(totalCodEl?.textContent?.replace(/[^0-9.]/g, '') || 0);
  const tax4 = Math.round(totalCod * 0.04);
  const netPayable = totalCod - charges - tax4;
  document.getElementById('cod-tax-' + id).textContent = 'Rs ' + tax4.toLocaleString();
  document.getElementById('cod-net-' + id).textContent = 'Rs ' + netPayable.toLocaleString();
}

function erpEditMerchantPayment(id) {
  const chargesEl = document.getElementById('cod-charges-' + id);
  chargesEl.removeAttribute('disabled');
  chargesEl.focus();
}

function erpSaveMerchantPayment(id) {
  const charges = document.getElementById('cod-charges-' + id).value;
  const status = document.getElementById('cod-status-' + id).value;
  const csrf = '{{ csrf_token() }}';
  // Save the updated values
  fetch('/api/admin/merchant/status', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id, status: status }) })
    .then(r => r.json()).then(d => { if (d.success) erpToast('Payment status saved!'); else erpToast('Error', 'error'); })
    .catch(() => erpToast('Error', 'error'));
}

function erpPayMerchant(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/settlement/pay', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ user_id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 1000); }
      else erpToast(d.message, 'error');
    }).catch(() => erpToast('Error', 'error'));
}

// ==================== COURIER ACTIONS ====================
function erpToggleCourier(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/courier/toggle', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 800); }
    }).catch(() => erpToast('Error', 'error'));
}

function erpSaveCourier(id) {
  const crate = document.getElementById('crate-' + id).value;
  const mrate = document.getElementById('mrate-' + id).value;
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/courier/save-rates', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id, courier_rate: crate, merchant_rate: mrate }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 800); }
    }).catch(() => erpToast('Error', 'error'));
}

function erpShowAddCourier() {
  erpShowModal('Add New Courier',
    `<div class="erp-form-group" style="margin-bottom:12px"><label>Courier Name</label><input type="text" id="newCourierName" class="erp-input" style="width:100%;text-align:left"></div>
    <div class="erp-form-group" style="margin-bottom:12px"><label>Logo URL</label><input type="text" id="newCourierLogo" class="erp-input" style="width:100%;text-align:left"></div>
    <button class="erp-btn erp-btn-primary" onclick="erpAddCourier()">Add Courier</button>`);
}

function erpAddCourier() {
  const name = document.getElementById('newCourierName').value;
  const logo = document.getElementById('newCourierLogo').value;
  if (!name) { erpToast('Enter courier name', 'error'); return; }
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/courier/add', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ courier_name: name, logo_path: logo }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); erpCloseModal(); setTimeout(() => location.reload(), 800); }
    }).catch(() => erpToast('Error', 'error'));
}

// ==================== INVOICE ACTIONS ====================
function erpGenerateAllInvoices() {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/invoice/generate', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({}) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 1000); }
      else erpToast(d.message || 'Error', 'error');
    }).catch(() => erpToast('Error', 'error'));
}

function erpGenerateSingleInvoice() {
  const uid = prompt('Enter Merchant ID:');
  if (!uid) return;
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/invoice/generate', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ user_id: parseInt(uid) }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 1000); }
      else erpToast(d.message || 'Error', 'error');
    }).catch(() => erpToast('Error', 'error'));
}

function erpViewInvoiceOrders(id) {
  fetch('/api/admin/invoices/' + id + '/orders')
    .then(r => r.json()).then(d => {
      if (d.success) {
        let html = '<table class="erp-table"><thead><tr><th>Order #</th><th>Customer</th><th>COD</th><th>Charges</th><th>Status</th></tr></thead><tbody>';
        (d.orders || []).forEach(o => {
          html += `<tr><td><b>#${o.id}</b></td><td>${o.customer_name}</td><td>Rs ${Number(o.cod_amount).toLocaleString()}</td><td>Rs ${Number(o.delivery_charges).toLocaleString()}</td><td><span class="erp-badge erp-badge-info">${o.status}</span></td></tr>`;
        });
        html += '</tbody></table>';
        html += `<p style="margin-top:12px"><b>Net Payable:</b> <span class="pos">Rs ${Number(d.invoice?.net_amount || 0).toLocaleString()}</span></p>`;
        erpShowModal('Invoice Orders — ' + (d.invoice?.invoice_number || ''), html);
      }
    }).catch(() => erpToast('Error loading invoice', 'error'));
}

function erpEditInvoice(id) {
  const row = document.getElementById('invoice-row-' + id);
  if (!row) return;
  const codText = row.querySelector('td:nth-child(5)')?.textContent?.replace(/[^0-9.]/g, '') || '0';
  const chargesText = row.querySelector('td:nth-child(6)')?.textContent?.replace(/[^0-9.]/g, '') || '0';
  erpShowModal('Edit Invoice #' + id,
    `<div class="erp-form-group" style="margin-bottom:10px"><label>Total COD</label><input type="number" id="editInvCod" value="${codText}" class="erp-input" style="width:100%;text-align:left"></div>
    <div class="erp-form-group" style="margin-bottom:10px"><label>Delivery Charges</label><input type="number" id="editInvCharges" value="${chargesText}" class="erp-input" style="width:100%;text-align:left"></div>
    <div class="erp-form-group" style="margin-bottom:10px"><label>Net Amount</label><input type="number" id="editInvNet" value="0" class="erp-input" style="width:100%;text-align:left"></div>
    <button class="erp-btn erp-btn-primary" onclick="erpSaveEditedInvoice(${id})">Save Changes</button>
    <script>setTimeout(() => { const cod = parseFloat(document.getElementById('editInvCod').value) || 0; const ch = parseFloat(document.getElementById('editInvCharges').value) || 0; document.getElementById('editInvNet').value = Math.max(0, cod - ch - Math.round(cod * 0.04)); }, 100);<\/script>`);
}

function erpSaveEditedInvoice(id) {
  const cod = parseFloat(document.getElementById('editInvCod').value) || 0;
  const charges = parseFloat(document.getElementById('editInvCharges').value) || 0;
  const net = parseFloat(document.getElementById('editInvNet').value) || 0;
  const tax = Math.round(cod * 0.04);
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/invoices/' + id + '/edit', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ total_cod: cod, delivery_charges: charges, net_amount: net, tax: tax }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); erpCloseModal(); setTimeout(() => location.reload(), 800); }
      else erpToast('Error', 'error');
    }).catch(() => erpToast('Error', 'error'));
}

function erpMarkInvoicePaid(id) {
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/invoice/mark-paid', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ id: id }) })
    .then(r => r.json()).then(d => {
      if (d.success) { erpToast(d.message); setTimeout(() => location.reload(), 800); }
    }).catch(() => erpToast('Error', 'error'));
}

function erpTodayPay() {
  fetch('/api/admin/invoice/today-pay')
    .then(r => r.json()).then(d => {
      if (d.success) {
        let html = `<p><b>Total Amount:</b> <span class="pos" style="font-size:18px">Rs ${Number(d.total_amount).toLocaleString()}</span></p>
        <p><b>Total Merchants:</b> ${d.count}</p>
        <table class="erp-table"><thead><tr><th>Merchant</th><th>Delivered</th><th>Net Payable</th></tr></thead><tbody>`;
        (d.merchants || []).forEach(m => {
          html += `<tr><td><b>${m.merchant}</b></td><td>${m.delivered_count || 0}</td><td class="pos">Rs ${Number(m.net_payable).toLocaleString()}</td></tr>`;
        });
        html += `</tbody></table>
        <button class="erp-btn erp-btn-success" onclick="erpCloseModal();erpToast('Mark all as paid? Use Pay Now button!')" style="margin-top:12px">Pay All Now</button>`;
        erpShowModal('Today Pay Summary', html);
      } else erpToast(d.message, 'error');
    }).catch(() => erpToast('Error', 'error'));
}

// ==================== PRICING PLANS ====================
function erpSavePlan(planId) {
  const diffCity = document.getElementById('plan-' + planId + '-diff')?.value;
  const sameCity = document.getElementById('plan-' + planId + '-same')?.value;
  const kg = document.getElementById('plan-' + planId + '-kg')?.value;
  const ret = document.getElementById('plan-' + planId + '-return')?.value;
  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/pricing/save', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify({ plan_id: planId, different_city_delivery: diffCity, same_city_delivery: sameCity, additional_kg_rate: kg, return_charge: ret })
  }).then(r => r.json()).then(d => { if (d.success) erpToast(d.message); else erpToast('Error', 'error'); })
    .catch(() => erpToast('Error', 'error'));
}

function erpFilterMerchantPlan(plan, el) {
  el.closest('.erp-filter-bar').querySelectorAll('.erp-filter-btn').forEach(b => b.classList.remove('active'));
  el.classList.add('active');
  document.querySelectorAll('.plan-merchant-row').forEach(r => {
    if (plan === 'all') { r.style.display = ''; return; }
    r.style.display = r.getAttribute('data-plan') === plan ? '' : 'none';
  });
}

// ==================== NOTIFICATIONS ====================
function erpSendNotif(channel) {
  const merchantEl = channel === 'whatsapp' ? document.getElementById('erpWaMerchant') : document.getElementById('erpWebMerchant');
  const typeEl = channel === 'whatsapp' ? document.getElementById('erpWaType') : document.getElementById('erpWebType');
  const msgEl = channel === 'whatsapp' ? document.getElementById('erpWaMessage') : document.getElementById('erpWebMessage');
  const msg = (msgEl.value || '').trim();
  if (!msg) { erpToast('Please enter a message', 'error'); return; }

  const csrf = '{{ csrf_token() }}';
  fetch('/api/admin/notification/send', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
    body: JSON.stringify({ send_to: merchantEl.value, channel: channel, type: typeEl.value, notification_type: typeEl.value, subject: typeEl.value, message: msg })
  }).then(r => r.json()).then(d => {
    if (d.success) { erpToast(d.message); msgEl.value = ''; setTimeout(() => location.reload(), 1500); }
    else erpToast(d.message || 'Error', 'error');
  }).catch(() => erpToast('Error', 'error'));
}

// ==================== TAX CALCULATOR ====================
function erpCalcTax() {
  const cod = +(document.getElementById('erpCodInput').value) || 0;
  const t4 = Math.round(cod * 0.04);
  const t2c = Math.round(cod * 0.02);
  const t2o = t4 - t2c;
  const net = cod - t4;
  document.getElementById('erpTax4').textContent = 'Rs ' + t4.toLocaleString();
  document.getElementById('erpTax2Courier').textContent = 'Rs ' + t2c.toLocaleString();
  document.getElementById('erpTax2Our').textContent = 'Rs ' + t2o.toLocaleString();
  document.getElementById('erpTaxNet').textContent = 'Rs ' + net.toLocaleString();
}

// ==================== MERCHANT DATE FILTER ====================
function erpApplyMerchantFilter() {
  const from = document.getElementById('erpMerchantFrom').value;
  const to = document.getElementById('erpMerchantTo').value;
  if (!from || !to) { erpToast('Please select both dates', 'error'); return; }
  const params = new URLSearchParams({ period: 'date_to_date', from: from, to: to });
  window.location.href = '?' + params.toString() + '#merchants';
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  erpCalcTax();
  // Live refresh dashboard cards (company position + operational + financial)
  // Uses AdminController@getDashboardData API.
  try {
    const refreshMs = 30000; // 30 seconds
    const csrf = '{{ csrf_token() }}';
    const applyDashboardData = (d) => {
      if (!d || !d.success) return;
      const oc = d.operationalCards || {};
      const fc = d.financialCards || {};
      const cp = d.companyPosition || {};

      // Company position
      const totalCodEl = document.querySelector('#erp-page-dashboard .erp-fin-row:nth-child(2) span:last-child');
      const merchantPayEl = document.querySelector('#erp-page-dashboard .erp-fin-row:nth-child(3) span:last-child');
      const courierRecEl = document.querySelector('#erp-page-dashboard .erp-fin-row:nth-child(4) span:last-child');
      const taxHeldEl = document.querySelector('#erp-page-dashboard .erp-fin-row:nth-child(5) span:last-child');
      const availableCashMain = document.querySelector('#erp-page-dashboard .erp-fin-main');
      if (totalCodEl) totalCodEl.textContent = 'Rs ' + Number(cp.totalCodAll || 0).toLocaleString();
      if (merchantPayEl) merchantPayEl.textContent = '− Rs ' + Number(cp.merchantPayables || 0).toLocaleString();
      if (courierRecEl) courierRecEl.textContent = '+ Rs ' + Number(cp.courierReceivables || 0).toLocaleString();
      if (taxHeldEl) taxHeldEl.textContent = '− Rs ' + Number(cp.taxHeld || 0).toLocaleString();
      if (availableCashMain) availableCashMain.textContent = 'Rs ' + Number(cp.availableCash || 0).toLocaleString();

      // Operational cards
      const setCardValue = (id, value, prefix) => {
        const el = document.querySelector('#' + id + ' .erp-stat-value');
        if (el) el.textContent = (prefix || '') + Number(value || 0).toLocaleString();
      };

      // These ids must exist; otherwise skip.
      setCardValue('erp-card-bookedToday', oc.bookedToday || 0);
      setCardValue('erp-card-bookedTodayCod', oc.bookedTodayCod || 0, '');

      setCardValue('erp-card-dispatched', oc.dispatched || 0);
      setCardValue('erp-card-delivered', oc.delivered || 0);
      setCardValue('erp-card-inProgress', oc.inProgress || 0);
      setCardValue('erp-card-issueOrders', oc.issueOrders || 0);
      setCardValue('erp-card-readyToReturn', oc.readyToReturn || 0);
      setCardValue('erp-card-returnConfirmed', oc.returnConfirmed || 0);
      setCardValue('erp-card-totalReturned', oc.totalReturned || 0);

      // Financial cards
      // (Only update key visible numbers safely; avoid complex selectors)
      // We'll update tax & available cash which are reliable in the template.
      const tax4Inline = document.querySelector('#erp-page-dashboard .erp-fin-row:nth-child(5) span:last-child');
      if (tax4Inline) tax4Inline.textContent = '− Rs ' + Number(cp.taxHeld || 0).toLocaleString();
    };


    const fetchAndApply = async () => {
      // Respect current filters from URL (period/from/to)
      const params = new URLSearchParams(window.location.search);
      const qs = params.toString();
      const url = '/api/admin/dashboard/data' + (qs ? '?' + qs : '');
      const res = await fetch(url, { headers: { 'X-CSRF-TOKEN': csrf } });
      const d = await res.json();
      applyDashboardData(d);
    };

    // First refresh after load
    setTimeout(fetchAndApply, refreshMs);
    setInterval(fetchAndApply, refreshMs);
  } catch (e) {
    console.warn('Dashboard live refresh disabled:', e);
  }

  // Handle hash-based navigation
  const hash = window.location.hash.replace('#', '');
  if (hash && ['dashboard','orders','cod','invoices','merchants','couriers','overall-sales','pricing','profit','tax','notif'].includes(hash)) {
    const navItems = document.querySelectorAll('.erp-nav-item');
    navItems.forEach(n => {
      if (n.textContent.trim().toLowerCase() === hash.replace('-',' ')) {
        erpNavigate(hash, n);
      }
    });
    // Fallback: try to find by onclick attribute
    if (!document.querySelector('.erp-page.active') || document.querySelector('.erp-page.active')?.id !== 'erp-page-' + hash) {
      const target = document.getElementById('erp-page-' + hash);
      if (target) {
        document.querySelectorAll('.erp-page').forEach(p => p.classList.remove('active'));
        target.classList.add('active');
        document.getElementById('erpPageTitle').textContent = hash.charAt(0).toUpperCase() + hash.slice(1).replace('-',' ');
      }
    }
  }
  // Set active date filter from URL
  const params = new URLSearchParams(window.location.search);
  const period = params.get('period') || 'today';
  document.querySelectorAll('.erp-filter-btn').forEach(b => {
    if (b.textContent.trim().toLowerCase() === period.replace('_',' ') || b.textContent.trim().toLowerCase().replace(/ /g,'_') === period) {
      b.classList.add('active');
    } else if (b.classList.contains('active') && period !== 'today') {
      b.classList.remove('active');
    }
  });
});
</script>
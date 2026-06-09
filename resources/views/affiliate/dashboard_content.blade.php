<style>
*{box-sizing:border-box;margin:0;padding:0;font-family:var(--font-sans)}
:root{
  --o:#FF7A00;--o-light:#FFF0E0;--o-dark:#CC5F00;--o-text:#7A3800;
  --font-sans: Inter, ui-sans-serif, system-ui, sans-serif;
  --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;

  /* Colors used in markup */
  --color-border-tertiary: rgba(148,163,184,0.35);
  --color-border-secondary: rgba(148,163,184,0.55);

  --color-background-primary: #0b1220;
  --color-background-secondary: rgba(255,255,255,0.06);
  --color-background-tertiary: #0f172a;

  --color-background-success: rgba(16,185,129,0.15);
  --color-text-success: #059669;

  --color-background-warning: rgba(245,158,11,0.18);
  --color-text-warning: #b45309;

  --color-background-danger: rgba(239,68,68,0.18);
  --color-text-danger: #dc2626;

  --color-background-info: rgba(59,130,246,0.18);
  --color-text-info: #2563eb;

  --color-text-primary: rgba(226,232,240,0.95);
  --color-text-secondary: rgba(148,163,184,1);
}

.app{display:flex;min-height:700px;border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);overflow:hidden;background:var(--color-background-tertiary)}
.sidebar{width:210px;flex-shrink:0;background:var(--color-background-primary);border-right:0.5px solid var(--color-border-tertiary);display:flex;flex-direction:column}
.sb-brand{padding:1rem;display:flex;align-items:center;gap:8px;border-bottom:0.5px solid var(--color-border-tertiary)}
.sb-icon{width:32px;height:32px;background:#FF7A00;border-radius:var(--border-radius-md);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px}
.sb-name{font-size:13px;font-weight:500;color:var(--color-text-primary);line-height:1.2}
.sb-code{font-size:10px;color:#FF7A00;font-family:var(--font-mono)}
.sb-nav{padding:0.75rem 0;flex:1}
.nav-section{font-size:10px;color:var(--color-text-secondary);padding:0.5rem 1rem 0.25rem;text-transform:uppercase;letter-spacing:0.06em}
.nav-item{display:flex;align-items:center;gap:8px;padding:7px 1rem;font-size:12.5px;color:var(--color-text-secondary);cursor:pointer;border-radius:0;transition:all 0.15s}
.nav-item:hover{color:var(--color-text-primary);background:var(--color-background-secondary)}
.nav-item.active{color:#FF7A00;background:#FFF0E0;font-weight:500}
.nav-item .ti{font-size:15px}
.sb-bottom{padding:0.75rem;border-top:0.5px solid var(--color-border-tertiary)}
.sb-profile{display:flex;align-items:center;gap:8px;cursor:pointer;padding:6px;border-radius:var(--border-radius-md)}
.sb-profile:hover{background:var(--color-background-secondary)}
.avatar-sm{width:28px;height:28px;border-radius:50%;background:#FF7A00;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:500;color:#fff;flex-shrink:0}
.main{flex:1;display:flex;flex-direction:column;overflow:hidden}
.topnav{padding:0.75rem 1.25rem;display:flex;align-items:center;justify-content:space-between;background:var(--color-background-primary);border-bottom:0.5px solid var(--color-border-tertiary)}
.page-title{font-size:15px;font-weight:500;color:var(--color-text-primary)}
.topnav-right{display:flex;align-items:center;gap:10px}
.notif-btn{position:relative;width:30px;height:30px;display:flex;align-items:center;justify-content:center;border-radius:var(--border-radius-md);cursor:pointer;color:var(--color-text-secondary);border:0.5px solid var(--color-border-tertiary)}
.notif-btn:hover{background:var(--color-background-secondary)}
.notif-dot{position:absolute;top:5px;right:5px;width:6px;height:6px;background:#E24B4A;border-radius:50%}
.content{flex:1;padding:1.25rem;overflow:auto}
.panel{display:none;animation:fadeIn 0.2s ease}
.panel.active{display:block}
@keyframes fadeIn{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px;margin-bottom:1.25rem}
.stat-card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:0.85rem 1rem;position:relative;overflow:hidden}
.stat-card::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:#FF7A00;border-radius:2px 0 0 2px}
.stat-icon{font-size:18px;margin-bottom:6px}
.stat-lbl{font-size:10px;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:3px}
.stat-val{font-size:20px;font-weight:500;color:var(--color-text-primary)}
.stat-sub{font-size:10px;color:var(--color-text-secondary);margin-top:3px}
.orange-val{color:#FF7A00}
.card{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-lg);padding:1rem 1.25rem;margin-bottom:1rem}
.card-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:0.85rem}
.card-title{font-size:13px;font-weight:500;color:var(--color-text-primary)}
.badge{font-size:10px;padding:2px 8px;border-radius:20px;font-weight:500}
.badge-orange{background:#FFF0E0;color:#7A3800}
.badge-green{background:var(--color-background-success);color:var(--color-text-success)}
.badge-amber{background:var(--color-background-warning);color:var(--color-text-warning)}
.badge-red{background:var(--color-background-danger);color:var(--color-text-danger)}
.badge-blue{background:var(--color-background-info);color:var(--color-text-info)}
.badge-gray{background:var(--color-background-secondary);color:var(--color-text-secondary)}
table{width:100%;border-collapse:collapse;font-size:12px;table-layout:fixed}
th{padding:7px 10px;text-align:left;font-size:10px;font-weight:500;color:var(--color-text-secondary);border-bottom:0.5px solid var(--color-border-tertiary);background:var(--color-background-secondary);text-transform:uppercase;letter-spacing:0.04em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
td{padding:8px 10px;border-bottom:0.5px solid var(--color-border-tertiary);color:var(--color-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:var(--color-background-secondary)}
.mono{font-family:var(--font-mono);font-size:11px;color:var(--color-text-secondary)}
.earn{color:#CC5F00;font-weight:500}
.progress{height:4px;background:var(--color-background-secondary);border-radius:2px;overflow:hidden;min-width:40px}
.progress-fill{height:100%;background:#FF7A00;border-radius:2px}
.bar-chart{display:flex;align-items:flex-end;gap:6px;height:80px;padding:0 2px}
.bar-col{display:flex;flex-direction:column;align-items:center;gap:4px;flex:1}
.bar-body{width:100%;border-radius:3px 3px 0 0;transition:height 0.4s ease}
.bar-lbl{font-size:9px;color:var(--color-text-secondary)}
.bar-val{font-size:9px;color:var(--color-text-secondary)}
.charts-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:1rem}
.toolkit-row{display:flex;align-items:center;gap:8px;margin-bottom:0.65rem}
.tk-lbl{font-size:11px;color:var(--color-text-secondary);min-width:70px}
.tk-box{flex:1;background:var(--color-background-secondary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:6px 10px;font-size:11px;font-family:var(--font-mono);color:var(--color-text-primary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.tk-btn{display:inline-flex;align-items:center;gap:4px;padding:6px 10px;background:var(--color-background-primary);border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);font-size:11px;cursor:pointer;color:var(--color-text-primary);white-space:nowrap}
.tk-btn:hover{background:var(--color-background-secondary)}
.tk-btn.orange{background:#FF7A00;color:#fff;border-color:#FF7A00}
.tk-btn.orange:hover{background:#CC5F00}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:0.85rem}
.form-group{display:flex;flex-direction:column;gap:4px}
.form-label{font-size:11px;color:var(--color-text-secondary)}
.form-input{border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:7px 10px;font-size:12px;color:var(--color-text-primary);background:var(--color-background-primary);width:100%}
.form-input:focus{outline:none;border-color:#FF7A00}
.form-select{border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);padding:7px 10px;font-size:12px;color:var(--color-text-primary);background:var(--color-background-primary);width:100%}
.action-bar{display:flex;align-items:center;gap:8px;margin-bottom:0.85rem;flex-wrap:wrap}
.search-inp{display:flex;align-items:center;gap:6px;background:var(--color-background-secondary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:6px 10px;flex:1;min-width:120px}
.search-inp input{background:transparent;border:none;outline:none;font-size:12px;color:var(--color-text-primary);width:100%;font-family:var(--font-sans)}
.filter-pill{padding:4px 12px;border:0.5px solid var(--color-border-tertiary);border-radius:20px;font-size:11px;cursor:pointer;background:var(--color-background-primary);color:var(--color-text-secondary)}
.filter-pill.on{background:#FFF0E0;color:#7A3800;border-color:#FF7A00}
.notif-item{display:flex;align-items:flex-start;gap:10px;padding:0.75rem 0;border-bottom:0.5px solid var(--color-border-tertiary)}
.notif-item:last-child{border-bottom:none}
.notif-dot2{width:8px;height:8px;border-radius:50%;margin-top:4px;flex-shrink:0}
.notif-text{font-size:12px;color:var(--color-text-primary);line-height:1.5}
.notif-time{font-size:10px;color:var(--color-text-secondary);margin-top:2px}
.payout-hero{background:#FFF0E0;border:0.5px solid #FFBD85;border-radius:var(--border-radius-lg);padding:1rem 1.25rem;margin-bottom:1rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px}
.payout-amt{font-size:26px;font-weight:500;color:#CC5F00}
.payout-lbl{font-size:11px;color:#7A3800;margin-bottom:4px}
.threshold-bar{margin-top:8px}
.th-labels{display:flex;justify-content:space-between;font-size:10px;color:#7A3800;margin-bottom:4px}
.th-track{height:6px;background:#FFBD85;border-radius:3px;overflow:hidden}
.th-fill{height:100%;background:#FF7A00;border-radius:3px;width:74%}
.admin-stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(110px,1fr));gap:8px;margin-bottom:1rem}
.admin-stat{background:var(--color-background-primary);border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);padding:0.75rem}
.admin-lbl{font-size:10px;color:var(--color-text-secondary);margin-bottom:2px}
.admin-val{font-size:18px;font-weight:500;color:var(--color-text-primary)}
.chip{display:inline-flex;align-items:center;gap:3px;font-size:10px;padding:2px 7px;border-radius:20px}
.chip-orange{background:#FFF0E0;color:#7A3800}
.mini-actions{display:flex;gap:4px}
.icon-btn{width:24px;height:24px;border:0.5px solid var(--color-border-tertiary);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;color:var(--color-text-secondary);background:var(--color-background-primary)}
.icon-btn:hover{background:var(--color-background-secondary);color:var(--color-text-primary)}
.icon-btn.red:hover{background:var(--color-background-danger);color:var(--color-text-danger)}
.icon-btn.orange:hover{background:#FFF0E0;color:#7A3800}
.profile-avatar{width:60px;height:60px;border-radius:50%;background:#FF7A00;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;margin-bottom:0.75rem}
.form-section{font-size:11px;font-weight:500;color:var(--color-text-secondary);text-transform:uppercase;letter-spacing:0.05em;margin:0.85rem 0 0.5rem}
.divider{height:0.5px;background:var(--color-border-tertiary);margin:0.75rem 0}
.export-btns{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:0.75rem}
.exp-btn{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);font-size:11px;cursor:pointer;color:var(--color-text-secondary);background:var(--color-background-primary)}
.exp-btn:hover{background:var(--color-background-secondary)}
.status-row{display:flex;align-items:center;gap:4px}
/* End of CSS */
/* The full HTML from the mockup follows */

<div class="sidebar">
  <div class="sb-brand">
    <div class="sb-icon"><i class="ti ti-truck-delivery" aria-hidden="true"></i></div>
    <div>
      <div class="sb-name">Shah Jee Courier</div>
      <div class="sb-code">{{ $affiliate->affiliate_code ?? '—' }}</div>
    </div>
  </div>
  <div class="sb-nav">
    <div class="nav-section">Main</div>
    <div class="nav-item active" onclick="show('dashboard',this)"><i class="ti ti-layout-dashboard"></i> Dashboard</div>
    <div class="nav-item" onclick="show('shippers',this)"><i class="ti ti-users"></i> Shippers</div>
    <div class="nav-item" onclick="show('ledger',this)"><i class="ti ti-list-details"></i> Earnings ledger</div>
    <div class="nav-item" onclick="show('transactions',this)"><i class="ti ti-arrows-exchange"></i> Transactions</div>
    <div class="nav-item" onclick="show('payouts',this)"><i class="ti ti-cash"></i> Payouts</div>
    <div class="nav-section">Tools</div>
    <div class="nav-item" onclick="show('toolkit',this)"><i class="ti ti-tool"></i> Referral toolkit</div>
    <div class="nav-item" onclick="show('notifications',this)"><i class="ti ti-bell"></i> Notifications <span style="margin-left:auto;background:#E24B4A;color:#fff;font-size:9px;padding:1px 5px;border-radius:20px">0</span></div>
    <div class="nav-section">Admin</div>
    <div class="nav-item" onclick="show('reports',this)"><i class="ti ti-report"></i> Reports</div>
    <div class="nav-item" onclick="show('profile',this)"><i class="ti ti-user-circle"></i> My profile</div>
  </div>
  <div class="sb-bottom">
    <div class="sb-profile">
      <div class="avatar-sm">{{ strtoupper(mb_substr(optional(auth()->user())->name ?? 'U',0,2)) }}</div>
      <div style="min-width:0">
        <div style="font-size:12px;font-weight:500;color:var(--color-text-primary);overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ optional(auth()->user())->name ?? '—' }}</div>
        <div style="font-size:10px;color:var(--color-text-secondary)">Affiliate agent</div>
      </div>
      <i class="ti ti-chevron-right" style="margin-left:auto;font-size:12px;color:var(--color-text-secondary)" aria-hidden="true"></i>
    </div>
  </div>
</div>
<div class="main">
  <div class="topnav">
    <div class="page-title" id="page-title">Dashboard</div>
    <div class="topnav-right">
      <div class="notif-btn" onclick="show('notifications',this)"><i class="ti ti-bell" style="font-size:15px" aria-hidden="true"></i><span class="notif-dot"></span></div>
      <div style="width:28px;height:28px;border-radius:50%;background:#FF7A00;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:500;color:#fff;cursor:pointer">{{ strtoupper(mb_substr(auth()->user()->name ?? 'U',0,2)) }}</div>
    </div>
  </div>
  <div class="content">
    <!-- Panels -->
    <div class="panel active" id="panel-dashboard">
      <div class="stat-grid">
        <div class="stat-card"><div class="stat-icon" style="color:#FF7A00"><i class="ti ti-users" aria-hidden="true"></i></div><div class="stat-lbl">Connected shippers</div><div class="stat-val orange-val">0</div><div class="stat-sub">+0 this month</div></div>
        <div class="stat-card"><div class="stat-icon" style="color:#1D9E75"><i class="ti ti-package" aria-hidden="true"></i></div><div class="stat-lbl">Delivered orders</div><div class="stat-val">0</div><div class="stat-sub">—</div></div>
        <div class="stat-card"><div class="stat-icon" style="color:#FF7A00"><i class="ti ti-wallet" aria-hidden="true"></i></div><div class="stat-lbl">Available wallet</div><div class="stat-val orange-val">₨ {{ number_format($availableBalance,2) }}</div><div class="stat-sub">Cleared COD only</div></div>
        <div class="stat-card"><div class="stat-icon" style="color:#BA7517"><i class="ti ti-clock" aria-hidden="true"></i></div><div class="stat-lbl">Pending balance</div><div class="stat-val">₨ {{ number_format($pendingBalance,2) }}</div><div class="stat-sub">Awaiting COD</div></div>
        <div class="stat-card"><div class="stat-icon" style="color:#185FA5"><i class="ti ti-star" aria-hidden="true"></i></div><div class="stat-lbl">Lifetime earnings</div><div class="stat-val">₨ {{ number_format($lifetimeEarnings,2) }}</div><div class="stat-sub">All time</div></div>
        <div class="stat-card"><div class="stat-icon" style="color:#3B6D11"><i class="ti ti-check" aria-hidden="true"></i></div><div class="stat-lbl">Total paid out</div><div class="stat-val">₨ 0</div><div class="stat-sub">—</div></div>
      </div>

      <div class="card" style="margin-top:1rem">
        <div class="card-hdr"><div class="card-title">Latest commissions</div><span class="badge badge-orange">Recent</span></div>
        <table>
          <colgroup><col style="width:40%"><col style="width:20%"><col style="width:20%"><col style="width:20%"></colgroup>
          <thead><tr><th>Order</th><th>Status</th><th>Shipper</th><th>Earned</th></tr></thead>
          <tbody>
            @forelse($commissionsLatest as $c)
              <tr>
                <td class="mono">{{ $c->order_id }}</td>
                <td>{!! $c->status === 'paid' ? '<span class="badge badge-green">Paid</span>' : ($c->status === 'approved' ? '<span class="badge badge-blue">Approved</span>' : ($c->status === 'rejected' ? '<span class="badge badge-red">Rejected</span>' : '<span class="badge badge-amber">Pending</span>')) !!}</td>
                <td class="mono">{{ $c->shipper_id }}</td>
                <td class="earn">₨ {{ number_format((float)$c->commission_amount,2) }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="mono" style="color:var(--color-text-secondary)">No commissions yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel" id="panel-transactions">
      <div class="card" style="padding:0">
        <table>
          <colgroup><col style="width:18%"><col style="width:12%"><col style="width:18%"><col style="width:32%"><col style="width:20%"></colgroup>
          <thead><tr><th>Date</th><th>Type</th><th>Reference</th><th>Description</th><th>Amount</th></tr></thead>
          <tbody>
            @forelse($transactionsLatest as $t)
              <tr>
                <td class="mono">{{ optional($t->created_at)->format('d M Y') }}</td>
                <td>{!! $t->type === 'credit' ? '<span class="badge badge-green">Credit</span>' : '<span class="badge badge-red">Debit</span>' !!}</td>
                <td class="mono">{{ $t->reference ?? '—' }}</td>
                <td style="color:var(--color-text-secondary)">{{ $t->description ?? '—' }}</td>
                <td class="earn">{{ $t->type === 'credit' ? '+' : '−' }} ₨ {{ number_format((float)$t->amount,2) }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="mono" style="color:var(--color-text-secondary)">No transactions yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel" id="panel-payouts">
      <div class="payout-hero">
        <div>
          <div class="payout-lbl">Available wallet balance</div>
          <div class="payout-amt">₨ {{ number_format($availableBalance,2) }}</div>
          <div class="threshold-bar">
            <div class="th-labels"><span>Threshold progress</span><span>₨ {{ number_format($availableBalance,2) }} / ₨ 1000</span></div>
            <div class="th-track"><div class="th-fill" style="width:{{ $availableBalance > 0 ? min(100, round(($availableBalance/1000)*100)) : 0 }}%"></div></div>
          </div>
          <div style="font-size:10px;color:#7A3800;margin-top:5px;display:flex;align-items:center;gap:4px"><i class="ti ti-lock" style="font-size:11px" aria-hidden="true"></i> Withdrawal unlock at ₨ 1,000</div>
        </div>
        <button class="tk-btn" style="opacity:0.5;cursor:not-allowed;padding:8px 18px;font-size:12px" disabled><i class="ti ti-cash" aria-hidden="true" style="margin-right:4px"></i> Request payout</button>
      </div>

      <div class="card" style="padding:0;margin-bottom:0">
        <table>
          <colgroup><col style="width:16%"><col style="width:16%"><col style="width:18%"><col style="width:24%"><col style="width:14%"><col style="width:12%"></colgroup>
          <thead><tr><th>Date</th><th>Amount</th><th>Method</th><th>Txn ID</th><th>Status</th><th></th></tr></thead>
          <tbody>
            @forelse($payoutsLatest as $p)
              <tr>
                <td class="mono">{{ optional($p->created_at)->format('d M Y') }}</td>
                <td class="earn">₨ {{ number_format((float)$p->amount,2) }}</td>
                <td><span style="color:var(--color-text-secondary)">{{ ucfirst(str_replace(['jazzcash','easypaisa','bank'], ['JazzCash','EasyPaisa','Bank'], $p->method)) }}</span></td>
                <td class="mono">{{ $p->transaction_id ?? '—' }}</td>
                <td>
                  @if($p->status === 'paid')<span class="badge badge-green">Paid</span>
                  @elseif($p->status === 'approved')<span class="badge badge-blue">Approved</span>
                  @elseif($p->status === 'rejected')<span class="badge badge-red">Rejected</span>
                  @else<span class="badge badge-amber">Pending</span>
                  @endif
                </td>
                <td><button class="icon-btn orange" type="button"><i class="ti ti-receipt" aria-hidden="true"></i></button></td>
              </tr>
            @empty
              <tr><td colspan="6" class="mono" style="color:var(--color-text-secondary)">No payouts yet</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel" id="panel-ledger"></div>
    <div class="panel" id="panel-shippers"></div>
    <div class="panel" id="panel-toolkit"></div>
    <div class="panel" id="panel-notifications"></div>
    <div class="panel" id="panel-reports"></div>
    <div class="panel" id="panel-profile"></div>
  </div>
</div>

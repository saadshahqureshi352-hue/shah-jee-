<x-filament-panels::page>
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes pulseGreen {
    0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.3); }
    50% { box-shadow: 0 0 15px 4px rgba(16,185,129,0.15); }
}
@keyframes pulseRed {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.3); }
    50% { box-shadow: 0 0 15px 4px rgba(239,68,68,0.15); }
}
.courier-card {
    transition: all 0.3s ease;
}
.courier-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important;
}
.courier-card.on-card {
    border-color: #10b981 !important;
    animation: pulseGreen 3s infinite;
}
.courier-card.off-card {
    border-color: #ef4444 !important;
    animation: pulseRed 3s infinite;
    opacity: 0.7;
}
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}
.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #d1d5db;
    transition: 0.3s;
    border-radius: 28px;
}
.toggle-slider:before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: 0.3s;
    border-radius: 50%;
}
.toggle-switch input:checked + .toggle-slider {
    background-color: #10b981;
}
.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(24px);
}
.modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease-out;
}
.modal-box {
    background: white;
    border-radius: 18px;
    padding: 28px;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}
.api-input {
    border: 1px solid #d1d5db;
    border-radius: 10px;
    padding: 10px 14px;
    width: 100%;
    font-size: 13px;
    transition: border-color 0.2s;
    font-family: 'SF Mono','Consolas',monospace;
}
.api-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
}
.stat-mini {
    text-align: center;
    padding: 12px;
    border-radius: 12px;
}
</style>

@php $couriers = $this->getCouriers(); @endphp

<div class="space-y-5" style="padding:0 4px;">

    {{-- Header --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:20px 24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);display:flex;align-items:center;justify-content:space-between;">
        <div>
            <h2 style="font-size:20px;font-weight:800;color:#111827;margin:0;">🚛 Courier Hub</h2>
            <p style="font-size:13px;color:#6b7280;margin:4px 0 0;">Manage courier integrations, API keys, ON/OFF toggle aur Profit Matrix</p>
        </div>
        <button onclick="openAddCourierModal()"
            style="background:linear-gradient(135deg,#3b82f6,#6366f1);color:white;border:none;padding:12px 24px;border-radius:12px;font-size:14px;font-weight:600;cursor:pointer;transition:transform 0.2s;"
            onmouseover="this.style.transform='scale(1.03)'"
            onmouseout="this.style.transform='scale(1)'">
            ➕ Add New Courier
        </button>
    </div>

    {{-- Courier Cards Row --}}
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;">
        @forelse($couriers as $idx => $courier)
        <div class="courier-card {{ $courier->is_active ? 'on-card' : 'off-card' }}"
            style="background:white;border:2px solid #e5e7eb;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);animation:fadeIn 0.5s ease-out {{ $idx * 0.1 }}s both;">
            {{-- Top Row: Name + Toggle --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:52px;height:52px;border-radius:14px;background:linear-gradient(135deg,{{ $courier->is_active ? '#ecfdf5,#d1fae5' : '#fef2f2,#fee2e2' }});display:flex;align-items:center;justify-content:center;font-size:24px;">
                        🚚
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:800;color:#111827;">{{ $courier->courier_name }}</div>
                        <div style="font-size:12px;color:#6b7280;">
                            {{ $courier->total_orders }} total orders
                            @if($courier->today_orders > 0)
                                &nbsp;|&nbsp; {{ $courier->today_orders }} today
                            @endif
                        </div>
                    </div>
                </div>
                <label class="toggle-switch" style="cursor:pointer;">
                    <input type="checkbox" {{ $courier->is_active ? 'checked' : '' }}
                        onchange="toggleCourier({{ $courier->id }}, this.checked)">
                    <span class="toggle-slider"></span>
                </label>
            </div>

            @if(!$courier->is_active)
            <div style="background:#fef2f2;border-radius:10px;padding:10px 14px;margin-bottom:14px;font-size:12px;color:#991b1b;display:flex;align-items:center;gap:8px;">
                ⚠️ OFF hai — checkout par shipper ko nahi dikhega
            </div>
            @endif

            {{-- Stats Grid --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:14px;">
                <div class="stat-mini" style="background:#eff6ff;">
                    <div style="font-size:10px;color:#6b7280;text-transform:uppercase;font-weight:600;">Delivery Rate</div>
                    <div style="font-size:22px;font-weight:800;color:{{ ($courier->delivery_rate ?? 0) >= 60 ? '#10b981' : '#ef4444' }};">
                        {{ $courier->delivery_rate ?? 0 }}%
                    </div>
                </div>
                <div class="stat-mini" style="background:#f5f3ff;">
                    <div style="font-size:10px;color:#6b7280;text-transform:uppercase;font-weight:600;">Per Order Profit</div>
                    <div style="font-size:22px;font-weight:800;color:#8b5cf6;">Rs {{ number_format($courier->per_order_profit ?? 0) }}</div>
                </div>
                <div class="stat-mini" style="background:{{ ($courier->total_profit ?? 0) >= 0 ? '#f0fdf4' : '#fef2f2' }};">
                    <div style="font-size:10px;color:#6b7280;text-transform:uppercase;font-weight:600;">Total Profit</div>
                    <div style="font-size:22px;font-weight:800;color:{{ ($courier->total_profit ?? 0) >= 0 ? '#10b981' : '#ef4444' }};">
                        Rs {{ number_format(max(0, $courier->total_profit ?? 0)) }}
                    </div>
                </div>
            </div>

            {{-- Profit Matrix --}}
            <div style="background:#f8fafc;border-radius:14px;padding:16px;margin-bottom:14px;">
                <div style="font-size:12px;font-weight:700;color:#374151;margin-bottom:10px;">💎 Profit Matrix</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
                    <div>
                        <div style="font-size:10px;color:#6b7280;text-transform:uppercase;">Shipper Charge</div>
                        <div style="font-size:16px;font-weight:700;color:#8b5cf6;">Rs {{ number_format($courier->avg_shipper_charge ?? 0) }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#6b7280;text-transform:uppercase;">Courier Cost</div>
                        <div style="font-size:16px;font-weight:700;color:#ef4444;">Rs {{ number_format($courier->avg_courier_cost ?? 0) }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#6b7280;text-transform:uppercase;">Profit/Order</div>
                        <div style="font-size:16px;font-weight:700;color:#10b981;">Rs {{ number_format($courier->per_order_profit ?? 0) }}</div>
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;margin-top:10px;padding-top:10px;border-top:1px solid #e5e7eb;font-size:11px;color:#6b7280;">
                    <span>Total Shipper Charges: <strong style="color:#8b5cf6;">Rs {{ number_format($courier->total_shipper_charge ?? 0) }}</strong></span>
                    <span>Total Courier Costs: <strong style="color:#ef4444;">Rs {{ number_format($courier->total_courier_cost ?? 0) }}</strong></span>
                    <span>Today's Profit: <strong style="color:#10b981;">Rs {{ number_format(max(0, $courier->today_profit ?? 0)) }}</strong></span>
                </div>
            </div>

            {{-- API Key Section --}}
            <div style="background:#f8fafc;border-radius:14px;padding:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="font-size:12px;font-weight:700;color:#374151;">🔑 API Keys</div>
                    <button onclick="event.stopPropagation(); openApiModal({{ $courier->id }}, '{{ $courier->courier_name }}', '{{ $courier->api_key ?? '' }}', '{{ $courier->api_secret ?? '' }}', '{{ $courier->account_number ?? '' }}')"
                        style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;transition:background 0.2s;"
                        onmouseover="this.style.background='#dbeafe'"
                        onmouseout="this.style.background='#eff6ff'">
                        ✏️ Update API Key
                    </button>
                </div>
                <div style="display:flex;gap:20px;font-size:12px;">
                    <div>
                        <span style="color:#9ca3af;">API Key:</span>
                        <span style="font-family:'SF Mono','Consolas',monospace;color:#374151;font-weight:500;">
                            {{ !empty($courier->api_key) ? substr($courier->api_key, 0, 12) . '••••••••' : '—' }}
                        </span>
                    </div>
                    <div>
                        <span style="color:#9ca3af;">Account:</span>
                        <span style="font-family:'SF Mono','Consolas',monospace;color:#374151;font-weight:500;">
                            {{ $courier->account_number ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div style="display:flex;gap:10px;margin-top:14px;">
                <a href="/admin/rate-matrices" style="flex:1;text-align:center;background:#f3f4f6;border-radius:10px;padding:10px;font-size:12px;color:#374151;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    📊 Rate Matrix
                </a>
                <a href="/admin/cod-reconciliations" style="flex:1;text-align:center;background:#f3f4f6;border-radius:10px;padding:10px;font-size:12px;color:#374151;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    💵 COD Recon
                </a>
                <a href="/admin/payouts" style="flex:1;text-align:center;background:#f3f4f6;border-radius:10px;padding:10px;font-size:12px;color:#374151;font-weight:600;text-decoration:none;transition:background 0.2s;"
                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                    💰 Payouts
                </a>
            </div>
        </div>
        @empty
        <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:60px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
            <div style="font-size:48px;margin-bottom:16px;">🚛</div>
            <div style="font-size:16px;font-weight:600;color:#374151;">Koi courier setup nahi hai</div>
            <div style="font-size:13px;color:#9ca3af;margin-top:6px;">"Add New Courier" button se pehla courier add karein</div>
        </div>
        @endforelse
    </div>

    {{-- Summary Section --}}
    @if(count($couriers) > 0)
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h3 style="font-size:16px;font-weight:700;color:#111827;margin:0 0 16px 0;">📈 Combined Courier Analytics</h3>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            <div class="stat-mini" style="background:#eff6ff;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Active Couriers</div>
                <div style="font-size:28px;font-weight:800;color:#3b82f6;">{{ $couriers->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-mini" style="background:#f5f3ff;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Total Profit (All)</div>
                <div style="font-size:28px;font-weight:800;color:#8b5cf6;">Rs {{ number_format($couriers->sum('total_profit')) }}</div>
            </div>
            <div class="stat-mini" style="background:#f0fdf4;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Best Courier</div>
                @php $best = $couriers->sortByDesc('delivery_rate')->first(); @endphp
                <div style="font-size:20px;font-weight:800;color:#10b981;">{{ $best->courier_name ?? '—' }} ({{ $best->delivery_rate ?? 0 }}%)</div>
            </div>
            <div class="stat-mini" style="background:#fef3c7;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Needs Attention</div>
                @php $worst = $couriers->where('delivery_rate', '>', 0)->sortBy('delivery_rate')->first(); @endphp
                <div style="font-size:20px;font-weight:800;color:#d97706;">{{ $worst && $worst->delivery_rate < 60 ? $worst->courier_name : '✅ All Good' }}</div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- ==================== API KEY MODAL ==================== --}}
<div id="api-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">🔑 Update API Key - <span id="api-modal-courier-name"></span></h3>
            <button onclick="closeApiModal()" style="background:transparent;border:none;font-size:24px;cursor:pointer;color:#9ca3af;line-height:1;">✕</button>
        </div>
        <input type="hidden" id="api-courier-id">
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">API Key</label>
                <input type="text" id="api-key-input" class="api-input" placeholder="Enter API Key">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">API Secret</label>
                <input type="text" id="api-secret-input" class="api-input" placeholder="Enter API Secret">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Account Number</label>
                <input type="text" id="api-account-input" class="api-input" placeholder="Enter Account Number">
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:20px;">
            <button onclick="closeApiModal()" style="flex:1;background:#f3f4f6;border:1px solid #e5e7eb;padding:12px;border-radius:10px;font-size:14px;font-weight:600;color:#374151;cursor:pointer;">Cancel</button>
            <button onclick="saveApiKey()" style="flex:1;background:#3b82f6;border:none;padding:12px;border-radius:10px;font-size:14px;font-weight:600;color:white;cursor:pointer;">💾 Save API Key</button>
        </div>
    </div>
</div>

{{-- ==================== ADD COURIER MODAL ==================== --}}
<div id="add-courier-modal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:700;color:#111827;margin:0;">➕ Add New Courier</h3>
            <button onclick="closeAddCourierModal()" style="background:transparent;border:none;font-size:24px;cursor:pointer;color:#9ca3af;line-height:1;">✕</button>
        </div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Courier Name *</label>
                <input type="text" id="new-courier-name" class="api-input" placeholder="e.g. TCS, Leopards, Trax">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">API Key</label>
                <input type="text" id="new-courier-api-key" class="api-input" placeholder="API Key (optional)">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">API Secret</label>
                <input type="text" id="new-courier-api-secret" class="api-input" placeholder="API Secret (optional)">
            </div>
            <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:6px;">Account Number</label>
                <input type="text" id="new-courier-account" class="api-input" placeholder="Account Number (optional)">
            </div>
        </div>
        <div style="display:flex;gap:10px;margin-top:20px;">
            <button onclick="closeAddCourierModal()" style="flex:1;background:#f3f4f6;border:1px solid #e5e7eb;padding:12px;border-radius:10px;font-size:14px;font-weight:600;color:#374151;cursor:pointer;">Cancel</button>
            <button onclick="addCourier()" style="flex:1;background:linear-gradient(135deg,#3b82f6,#6366f1);border:none;padding:12px;border-radius:10px;font-size:14px;font-weight:600;color:white;cursor:pointer;">➕ Add Courier</button>
        </div>
    </div>
</div>

<script>
function toggleCourier(id, status) {
    fetch('/admin/api/toggle-courier', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
        },
        body: JSON.stringify({ id: id, status: status }),
    }).then(r => r.json()).then(data => {
        location.reload();
    });
}

function openApiModal(id, name, key, secret, account) {
    document.getElementById('api-courier-id').value = id;
    document.getElementById('api-modal-courier-name').textContent = name;
    document.getElementById('api-key-input').value = key || '';
    document.getElementById('api-secret-input').value = secret || '';
    document.getElementById('api-account-input').value = account || '';
    document.getElementById('api-modal').style.display = 'flex';
}

function closeApiModal() {
    document.getElementById('api-modal').style.display = 'none';
}

async function saveApiKey() {
    const btn = event.target;
    btn.disabled = true;
    btn.textContent = '⏳ Saving...';

    try {
        const resp = await fetch('/admin/api/save-api-key', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                courierId: parseInt(document.getElementById('api-courier-id').value),
                data: {
                    api_key: document.getElementById('api-key-input').value,
                    api_secret: document.getElementById('api-secret-input').value,
                    account_number: document.getElementById('api-account-input').value,
                    key_type: 'api_key',
                    is_active: true,
                }
            }),
        });

        if (resp.ok) {
            btn.textContent = '✅ Saved!';
            btn.style.background = '#10b981';
            setTimeout(() => location.reload(), 1000);
        } else {
            btn.textContent = '❌ Error';
            btn.style.background = '#ef4444';
            btn.disabled = false;
        }
    } catch (err) {
        btn.textContent = '❌ Error';
        btn.style.background = '#ef4444';
        btn.disabled = false;
    }
}

function openAddCourierModal() {
    document.getElementById('add-courier-modal').style.display = 'flex';
}

function closeAddCourierModal() {
    document.getElementById('add-courier-modal').style.display = 'none';
}

async function addCourier() {
    const name = document.getElementById('new-courier-name').value.trim();
    if (!name) {
        alert('Please enter a courier name');
        return;
    }

    const btn = event.target;
    btn.disabled = true;
    btn.textContent = '⏳ Adding...';

    try {
        const resp = await fetch('/admin/api/add-courier', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                data: {
                    courier_name: name,
                    api_key: document.getElementById('new-courier-api-key').value,
                    api_secret: document.getElementById('new-courier-api-secret').value,
                    account_number: document.getElementById('new-courier-account').value,
                }
            }),
        });

        if (resp.ok) {
            btn.textContent = '✅ Added!';
            btn.style.background = '#10b981';
            setTimeout(() => location.reload(), 1000);
        } else {
            btn.textContent = '❌ Error';
            btn.disabled = false;
        }
    } catch (err) {
        btn.textContent = '❌ Error';
        btn.disabled = false;
    }
}
</script>

</x-filament-panels::page>
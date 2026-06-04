<x-filament-panels::page>
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideDown {
    from { opacity: 0; max-height: 0; }
    to { opacity: 1; max-height: 2000px; }
}
.shipper-card {
    transition: all 0.3s ease;
    cursor: pointer;
}
.shipper-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
.shipper-card.active-card {
    border-color: #3b82f6 !important;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.15) !important;
}
.rate-input {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 6px 10px;
    width: 100%;
    font-size: 13px;
    text-align: right;
    transition: border-color 0.2s;
}
.rate-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.2);
}
.profit-positive { color: #10b981; }
.profit-negative { color: #ef4444; }
.discount-badge {
    background: #dcfce7;
    color: #166534;
    padding: 2px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 700;
}
.rate-table-wrapper {
    animation: slideDown 0.5s ease-out;
    overflow: hidden;
}
</style>

@php
$shippers = $this->getShippers();
@endphp

<div class="space-y-5" style="padding:0 4px;">

    {{-- Header --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:20px 24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h2 style="font-size:20px;font-weight:800;color:#111827;margin:0;">🏪 Shipper-Specific Rates</h2>
                <p style="font-size:13px;color:#6b7280;margin:4px 0 0;">Har shipper ka alag rate yaad rakhta hai — 150+ orders walon ko discount do</p>
            </div>
        </div>
    </div>

    {{-- Shippers List --}}
    @forelse($shippers as $shipper)
    @php
        $monthlyOrders = $this->getShipperMonthlyOrders($shipper->id);
        $shipperRates = $this->getShipperRates($shipper->id);
        $hasDiscount = !empty($shipperRates);
        $globalRates = $this->getGlobalRates();
    @endphp
    <div class="shipper-card" id="shipper-card-{{ $shipper->id }}" style="background:white;border:2px solid #e5e7eb;border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);" onclick="toggleShipperRates({{ $shipper->id }})">
        {{-- Shipper Header --}}
        <div style="padding:18px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#eff6ff,#dbeafe);display:flex;align-items:center;justify-content:center;font-size:20px;">🏪</div>
                <div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span style="font-size:16px;font-weight:700;color:#111827;">{{ $shipper->name }}</span>
                        @if($hasDiscount)
                            <span class="discount-badge">🎯 Custom Rate</span>
                        @endif
                        @if($monthlyOrders >= 150)
                            <span style="background:#fef3c7;color:#92400e;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:700;">⭐ 150+ Orders</span>
                        @endif
                    </div>
                    <div style="font-size:12px;color:#6b7280;margin-top:2px;">
                        {{ $shipper->email }} &nbsp;|&nbsp; Monthly: {{ $monthlyOrders }} orders
                        &nbsp;|&nbsp; Total: {{ $shipper->monthly_orders ?? 0 }} orders
                    </div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                @if($monthlyOrders >= 100)
                    <div style="background:#f0fdf4;padding:8px 16px;border-radius:10px;font-size:13px;color:#166534;font-weight:700;">
                        💰 Volume Shipper — Discount Eligible
                    </div>
                @endif
                <span style="font-size:20px;transition:transform 0.3s;" id="chevron-{{ $shipper->id }}">▼</span>
            </div>
        </div>

        {{-- Rate Table (Hidden by default) --}}
        <div id="rate-table-{{ $shipper->id }}" style="display:none;">
            <div style="border-top:1px solid #e5e7eb;overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;" id="shipper-rate-table-{{ $shipper->id }}">
                    <thead>
                        <tr style="background:#f9fafb;">
                            <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Courier</th>
                            <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Weight</th>
                            <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#9ca3af;text-transform:uppercase;">Global Rate</th>
                            <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#8b5cf6;text-transform:uppercase;">Custom Rate</th>
                            <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#f97316;text-transform:uppercase;">Courier Cost</th>
                            <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#10b981;text-transform:uppercase;">Your Profit</th>
                            <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#06b6d4;text-transform:uppercase;">COD %</th>
                            <th style="text-align:center;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($globalRates as $idx => $gr)
                        @php
                            $matrixId = $gr->id;
                            $customRate = $shipperRates[$matrixId] ?? null;
                            $globalCharge = $gr->shipper_charge ?: $gr->rate;
                            $courierCost = $gr->courier_cost ?? 0;
                            $displayRate = $customRate ?? $globalCharge;
                            $profit = $displayRate - $courierCost;
                            $discount = $globalCharge > 0 ? round((($globalCharge - $displayRate) / $globalCharge) * 100) : 0;
                        @endphp
                        <tr class="rate-row-anim" style="border-top:1px solid #f3f4f6;transition:background 0.2s;animation:fadeIn 0.4s ease-out {{ $idx * 0.03 }}s both;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding:10px 16px;font-weight:600;color:#111827;font-size:13px;">{{ $gr->courierIntegration->courier_name ?? '—' }}</td>
                            <td style="padding:10px 16px;font-size:13px;color:#374151;">{{ $gr->weight_category }}</td>
                            <td style="padding:10px 16px;text-align:right;color:#9ca3af;font-size:13px;">Rs {{ number_format($globalCharge) }}</td>
                            <td style="padding:10px 12px;">
                                <input type="number" step="0.01" class="rate-input"
                                    data-matrix-id="{{ $matrixId }}"
                                    data-field="custom_rate"
                                    data-global="{{ $globalCharge }}"
                                    data-cost="{{ $courierCost }}"
                                    value="{{ $displayRate }}"
                                    onchange="recalcShipperProfit(this, {{ $shipper->id }})"
                                    style="color:#8b5cf6;font-weight:600;{{ $customRate ? 'background:#f5f3ff;' : '' }}">
                            </td>
                            <td style="padding:10px 16px;text-align:right;color:#f97316;font-weight:600;font-size:13px;">Rs {{ number_format($courierCost) }}</td>
                            <td style="padding:10px 16px;text-align:right;font-weight:700;font-size:14px;" class="profit-cell {{ $profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                                Rs {{ number_format(max(0, $profit)) }}
                                @if($discount > 0)
                                    <div style="font-size:10px;color:#f97316;">(-{{ $discount }}% off)</div>
                                @endif
                            </td>
                            <td style="padding:10px 12px;">
                                <input type="number" step="0.01" class="rate-input"
                                    data-matrix-id="{{ $matrixId }}"
                                    data-field="custom_cod_percent"
                                    value="{{ $gr->shipper_cod_percent ?? $gr->cod_commission_percent ?? 0 }}"
                                    style="color:#06b6d4;font-weight:600;font-size:12px;width:80px;">
                            </td>
                            <td style="padding:10px 16px;text-align:center;">
                                <input type="checkbox" checked
                                    data-matrix-id="{{ $matrixId }}"
                                    data-field="is_active"
                                    style="width:18px;height:18px;cursor:pointer;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action Row --}}
            <div style="padding:14px 24px;background:#f8fafc;border-top:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
                <div style="font-size:12px;color:#6b7280;">
                    💡 Custom rate sirf is shipper ke liye apply hogi — Global rate override
                </div>
                <button onclick="event.stopPropagation(); saveShipperRates({{ $shipper->id }})"
                    style="background:#3b82f6;color:white;border:none;padding:10px 24px;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;transition:background 0.2s;"
                    onmouseover="this.style.background='#2563eb'"
                    onmouseout="this.style.background='#3b82f6'">
                    💾 Save Rates for {{ $shipper->name }}
                </button>
            </div>
        </div>
    </div>
    @empty
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:60px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="font-size:48px;margin-bottom:16px;">👤</div>
        <div style="font-size:16px;font-weight:600;color:#374151;">Koi approved shipper nahi hai</div>
        <div style="font-size:13px;color:#9ca3af;margin-top:6px;">Pehle shippers approve karein</div>
    </div>
    @endforelse

</div>

<script>
function toggleShipperRates(shipperId) {
    const table = document.getElementById('rate-table-' + shipperId);
    const card = document.getElementById('shipper-card-' + shipperId);
    const chevron = document.getElementById('chevron-' + shipperId);

    if (table.style.display === 'none') {
        table.style.display = 'block';
        card.classList.add('active-card');
        chevron.style.transform = 'rotate(180deg)';
    } else {
        table.style.display = 'none';
        card.classList.remove('active-card');
        chevron.style.transform = 'rotate(0deg)';
    }
}

function recalcShipperProfit(input, shipperId) {
    const row = input.closest('tr');
    const costInput = row.querySelector('[data-field]');
    const profitCell = row.querySelector('.profit-cell');
    const globalRate = parseFloat(input.getAttribute('data-global')) || 0;
    const courierCost = parseFloat(input.getAttribute('data-cost')) || 0;
    const customRate = parseFloat(input.value) || 0;
    const profit = customRate - courierCost;
    const discount = globalRate > 0 ? Math.round(((globalRate - customRate) / globalRate) * 100) : 0;

    profitCell.innerHTML = 'Rs ' + Math.max(0, profit).toLocaleString('en-US', {maximumFractionDigits: 0}) +
        (discount > 0 ? '<div style="font-size:10px;color:#f97316;">(-' + discount + '% off)</div>' : '');
    profitCell.className = 'profit-cell ' + (profit >= 0 ? 'profit-positive' : 'profit-negative');
    profitCell.style.cssText = 'padding:10px 16px;text-align:right;font-weight:700;font-size:14px;';
}

async function saveShipperRates(shipperId) {
    const table = document.getElementById('shipper-rate-table-' + shipperId);
    const rows = table.querySelectorAll('tbody tr');
    const rates = [];

    rows.forEach(row => {
        const matrixId = parseInt(row.querySelector('[data-matrix-id]').getAttribute('data-matrix-id'));
        const customRate = parseFloat(row.querySelector('[data-field="custom_rate"]').value) || 0;
        const codPercent = parseFloat(row.querySelector('[data-field="custom_cod_percent"]').value) || null;
        const isActive = row.querySelector('[data-field="is_active"]').checked;

        rates.push({
            courier_rate_matrix_id: matrixId,
            custom_rate: customRate,
            custom_cod_percent: codPercent,
            is_active: isActive,
            notes: null,
        });
    });

    const btn = event.target;
    const origText = btn.textContent;
    btn.disabled = true;
    btn.textContent = '⏳ Saving...';

    try {
        const response = await fetch('/admin/api/shipper-rates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ user_id: shipperId, rates: rates }),
        });

        if (response.ok) {
            btn.textContent = '✅ Saved!';
            btn.style.background = '#10b981';
            setTimeout(() => {
                btn.textContent = origText;
                btn.style.background = '#3b82f6';
                btn.disabled = false;
            }, 2000);
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
</script>

</x-filament-panels::page>
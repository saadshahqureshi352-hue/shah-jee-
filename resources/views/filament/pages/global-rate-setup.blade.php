<x-filament-panels::page>
<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.rate-row-anim { animation: fadeIn 0.4s ease-out both; }
.rate-row-anim:nth-child(1) { animation-delay: 0.02s; }
.rate-row-anim:nth-child(2) { animation-delay: 0.04s; }
.rate-row-anim:nth-child(3) { animation-delay: 0.06s; }
.rate-row-anim:nth-child(4) { animation-delay: 0.08s; }
.rate-row-anim:nth-child(5) { animation-delay: 0.10s; }
.rate-row-anim:nth-child(6) { animation-delay: 0.12s; }
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
</style>

@php
$couriers = $this->getCouriers();
$weightCategories = $this->getWeightCategories();
$existingRates = collect($this->getRateData())->groupBy('courier_integration_id');
@endphp

<div class="space-y-5" style="padding:0 4px;">

    {{-- Header --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:20px 24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h2 style="font-size:20px;font-weight:800;color:#111827;margin:0;">🌍 Global Rate Setup</h2>
                <p style="font-size:13px;color:#6b7280;margin:4px 0 0;">Poori website ka master rate table — yahan set karo, automatically apply hoga</p>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="saveAllRates()" style="background:#3b82f6;color:white;border:none;padding:10px 24px;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                    💾 Save All Rates
                </button>
            </div>
        </div>
    </div>

    @forelse($couriers as $courier)
    @php $courierRates = $existingRates->get($courier->id, collect()); @endphp
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        {{-- Courier Header --}}
        <div style="padding:16px 24px;background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid #e5e7eb;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <span style="font-size:16px;font-weight:700;color:#111827;">🚚 {{ $courier->courier_name }}</span>
                <span style="font-size:12px;color:#6b7280;margin-left:8px;">Rate Table</span>
            </div>
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="background:#eff6ff;padding:6px 14px;border-radius:8px;font-size:12px;color:#1d4ed8;font-weight:600;">
                    Charges: Shipper → Admin
                </div>
                <div style="background:#fef3c7;padding:6px 14px;border-radius:8px;font-size:12px;color:#92400e;font-weight:600;">
                    Costs: Admin → Courier
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;" data-courier-id="{{ $courier->id }}">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;width:140px;">Weight Category</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Weight Range</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#8b5cf6;text-transform:uppercase;">Shipper Charge (Rs)</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#f97316;text-transform:uppercase;">Courier Cost (Rs)</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#10b981;text-transform:uppercase;">Profit (Rs)</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#06b6d4;text-transform:uppercase;">COD Courier %</th>
                        <th style="text-align:right;padding:12px 16px;font-size:11px;font-weight:700;color:#ec4899;text-transform:uppercase;">COD Shipper %</th>
                        <th style="text-align:center;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Active</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($weightCategories as $wcIdx => $wc)
                    @php
                        $existing = $courierRates->first(function($r) use ($wc) {
                            return $r['weight_category'] == $wc['label'];
                        });
                        $shipperCharge = $existing['shipper_charge'] ?? $existing['rate'] ?? 0;
                        $courierCost = $existing['courier_cost'] ?? 0;
                        $profit = $shipperCharge - $courierCost;
                        $codCourier = $existing['cod_commission_percent'] ?? 0;
                        $codShipper = $existing['shipper_cod_percent'] ?? 0;
                    @endphp
                    <tr class="rate-row-anim" style="border-top:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:10px 16px;font-weight:600;color:#111827;font-size:13px;">{{ $wc['label'] }}</td>
                        <td style="padding:10px 16px;text-align:right;color:#9ca3af;font-size:12px;">{{ $wc['from'] }} - {{ $wc['to'] }} kg</td>
                        <td style="padding:10px 12px;">
                            <input type="number" step="0.01" class="rate-input"
                                data-courier="{{ $courier->id }}"
                                data-weight="{{ $wc['label'] }}"
                                data-weight-from="{{ $wc['from'] }}"
                                data-weight-to="{{ $wc['to'] }}"
                                data-field="shipper_charge"
                                value="{{ $shipperCharge }}"
                                onchange="recalcProfit(this)"
                                style="color:#8b5cf6;font-weight:600;">
                        </td>
                        <td style="padding:10px 12px;">
                            <input type="number" step="0.01" class="rate-input"
                                data-courier="{{ $courier->id }}"
                                data-weight="{{ $wc['label'] }}"
                                data-weight-from="{{ $wc['from'] }}"
                                data-weight-to="{{ $wc['to'] }}"
                                data-field="courier_cost"
                                value="{{ $courierCost }}"
                                onchange="recalcProfit(this)"
                                style="color:#f97316;font-weight:600;">
                        </td>
                        <td style="padding:10px 16px;text-align:right;font-weight:700;font-size:14px;" class="profit-cell {{ $profit >= 0 ? 'profit-positive' : 'profit-negative' }}">
                            Rs {{ number_format(max(0, $profit)) }}
                        </td>
                        <td style="padding:10px 12px;">
                            <input type="number" step="0.01" class="rate-input"
                                data-courier="{{ $courier->id }}"
                                data-weight="{{ $wc['label'] }}"
                                data-weight-from="{{ $wc['from'] }}"
                                data-weight-to="{{ $wc['to'] }}"
                                data-field="cod_commission_percent"
                                value="{{ $codCourier }}"
                                style="color:#06b6d4;font-weight:600;font-size:12px;width:80px;">
                        </td>
                        <td style="padding:10px 12px;">
                            <input type="number" step="0.01" class="rate-input"
                                data-courier="{{ $courier->id }}"
                                data-weight="{{ $wc['label'] }}"
                                data-weight-from="{{ $wc['from'] }}"
                                data-weight-to="{{ $wc['to'] }}"
                                data-field="shipper_cod_percent"
                                value="{{ $codShipper }}"
                                style="color:#ec4899;font-weight:600;font-size:12px;width:80px;">
                        </td>
                        <td style="padding:10px 16px;text-align:center;">
                            <input type="checkbox" checked
                                data-courier="{{ $courier->id }}"
                                data-weight="{{ $wc['label'] }}"
                                data-weight-from="{{ $wc['from'] }}"
                                data-weight-to="{{ $wc['to'] }}"
                                data-field="is_active"
                                style="width:18px;height:18px;cursor:pointer;">
                        </td>
                    </tr>
                    @php
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Summary Row --}}
        <div style="padding:12px 24px;background:#f8fafc;border-top:2px solid #e5e7eb;display:flex;gap:20px;font-size:12px;color:#6b7280;">
            <span>💡 <strong>Tip:</strong> Shipper Charge = jo shipper se lete ho | Courier Cost = jo courier ko dete ho | Profit = Charge - Cost</span>
        </div>
    </div>
    @empty
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:60px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="font-size:48px;margin-bottom:16px;">📋</div>
        <div style="font-size:16px;font-weight:600;color:#374151;">Koi active courier nahi hai</div>
        <div style="font-size:13px;color:#9ca3af;margin-top:6px;">Pehle Courier Hub mein ja kar couriers activate karein</div>
    </div>
    @endforelse

    {{-- COD Section --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <h3 style="font-size:16px;font-weight:700;color:#111827;margin:0 0 4px 0;">💵 COD Commission Setup</h3>
        <p style="font-size:13px;color:#6b7280;margin:0 0 16px 0;">Courier 1.25% leta hai lekin aap shipper se 1.5% lete ho — 0.25% aapka extra profit</p>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
            <div style="background:#f0fdf4;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Default COD (Courier)</div>
                <div style="font-size:24px;font-weight:800;color:#10b981;margin-top:4px;">1.25%</div>
            </div>
            <div style="background:#f5f3ff;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Default COD (Shipper)</div>
                <div style="font-size:24px;font-weight:800;color:#8b5cf6;margin-top:4px;">1.5%</div>
            </div>
            <div style="background:#fef3c7;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Extra Profit</div>
                <div style="font-size:24px;font-weight:800;color:#f59e0b;margin-top:4px;">0.25%</div>
            </div>
            <div style="background:#eff6ff;border-radius:12px;padding:16px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;font-weight:600;">Per Rs 10,000 COD</div>
                <div style="font-size:24px;font-weight:800;color:#3b82f6;margin-top:4px;">Rs 25</div>
            </div>
        </div>
    </div>
</div>

<script>
function recalcProfit(input) {
    const row = input.closest('tr');
    const chargeInput = row.querySelector('[data-field="shipper_charge"]');
    const costInput = row.querySelector('[data-field="courier_cost"]');
    const profitCell = row.querySelector('.profit-cell');

    const charge = parseFloat(chargeInput.value) || 0;
    const cost = parseFloat(costInput.value) || 0;
    const profit = charge - cost;

    profitCell.textContent = 'Rs ' + Math.max(0, profit).toLocaleString('en-US', {maximumFractionDigits: 0});
    profitCell.className = 'profit-cell ' + (profit >= 0 ? 'profit-positive' : 'profit-negative');
    profitCell.style.cssText = 'padding:10px 16px;text-align:right;font-weight:700;font-size:14px;';
}

async function saveAllRates() {
    const tables = document.querySelectorAll('table[data-courier-id]');
    const rates = [];

    tables.forEach(table => {
        const courierId = table.getAttribute('data-courier-id');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const shipperCharge = parseFloat(row.querySelector('[data-field="shipper_charge"]').value) || 0;
            const courierCost = parseFloat(row.querySelector('[data-field="courier_cost"]').value) || 0;
            const codCommission = parseFloat(row.querySelector('[data-field="cod_commission_percent"]').value) || 0;
            const shipperCodPercent = parseFloat(row.querySelector('[data-field="shipper_cod_percent"]').value) || 0;
            const isActive = row.querySelector('[data-field="is_active"]').checked;

            const weightLabel = row.querySelector('[data-weight]').getAttribute('data-weight');
            const weightFrom = parseFloat(row.querySelector('[data-weight-from]').getAttribute('data-weight-from'));
            const weightTo = parseFloat(row.querySelector('[data-weight-to]').getAttribute('data-weight-to'));

            rates.push({
                courier_integration_id: parseInt(courierId),
                weight_category: weightLabel,
                weight_from: weightFrom,
                weight_to: weightTo,
                zone: 'all',
                rate: shipperCharge,
                courier_cost: courierCost,
                shipper_charge: shipperCharge,
                cod_commission_percent: codCommission,
                shipper_cod_percent: shipperCodPercent,
                fuel_surcharge_percent: 0,
                is_active: isActive,
            });
        });
    });

    const saveBtn = event.target;
    saveBtn.disabled = true;
    saveBtn.textContent = '⏳ Saving...';

    try {
        const response = await fetch('/admin/api/global-rates', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({ rates: rates }),
        });

        if (response.ok) {
            saveBtn.textContent = '✅ Saved!';
            saveBtn.style.background = '#10b981';
            setTimeout(() => {
                saveBtn.textContent = '💾 Save All Rates';
                saveBtn.style.background = '#3b82f6';
                saveBtn.disabled = false;
            }, 2000);
        } else {
            saveBtn.textContent = '❌ Error';
            saveBtn.style.background = '#ef4444';
            saveBtn.disabled = false;
        }
    } catch (err) {
        saveBtn.textContent = '❌ Error';
        saveBtn.style.background = '#ef4444';
        saveBtn.disabled = false;
    }
}
</script>

</x-filament-panels::page>
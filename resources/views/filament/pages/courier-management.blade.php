<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.2s; }
.anim-card-2 { animation: fadeInUp 0.7s ease-out both; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
</style>

@php
    $couriers = $this->getCouriers();
    $rateMatrix = $this->getRateMatrix();
    $groupedRates = $rateMatrix->groupBy('courier_name');
    $zones = ['Local', 'Same-Day', 'Intercity', 'Remote'];
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="anim-card" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="font-size:18px;font-weight:800;color:#111827;">🔌 Courier & API Management</div>
        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Central hub for courier integrations, API keys, and rate matrices</div>
    </div>

    {{-- Couriers List --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:14px;font-weight:700;color:#111827;">🚚 Courier Integrations</div>
            <div style="font-size:11px;color:#9ca3af;">Manage courier partners, API keys, and account status</div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Courier</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Account ID</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">API Key</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($couriers as $idx => $courier)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.05 }}s both;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 16px;">
                            <div style="display:flex;align-items:center;gap:10px;">
                                <span style="font-size:22px;">🚚</span>
                                <div>
                                    <div style="font-weight:700;color:#111827;">{{ $courier->courier_name }}</div>
                                    <div style="font-size:11px;color:#9ca3af;">{{ $courier->is_active ? 'Active' : 'Disabled' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:12px 16px;font-family:monospace;font-size:12px;color:#6b7280;">{{ $courier->account_number }}</td>
                        <td style="padding:12px 16px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="font-family:monospace;font-size:11px;color:#6b7280;background:#f3f4f6;padding:4px 8px;border-radius:6px;">{{ substr($courier->api_key, 0, 20) }}...</span>
                                <button wire:click="regenerateApiKey({{ $courier->id }})"
                                    style="background:#e0e7ff;color:#4338ca;border:none;padding:4px 10px;border-radius:6px;font-size:10px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    🔄 Regenerate
                                </button>
                            </div>
                        </td>
                        <td style="padding:12px 16px;">
                            <div wire:click="toggleCourier({{ $courier->id }}, {{ $courier->is_active ? 'false' : 'true' }})"
                                style="width:44px;height:24px;border-radius:12px;cursor:pointer;display:flex;align-items:center;padding:2px;background:{{ $courier->is_active ? 'linear-gradient(135deg,#10b981,#34d399)' : '#d1d5db' }};justify-content:{{ $courier->is_active ? 'flex-end' : 'flex-start' }};transition:all 0.3s;">
                                <div style="width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                            </div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="background:{{ $courier->is_active ? '#dcfce7' : '#fee2e2' }};color:{{ $courier->is_active ? '#166534' : '#991b1b' }};padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;">
                                {{ $courier->is_active ? '🟢 Active' : '🔴 Disabled' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:40px;text-align:center;color:#9ca3af;">
                            <div style="font-size:40px;margin-bottom:10px;">🚚</div>
                            No couriers configured
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rate Matrix --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:14px;font-weight:700;color:#111827;">📊 Rate Matrix</div>
            <div style="font-size:11px;color:#9ca3af;">Shipping charges by weight and zone for each courier</div>
        </div>
        @forelse($groupedRates as $courierName => $rates)
        <div style="padding:16px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:13px;font-weight:700;color:#111827;margin-bottom:12px;">🚚 {{ $courierName }}</div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;font-size:12px;">
                    <thead>
                        <tr style="background:#f9fafb;">
                            <th style="text-align:left;padding:6px 10px;font-weight:600;color:#6b7280;font-size:10px;text-transform:uppercase;">Weight</th>
                            @foreach($zones as $zone)
                                <th style="text-align:center;padding:6px 10px;font-weight:600;color:#6b7280;font-size:10px;text-transform:uppercase;">{{ $zone }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php $uniqueWeights = $rates->pluck('weight_from')->unique()->sort(); @endphp
                        @foreach($uniqueWeights as $w)
                        <tr style="border-top:1px solid #f3f4f6;">
                            <td style="padding:6px 10px;font-weight:600;color:#374151;">{{ $w }} kg</td>
                            @foreach($zones as $zone)
                                @php
                                    $rate = $rates->where('zone_type', $zone)->where('weight_from', $w)->first();
                                @endphp
                                <td style="padding:6px 10px;text-align:center;font-weight:600;color:{{ $rate ? '#10b981' : '#9ca3af' }};">
                                    @if($rate)
                                        Rs {{ number_format($rate->charge) }}
                                    @else
                                        —
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div style="padding:40px;text-align:center;color:#9ca3af;">
            <div style="font-size:40px;margin-bottom:10px;">📊</div>
            No rate matrices configured
        </div>
        @endforelse
    </div>

</div>

</x-filament-panels::page>
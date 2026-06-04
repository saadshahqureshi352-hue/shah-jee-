<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
@keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
@keyframes progressBar { from { width: 0; } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.18s; }
.anim-card:nth-child(3) { animation-delay: 0.26s; }
.anim-card:nth-child(4) { animation-delay: 0.34s; }
.anim-card-2 { animation: fadeInUp 0.7s ease-out both; }
.anim-card-2:nth-child(1) { animation-delay: 0.15s; }
.anim-card-2:nth-child(2) { animation-delay: 0.25s; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
.stat-value { animation: countUp 0.5s ease-out both; }
.stat-value:nth-child(1) { animation-delay: 0.2s; }
.progress-fill { animation: progressBar 1.5s ease-out forwards; }
</style>

@php
    $stats = $this->getStats();
    $reconciliations = $this->getReconciliations();
    $payouts = $this->getPayouts();
    $profits = $this->getMerchantProfits();
    $totalExpected = $reconciliations->sum('reported_cash');
    $totalReceived = $reconciliations->sum('transferred_cash');
    $totalGap = $totalExpected - $totalReceived;
@endphp

<div class="space-y-6">

    {{-- Stats Row --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
        @foreach([
            ['title' => 'Total Revenue', 'value' => number_format($stats['totalRevenue']), 'icon' => '💰', 'color' => '#10b981', 'bg' => '#ecfdf5', 'sub' => 'Delivery charges collected'],
            ['title' => 'Total COD', 'value' => number_format($stats['totalCOD']), 'icon' => '💵', 'color' => '#f97316', 'bg' => '#fff7ed', 'sub' => 'Cash on delivery amount'],
            ['title' => 'Total Payouts', 'value' => number_format($stats['totalPayouts']), 'icon' => '🏦', 'color' => '#3b82f6', 'bg' => '#eff6ff', 'sub' => 'Paid to merchants'],
            ['title' => 'Pending Payouts', 'value' => number_format($stats['pendingPayouts']), 'icon' => '⏳', 'color' => '#ef4444', 'bg' => '#fef2f2', 'sub' => 'Awaiting payment'],
        ] as $i => $stat)
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;right:0;width:80px;height:80px;border-radius:0 16px 0 60px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;font-size:28px;">{{ $stat['icon'] }}</div>
            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:8px;letter-spacing:0.5px;">{{ $stat['title'] }}</div>
            <div class="stat-value" style="font-size:28px;font-weight:800;color:{{ $stat['color'] }};">Rs {{ $stat['value'] }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:6px;">{{ $stat['sub'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Reconciliation Summary --}}
    <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:14px;">📊 Reconciliation Summary</div>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
            <div style="padding:12px;background:#f0fdf4;border-radius:10px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;font-weight:500;">Expected COD</div>
                <div style="font-size:20px;font-weight:800;color:#166534;">Rs {{ number_format($totalExpected) }}</div>
            </div>
            <div style="padding:12px;background:#eff6ff;border-radius:10px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;font-weight:500;">Received</div>
                <div style="font-size:20px;font-weight:800;color:#1d4ed8;">Rs {{ number_format($totalReceived) }}</div>
            </div>
            <div style="padding:12px;background:#fef2f2;border-radius:10px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;font-weight:500;">Gap</div>
                <div style="font-size:20px;font-weight:800;color:#991b1b;">Rs {{ number_format($totalGap) }}</div>
            </div>
            <div style="padding:12px;background:#f5f3ff;border-radius:10px;text-align:center;">
                <div style="font-size:11px;color:#6b7280;font-weight:500;">Recovery Rate</div>
                <div style="font-size:20px;font-weight:800;color:#6d28d9;">{{ $totalExpected > 0 ? round(($totalReceived / $totalExpected) * 100, 1) : 0 }}%</div>
            </div>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- COD Reconciliation Table --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="padding:14px 16px;border-bottom:1px solid #e5e7eb;">
                <div style="font-size:14px;font-weight:700;color:#111827;">🔄 COD Reconciliation</div>
                <div style="font-size:11px;color:#9ca3af;">Expected vs Received from couriers</div>
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Courier</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Expected</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Received</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Gap</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reconciliations as $idx => $r)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.05 }}s both;">
                        <td style="padding:8px 12px;font-weight:600;">{{ $r->courier_name ?? '—' }}</td>
                        <td style="padding:8px 12px;color:#374151;">Rs {{ number_format($r->reported_cash ?? 0) }}</td>
                        <td style="padding:8px 12px;color:#10b981;font-weight:600;">Rs {{ number_format($r->transferred_cash ?? 0) }}</td>
                        <td style="padding:8px 12px;font-weight:600;color:{{ ($r->variance ?? 0) > 0 ? '#ef4444' : '#10b981' }};">
                            Rs {{ number_format($r->variance ?? 0) }}
                        </td>
                        <td style="padding:8px 12px;">
                            @if(($r->transferred_cash ?? 0) >= ($r->reported_cash ?? 0))
                                <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">Verified ✅</span>
                            @else
                                <span style="background:#fee2e2;color:#991b1b;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">Discrepancy ⚠️</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:32px;text-align:center;color:#9ca3af;">No records found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Payouts Table --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="padding:14px 16px;border-bottom:1px solid #e5e7eb;">
                <div style="font-size:14px;font-weight:700;color:#111827;">🏦 Recent Payouts</div>
                <div style="font-size:11px;color:#9ca3af;">Merchant payout history</div>
            </div>
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Amount</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Reference</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $idx => $p)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.05 }}s both;">
                        <td style="padding:8px 12px;font-weight:600;color:#111827;">{{ $p->merchant_name ?? '—' }}</td>
                        <td style="padding:8px 12px;font-weight:700;color:#10b981;">Rs {{ number_format($p->net_amount ?? 0) }}</td>
                        <td style="padding:8px 12px;font-family:monospace;font-size:11px;color:#6b7280;">{{ $p->payout_reference ?? '—' }}</td>
                        <td style="padding:8px 12px;">
                            @if(($p->status ?? '') === 'completed')
                                <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">Completed ✅</span>
                            @else
                                <span style="background:#fff7ed;color:#c2410c;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">Pending ⏳</span>
                            @endif
                        </td>
                        <td style="padding:8px 12px;color:#9ca3af;font-size:12px;">{{ isset($p->created_at) ? \Carbon\Carbon::parse($p->created_at)->format('d M Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:32px;text-align:center;color:#9ca3af;">No payouts found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- Merchant Profits --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:14px;font-weight:700;color:#111827;">📈 Per-Merchant Revenue</div>
            <div style="font-size:11px;color:#9ca3af;">Revenue breakdown by merchant</div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Total Shipments</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Delivery Revenue</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Total COD</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($profits as $idx => $p)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.04 }}s both;">
                        <td style="padding:10px 16px;font-weight:700;color:#111827;">{{ $p->merchant_name ?? 'Unknown' }}</td>
                        <td style="padding:10px 16px;">
                            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 12px;border-radius:99px;font-size:12px;font-weight:600;">{{ $p->total_bookings }}</span>
                        </td>
                        <td style="padding:10px 16px;font-weight:700;color:#10b981;">Rs {{ number_format($p->total_charges) }}</td>
                        <td style="padding:10px 16px;font-weight:700;color:#f97316;">Rs {{ number_format($p->total_cod) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding:40px;text-align:center;color:#9ca3af;">No data available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-filament-panels::page>
<x-filament-panels::page>
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(59,130,246,0.3); }
    50% { box-shadow: 0 0 20px 8px rgba(59,130,246,0.15); }
}
@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}
@keyframes countUp {
    from { opacity: 0; transform: scale(0.5); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes progressBar {
    from { width: 0; }
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-5px); }
}
@keyframes borderGlow {
    0%, 100% { border-color: rgba(59,130,246,0.2); }
    50% { border-color: rgba(59,130,246,0.6); }
}
.anim-card {
    animation: fadeInUp 0.6s ease-out both;
}
.anim-card:nth-child(1) { animation-delay: 0.05s; }
.anim-card:nth-child(2) { animation-delay: 0.12s; }
.anim-card:nth-child(3) { animation-delay: 0.19s; }
.anim-card:nth-child(4) { animation-delay: 0.26s; }
.anim-card-2 {
    animation: fadeInUp 0.7s ease-out both;
}
.anim-card-2:nth-child(1) { animation-delay: 0.1s; }
.anim-card-2:nth-child(2) { animation-delay: 0.2s; }
.anim-card-2:nth-child(3) { animation-delay: 0.3s; }
.card-hover {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
}
.stat-value {
    animation: countUp 0.5s ease-out both;
}
.stat-value:nth-child(1) { animation-delay: 0.2s; }
.shimmer-bg {
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    background-size: 200% 100%;
    animation: shimmer 2s infinite;
}
.progress-fill {
    animation: progressBar 1s ease-out forwards;
}
</style>

@php $d = $this->getDashboardData(); @endphp

<div class="space-y-6">

    {{-- Stats Row --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;">
        @foreach([
            ['title' => "Today's Bookings", 'value' => $d['todayBookings'], 'sub' => 'Yesterday: ' . $d['yesterdayBookings'] . ($d['todayBookings'] > $d['yesterdayBookings'] ? ' <span style="color:#10b981;">▲ up</span>' : ($d['todayBookings'] < $d['yesterdayBookings'] ? ' <span style="color:#ef4444;">▼ down</span>' : '')), 'icon' => '📦', 'color' => '#3b82f6', 'bg' => '#eff6ff'],
            ['title' => "Today's Revenue", 'value' => 'Rs ' . number_format($d['todayRevenue']), 'sub' => 'Total: Rs ' . number_format($d['totalRevenue']), 'icon' => '💰', 'color' => '#10b981', 'bg' => '#ecfdf5'],
            ['title' => 'Total COD Collected', 'value' => 'Rs ' . number_format($d['totalCOD']), 'sub' => 'Cash on delivery orders', 'icon' => '💵', 'color' => '#f97316', 'bg' => '#fff7ed'],
            ['title' => 'Net Profit', 'value' => 'Rs ' . number_format(max(0, $d['totalRevenue'] - ($d['totalRevenue'] * 0.4))), 'sub' => 'After courier costs & commissions', 'icon' => '📈', 'color' => '#8b5cf6', 'bg' => '#f5f3ff'],
        ] as $i => $stat)
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);position:relative;overflow:hidden;">
            <div style="position:absolute;top:0;right:0;width:80px;height:80px;border-radius:0 16px 0 60px;background:{{ $stat['bg'] }};display:flex;align-items:center;justify-content:center;font-size:28px;">{{ $stat['icon'] }}</div>
            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:8px;letter-spacing:0.5px;">{{ $stat['title'] }}</div>
            <div class="stat-value" style="font-size:30px;font-weight:800;color:{{ $stat['color'] }};">{{ $stat['value'] }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:6px;">{!! $stat['sub'] !!}</div>
        </div>
        @endforeach
    </div>

    {{-- Middle Row: Charts & Alerts --}}
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;">

        {{-- Courier Performance Chart --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);animation:pulseGlow 3s infinite;">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;background:#3b82f6;border-radius:50%;display:inline-block;animation:float 2s ease-in-out infinite;"></span>
                Courier Performance
            </div>
            @php $maxCourier = $d['courierStats']->max('total') ?: 1; $colors = ['#3b82f6','#10b981','#f97316','#8b5cf6','#ef4444','#06b6d4']; $ci = 0; @endphp
            @forelse($d['courierStats'] as $courier)
            <div style="margin-bottom:12px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#374151;font-weight:500;">{{ $courier->name }}</span>
                    <span style="color:#6b7280;font-weight:600;">{{ $courier->total }} ({{ round(($courier->total/$maxCourier)*100) }}%)</span>
                </div>
                <div style="height:8px;background:#f3f4f6;border-radius:6px;overflow:hidden;">
                    <div class="progress-fill" style="height:8px;background:{{ $colors[$ci % count($colors)] }};border-radius:6px;width:{{ round(($courier->total/$maxCourier)*100) }}%;transition:width 1s ease;"></div>
                </div>
            </div>
            @php $ci++; @endphp
            @empty
            <div style="font-size:12px;color:#9ca3af;text-align:center;padding:20px;">No courier data available</div>
            @endforelse
        </div>

        {{-- Revenue vs Profit --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;background:#10b981;border-radius:50%;display:inline-block;animation:float 2s ease-in-out infinite 0.5s;"></span>
                Revenue vs Profit
            </div>
            @php
                $rev = $d['totalRevenue'];
                $costs = $rev * 0.4;
                $profit = $rev - $costs;
                $revPct = $rev > 0 ? 100 : 0;
                $profitPct = $rev > 0 ? ($profit / $rev) * 100 : 0;
            @endphp
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#374151;font-weight:500;">Total Revenue</span>
                    <span style="color:#10b981;font-weight:700;">Rs {{ number_format($rev) }}</span>
                </div>
                <div style="height:10px;background:#f3f4f6;border-radius:6px;overflow:hidden;">
                    <div class="progress-fill" style="height:10px;background:linear-gradient(90deg,#10b981,#34d399);border-radius:6px;width:100%;"></div>
                </div>
            </div>
            <div style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#374151;font-weight:500;">Costs (40%)</span>
                    <span style="color:#ef4444;font-weight:700;">Rs {{ number_format($costs) }}</span>
                </div>
                <div style="height:10px;background:#f3f4f6;border-radius:6px;overflow:hidden;">
                    <div class="progress-fill" style="height:10px;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:6px;width:{{ $revPct }}%;"></div>
                </div>
            </div>
            <div>
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                    <span style="color:#374151;font-weight:500;">Net Profit</span>
                    <span style="color:#8b5cf6;font-weight:700;">Rs {{ number_format($profit) }}</span>
                </div>
                <div style="height:10px;background:#f3f4f6;border-radius:6px;overflow:hidden;">
                    <div class="progress-fill" style="height:10px;background:linear-gradient(90deg,#8b5cf6,#a78bfa);border-radius:6px;width:{{ $profitPct }}%;"></div>
                </div>
            </div>
            <div style="margin-top:12px;padding:10px;background:#f0fdf4;border-radius:10px;text-align:center;font-size:13px;color:#166534;font-weight:600;">
                Profit Margin: {{ number_format($profitPct, 1) }}%
            </div>
        </div>

        {{-- Live Alerts --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;display:inline-block;animation:float 1.5s ease-in-out infinite;"></span>
                Live Alerts
                <span style="margin-left:auto;background:#fee2e2;color:#991b1b;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">{{ $d['pendingBookings'] + $d['returnedBookings'] }} alerts</span>
            </div>
            <div style="display:flex;flex-direction:column;gap:10px;">
                @if($d['pendingBookings'] > 0)
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fff7ed;border-radius:10px;border-left:3px solid #f97316;animation:slideInLeft 0.5s ease-out;">
                    <span style="font-size:18px;">⏳</span>
                    <div style="flex:1;">
                        <div style="font-size:12px;font-weight:600;color:#c2410c;">Pending Shipments</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $d['pendingBookings'] }} shipments need processing</div>
                    </div>
                    <span style="background:white;color:#c2410c;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:700;">{{ $d['pendingBookings'] }}</span>
                </div>
                @endif
                @if($d['returnedBookings'] > 0)
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fee2e2;border-radius:10px;border-left:3px solid #ef4444;animation:slideInLeft 0.5s ease-out 0.1s both;">
                    <span style="font-size:18px;">↩️</span>
                    <div style="flex:1;">
                        <div style="font-size:12px;font-weight:600;color:#991b1b;">Returned Shipments</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $d['returnedBookings'] }} items returned to merchant</div>
                    </div>
                    <span style="background:white;color:#991b1b;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:700;">{{ $d['returnedBookings'] }}</span>
                </div>
                @endif
                @if($d['pendingShippers'] > 0)
                <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#fef3c7;border-radius:10px;border-left:3px solid #f59e0b;animation:slideInLeft 0.5s ease-out 0.2s both;">
                    <span style="font-size:18px;">👤</span>
                    <div style="flex:1;">
                        <div style="font-size:12px;font-weight:600;color:#92400e;">Pending Approvals</div>
                        <div style="font-size:11px;color:#9ca3af;">{{ $d['pendingShippers'] }} merchants need verification</div>
                    </div>
                    <span style="background:white;color:#92400e;padding:2px 10px;border-radius:99px;font-size:11px;font-weight:700;">{{ $d['pendingShippers'] }}</span>
                </div>
                @endif
                @if($d['pendingBookings'] == 0 && $d['returnedBookings'] == 0 && $d['pendingShippers'] == 0)
                <div style="text-align:center;padding:20px;">
                    <span style="font-size:40px;">✅</span>
                    <div style="font-size:13px;color:#10b981;font-weight:600;margin-top:8px;">All Clear! No Alerts</div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Shipment Status Bar --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:14px;">Shipment Status Breakdown</div>
            @php
                $total = max(1, $d['pendingBookings'] + $d['deliveredBookings'] + $d['returnedBookings'] + $d['todayBookings']);
                $statuses = [
                    ['label' => 'Pending', 'value' => $d['pendingBookings'], 'color' => '#f97316', 'bg' => '#fff7ed'],
                    ['label' => 'Delivered', 'value' => $d['deliveredBookings'], 'color' => '#10b981', 'bg' => '#f0fdf4'],
                    ['label' => 'Returned', 'value' => $d['returnedBookings'], 'color' => '#ef4444', 'bg' => '#fef2f2'],
                    ['label' => 'Today New', 'value' => $d['todayBookings'], 'color' => '#3b82f6', 'bg' => '#eff6ff'],
                ];
            @endphp
            @foreach($statuses as $s)
            <div style="display:flex;align-items:center;margin-bottom:10px;">
                <div style="flex:1;">
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:3px;">
                        <span style="color:#374151;font-weight:500;">{{ $s['label'] }}</span>
                        <span style="color:{{ $s['color'] }};font-weight:700;">{{ $s['value'] }} ({{ round(($s['value']/$total)*100) }}%)</span>
                    </div>
                    <div style="height:8px;background:#f3f4f6;border-radius:6px;overflow:hidden;">
                        <div class="progress-fill" style="height:8px;background:{{ $s['color'] }};border-radius:6px;width:{{ round(($s['value']/$total)*100) }}%;"></div>
                    </div>
                </div>
                <span style="margin-left:12px;padding:4px 10px;border-radius:99px;font-size:11px;font-weight:600;background:{{ $s['bg'] }};color:{{ $s['color'] }};">{{ $s['value'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Quick Actions --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:18px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:14px;">Quick Actions</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                @foreach([
                    ['url' => '/admin/shipper-management', 'icon' => '👥', 'label' => 'Merchants', 'badge' => $d['pendingShippers'] > 0 ? $d['pendingShippers'] . ' pending' : null, 'color' => '#3b82f6'],
                    ['url' => '/admin/shipment-management', 'icon' => '📦', 'label' => 'Shipments', 'badge' => null, 'color' => '#10b981'],
                    ['url' => '/admin/plan-management', 'icon' => '💎', 'label' => 'Pricing Plans', 'badge' => null, 'color' => '#8b5cf6'],
                    ['url' => '/admin/finance-overview', 'icon' => '💰', 'label' => 'Financials', 'badge' => null, 'color' => '#f97316'],
                    ['url' => '/admin/bookings', 'icon' => '📋', 'label' => 'All Bookings', 'badge' => null, 'color' => '#06b6d4'],
                    ['url' => '/admin/settings', 'icon' => '⚙️', 'label' => 'Settings', 'badge' => null, 'color' => '#6b7280'],
                ] as $action)
                <a href="{{ $action['url'] }}"
                    style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:14px;text-decoration:none;display:flex;flex-direction:column;align-items:center;gap:6px;transition:all 0.3s;"
                    onmouseover="this.style.borderColor='{{ $action['color'] }}';this.style.transform='scale(1.05)'"
                    onmouseout="this.style.borderColor='#e5e7eb';this.style.transform='scale(1)'">
                    <span style="font-size:24px;">{{ $action['icon'] }}</span>
                    <span style="font-size:12px;font-weight:600;color:#374151;">{{ $action['label'] }}</span>
                    @if($action['badge'])
                    <span style="background:#ef4444;color:white;border-radius:99px;padding:1px 8px;font-size:10px;font-weight:600;">{{ $action['badge'] }}</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Bookings Table with Animation --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:15px;font-weight:700;color:#111827;">📋 Recent Shipments</span>
            <a href="/admin/shipment-management" style="font-size:12px;color:#3b82f6;text-decoration:none;font-weight:600;">View All →</a>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Tracking #</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Customer</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">City</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">COD</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Charges</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $recentBookings = \DB::table('bookings')
                            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
                            ->select('bookings.*', 'courier_integrations.courier_name')
                            ->orderByDesc('bookings.created_at')
                            ->limit(8)
                            ->get();
                    @endphp
                    @forelse($recentBookings as $idx => $booking)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.5s ease-out {{ $idx * 0.08 }}s both;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:10px 16px;font-family:monospace;font-size:12px;color:#6b7280;font-weight:600;">{{ $booking->tracking_number ?? '—' }}</td>
                        <td style="padding:10px 16px;">
                            <div style="font-weight:600;color:#111827;">{{ $booking->customer_name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $booking->courier_name ?? '' }}</div>
                        </td>
                        <td style="padding:10px 16px;color:#6b7280;">{{ $booking->destination_city }}</td>
                        <td style="padding:10px 16px;color:#374151;font-weight:600;">Rs {{ number_format($booking->cod_amount) }}</td>
                        <td style="padding:10px 16px;color:#374151;">Rs {{ number_format($booking->delivery_charges) }}</td>
                        <td style="padding:10px 16px;">
                            @php $status = $booking->status; @endphp
                            @if($status === 'delivered')
                                <span style="background:#dcfce7;color:#166534;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">Delivered ✅</span>
                            @elseif($status === 'pending')
                                <span style="background:#fff7ed;color:#c2410c;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">Pending ⏳</span>
                            @elseif($status === 'in_transit')
                                <span style="background:#eff6ff;color:#1d4ed8;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">In Transit 🚚</span>
                            @elseif($status === 'picked')
                                <span style="background:#f0fdf4;color:#166534;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">Picked 📌</span>
                            @elseif($status === 'returned')
                                <span style="background:#fee2e2;color:#991b1b;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">Returned ↩️</span>
                            @else
                                <span style="background:#f3f4f6;color:#6b7280;padding:4px 12px;border-radius:99px;font-size:11px;font-weight:600;display:inline-block;">{{ ucfirst($status) }}</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;color:#9ca3af;font-size:12px;">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y, h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af;">
                            <div style="font-size:40px;margin-bottom:10px;">📭</div>
                            No shipments found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-filament-panels::page>
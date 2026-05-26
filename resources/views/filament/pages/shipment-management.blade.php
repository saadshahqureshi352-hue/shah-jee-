<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
</style>

@php $shipments = $this->getShipments(); @endphp

<div class="space-y-5">

    {{-- Filters Section --}}
    <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <div style="font-size:18px;font-weight:800;color:#111827;">📦 Shipment Management</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Track, filter and manage all shipments</div>
            </div>
            <div style="display:flex;gap:8px;">
                <button onclick="alert('Printing labels...')" style="background:#f3f4f6;color:#374151;border:none;padding:8px 16px;border-radius:8px;font-size:12px;cursor:pointer;font-weight:600;">🖨️ Print Labels</button>
                <button onclick="alert('Exporting data...')" style="background:#1e293b;color:white;border:none;padding:8px 16px;border-radius:8px;font-size:12px;cursor:pointer;font-weight:600;">📥 Export CSV</button>
            </div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:2;min-width:200px;position:relative;">
                <span style="position:absolute;left:12px;top:12px;font-size:14px;">🔍</span>
                <input wire:model.live="search" type="text" placeholder="Search by tracking, customer, merchant..."
                    style="width:100%;padding:10px 14px 10px 38px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;outline:none;" />
            </div>
            <div>
                <label style="font-size:11px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Status</label>
                <select wire:model.live="status"
                    style="padding:9px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:white;min-width:130px;">
                    <option value="all">📋 All Status</option>
                    <option value="pending">⏳ Pending</option>
                    <option value="picked">📌 Picked</option>
                    <option value="in_transit">🚚 In Transit</option>
                    <option value="delivered">✅ Delivered</option>
                    <option value="returned">↩️ Returned</option>
                    <option value="cancelled">❌ Cancelled</option>
                </select>
            </div>
            <div>
                <label style="font-size:11px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">From</label>
                <input wire:model.live="dateFrom" type="date"
                    style="padding:9px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:11px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">To</label>
                <input wire:model.live="dateTo" type="date"
                    style="padding:9px 12px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;" />
            </div>
        </div>
    </div>

    {{-- Shipments Table --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:14px;font-weight:700;color:#111827;">📋 Shipments List</span>
            <span style="font-size:12px;color:#9ca3af;background:#f3f4f6;padding:3px 12px;border-radius:99px;">{{ $shipments->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Tracking #</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Customer</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Courier</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">City</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">COD</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                        <th style="text-align:left;padding:10px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shipments as $idx => $s)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.4s ease-out {{ $idx * 0.02 }}s both;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:10px 12px;font-family:monospace;font-size:12px;color:#6b7280;font-weight:600;">{{ $s->tracking_number ?? '—' }}</td>
                        <td style="padding:10px 12px;">
                            <div style="font-weight:600;color:#111827;">{{ $s->customer_name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $s->customer_phone }}</div>
                        </td>
                        <td style="padding:10px 12px;color:#374151;font-weight:500;">{{ $s->merchant_name ?? '—' }}</td>
                        <td style="padding:10px 12px;color:#374151;">{{ $s->courier_name ?? '—' }}</td>
                        <td style="padding:10px 12px;color:#6b7280;">{{ $s->destination_city }}</td>
                        <td style="padding:10px 12px;font-weight:700;color:#374151;">Rs {{ number_format($s->cod_amount) }}</td>
                        <td style="padding:10px 12px;">
                            <select wire:change="updateStatus({{ $s->id }}, $event.target.value)"
                                style="padding:4px 8px;border:1px solid #d1d5db;border-radius:8px;font-size:11px;background:white;cursor:pointer;">
                                @foreach(['pending','picked','in_transit','delivered','returned','cancelled'] as $st)
                                    <option value="{{ $st }}" {{ $s->status === $st ? 'selected' : '' }}>
                                        @if($st === 'delivered') ✅ @elseif($st === 'pending') ⏳ @elseif($st === 'in_transit') 🚚 @elseif($st === 'picked') 📌 @elseif($st === 'returned') ↩️ @else ❌ @endif
                                        {{ ucfirst(str_replace('_',' ',$st)) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding:10px 12px;color:#9ca3af;font-size:12px;">{{ \Carbon\Carbon::parse($s->created_at)->format('d M Y') }}</td>
                        <td style="padding:10px 12px;">
                            <a href="/admin/bookings/{{ $s->id }}"
                                style="background:#e0e7ff;color:#4338ca;padding:4px 12px;border-radius:8px;font-size:11px;font-weight:600;text-decoration:none;transition:all 0.2s;display:inline-block;"
                                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                View →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" style="padding:40px;text-align:center;color:#9ca3af;">
                            <div style="font-size:40px;margin-bottom:10px;">📭</div>
                            No shipments found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;border-top:1px solid #f3f4f6;">
            {{ $shipments->links() }}
        </div>
    </div>

</div>

</x-filament-panels::page>
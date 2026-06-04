<x-filament-panels::page>
<style>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes slideIn {
    from { opacity: 0; transform: translateX(-30px); }
    to { opacity: 1; transform: translateX(0); }
}
@keyframes pulseRed {
    0%, 100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.2); }
    50% { box-shadow: 0 0 15px 4px rgba(239,68,68,0.1); }
}
.anim-row {
    animation: fadeInUp 0.4s ease-out both;
}
.anim-row:nth-child(1) { animation-delay: 0.02s; }
.anim-row:nth-child(2) { animation-delay: 0.04s; }
.anim-row:nth-child(3) { animation-delay: 0.06s; }
.anim-row:nth-child(4) { animation-delay: 0.08s; }
.anim-row:nth-child(5) { animation-delay: 0.10s; }
.card-hover {
    transition: all 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08) !important;
}
</style>

@php
$pending = $this->getPendingMerchants();
$approved = $this->getApprovedMerchants();
@endphp

<div class="space-y-5" style="padding:0 4px;">

    {{-- Header --}}
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:20px 24px;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h2 style="font-size:20px;font-weight:800;color:#111827;margin:0;">🔐 Merchant Approval Center</h2>
                <p style="font-size:13px;color:#6b7280;margin:4px 0 0;">Review, approve or reject new merchant registrations. Edit any merchant's data.</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="background:#fef2f2;color:#991b1b;padding:8px 16px;border-radius:10px;font-size:13px;font-weight:700;animation:pulseRed 2s infinite;">
                    ⏳ {{ $pending->count() }} Pending
                </div>
                <div style="background:#f0fdf4;color:#166534;padding:8px 16px;border-radius:10px;font-size:13px;font-weight:700;">
                    ✅ {{ $approved->count() }} Approved
                </div>
            </div>
        </div>
    </div>

    {{-- ============ PENDING MERCHANTS ============ --}}
    @if($pending->count() > 0)
    <div style="background:white;border:2px solid #fecaca;border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px;background:linear-gradient(135deg,#fef2f2,#fee2e2);border-bottom:1px solid #fecaca;">
            <span style="font-size:15px;font-weight:700;color:#991b1b;">⏳ Pending Approvals ({{ $pending->count() }})</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Contact</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">City</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Registered</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Plan</th>
                        <th style="text-align:center;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pending as $idx => $m)
                    <tr class="anim-row" style="border-top:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700;color:#111827;font-size:13px;">{{ $m->name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $m->brand_name ?? $m->username ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-size:12px;color:#374151;">{{ $m->email }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $m->phone ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:12px;color:#6b7280;">{{ $m->city ?? '—' }}</span>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:12px;color:#6b7280;">{{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</span>
                            <div style="font-size:10px;color:#9ca3af;">{{ \Carbon\Carbon::parse($m->created_at)->diffForHumans() }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;">
                                {{ $m->pricingPlan->name ?? 'No Plan' }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                {{-- View Full Details (Edit Page) --}}
                                <a href="/admin/merchants/{{ $m->id }}/edit"
                                    style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                                    onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                    👁️ View & Edit
                                </a>
                                {{-- Approve --}}
                                <button wire:click="approveMerchant({{ $m->id }})"
                                    style="background:#10b981;color:white;border:none;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.2s;"
                                    onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    ✅ Approve
                                </button>
                                {{-- Reject --}}
                                <button wire:click="rejectMerchant({{ $m->id }})"
                                    style="background:#ef4444;color:white;border:none;padding:6px 14px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.2s;"
                                    onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                                    ❌ Reject
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;padding:40px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="font-size:48px;margin-bottom:10px;">✅</div>
        <div style="font-size:15px;font-weight:600;color:#10b981;">All Clear! Koi pending approvals nahi hain.</div>
    </div>
    @endif

    {{-- ============ APPROVED MERCHANTS ============ --}}
    @if($approved->count() > 0)
    <div style="background:white;border:1px solid #e5e7eb;border-radius:18px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.04);">
        <div style="padding:16px 24px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-bottom:1px solid #bbf7d0;">
            <span style="font-size:15px;font-weight:700;color:#166534;">✅ Approved Merchants ({{ $approved->count() }})</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Contact</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Plan</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Orders</th>
                        <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Registered</th>
                        <th style="text-align:center;padding:12px 16px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($approved as $idx => $m)
                    <tr class="anim-row" style="border-top:1px solid #f3f4f6;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 16px;">
                            <div style="font-weight:700;color:#111827;font-size:13px;">{{ $m->name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $m->brand_name ?? $m->username ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            <div style="font-size:12px;color:#374151;">{{ $m->email }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $m->phone ?? '—' }}</div>
                        </td>
                        <td style="padding:12px 16px;">
                            @if($m->status === 'active')
                                <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;">Active</span>
                            @elseif($m->status === 'suspended')
                                <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;">Suspended</span>
                            @else
                                <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;">{{ ucfirst($m->status) }}</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;">
                                {{ $m->pricingPlan->name ?? 'No Plan' }}
                            </span>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:13px;font-weight:700;color:#111827;">{{ $m->bookings_count }}</span>
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:12px;color:#6b7280;">{{ \Carbon\Carbon::parse($m->created_at)->format('d M Y') }}</span>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            <div style="display:flex;gap:6px;justify-content:center;">
                                <a href="/admin/merchants/{{ $m->id }}/edit"
                                    style="background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;text-decoration:none;transition:all 0.2s;"
                                    onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                                    ✏️ Edit
                                </a>
                                <button wire:click="toggleStatus({{ $m->id }})"
                                    style="background:{{ $m->status === 'active' ? '#fef3c7' : '#f0fdf4' }};color:{{ $m->status === 'active' ? '#92400e' : '#166534' }};border:1px solid {{ $m->status === 'active' ? '#fde68a' : '#bbf7d0' }};padding:6px 12px;border-radius:8px;font-size:11px;font-weight:600;cursor:pointer;transition:all 0.2s;"
                                    onmouseover="this.style.background='{{ $m->status === 'active' ? '#fde68a' : '#bbf7d0' }}'"
                                    onmouseout="this.style.background='{{ $m->status === 'active' ? '#fef3c7' : '#f0fdf4' }}'">
                                    {{ $m->status === 'active' ? '⏸️ Suspend' : '▶️ Activate' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
</x-filament-panels::page>
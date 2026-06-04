<x-filament-panels::page>
<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
@keyframes countUp { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }
@keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.2s; }
.anim-card:nth-child(3) { animation-delay: 0.3s; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
.stat-value { animation: countUp 0.5s ease-out both; }
</style>

@php $shippers = $this->getShippers(); $totalApproved = \App\Models\User::where('role','shipper')->where('is_approved', true)->count(); $totalPending = \App\Models\User::where('role','shipper')->where('is_approved', false)->count(); @endphp

<div class="space-y-5">

    {{-- Header with Search --}}
    <div class="anim-card" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div>
                <div style="font-size:18px;font-weight:800;color:#111827;">👥 Merchant Management</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Manage all registered merchants and their wallets</div>
            </div>
            <button wire:click="exportToExcel" style="background:#1e293b;color:white;border:none;padding:8px 18px;border-radius:8px;font-size:12px;cursor:pointer;font-weight:600;">📥 Export to Excel</button>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
            <div style="flex:2;min-width:200px;position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);font-size:14px;">🔍</span>
                <input wire:model.live="search" type="text" placeholder="Search name, email, phone, city..."
                    style="width:100%;padding:10px 14px 10px 38px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;outline:none;" />
            </div>
            <select wire:model.live="status"
                style="padding:10px 14px;border:1px solid #e5e7eb;border-radius:10px;font-size:13px;background:white;">
                <option value="all">All Merchants</option>
                <option value="pending">Pending Only</option>
                <option value="approved">Approved Only</option>
            </select>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;">
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:14px;padding:18px;text-align:center;box-shadow:0 1px 6px rgba(0,0,0,0.04);position:relative;overflow:hidden;">
            <div style="position:absolute;top:-10px;right:-10px;width:60px;height:60px;background:#eff6ff;border-radius:50%;"></div>
            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:6px;">Total Merchants</div>
            <div class="stat-value" style="font-size:32px;font-weight:800;color:#3b82f6;">{{ $shippers->total() }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Registered on platform</div>
        </div>
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:14px;padding:18px;text-align:center;box-shadow:0 1px 6px rgba(0,0,0,0.04);position:relative;overflow:hidden;">
            <div style="position:absolute;top:-10px;right:-10px;width:60px;height:60px;background:#f0fdf4;border-radius:50%;"></div>
            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:6px;">Approved</div>
            <div class="stat-value" style="font-size:32px;font-weight:800;color:#10b981;">{{ $totalApproved }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Active merchants</div>
        </div>
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:14px;padding:18px;text-align:center;box-shadow:0 1px 6px rgba(0,0,0,0.04);position:relative;overflow:hidden;">
            <div style="position:absolute;top:-10px;right:-10px;width:60px;height:60px;background:#fff7ed;border-radius:50%;"></div>
            <div style="font-size:11px;color:#9ca3af;text-transform:uppercase;font-weight:600;margin-bottom:6px;">Pending Approval</div>
            <div class="stat-value" style="font-size:32px;font-weight:800;color:#f97316;">{{ $totalPending }}</div>
            <div style="font-size:12px;color:#9ca3af;margin-top:4px;">Need verification</div>
        </div>
    </div>

    {{-- Merchants Table --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:14px;font-weight:700;color:#111827;">📋 All Merchants</span>
            <span style="font-size:12px;color:#9ca3af;background:#f3f4f6;padding:3px 12px;border-radius:99px;">{{ $shippers->total() }} total</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Contact</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">City / Plan</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Account Status</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Wallet Balance</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Add Balance</th>
                        <th style="text-align:left;padding:10px 14px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippers as $idx => $shipper)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.4s ease-out {{ $idx * 0.03 }}s both;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:12px 14px;">
                            <div style="font-weight:700;color:#111827;">{{ $shipper->name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">@{{ $shipper->username }}</div>
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="color:#374151;">{{ $shipper->email }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $shipper->phone }}</div>
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="color:#374151;">{{ $shipper->city ?? '—' }}</div>
                            <div style="font-size:11px;">
                                @php $planName = \DB::table('pricing_plans')->where('id', $shipper->pricing_plan_id)->value('name'); @endphp
                                @if($planName)
                                    <span style="background:#eff6ff;color:#1d4ed8;padding:1px 8px;border-radius:99px;font-size:10px;font-weight:600;">{{ $planName }}</span>
                                @else
                                    <span style="color:#9ca3af;">No plan</span>
                                @endif
                            </div>
                        </td>
                        <td style="padding:12px 14px;">
                            @if($shipper->is_approved)
                                <span style="background:#dcfce7;color:#166534;padding:3px 12px;border-radius:99px;font-size:11px;font-weight:600;">Approved ✅</span>
                            @else
                                <span style="background:#fff7ed;color:#c2410c;padding:3px 12px;border-radius:99px;font-size:11px;font-weight:600;">Pending ⏳</span>
                            @endif
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="font-weight:700;color:{{ ($shipper->wallet_balance ?? 0) < 500 ? '#ef4444' : '#10b981' }};">
                                Rs {{ number_format($shipper->wallet_balance ?? 0) }}
                            </div>
                            @if($shipper->wallet_blocked ?? false)
                                <div style="font-size:10px;color:#ef4444;font-weight:700;">🔴 BLOCKED</div>
                            @endif
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="display:flex;gap:6px;align-items:center;">
                                <input wire:model="walletAmounts.{{ $shipper->id }}" type="number" placeholder="Rs"
                                    style="width:80px;padding:6px 8px;border:1px solid #d1d5db;border-radius:8px;font-size:12px;" />
                                <button wire:click="addWalletBalance({{ $shipper->id }})"
                                    style="background:#4f46e5;color:white;border:none;padding:6px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    Add
                                </button>
                            </div>
                        </td>
                        <td style="padding:12px 14px;">
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                @if(!$shipper->is_approved)
                                    <button wire:click="approveShipper({{ $shipper->id }})"
                                        style="background:#10b981;color:white;border:none;padding:5px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        Approve ✅
                                    </button>
                                @else
                                    <button wire:click="rejectShipper({{ $shipper->id }})"
                                        style="background:#f97316;color:white;border:none;padding:5px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        Set Pending
                                    </button>
                                @endif
                                @if($shipper->wallet_blocked ?? false)
                                    <button wire:click="unblockWallet({{ $shipper->id }})"
                                        style="background:#3b82f6;color:white;border:none;padding:5px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;"
                                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        Unblock 🔓
                                    </button>
                                @else
                                    <button wire:click="blockWallet({{ $shipper->id }})"
                                        style="background:#ef4444;color:white;border:none;padding:5px 12px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;"
                                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        Block 🔒
                                    </button>
                                @endif
                                <a href="/admin/shippers/{{ $shipper->id }}/edit"
                                    style="background:#6b7280;color:white;padding:5px 12px;border-radius:8px;font-size:11px;text-decoration:none;font-weight:600;display:inline-block;transition:all 0.2s;"
                                    onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                    Edit ✏️
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:40px;text-align:center;color:#9ca3af;">
                            <div style="font-size:40px;margin-bottom:10px;">👥</div>
                            No merchants found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:12px 20px;border-top:1px solid #f3f4f6;">
            {{ $shippers->links() }}
        </div>
    </div>

</div>

</x-filament-panels::page>
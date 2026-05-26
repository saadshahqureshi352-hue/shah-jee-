<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.2s; }
.anim-card-2 { animation: fadeInUp 0.6s ease-out both; }
.anim-card-2:nth-child(1) { animation-delay: 0.15s; }
.anim-card-2:nth-child(2) { animation-delay: 0.25s; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
</style>

@php $couriers = $this->getCouriers(); @endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="anim-card" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="font-size:18px;font-weight:800;color:#111827;">⚙️ System Settings & Controls</div>
        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Main switchboard - manage features, couriers, and charges</div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- Feature Toggles --}}
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;">🔘 Feature Toggles</div>

            @foreach([
                ['field' => 'newRegistrations', 'label' => 'New Registrations', 'desc' => 'Allow new merchants to register'],
                ['field' => 'autoApprove', 'label' => 'Auto-Approve', 'desc' => 'Auto approve new merchant accounts'],
                ['field' => 'codAvailable', 'label' => 'COD Available', 'desc' => 'Cash on delivery service'],
                ['field' => 'maintenanceMode', 'label' => 'Maintenance Mode', 'desc' => 'Disable portal access for users'],
                ['field' => 'whatsappNotifications', 'label' => 'WhatsApp Notifications', 'desc' => 'Send alerts via WhatsApp'],
            ] as $toggle)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f3f4f6;">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#111827;">{{ $toggle['label'] }}</div>
                    <div style="font-size:11px;color:#9ca3af;">{{ $toggle['desc'] }}</div>
                </div>
                <div wire:click="$set('{{ $toggle['field'] }}', {{ $this->{$toggle['field']} ? 'false' : 'true' }})"
                    style="width:44px;height:24px;border-radius:12px;cursor:pointer;display:flex;align-items:center;padding:2px;transition:all 0.3s;background:{{ $this->{$toggle['field']} ? 'linear-gradient(135deg,#10b981,#34d399)' : '#d1d5db' }};justify-content:{{ $this->{$toggle['field']} ? 'flex-end' : 'flex-start' }};">
                    <div style="width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                </div>
            </div>
            @endforeach

            <button wire:click="saveToggles"
                style="margin-top:16px;background:#1e293b;color:white;border:none;padding:10px 24px;border-radius:10px;font-size:13px;cursor:pointer;font-weight:600;width:100%;transition:all 0.2s;"
                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                💾 Save Settings
            </button>
        </div>

        {{-- Courier On/Off --}}
        <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;">🚚 Courier On/Off</div>
            @forelse($couriers as $idx => $courier)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.05 }}s both;">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#111827;">{{ $courier->courier_name }}</div>
                    <div style="font-size:11px;color:{{ $courier->is_active ? '#10b981' : '#ef4444' }};">
                        {{ $courier->is_active ? '🟢 Active — visible at checkout' : '🔴 Disabled' }}
                    </div>
                </div>
                <div wire:click="toggleCourier({{ $courier->id }}, {{ $courier->is_active ? 'false' : 'true' }})"
                    style="width:44px;height:24px;border-radius:12px;cursor:pointer;display:flex;align-items:center;padding:2px;background:{{ $courier->is_active ? 'linear-gradient(135deg,#10b981,#34d399)' : '#d1d5db' }};justify-content:{{ $courier->is_active ? 'flex-end' : 'flex-start' }};transition:all 0.3s;">
                    <div style="width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:30px;color:#9ca3af;">
                <div style="font-size:40px;margin-bottom:10px;">🚚</div>
                No couriers configured
            </div>
            @endforelse
        </div>

    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">

        {{-- Manual Rate Controls --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;">💰 Manual Rate Controls</div>
            @foreach([
                ['field' => 'defaultCharge', 'label' => 'Default Delivery Charge (Rs)', 'icon' => '📦'],
                ['field' => 'fuelSurcharge', 'label' => 'Fuel Surcharge (%)', 'icon' => '⛽'],
                ['field' => 'remoteAreaCharge', 'label' => 'Remote Area Extra (Rs)', 'icon' => '🏔️'],
                ['field' => 'codHandlingFee', 'label' => 'COD Handling Fee (%)', 'icon' => '💳'],
            ] as $field)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f3f4f6;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:18px;">{{ $field['icon'] }}</span>
                    <span style="font-size:13px;font-weight:500;color:#374151;">{{ $field['label'] }}</span>
                </div>
                <input wire:model="{{ $field['field'] }}" type="number"
                    style="width:100px;padding:7px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:13px;text-align:right;font-weight:600;" />
            </div>
            @endforeach
            <button wire:click="saveRates"
                style="margin-top:16px;background:linear-gradient(135deg,#10b981,#34d399);color:white;border:none;padding:10px 24px;border-radius:10px;font-size:13px;cursor:pointer;font-weight:600;width:100%;transition:all 0.2s;"
                onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                💾 Save Rates
            </button>
        </div>

        {{-- Notification Settings --}}
        <div class="anim-card-2 card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
            <div style="font-size:14px;font-weight:700;color:#111827;margin-bottom:16px;">🔔 Notification Settings</div>
            @foreach([
                ['field' => 'deliveredAlert', 'label' => 'Delivery Alert', 'desc' => 'Notify merchant on delivery'],
                ['field' => 'returnAlert', 'label' => 'Return Alert', 'desc' => 'Notify merchant on return'],
                ['field' => 'lowWalletAlert', 'label' => 'Low Wallet Alert', 'desc' => 'Alert when balance < Rs 500'],
                ['field' => 'payoutNotification', 'label' => 'Payout Notification', 'desc' => 'Alert when payout is processed'],
            ] as $toggle)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #f3f4f6;">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#111827;">{{ $toggle['label'] }}</div>
                    <div style="font-size:11px;color:#9ca3af;">{{ $toggle['desc'] }}</div>
                </div>
                <div wire:click="$set('{{ $toggle['field'] }}', {{ $this->{$toggle['field']} ? 'false' : 'true' }})"
                    style="width:44px;height:24px;border-radius:12px;cursor:pointer;display:flex;align-items:center;padding:2px;background:{{ $this->{$toggle['field']} ? 'linear-gradient(135deg,#10b981,#34d399)' : '#d1d5db' }};justify-content:{{ $this->{$toggle['field']} ? 'flex-end' : 'flex-start' }};transition:all 0.3s;">
                    <div style="width:20px;height:20px;background:white;border-radius:50%;box-shadow:0 1px 3px rgba(0,0,0,0.2);"></div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>

</x-filament-panels::page>
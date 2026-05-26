<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
@keyframes scaleIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
.anim-card { animation: fadeInUp 0.6s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.2s; }
.anim-card:nth-child(3) { animation-delay: 0.3s; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); }
.plan-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
.plan-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.12); }
</style>

@php
    $plans = $this->getPlans();
    $merchants = $this->getMerchants();
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:20px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <div style="font-size:18px;font-weight:800;color:#111827;">💎 Pricing Plans Setup</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Create and manage pricing plans for merchants</div>
            </div>
            <button
                wire:click="$set('showAddPlan', true)"
                style="background:#1e293b;color:white;border:none;padding:10px 20px;border-radius:10px;font-size:13px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                ➕ New Plan
            </button>
        </div>
    </div>

    {{-- Plans Grid --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        @forelse($plans as $idx => $plan)
        <div class="anim-card plan-card" style="background:white;border:2px solid {{ $plan->name === 'VIP Plan' ? '#f59e0b' : ($plan->name === 'Basic Plan' ? '#3b82f6' : '#8b5cf6') }};border-radius:16px;padding:20px;box-shadow:0 2px 8px rgba(0,0,0,0.05);position:relative;overflow:hidden;">
            <div style="position:absolute;top:-30px;right:-30px;width:100px;height:100px;border-radius:50%;background:{{ $plan->name === 'VIP Plan' ? '#fef3c7' : ($plan->name === 'Basic Plan' ? '#eff6ff' : '#f5f3ff') }};opacity:0.5;"></div>
            <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:10px;">
                <div>
                    <div style="font-size:18px;font-weight:800;color:#111827;">{{ $plan->name }}</div>
                    @if($plan->description)
                        <div style="font-size:12px;color:#9ca3af;margin-top:2px;">{{ $plan->description }}</div>
                    @endif
                </div>
                @if($plan->is_active)
                    <span style="background:#dcfce7;color:#166534;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:600;">Active ✅</span>
                @else
                    <span style="background:#f3f4f6;color:#6b7280;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:600;">Inactive</span>
                @endif
            </div>
            <div style="margin:16px 0;padding:16px;background:#f9fafb;border-radius:12px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                    <div>
                        <div style="font-size:10px;color:#9ca3af;text-transform:uppercase;">Base Charge</div>
                        <div style="font-size:22px;font-weight:800;color:#111827;">Rs {{ number_format($plan->base_delivery_charge) }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#9ca3af;text-transform:uppercase;">COD Commission</div>
                        <div style="font-size:22px;font-weight:800;color:#f97316;">{{ $plan->cod_commission_percent }}%</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#9ca3af;text-transform:uppercase;">Per KG</div>
                        <div style="font-size:22px;font-weight:800;color:#3b82f6;">Rs {{ $plan->weight_charge_per_kg }}</div>
                    </div>
                    <div>
                        <div style="font-size:10px;color:#9ca3af;text-transform:uppercase;">Fuel</div>
                        <div style="font-size:22px;font-weight:800;color:#8b5cf6;">{{ $plan->fuel_surcharge_percent }}%</div>
                    </div>
                </div>
            </div>
            <button
                wire:click="deletePlan({{ $plan->id }})"
                style="background:#fee2e2;color:#991b1b;border:none;padding:8px 16px;border-radius:8px;font-size:12px;cursor:pointer;font-weight:600;width:100%;transition:all 0.2s;"
                onmouseover="this.style.background='#fecaca';this.style.transform='scale(1.02)'" onmouseout="this.style.background='#fee2e2';this.style.transform='scale(1)'">
                🗑️ Delete Plan
            </button>
        </div>
        @empty
        <div style="grid-column:span 3;padding:40px;text-align:center;color:#9ca3af;background:white;border-radius:16px;">
            <div style="font-size:40px;margin-bottom:10px;">💎</div>
            No plans created yet — click "New Plan" to create one
        </div>
        @endforelse
    </div>

    {{-- Add Plan Form --}}
    @if($showAddPlan)
    <div class="anim-card" style="background:white;border:2px solid #10b981;border-radius:16px;padding:24px;box-shadow:0 4px 20px rgba(16,185,129,0.15);">
        <div style="font-size:16px;font-weight:700;color:#111827;margin-bottom:20px;">✨ Create New Pricing Plan</div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:18px;">
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Plan Name *</label>
                <input wire:model="planForm.name" type="text" placeholder="e.g. VIP Plan"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Description</label>
                <input wire:model="planForm.description" type="text" placeholder="Optional"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Base Delivery Charge</label>
                <input wire:model="planForm.base_delivery_charge" type="number" placeholder="150"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">COD Commission (%)</label>
                <input wire:model="planForm.cod_commission_percent" type="number" placeholder="2"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Weight Charge / KG</label>
                <input wire:model="planForm.weight_charge_per_kg" type="number" placeholder="20"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:4px;font-weight:600;">Fuel Surcharge (%)</label>
                <input wire:model="planForm.fuel_surcharge_percent" type="number" placeholder="5"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <button wire:click="savePlan"
                style="background:#10b981;color:white;border:none;padding:10px 24px;border-radius:10px;font-size:13px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                ✅ Save Plan
            </button>
            <button wire:click="$set('showAddPlan', false)"
                style="background:#f3f4f6;color:#374151;border:none;padding:10px 24px;border-radius:10px;font-size:13px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                Cancel
            </button>
        </div>
    </div>
    @endif

    {{-- Per Merchant Rate Override --}}
    <div class="card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.05);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:14px;font-weight:700;color:#111827;">📝 Per-Merchant Rate Override</div>
            <div style="font-size:11px;color:#9ca3af;">Assign custom plans or rates to individual merchants</div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Merchant</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Assign Plan</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Custom Rate (Rs)</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Current Rate</th>
                        <th style="text-align:left;padding:10px 16px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchants as $idx => $merchant)
                    <tr style="border-top:1px solid #f3f4f6;animation:fadeInUp 0.3s ease-out {{ $idx * 0.02 }}s both;transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding:10px 16px;">
                            <div style="font-weight:700;color:#111827;">{{ $merchant->name }}</div>
                            <div style="font-size:11px;color:#9ca3af;">{{ $merchant->email }}</div>
                        </td>
                        <td style="padding:10px 16px;">
                            <select wire:model="merchantRates.{{ $merchant->id }}.plan_id"
                                style="padding:6px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:12px;background:white;">
                                <option value="">-- No Plan --</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" {{ $merchant->pricing_plan_id == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (Rs {{ number_format($plan->base_delivery_charge) }})
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding:10px 16px;">
                            <input wire:model="merchantRates.{{ $merchant->id }}.custom_rate" type="number" placeholder="e.g. 130"
                                value="{{ $merchant->fixed_delivery_charges }}"
                                style="width:90px;padding:6px 8px;border:1px solid #d1d5db;border-radius:8px;font-size:12px;" />
                        </td>
                        <td style="padding:10px 16px;">
                            @if($merchant->fixed_delivery_charges)
                                <span style="color:#10b981;font-weight:600;">Rs {{ number_format($merchant->fixed_delivery_charges) }} (custom)</span>
                            @elseif($merchant->pricing_plan_id)
                                <span style="color:#3b82f6;font-weight:600;">Plan assigned</span>
                            @else
                                <span style="color:#9ca3af;">Default rate</span>
                            @endif
                        </td>
                        <td style="padding:10px 16px;">
                            <button wire:click="assignPlan({{ $merchant->id }})"
                                style="background:#4f46e5;color:white;border:none;padding:6px 14px;border-radius:8px;font-size:11px;cursor:pointer;font-weight:600;transition:all 0.2s;"
                                onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                💾 Save
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding:32px;text-align:center;color:#9ca3af;">No merchants found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-filament-panels::page>
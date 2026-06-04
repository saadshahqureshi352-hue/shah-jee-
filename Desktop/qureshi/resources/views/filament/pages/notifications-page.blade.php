<x-filament-panels::page>

<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
.anim-card { animation: fadeInUp 0.5s ease-out both; }
.anim-card:nth-child(1) { animation-delay: 0.1s; }
.anim-card:nth-child(2) { animation-delay: 0.2s; }
.card-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
</style>

<div class="space-y-5">

    <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;padding:24px;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <span style="font-size:32px;">📨</span>
            <div>
                <div style="font-size:18px;font-weight:800;color:#111827;">Send Notification to Shippers</div>
                <div style="font-size:12px;color:#9ca3af;">Send WhatsApp, SMS, or Email notifications to your merchants</div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;font-weight:600;">Notification Type</label>
                <select wire:model="type"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;background:white;">
                    <option value="whatsapp">📱 WhatsApp Message</option>
                    <option value="sms">💬 SMS Text</option>
                    <option value="email">📧 Email</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;font-weight:600;">Send To</label>
                <select wire:model="sendTo"
                    style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;background:white;">
                    <option value="all">👥 All Shippers</option>
                    <option value="single">👤 Single Shipper</option>
                </select>
            </div>
        </div>

        @if($sendTo === 'single')
        <div style="margin-bottom:16px;">
            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;font-weight:600;">Select Shipper</label>
            <select wire:model="selectedShipper"
                style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;background:white;">
                <option value="0">-- Select Shipper --</option>
                @foreach($this->shippers as $s)
                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->phone }})</option>
                @endforeach
            </select>
        </div>
        @endif

        <div style="margin-bottom:12px;">
            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;font-weight:600;">Subject (optional)</label>
            <input wire:model="subject" type="text" placeholder="Notification subject..."
                style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;" />
        </div>

        <div style="margin-bottom:16px;">
            <label style="font-size:12px;color:#6b7280;display:block;margin-bottom:6px;font-weight:600;">Message *</label>
            <textarea wire:model="message" rows="5" placeholder="Type your message here..."
                style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:10px;font-size:13px;resize:vertical;"></textarea>
        </div>

        <button wire:click="sendNotification"
            style="background:linear-gradient(135deg,#f59e0b,#d97706);color:white;border:none;padding:12px 32px;border-radius:10px;font-size:14px;cursor:pointer;font-weight:700;transition:all 0.2s;"
            onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
            📤 Send Notification
        </button>
    </div>

    {{-- Sent Notifications History --}}
    <div class="anim-card card-hover" style="background:white;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.04);">
        <div style="padding:14px 20px;border-bottom:1px solid #e5e7eb;">
            <div style="font-size:14px;font-weight:700;color:#111827;">📋 Notification History</div>
            <div style="font-size:11px;color:#9ca3af;">Last 20 sent notifications</div>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;font-size:13px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Type</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Shipper</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Subject</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Message</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Status</th>
                        <th style="text-align:left;padding:8px 12px;font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->sentNotifications as $n)
                    <tr style="border-top:1px solid #f3f4f6;">
                        <td style="padding:8px 12px;">
                            <span style="background:#f3f4f6;padding:3px 10px;border-radius:99px;font-size:10px;font-weight:600;">
                                {{ $n->type === 'whatsapp' ? '📱 WhatsApp' : ($n->type === 'sms' ? '💬 SMS' : '📧 Email') }}
                            </span>
                        </td>
                        <td style="padding:8px 12px;font-weight:500;color:#374151;">{{ \App\Models\User::find($n->user_id)?->name ?? '—' }}</td>
                        <td style="padding:8px 12px;color:#6b7280;">{{ Str::limit($n->subject, 30) }}</td>
                        <td style="padding:8px 12px;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ Str::limit($n->message, 40) }}</td>
                        <td style="padding:8px 12px;">
                            <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:99px;font-size:10px;font-weight:600;">✅ {{ $n->status }}</span>
                        </td>
                        <td style="padding:8px 12px;color:#9ca3af;font-size:11px;">{{ \Carbon\Carbon::parse($n->created_at)->format('d M Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="padding:32px;text-align:center;color:#9ca3af;">No notifications sent yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

</x-filament-panels::page>
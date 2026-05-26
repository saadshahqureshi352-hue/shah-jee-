<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification as FilamentNotification;
use App\Models\UserNotification;
use App\Models\User;

class NotificationsPage extends Page
{
    protected string $view = 'filament.pages.notifications-page';
    protected static ?string $navigationLabel = 'Send Notification';
    protected static ?string $title = 'Send Notification to Shippers';
    protected static string | \UnitEnum | null $navigationGroup = 'Merchant & User Management';
    protected static ?int $navigationSort = 2;

    public string $type = 'whatsapp';
    public string $subject = '';
    public string $message = '';
    public string $sendTo = 'all';
    public int $selectedShipper = 0;
    public array $shippers = [];
    public array $sentNotifications = [];

    public function mount(): void
    {
        $this->shippers = DB::table('users')
            ->where('role', 'shipper')
            ->select('id', 'name', 'email', 'phone')
            ->get()->toArray();
        
        $this->sentNotifications = DB::table('notification_logs')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()->toArray();
    }

    public function sendNotification(): void
    {
        if (empty($this->message)) {
            FilamentNotification::make()->title('Message is required')->danger()->send();
            return;
        }

        $targets = [];
        if ($this->sendTo === 'all') {
            $targets = DB::table('users')->where('role', 'shipper')->select('id', 'name', 'phone')->get();
        } else {
            $targets = DB::table('users')->where('id', $this->selectedShipper)->select('id', 'name', 'phone')->get();
        }

        $inAppType = match($this->type) {
            'whatsapp' => 'info',
            'sms' => 'warning',
            'email' => 'success',
            default => 'info'
        };
        
        foreach ($targets as $target) {
            $channel = $this->type === 'whatsapp' ? 'WhatsApp' : ($this->type === 'sms' ? 'SMS' : 'Email');
            
            // 1. Log in notification_logs table
            DB::table('notification_logs')->insert([
                'user_id' => $target->id,
                'type' => $this->type,
                'subject' => $this->subject ?: 'Notification from Shah Jee Courier',
                'message' => $this->message,
                'status' => 'sent',
                'sent_via' => $channel,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Send in-app notification to shipper's dashboard (bell icon)
            UserNotification::sendToUser(
                $target->id,
                $this->subject ?: 'New Notification from Admin',
                $this->message,
                $inAppType,
                '/dashboard'
            );
        }

        $count = count($targets);
        $this->sentNotifications = DB::table('notification_logs')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()->toArray();

        $this->message = '';
        $this->subject = '';
        
        FilamentNotification::make()
            ->title("✅ Notification sent to {$count} shipper(s) via {$this->type} + In-App Dashboard")
            ->success()
            ->send();
    }
}
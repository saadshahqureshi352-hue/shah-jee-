<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class MerchantApproval extends Page
{
    protected string $view = 'filament.pages.merchant-approval';
    protected static ?string $navigationLabel = 'Merchant Approval';
    protected static ?string $title = 'Merchant Approval';
    protected static string | \UnitEnum | null $navigationGroup = 'Merchant & User Management';
    protected static ?int $navigationSort = 1;
    protected static bool $shouldRegisterNavigation = false;

    public function getPendingMerchants()
    {
        return User::where('role', 'shipper')
            ->where('is_approved', false)
            ->with('pricingPlan')
            ->withCount('bookings')
            ->latest()
            ->get();
    }

    public function getApprovedMerchants()
    {
        return User::where('role', 'shipper')
            ->where('is_approved', true)
            ->with('pricingPlan')
            ->withCount('bookings')
            ->latest()
            ->get();
    }

    public function approveMerchant(int $userId): void
    {
        $basicPlanId = DB::table('pricing_plans')
            ->where('is_active', 1)->orderBy('id')->value('id');

        User::where('id', $userId)->update([
            'is_approved' => true,
            'status' => 'active',
            'pricing_plan_id' => $basicPlanId,
        ]);

        Notification::make()->title('Merchant approved! Basic Plan assigned.')->success()->send();
    }

    public function rejectMerchant(int $userId): void
    {
        User::where('id', $userId)->update([
            'is_approved' => false,
            'status' => 'pending',
        ]);

        Notification::make()->title('Merchant rejected.')->warning()->send();
    }

    public function toggleStatus(int $userId): void
    {
        $user = User::find($userId);
        if (!$user) return;

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        Notification::make()->title('Status changed to ' . $newStatus)->success()->send();
    }
}
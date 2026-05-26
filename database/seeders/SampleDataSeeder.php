<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        DB::table('users')->updateOrInsert(['email' => 'admin@shahjee.com'], [
            'name' => 'Admin Shah Jee',
            'email' => 'admin@shahjee.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
            'phone' => '03001234567',
            'city' => 'Karachi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Pricing Plans
        DB::table('pricing_plans')->insert([
            ['name' => 'VIP Plan', 'description' => 'Premium plan with discounted rates', 'base_delivery_charge' => 120, 'cod_commission_percent' => 1.5, 'weight_charge_per_kg' => 15, 'fuel_surcharge_percent' => 3, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Basic Plan', 'description' => 'Standard plan for regular merchants', 'base_delivery_charge' => 150, 'cod_commission_percent' => 2.0, 'weight_charge_per_kg' => 20, 'fuel_surcharge_percent' => 5, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Custom Plan', 'description' => 'Custom negotiated rates', 'base_delivery_charge' => 100, 'cod_commission_percent' => 1.0, 'weight_charge_per_kg' => 12, 'fuel_surcharge_percent' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
        $plans = DB::table('pricing_plans')->get();
        $planVip = $plans->where('name', 'VIP Plan')->first()->id;
        $planBasic = $plans->where('name', 'Basic Plan')->first()->id;
        $planCustom = $plans->where('name', 'Custom Plan')->first()->id;

        // 3. Merchants
        $merchants = [
            ['name' => 'Alibaba Express', 'email' => 'alibaba@test.com', 'city' => 'Karachi', 'plan' => $planVip, 'wallet' => 25000],
            ['name' => 'Daraz Mart', 'email' => 'daraz@test.com', 'city' => 'Lahore', 'plan' => $planBasic, 'wallet' => 5000],
            ['name' => 'Shopify Store PK', 'email' => 'shopify@test.com', 'city' => 'Islamabad', 'plan' => $planVip, 'wallet' => 18000],
            ['name' => 'Telemart Online', 'email' => 'telemart@test.com', 'city' => 'Karachi', 'plan' => $planBasic, 'wallet' => 3200],
            ['name' => 'Homeshopping.pk', 'email' => 'homeshop@test.com', 'city' => 'Lahore', 'plan' => $planCustom, 'wallet' => 45000],
            ['name' => 'ElectroCity', 'email' => 'electro@test.com', 'city' => 'Faisalabad', 'plan' => $planBasic, 'wallet' => 1500],
            ['name' => 'Fashion Hub', 'email' => 'fashion@test.com', 'city' => 'Karachi', 'plan' => $planVip, 'wallet' => 12000],
            ['name' => 'Book World', 'email' => 'books@test.com', 'city' => 'Lahore', 'plan' => $planBasic, 'wallet' => 800],
            ['name' => 'TechZone', 'email' => 'tech@test.com', 'city' => 'Islamabad', 'plan' => $planCustom, 'wallet' => 32000],
            ['name' => 'Organic Store', 'email' => 'organic@test.com', 'city' => 'Peshawar', 'plan' => $planBasic, 'wallet' => 2200],
        ];

        foreach ($merchants as $m) {
            DB::table('users')->updateOrInsert(['email' => $m['email']], [
                'name' => $m['name'],
                'email' => $m['email'],
                'password' => Hash::make('password'),
                'role' => 'shipper',
                'is_approved' => !in_array($m['email'], ['electro@test.com', 'organic@test.com']),
                'approved_at' => now(),
                'account_status' => in_array($m['email'], ['electro@test.com', 'organic@test.com']) ? 'pending' : 'approved',
                'phone' => '0300' . rand(1000000, 9999999),
                'city' => $m['city'],
                'pricing_plan_id' => $m['plan'],
                'wallet_balance' => $m['wallet'],
                'company_name' => $m['name'],
                'created_at' => now()->subDays(rand(1, 60)),
                'updated_at' => now(),
            ]);
        }

        // 4. Courier Integrations
        $couriers = [
            ['name' => 'TCS Courier', 'is_active' => true],
            ['name' => 'Leopards Courier', 'is_active' => true],
            ['name' => 'M&P Courier', 'is_active' => true],
            ['name' => 'Call Courier', 'is_active' => false],
            ['name' => 'BlueEX', 'is_active' => true],
            ['name' => 'Star Track', 'is_active' => true],
        ];

        foreach ($couriers as $c) {
            DB::table('courier_integrations')->insert([
                'courier_name' => $c['name'],
                'api_key' => 'auto_' . bin2hex(random_bytes(16)),
                'api_secret' => bin2hex(random_bytes(16)),
                'account_number' => strtoupper(substr($c['name'], 0, 3)) . '-PK-' . rand(100, 999),
                'is_active' => $c['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $merchantIds = DB::table('users')->where('role', 'shipper')->pluck('id');
        $courierIdsArr = DB::table('courier_integrations')->pluck('id');

        // 5. Bookings
        $statuses = ['pending', 'picked', 'in_transit', 'delivered', 'returned'];
        $cities = ['Karachi', 'Lahore', 'Islamabad', 'Faisalabad', 'Peshawar', 'Quetta', 'Multan', 'Rawalpindi', 'Sialkot', 'Gujranwala'];
        $customerNames = ['Ahmed Ali', 'Sara Khan', 'Usman Raza', 'Fatima Ahmed', 'Bilal Hussain', 'Ayesha Malik', 'Kamran Shah', 'Zainab Ali', 'Rizwan Ahmed', 'Hina Tariq'];

        for ($i = 1; $i <= 85; $i++) {
            $status = $statuses[array_rand($statuses)];
            $isCOD = rand(0, 1);
            DB::table('bookings')->insert([
                'tracking_number' => 'SJC-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'user_id' => $merchantIds->random(),
                'courier_integration_id' => $courierIdsArr->random(),
                'customer_name' => $customerNames[array_rand($customerNames)],
                'customer_phone' => '03' . rand(0, 9) . rand(10000000, 99999999),
                'customer_address' => 'House #' . rand(1, 500) . ', Street ' . rand(1, 20),
                'destination_city' => $cities[array_rand($cities)],
                'origin_city' => $cities[array_rand($cities)],
                'weight' => round(rand(1, 200) / 10, 1),
                'is_cod' => $isCOD,
                'cod_amount' => $isCOD ? rand(500, 15000) : 0,
                'delivery_charges' => rand(120, 350),
                'status' => $status,
                'description' => 'Order #' . rand(1000, 9999),
                'created_at' => now()->subDays(rand(0, 45))->subHours(rand(0, 12)),
                'updated_at' => now(),
            ]);
        }

        // 6. COD Reconciliations (matches actual schema)
        foreach ($courierIdsArr as $cid) {
            $reported = rand(5000, 50000);
            $transferred = $reported - rand(0, 2000);
            DB::table('cod_reconciliations')->insert([
                'courier_integration_id' => $cid,
                'reconciliation_date' => now()->subDays(rand(1, 14)),
                'reported_cash' => $reported,
                'transferred_cash' => $transferred,
                'variance' => $reported - $transferred,
                'total_cod_shipments' => rand(10, 100),
                'successful_deliveries' => rand(8, 95),
                'status' => $transferred >= $reported ? 'verified' : 'discrepancy',
                'notes' => $transferred >= $reported ? 'All matched' : 'Discrepancy found',
                'created_at' => now()->subDays(rand(0, 15)),
                'updated_at' => now(),
            ]);
        }

        // 7. Payouts (matches actual schema)
        foreach ($merchantIds as $mid) {
            $gross = rand(5000, 50000);
            $commission = round($gross * 0.1, 2);
            $charges = rand(0, 500);
            $net = $gross - $commission - $charges;
            $status = rand(0, 1) ? 'completed' : 'pending';
            DB::table('payouts')->insert([
                'user_id' => $mid,
                'payout_reference' => 'PAY-' . now()->format('Ymd') . '-' . str_pad($mid, 3, '0', STR_PAD_LEFT),
                'gross_amount' => $gross,
                'commissions_deducted' => $commission,
                'other_charges' => $charges,
                'net_amount' => $net,
                'period_start' => now()->subMonth(),
                'period_end' => now(),
                'status' => $status,
                'payment_method' => 'bank_transfer',
                'paid_at' => $status === 'completed' ? now() : null,
                'remarks' => 'Monthly payout cycle',
                'created_at' => now()->subDays(rand(0, 20)),
                'updated_at' => now(),
            ]);
        }

        // 8. Wallets (matches actual schema)
        foreach ($merchantIds as $mid) {
            DB::table('wallets')->updateOrInsert(['user_id' => $mid], [
                'balance' => rand(500, 50000),
                'total_credited' => rand(10000, 100000),
                'total_debited' => rand(5000, 50000),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 9. Tracking History
        $bookingIds = DB::table('bookings')->pluck('id');
        foreach ($bookingIds as $bid) {
            $historyCount = rand(1, 3);
            for ($h = 0; $h < $historyCount; $h++) {
                DB::table('tracking_history')->insert([
                    'booking_id' => $bid,
                    'status' => $statuses[array_rand($statuses)],
                    'location' => $cities[array_rand($cities)],
                    'remarks' => ['Received at hub', 'Dispatched', 'Out for delivery', 'Sorted', 'In transit'][rand(0, 4)],
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Sample data seeded successfully!');
    }
}
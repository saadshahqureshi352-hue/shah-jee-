<?php  

namespace Database\Seeders;  

use App\Models\User;  
use App\Models\Affiliate;  
use Illuminate\Database\Seeder;  
use Illuminate\Support\Facades\Hash;  

class AffiliateSeeder extends Seeder  
{  
    public function run(): void  
    {  
        // Create affiliate user if not exists  
        $user = User::updateOrCreate(  
            ['email' => 'affiliate@shahjee.com'],  
            [  
                'name' => 'Affiliate Agent',  
                'password' => Hash::make('affiliate123'),  
                'username' => 'affiliate_agent',  
                'phone' => '03211223344',  
            ]  
        );  

        // Create affiliate record  
        Affiliate::updateOrCreate(  
            ['user_id' => $user->id],  
            [  
                'referral_code' => 'SJC-AFF-001',  
                'available_wallet' => 0.00,  
                'pending_balance' => 0.00,  
                'lifetime_earnings' => 0.00,  
                'total_paid_out' => 0.00,  
                'jazzcash_number' => '03211223344',  
                'easypaisa_number' => '03211223344',  
                'bank_name' => 'Test Bank',  
                'iban' => 'PK1234567890123456',  
                'status' => 'active',  
            ]  
        );  

        $this->command->info('✅ Affiliate user created: affiliate@shahjee.com / affiliate123');  
    }  
}
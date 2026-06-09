<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TempAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'shahjeecourier@gmail.com'],
            [
                'name' => 'Admin Shah Jee',
                'password' => Hash::make('shahjee'),
            ]
        );

        $this->command->info('✅ shahjeecourier@gmail.com password set to shahjee');
    }
}


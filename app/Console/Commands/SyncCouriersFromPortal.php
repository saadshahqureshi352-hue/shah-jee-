<?php

namespace App\Console\Commands;

use App\Models\CourierIntegration;
use App\Models\CourierRateMatrix;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncCouriersFromPortal extends Command
{
    protected $signature = 'couriers:sync';
    protected $description = 'Sync couriers from client portal data and add well-known courier services';

    public function handle()
    {
        $this->info('Syncing couriers...');

        $knownCouriers = [
            [
                'courier_name' => 'TCS Express',
                'logo_path' => '/logos/tcs.png',
                'courier_cost' => 180,
                'shipper_charge' => 260,
            ],
            [
                'courier_name' => 'Leopards Courier',
                'logo_path' => '/logos/leopards.png',
                'courier_cost' => 170,
                'shipper_charge' => 250,
            ],
            [
                'courier_name' => 'M&P Logistics',
                'logo_path' => '/logos/mp.png',
                'courier_cost' => 160,
                'shipper_charge' => 240,
            ],
            [
                'courier_name' => 'Speedex Courier',
                'logo_path' => '/logos/speedex.png',
                'courier_cost' => 150,
                'shipper_charge' => 230,
            ],
            [
                'courier_name' => 'Call Courier',
                'logo_path' => '/logos/call.png',
                'courier_cost' => 175,
                'shipper_charge' => 255,
            ],
            [
                'courier_name' => 'Blueex Courier',
                'logo_path' => '/logos/blueex.png',
                'courier_cost' => 165,
                'shipper_charge' => 245,
            ],
            [
                'courier_name' => 'Trax Express',
                'logo_path' => '/logos/trax.png',
                'courier_cost' => 155,
                'shipper_charge' => 235,
            ],
            [
                'courier_name' => 'Star Track',
                'logo_path' => '/logos/startrack.png',
                'courier_cost' => 145,
                'shipper_charge' => 225,
            ],
        ];

        $existingNames = CourierIntegration::pluck('courier_name')
            ->map(fn($n) => strtolower(trim($n)))
            ->toArray();

        $added = 0;
        foreach ($knownCouriers as $courier) {
            $nameLower = strtolower(trim($courier['courier_name']));
            
            // Check if similar already exists
            $exists = false;
            foreach ($existingNames as $en) {
                if (str_contains($en, explode(' ', $nameLower)[0]) || str_contains($nameLower, explode(' ', $en)[0])) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $c = CourierIntegration::create([
                    'courier_name' => $courier['courier_name'],
                    'logo_path' => $courier['logo_path'],
                    'is_active' => true,
                ]);

                // Insert rate matrix with correct column names
                DB::table('courier_rate_matrices')->insert([
                    'courier_integration_id' => $c->id,
                    'weight_category' => 'standard',
                    'weight_from' => 0.00,
                    'weight_to' => 1000.00,
                    'courier_cost' => $courier['courier_cost'],
                    'shipper_charge' => $courier['shipper_charge'],
                    'rate' => $courier['shipper_charge'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $added++;
                $this->info("Added: {$courier['courier_name']}");
            } else {
                $this->warn("Already exists: {$courier['courier_name']}");
            }
        }

        $this->info("Done! Added {$added} new courier(s).");
        return Command::SUCCESS;
    }
}
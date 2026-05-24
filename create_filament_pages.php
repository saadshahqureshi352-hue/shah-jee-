#!/usr/bin/env php
<?php
/**
 * Setup Filament Pages - Direct PHP Script
 * Run directly: php create_filament_pages.php
 * No Laravel/Artisan dependencies
 */

$basePath = __DIR__ . '/app/Filament/Resources';

$filesToCreate = [
    // WalletResource Pages
    'WalletResource/Pages/ListWallets.php' => '<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use Filament\Resources\Pages\ListRecords;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;
}
',
    'WalletResource/Pages/EditWallet.php' => '<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWallet extends EditRecord
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
',
    // APIKeyResource Pages
    'APIKeyResource/Pages/ListAPIKeys.php' => '<?php

namespace App\Filament\Resources\APIKeyResource\Pages;

use App\Filament\Resources\APIKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAPIKeys extends ListRecords
{
    protected static string $resource = APIKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
',
    'APIKeyResource/Pages/CreateAPIKey.php' => '<?php

namespace App\Filament\Resources\APIKeyResource\Pages;

use App\Filament\Resources\APIKeyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAPIKey extends CreateRecord
{
    protected static string $resource = APIKeyResource::class;
}
',
    'APIKeyResource/Pages/EditAPIKey.php' => '<?php

namespace App\Filament\Resources\APIKeyResource\Pages;

use App\Filament\Resources\APIKeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAPIKey extends EditRecord
{
    protected static string $resource = APIKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
',
    // RateMatrixResource Pages
    'RateMatrixResource/Pages/ListRateMatrices.php' => '<?php

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRateMatrices extends ListRecords
{
    protected static string $resource = RateMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
',
    'RateMatrixResource/Pages/CreateRateMatrix.php' => '<?php

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRateMatrix extends CreateRecord
{
    protected static string $resource = RateMatrixResource::class;
}
',
    'RateMatrixResource/Pages/EditRateMatrix.php' => '<?php

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRateMatrix extends EditRecord
{
    protected static string $resource = RateMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
',
];

echo "🚀 Creating Filament Pages...\n\n";

$createdCount = 0;
foreach ($filesToCreate as $filePath => $content) {
    $fullPath = $basePath . '/' . $filePath;
    $dir = dirname($fullPath);
    
    // Create directory if it doesn't exist
    if (!is_dir($dir)) {
        if (@mkdir($dir, 0755, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
            continue;
        }
    }
    
    // Create file
    if (@file_put_contents($fullPath, $content)) {
        echo "✅ Created file: $filePath\n";
        $createdCount++;
    } else {
        echo "❌ Failed to create file: $filePath\n";
    }
}

echo "\n✨ Setup Complete!\n";
echo "📊 Total files created: $createdCount\n";
?>

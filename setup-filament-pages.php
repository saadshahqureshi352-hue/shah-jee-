<?php
// Setup script to create Filament Pages directories and files

$basePath = __DIR__ . '/app/Filament/Resources';

// Define files to create with their content
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

// Create directories and files
foreach ($filesToCreate as $filePath => $content) {
    $fullPath = $basePath . '/' . $filePath;
    $dir = dirname($fullPath);
    
    // Create directory if it doesn't exist
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "Created directory: $dir\n";
    }
    
    // Create file
    file_put_contents($fullPath, $content);
    echo "Created file: $fullPath\n";
}

echo "\n✅ All Filament Pages have been created successfully!\n";
?>

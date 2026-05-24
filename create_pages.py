import os
import pathlib

base_path = pathlib.Path(r"C:\laragon\www\shah-jee-courier\app\Filament\Resources")

pages = {
    "WalletResource/Pages/ListWallets.php": """<?php

namespace App\Filament\Resources\WalletResource\Pages;

use App\Filament\Resources\WalletResource;
use Filament\Resources\Pages\ListRecords;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;
}
""",
    "WalletResource/Pages/EditWallet.php": """<?php

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
""",
    "APIKeyResource/Pages/ListAPIKeys.php": """<?php

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
""",
    "APIKeyResource/Pages/CreateAPIKey.php": """<?php

namespace App\Filament\Resources\APIKeyResource\Pages;

use App\Filament\Resources\APIKeyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAPIKey extends CreateRecord
{
    protected static string $resource = APIKeyResource::class;
}
""",
    "APIKeyResource/Pages/EditAPIKey.php": """<?php

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
""",
    "RateMatrixResource/Pages/ListRateMatrices.php": """<?php

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
""",
    "RateMatrixResource/Pages/CreateRateMatrix.php": """<?php

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRateMatrix extends CreateRecord
{
    protected static string $resource = RateMatrixResource::class;
}
""",
    "RateMatrixResource/Pages/EditRateMatrix.php": """<?php

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
""",
    "CODReconciliationResource/Pages/ListCODReconciliations.php": """<?php

namespace App\Filament\Resources\CODReconciliationResource\Pages;

use App\Filament\Resources\CODReconciliationResource;
use Filament\Resources\Pages\ListRecords;

class ListCODReconciliations extends ListRecords
{
    protected static string $resource = CODReconciliationResource::class;
}
""",
    "PayoutResource/Pages/ListPayouts.php": """<?php

namespace App\Filament\Resources\PayoutResource\Pages;

use App\Filament\Resources\PayoutResource;
use Filament\Resources\Pages\ListRecords;

class ListPayouts extends ListRecords
{
    protected static string $resource = PayoutResource::class;
}
""",
}

created = 0
for path, content in pages.items():
    full_path = base_path / path
    full_path.parent.mkdir(parents=True, exist_ok=True)
    with open(full_path, 'w') as f:
        f.write(content)
    print(f"✅ Created: {path}")
    created += 1

print(f"\n✨ Created {created} files successfully!")

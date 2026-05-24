<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CODReconciliation extends Model
{
    protected $table = 'cod_reconciliations';

    protected $fillable = [
        'courier_integration_id',
        'reconciliation_date',
        'reported_cash',
        'transferred_cash',
        'variance',
        'total_cod_shipments',
        'successful_deliveries',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reconciliation_date' => 'date',
            'reported_cash' => 'decimal:2',
            'transferred_cash' => 'decimal:2',
            'variance' => 'decimal:2',
        ];
    }

    public function courierIntegration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class);
    }

    public function calculateVariance(): void
    {
        $this->variance = $this->reported_cash - $this->transferred_cash;
        if ($this->variance != 0) {
            $this->status = 'discrepancy';
        }
    }
}

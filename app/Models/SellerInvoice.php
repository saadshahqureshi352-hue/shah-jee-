<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerInvoice extends Model
{
    protected $table = 'seller_invoices';

    protected $fillable = [
        'user_id',
        'invoice_number',
        'period_start',
        'period_end',
        'total_cod',
        'total_deductions',
        'net_amount',
        'payment_method',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'total_cod' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'period_start' => 'date',
            'period_end' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateInvoiceNumber(): string
    {
        $count = static::whereYear('created_at', now()->year)->count() + 1;
        return 'INV-' . now()->year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
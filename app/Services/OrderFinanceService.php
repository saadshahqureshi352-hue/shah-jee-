<?php

namespace App\Services;

class OrderFinanceService
{
    public const TAX_RATE = 0.04;

    /** @return array{cod: float, delivery_charges: float, govt_tax: float, net_amount: float} */
    public static function parcelBreakdown(object $order): array
    {
        $cod = (float) ($order->cod_amount ?? 0);
        $delivery = (float) ($order->delivery_charges ?? 0);
        $govtTax = round($cod * self::TAX_RATE, 2);
        $net = round($cod - $delivery - $govtTax, 2);

        return [
            'cod' => $cod,
            'delivery_charges' => $delivery,
            'govt_tax' => $govtTax,
            'net_amount' => max(0, $net),
        ];
    }

    /** @return array{delivered_count: int, delivered_amount: float, delivery_charges: float, govt_tax: float, total_deductions: float, net_payable: float} */
    public static function summaryFromTotals(float $deliveredAmount, float $deliveryCharges, int $deliveredCount): array
    {
        $govtTax = round($deliveredAmount * self::TAX_RATE, 2);
        $netPayable = round($deliveredAmount - $deliveryCharges - $govtTax, 2);

        $totalDeductions = round($deliveryCharges + $govtTax, 2);

        return [
            'delivered_count' => $deliveredCount,
            'delivered_amount' => $deliveredAmount,
            'delivery_charges' => $deliveryCharges,
            'govt_tax' => $govtTax,
            'total_deductions' => $totalDeductions,
            'net_payable' => max(0, $netPayable),
        ];
    }

    public static function totalDeductionsForOrder(object $order): float
    {
        $b = self::parcelBreakdown($order);

        return round($b['delivery_charges'] + $b['govt_tax'], 2);
    }
}

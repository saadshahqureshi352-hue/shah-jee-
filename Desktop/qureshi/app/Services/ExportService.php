<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export a collection of records to CSV format
     */
    public static function export(Collection $records, string $filename = 'export'): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '-' . now()->format('Y-m-d-His') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($records->isNotEmpty()) {
                $firstRecord = $records->first();
                $attributes = $firstRecord->getAttributes();
                $headers = array_keys($attributes);
                
                // Make headers human-readable
                $readableHeaders = array_map(function ($header) {
                    return str_replace('_', ' ', ucwords($header));
                }, $headers);
                
                fputcsv($file, $readableHeaders);

                foreach ($records as $record) {
                    $row = [];
                    foreach ($headers as $header) {
                        $value = $record->{$header};
                        if ($value instanceof \Carbon\Carbon) {
                            $value = $value->format('Y-m-d H:i:s');
                        } elseif (is_bool($value)) {
                            $value = $value ? 'Yes' : 'No';
                        } elseif (is_array($value)) {
                            $value = json_encode($value);
                        }
                        $row[] = $value;
                    }
                    fputcsv($file, $row);
                }
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export bookings with relevant relationships
     */
    public static function exportBookings(Collection $bookings): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="bookings-' . now()->format('Y-m-d-His') . '.csv"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID', 'Consignment #', 'Customer Name', 'Customer Phone', 
                'Destination City', 'Weight (kg)', 'COD Amount (PKR)', 
                'Delivery Charges (PKR)', 'Status', 'Courier', 
                'Created At', 'Delivered At'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->consignment_no,
                    $booking->customer_name,
                    $booking->customer_phone,
                    $booking->destination_city,
                    $booking->weight,
                    $booking->cod_amount,
                    $booking->delivery_charges,
                    $booking->status,
                    $booking->courier_integration?->courier_name ?? 'N/A',
                    $booking->created_at?->format('Y-m-d H:i'),
                    $booking->delivered_at?->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingCancelController extends Controller
{
    /**
     * Cancel a booking (only allowed if status is 'pending' - not yet dispatched)
     */
    public function cancel(Request $request, int $bookingId)
    {
        $booking = DB::table('bookings')
            ->where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json(['ok' => false, 'message' => 'Booking not found.'], 404);
        }

        // Only allow cancel if status is 'pending' (not yet dispatched)
        if ($booking->status !== 'pending') {
            return response()->json([
                'ok' => false,
                'message' => 'This order cannot be cancelled because it has already been ' . ($booking->status ?? 'processed') . '.'
            ], 400);
        }

        // Cancel the order
        DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'status' => 'cancelled',
                'remarks' => 'Cancelled by shipper' . ($request->reason ? ': ' . $request->reason : ''),
                'updated_at' => now(),
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Order cancelled successfully.',
        ]);
    }

    /**
     * Show edit form for a booking (only if status is 'pending')
     */
    public function edit(int $bookingId)
    {
        $booking = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.id', $bookingId)
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'courier_integrations.courier_name')
            ->first();

        if (!$booking) {
            abort(404);
        }

        // Only allow edit if status is 'pending' (not yet dispatched/scanned)
        if ($booking->status !== 'pending') {
            return redirect()->route('bookings')
                ->withErrors(['edit' => 'This order cannot be edited because it has already been dispatched.']);
        }

        $cities = collect(config('pakistan_cities', []))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('bookings.edit', compact('booking', 'cities'));
    }

    /**
     * Update booking (only if status is 'pending')
     */
    public function update(Request $request, int $bookingId)
    {
        $booking = DB::table('bookings')
            ->where('id', $bookingId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json(['ok' => false, 'message' => 'Booking not found.'], 404);
        }

        if ($booking->status !== 'pending') {
            return response()->json([
                'ok' => false,
                'message' => 'This order cannot be edited because it has already been dispatched.'
            ], 400);
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'complete_address' => 'required|string|max:500',
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:99',
            'weight' => 'required|numeric|min:0.1',
            'cod_amount' => 'required|numeric|min:0',
            'reference_no' => 'nullable|string|max:255',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        DB::table('bookings')
            ->where('id', $bookingId)
            ->update([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'consignee_address' => $request->complete_address,
                'product_name' => $request->product_name,
                'quantity' => $request->quantity,
                'weight' => $request->weight,
                'cod_amount' => $request->cod_amount,
                'reference_no' => $request->reference_no,
                'special_instructions' => $request->special_instructions ?? 'Handle with Care',
                'updated_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'message' => 'Order updated successfully.']);
        }

        return redirect()->route('bookings')->with('success', 'Order updated successfully.');
    }

    /**
     * Export orders as Excel (CSV format for now)
     */
    public function exportExcel(Request $request)
    {
        $status = $request->get('status', 'all');
        $perPage = 10000; // max rows for export

        $query = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'courier_integrations.courier_name');

        if ($status !== 'all') {
            $query->where('bookings.status', $status);
        }

        $orders = $query->orderByDesc('bookings.created_at')->limit($perPage)->get();

        // Generate CSV
        $filename = 'orders_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($handle, [
                'Sr No', 'Tracking Number', 'Reference No', 'Customer Name',
                'Customer Phone', 'Courier', 'Destination City', 'Address',
                'Product', 'Quantity', 'Weight (KG)', 'COD Amount',
                'Delivery Charges', 'Service Type', 'Status', 'Booking Date'
            ]);

            foreach ($orders as $i => $order) {
                fputcsv($handle, [
                    $i + 1,
                    $order->tracking_number ?? 'N/A',
                    $order->reference_no ?? '',
                    $order->customer_name,
                    $order->customer_phone ?? '',
                    $order->courier_name ?? 'Standard',
                    $order->destination_city ?? '',
                    $order->consignee_address ?? '',
                    $order->product_name ?? '',
                    $order->quantity ?? 1,
                    $order->weight ?? 0,
                    $order->cod_amount ?? 0,
                    $order->delivery_charges ?? 0,
                    $order->service_type ?? '',
                    $order->status ?? 'pending',
                    $order->created_at ? date('Y-m-d H:i:s', strtotime($order->created_at)) : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export orders as PDF (simple HTML table → PDF download)
     */
    public function exportPdf(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'courier_integrations.courier_name');

        if ($status !== 'all') {
            $query->where('bookings.status', $status);
        }

        $orders = $query->orderByDesc('bookings.created_at')->limit(500)->get();

        $statusLabel = $status === 'all' ? 'All Orders' : ucfirst(str_replace('_', ' ', $status));

        return view('bookings.export-pdf', compact('orders', 'statusLabel'));
    }
}
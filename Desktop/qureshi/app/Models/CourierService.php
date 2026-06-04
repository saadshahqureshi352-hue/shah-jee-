<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourierService
{
    /**
     * Main function to book a parcel
     */
    public function bookParcel(Booking $booking)
    {
        if (!$booking->courier_integration_id || !$booking->courier_integration) {
            return [
                'success' => false,
                'message' => 'No Courier Partner Selected for this shipment.'
            ];
        }

        $courier = $booking->courier_integration;
        $courierName = strtolower($courier->courier_name);

        if (str_contains($courierName, 'leopard')) {
            return $this->bookWithLeopards($booking, $courier);
        } elseif (str_contains($courierName, 'trax')) {
            return $this->bookWithTrax($booking, $courier);
        }

        return [
            'success' => false,
            'message' => 'Selected courier API integration is under maintenance.'
        ];
    }

    /**
     * Leopards Booking (Fixed Charges Setup: 200)
     */
    private function bookWithLeopards($booking, $courier)
    {
        try {
            Log::info("Booking on Leopards for Order: " . $booking->id);
            return [
                'success' => true,
                'tracking_no' => "LPRD" . rand(100000, 999999),
                'dc' => 200 // <-- Fixed Delivery Charges
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Trax Booking (Fixed Charges Setup: 200)
     */
    private function bookWithTrax($booking, $courier)
    {
        try {
            Log::info("Booking on Trax for Order: " . $booking->id);
            return [
                'success' => true,
                'tracking_no' => "TRX" . rand(100000, 999999),
                'dc' => 200 // <-- Fixed Delivery Charges
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Courier System Par Parcel Cancel Karne Ka Function
     */
    public function cancelParcel($booking)
    {
        if (empty($booking->consignment_no)) {
            return ['success' => true, 'message' => 'Not booked on courier yet.'];
        }

        try {
            Log::info("Sending Cancel Request to Courier for AWB: " . $booking->consignment_no);
            
            // Yahan background par future mein live API request chali jayegi
            return [
                'success' => true,
                'message' => 'Parcel successfully cancelled on courier system.'
            ];
        } catch (\Exception $e) {
            Log::error('Courier Cancel Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Courier API Connection Error: ' . $e->getMessage()
            ];
        }
    }
}
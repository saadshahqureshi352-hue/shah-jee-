<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use App\Models\CourierService; // <--- Ab yeh seedha Models se load hoga
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $booking = new \App\Models\Booking($data);
        
        if (!empty($data['courier_integration_id'])) {
            $booking->courier_integration_id = $data['courier_integration_id'];
            $booking->load('courier_integration');
        }

        // Service initialization from Models folder
        $courierService = new CourierService();
        $apiResult = $courierService->bookParcel($booking);

        if ($apiResult['success']) {
            $data['consignment_no'] = $apiResult['tracking_no'];
            $data['delivery_charges'] = $apiResult['dc'];
            
            Notification::make()
                ->title('Courier Booking Successful')
                ->body('Parcel booked with AWB: ' . $apiResult['tracking_no'])
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Courier API Warning')
                ->body($apiResult['message'] ?? 'Could not generate real-time AWB.')
                ->warning()
                ->send();
        }

        return $data;
    }
}
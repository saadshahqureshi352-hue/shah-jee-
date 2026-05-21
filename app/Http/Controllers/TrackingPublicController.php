<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TrackingPublicController extends Controller
{
    /**
     * Public tracking page linked from WhatsApp alerts: /track/{tracking_number}
     */
    public function show(string $tracking_number)
    {
        $tracking_number = preg_replace('/[^A-Za-z0-9_-]/', '', $tracking_number);

        $order = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.tracking_number', $tracking_number)
            ->select('bookings.*', 'courier_integrations.courier_name')
            ->first();

        return view('track.public-show', compact('order', 'tracking_number'));
    }
}

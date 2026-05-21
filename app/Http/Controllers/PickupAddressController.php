<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickupAddressController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $addresses = DB::table('pickup_addresses')
            ->where('user_id', $userId)
            ->where('status', '!=', 'deleted')
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total' => DB::table('pickup_addresses')->where('user_id', $userId)->where('status', '!=', 'deleted')->count(),
            'active' => DB::table('pickup_addresses')->where('user_id', $userId)->where('status', 'active')->count(),
            'blocked' => DB::table('pickup_addresses')->where('user_id', $userId)->where('status', 'blocked')->count(),
            'deleted' => DB::table('pickup_addresses')->where('user_id', $userId)->where('status', 'deleted')->count(),
        ];

        $cities = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Multan', 'Faisalabad',
            'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala', 'Hyderabad', 'Sukkur',
        ];

        return view('pickup-addresses.index', compact('addresses', 'stats', 'cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
        ]);

        $bookingId = 'SJC'.str_pad((string) (DB::table('pickup_addresses')->max('id') + 1), 5, '0', STR_PAD_LEFT);

        DB::table('pickup_addresses')->insert([
            'user_id' => auth()->id(),
            'booking_id' => $bookingId,
            'brand_name' => $validated['brand_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('pickup-addresses.index')->with('success', 'Pickup address added successfully.');
    }
}

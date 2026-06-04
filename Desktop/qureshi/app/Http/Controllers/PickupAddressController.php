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

    /**
     * Show the form for editing the specified pickup address.
     */
    public function edit($id)
    {
        $userId = auth()->id();

        // Check karein ke address maujood hai aur usi logged-in user ka hai
        $address = DB::table('pickup_addresses')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$address) {
            abort(404, 'Address not found or unauthorized.');
        }

        // Cities list jo edit form ke dropdown/datalist me show hogi
        $cities = [
            'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Multan', 'Faisalabad',
            'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala', 'Hyderabad', 'Sukkur',
        ];

        return view('pickup-addresses.edit', compact('address', 'cities'));
    }

    /**
     * Update the specified pickup address in storage.
     */
    public function update(Request $request, $id)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'brand_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'status' => 'required|string|in:active,blocked',
        ]);

        // Database me check aur update karein
        $updated = DB::table('pickup_addresses')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'brand_name' => $validated['brand_name'],
                'phone' => $validated['phone'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'updated_at' => now(),
            ]);

        return redirect()->route('pickup-addresses.index')->with('success', 'Pickup address updated successfully.');
    }

    /**
     * Soft delete or remove the specified pickup address.
     */
    public function destroy($id)
    {
        $userId = auth()->id();

        // Aapke list logic ke mutabik 'deleted' status wale show nahi hote, 
        // isliye hum ise 'deleted' par update (Soft Delete) kar rahe hain.
        DB::table('pickup_addresses')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->update([
                'status' => 'deleted',
                'updated_at' => now()
            ]);

        return redirect()->route('pickup-addresses.index')->with('success', 'Pickup address deleted successfully.');
    }
}
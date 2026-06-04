<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmartToolsController extends Controller
{
    public function hub()
    {
        return redirect()->route('smart-tools.tracking');
    }

    /** Multi-courier live tracking (authenticated) */
    public function tracking(Request $request)
    {
        $trackingInput = trim((string) $request->get('q', ''));
        $courier = $request->get('courier');
        $result = null;
        $apiNote = null;

        if ($trackingInput !== '') {
            $q = DB::table('bookings')
                ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
                ->where(function ($qb) use ($trackingInput) {
                    $qb->where('bookings.tracking_number', 'like', '%'.$trackingInput.'%')
                        ->orWhere('bookings.reference_no', $trackingInput);
                })
                ->where('bookings.user_id', auth()->id())
                ->select('bookings.*', 'courier_integrations.courier_name');

            if ($courier) {
                $q->where('courier_integrations.courier_name', $courier);
            }

            $result = $q->first();

            if ($result) {
                $apiNote = 'Live courier API sync is stubbed until API credentials are configured. Showing portal record.';
            } else {
                $apiNote = 'No matching booking in your account. Detected format may route to courier API once enabled.';
            }
        }

        $couriers = ['TCS', 'Leopards', 'Trax', 'M&P', 'BarqRaftar'];

        return view('smart-tools.tracking', compact('result', 'trackingInput', 'courier', 'apiNote', 'couriers'));
    }

    /** WhatsApp gateway UI + profiles */
    public function whatsAppGateway(Request $request)
    {
        $profiles = DB::table('whatsapp_profiles')->where('user_id', auth()->id())->orderByDesc('created_at')->get();
        $settings = DB::table('user_alert_templates')->where('user_id', auth()->id())->first();

        return view('smart-tools.whatsapp-gateway', compact('profiles', 'settings'));
    }

    public function storeWhatsAppProfile(Request $request)
    {
        $request->validate(['name' => 'required|string|max:120']);

        $stub = 'pair_'.auth()->id().'_'.time();

        DB::table('whatsapp_profiles')->insert([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'connected' => false,
            'pair_stub' => $stub,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('smart-tools.whatsapp-gateway')
            ->with('success', 'Profile created — scan QR to complete linking.')
            ->with('new_pair_stub', $stub);
    }

    /** Demo: mark last profile connected */
    public function activateConsigneeAlert(Request $request)
    {
        $profile = DB::table('whatsapp_profiles')->where('user_id', auth()->id())->where('connected', false)->orderByDesc('id')->first();
        if (! $profile) {
            return back()->withErrors(['gateway' => 'Create a profile first, then activate.']);
        }

        DB::table('whatsapp_profiles')->where('id', $profile->id)->update([
            'connected' => true,
            'linked_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Consignee alert profile linked (demo).');
    }

    /** Demo disconnect all */
    public function deactivateConsigneeAlert()
    {
        DB::table('whatsapp_profiles')->where('user_id', auth()->id())->update([
            'connected' => false,
            'linked_at' => null,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Consignee alert disconnected.');
    }

    /** Alert templates */
    public function alertTemplates()
    {
        $row = DB::table('user_alert_templates')->where('user_id', auth()->id())->first();

        return view('smart-tools.alert-templates', [
            'shipper_alert' => $row ? $row->shipper_alert : '',
            'consignee_alert' => $row ? $row->consignee_alert : '',
            'notify_me_consent' => $row ? (bool) $row->notify_me_consent : false,
        ]);
    }

    public function saveAlertTemplates(Request $request)
    {
        $request->validate([
            'shipper_alert' => 'nullable|string|max:5000',
            'consignee_alert' => 'nullable|string|max:5000',
            'notify_me_consent' => 'sometimes|boolean',
        ]);

        $exists = DB::table('user_alert_templates')->where('user_id', auth()->id())->exists();

        $payload = [
            'shipper_alert' => $request->shipper_alert,
            'consignee_alert' => $request->consignee_alert,
            'notify_me_consent' => $request->boolean('notify_me_consent'),
            'updated_at' => now(),
        ];

        if ($exists) {
            DB::table('user_alert_templates')->where('user_id', auth()->id())->update($payload);
        } else {
            $payload['user_id'] = auth()->id();
            $payload['created_at'] = now();
            DB::table('user_alert_templates')->insert($payload);
        }

        return back()->with('success', 'Alert templates saved.');
    }
}

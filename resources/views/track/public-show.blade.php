@extends('layouts.guest-tracking')

@section('content')
    <div class="mx-auto max-w-lg p-6">
        @if($order)
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg">
                <p class="text-xs font-semibold uppercase text-teal-600">Tracking</p>
                <p class="mt-1 font-mono text-2xl font-bold text-slate-900">{{ $order->tracking_number }}</p>
                <p class="mt-2 text-sm text-slate-500">Courier: <span class="font-semibold text-slate-800">{{ $order->courier_name ?? 'Standard' }}</span></p>

                @php $meta = \App\Http\Controllers\OrderController::statusMeta($order->status); @endphp
                <div class="mt-6 rounded-xl border border-slate-100 bg-slate-50 p-4">
                    <p class="text-xs uppercase text-slate-500">Current status</p>
                    <span class="mt-2 inline-block rounded-lg px-3 py-1 text-sm font-bold {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                    <p class="mt-2 text-sm text-slate-600">{{ $meta['shipment'] ?? '' }}</p>
                </div>

                @if($order->destination_city)
                    <p class="mt-4 text-sm text-slate-600">Destination: <strong>{{ $order->destination_city }}</strong></p>
                @endif
                <p class="mt-4 text-xs text-slate-400">Multi-courier live sync will appear here when courier APIs are connected.</p>
            </div>
        @else
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-8 text-center text-rose-800">
                <p class="font-semibold">Tracking number not found.</p>
                <p class="mt-2 text-sm">Please check your link or SMS and try again.</p>
                <code class="mt-4 inline-block rounded bg-white px-2 py-1 text-xs">{{ $tracking_number }}</code>
            </div>
        @endif
    </div>
@endsection

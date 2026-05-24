<x-app-layout>
    <div class="mx-auto max-w-2xl space-y-6 p-4 sm:p-6 mt-6" style="background-color: #f8fafc; border-radius: 24px; border: 1px solid #e2e8f0;">
        
        <!-- Header -->
        <div class="pb-3 flex justify-between items-center" style="border-b: 1px solid #f1f5f9;">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 sm:text-2xl tracking-tight" style="font-weight: 800;">Edit Pickup Address</h1>
                <p class="text-xs text-slate-400 mt-1">Update your pickup location details below.</p>
            </div>
            <a href="{{ route('pickup-addresses.index') }}" class="text-xs font-bold text-slate-600 bg-slate-200 px-3 py-1.5 rounded-lg hover:bg-slate-300 transition-colors">
                Back to List
            </a>
        </div>

        <!-- Edit Form -->
        <form method="POST" action="{{ route('pickup-addresses.update', $address->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Brand Name -->
            <div>
                <label for="brand_name" class="mb-1 block text-sm font-semibold text-slate-800">Brand Name <span class="text-rose-500">*</span></label>
                <input id="brand_name" type="text" name="brand_name" value="{{ old('brand_name', $address->brand_name) }}" placeholder="Enter brand name" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                @error('brand_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="mb-1 block text-sm font-semibold text-slate-800">Phone Number <span class="text-rose-500">*</span></label>
                <input id="phone" type="tel" name="phone" value="{{ old('phone', $address->phone) }}" placeholder="Enter shipper phone" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>
            
            <!-- City Selection (Searchable) -->
            <div>
                <label for="city" class="mb-1 block text-sm font-semibold text-slate-800">Select City <span class="text-rose-500">*</span></label>
                <input list="pakistan-cities" id="city" name="city" value="{{ old('city', $address->city) }}" placeholder="Type to search city..." required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                
                <datalist id="pakistan-cities">
                    @if(isset($cities) && count($cities) > 0)
                        @foreach($cities as $city)
                            <option value="{{ $city }}">
                        @endforeach
                    @endif
                </datalist>
                @error('city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- Full Address -->
            <div>
                <label for="address" class="mb-1 block text-sm font-semibold text-slate-800">Address <span class="text-rose-500">*</span></label>
                <textarea id="address" name="address" rows="4" placeholder="Enter full address" required
                          class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">{{ old('address', $address->address) }}</textarea>
                @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- Status Configuration -->
            <div>
                <label for="status" class="mb-1 block text-sm font-semibold text-slate-800">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                    <option value="active" {{ old('status', $address->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="blocked" {{ old('status', $address->status) === 'blocked' ? 'selected' : '' }}>Blocked</option>
                </select>
                @error('status')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 pt-2">
                <a href="{{ route('pickup-addresses.index') }}"
                   class="flex-1 text-center rounded-lg bg-slate-800 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 order-2 sm:order-1">
                    Cancel
                </a>
                <button type="submit"
                        class="flex-1 rounded-lg bg-teal-600 py-2.5 text-sm font-semibold text-white hover:bg-teal-700 order-1 sm:order-2">
                    Update Address
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
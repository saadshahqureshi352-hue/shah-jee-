<x-app-layout>
    <div class="space-y-6 p-4 sm:p-8 bg-slate-50 min-h-screen" x-data="{ showModal: false }">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Manage {{ $courier['name'] }} Load Sheets</h1>
                <p class="text-slate-500 mt-1">Efficiently track and generate your loadsheets.</p>
            </div>
            <button @click="showModal = true" class="rounded-xl bg-violet-600 px-6 py-3 font-semibold text-white shadow-lg shadow-violet-200 transition hover:bg-violet-700">+ Generate New</button>
        </div>

        {{-- Modal for Generate Load Sheet --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showModal = false"></div>
            <div class="relative w-full max-w-lg rounded-2xl border border-slate-100 bg-white shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-800">Generate Load Sheet For <span class="text-violet-600">{{ $courier['name'] }}</span></h2>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-800">✕</button>
                </div>
                
                <div class="px-6 py-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Date Range</label>
                    <div class="relative">
                        <input type="text" value="Today Results" readonly class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm bg-white focus:ring-2 focus:ring-violet-200 cursor-pointer">
                        <svg class="absolute right-4 top-3.5 h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>

                <div class="mx-6 mb-5 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
                        <span class="text-sm font-bold text-slate-800">Available Orders</span>
                        <span class="text-xs font-bold text-orange-600 bg-orange-100 px-3 py-1 rounded-full">0 / 60</span>
                    </div>
                    <div class="py-16 text-center text-sm text-slate-400">
                        No orders found for the user.
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                    <span class="text-sm font-medium text-slate-400">No orders selected</span>
                    <div class="flex gap-3">
                        <button @click="showModal = false" class="px-5 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50">Cancel</button>
                        <button class="px-5 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700 shadow-md shadow-violet-100">Generate</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm flex flex-col md:flex-row gap-4">
            <input type="text" placeholder="Select date range" class="rounded-lg border border-slate-200 px-3 py-2 text-sm flex-1">
            <input type="text" placeholder="Search anything here" class="rounded-lg border border-slate-200 px-3 py-2 text-sm flex-1">
            <select class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <option>50 Per Page</option>
            </select>
        </div>

        <div class="flex gap-2 flex-wrap">
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 bg-white">Book Packet</button>
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 bg-white">Bulk Booking</button>
            <button class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 bg-white">Bulk Print Labels</button>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm overflow-x-auto">
            <table class="w-full min-w-[600px] text-left text-sm">
                <thead class="bg-slate-700 text-xs font-semibold uppercase text-white">
                    <tr>
                        <th class="px-3 py-3">Sr No</th>
                        <th class="px-3 py-3">Courier</th>
                        <th class="px-3 py-3">Created Date</th>
                        <th class="px-3 py-3">Packets</th>
                        <th class="px-3 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-3 py-4 font-bold">1<br><span class="text-xs text-slate-400 font-normal">7328125</span></td>
                        <td class="px-3 py-4">{{ $courier['name'] }}</td>
                        <td class="px-3 py-4 font-semibold">11 Apr 2026<br><span class="text-xs text-slate-400 font-normal">1 month ago</span></td>
                        <td class="px-3 py-4 text-sky-600 font-bold">1<br><span class="text-xs text-orange-400 font-normal">CN count</span></td>
                        <td class="px-3 py-4">↓</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

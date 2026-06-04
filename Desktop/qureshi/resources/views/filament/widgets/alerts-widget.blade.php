<x-filament-widgets::widget>
    <x-filament::section heading="Active Alerts">
        @php $alerts = $this->getAlerts(); @endphp

        <ul class="space-y-2">
            @foreach($alerts as $alert)
                <li class="flex items-start gap-3 p-3 rounded-lg
                    @if($alert['type'] === 'danger') bg-red-50 dark:bg-red-950/40
                    @elseif($alert['type'] === 'warning') bg-yellow-50 dark:bg-yellow-950/40
                    @else bg-green-50 dark:bg-green-950/40
                    @endif">
                    <span class="text-sm font-medium
                        @if($alert['type'] === 'danger') text-red-700 dark:text-red-300
                        @elseif($alert['type'] === 'warning') text-yellow-700 dark:text-yellow-300
                        @else text-green-700 dark:text-green-300
                        @endif">
                        {{ $alert['message'] }}
                    </span>
                </li>
            @endforeach
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
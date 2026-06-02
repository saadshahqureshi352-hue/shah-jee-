<table class="data-table w-full text-sm">
    <thead>
        <tr>
            <th>Order #</th>
            <th>Merchant</th>
            <th>City</th>
            <th>Courier</th>
            <th>COD</th>
            <th>4% Tax</th>
            <th>Courier 2%</th>
            <th>Delivery charge</th>
            <th>Profit</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            <tr>
                <td class="font-black text-slate-950">{{ $order[0] }}</td>
                <td class="font-semibold">{{ $order[1] }}</td>
                <td>{{ $order[2] }}</td>
                <td>{{ $order[3] }}</td>
                <td>{{ $order[4] }}</td>
                <td>{{ $order[5] }}</td>
                <td>{{ $order[6] }}</td>
                <td>{{ $order[7] }}</td>
                <td class="{{ $order[8] === '-' ? 'text-slate-400' : 'font-black text-emerald-700' }}">{{ $order[8] }}</td>
                <td>
                    <span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $badge[$order[10]] ?? $badge['slate'] }}">
                        {{ $order[9] }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

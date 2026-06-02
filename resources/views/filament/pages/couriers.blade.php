<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- COURIERS -->
                <div class="page active" id="page-couriers">
                    <div style="font-size:10px;color:var(--color-text-secondary);background:var(--color-background-warning);padding:7px 12px;border-radius:6px;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                        <i class="ti ti-info-circle" style="font-size:12px;color:var(--color-text-warning)" aria-hidden="true"></i>
                        <span style="color:var(--color-text-warning)">Profit = Merchant rate − Courier rate &nbsp;·&nbsp; Sirf dispatched orders pe profit count hoga &nbsp;·&nbsp; Courier 2% tax + delivery charges kat ke COD bheji ga</span>
                    </div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">Courier management — rate edit & profit</div>
                            <span class="badge bg-s" id="courier-saved" style="display:none"><i class="ti ti-check" aria-hidden="true"></i> Saved</span>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Courier</th>
                                <th>Status</th>
                                <th>Courier rate (Rs)</th>
                                <th>Merchant rate (Rs)</th>
                                <th>Profit/order</th>
                                <th>Dispatched</th>
                                <th>Total profit</th>
                                <th>Toggle</th>
                                <th>Save</th>
                            </tr>
                            </thead>
                            <tbody id="courier-tb"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const couriers=[
            {name:
                    'Leopards'
                ,on:true,cRate:165,mRate:220,dispatched:52},
            {name:
                    'TCS'
                ,on:true,cRate:200,mRate:240,dispatched:38},
            {name:
                    'M&P'
                ,on:true,cRate:150,mRate:220,dispatched:25},
            {name:
                    'Call Courier'
                ,on:true,cRate:140,mRate:200,dispatched:18},
            {name:
                    'Trax'
                ,on:false,cRate:180,mRate:220,dispatched:0},
            {name:
                    'BlueEx'
                ,on:true,cRate:160,mRate:210,dispatched:14},
        ];
        function renderCouriers(){
            document.getElementById(
                'courier-tb'
            ).innerHTML=couriers.map((c,i)=>{
                const profit=c.mRate-c.cRate;
                const total=c.on?profit*c.dispatched:0;
                const pc=profit>=0?
                    'pos'
                    :
                    'neg'
                ;
                return`<tr><td><b>${c.name}</b></td><td><span class="badge ${c.on?
                    'bg-s'
                    :
                    'bg-n'
                }">${c.on?
                    'Active'
                    :
                    'Off'
                }</span></td>
    <td><input class="rinput" type="number" step="5" value="${c.cRate}" onchange="couriers[${i}].cRate=+this.value;renderCouriers()"></td>
    <td><input class="rinput" type="number" step="5" value="${c.mRate}" onchange="couriers[${i}].mRate=+this.value;renderCouriers()"></td>
    <td><span class="badge ${profit>=0?
                    'bg-s'
                    :
                    'bg-d'
                }">${fmt(profit)}</span></td>
    <td><b>${c.dispatched}</b> <span style="font-size:9px;color:var(--color-text-tertiary)">dispatched</span></td>
    <td class="${pc}">${c.on?fmt(total):
                    '—'
                }</td>
    <td><button class="tgl ${c.on?
                    'on'
                    :
                    'off'
                }" onclick="couriers[${i}].on=!couriers[${i}].on;renderCouriers()" aria-label="Toggle ${c.name}"></button></td>
    <td><button class="btn btn-b" onclick="saveCourier()"><i class="ti ti-device-floppy" aria-hidden="true"></i>Save</button></td></tr>`;
            }).join(
                ''
            );
        }
        function saveCourier(){
            const el=document.getElementById(
                'courier-saved'
            );
            el.style.display=
                'inline-block'
            ;
            setTimeout(()=>el.style.display=
                'none'
            ,2000);
        }
        function fmt(n){return 'Rs '+Math.round(n).toLocaleString();}
        renderCouriers();
    </script>
</x-filament-panels::page>

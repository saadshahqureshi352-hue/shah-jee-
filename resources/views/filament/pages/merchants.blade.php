<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- MERCHANTS -->
                <div class="page active" id="page-merchants">
                    <div class="sec-title"><i class="ti ti-clock" aria-hidden="true"></i>Pending approval</div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">New merchant requests</div>
                            <span class="badge bg-w">4 pending</span>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Business</th>
                                <th>City</th>
                                <th>Plan</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="pending-tb"></tbody>
                        </table>
                    </div>

                    <div class="sec-title"><i class="ti ti-building-store" aria-hidden="true"></i>Active merchants — finance summary</div>
                    <div class="card">
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Plan</th>
                                <th>Dispatched</th>
                                <th>Delivered</th>
                                <th>Returned</th>
                                <th>Total COD</th>
                                <th>Delivery charges</th>
                                <th>4% Tax</th>
                                <th>Net payable</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody id="active-tb"></tbody>
                        </table>
                    </div>

                    <div class="sec-title"><i class="ti ti-settings" aria-hidden="true"></i>Return charges per merchant</div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">Custom return rate — admin sets per merchant</div>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Plan</th>
                                <th>Standard return rate</th>
                                <th>Custom return rate</th>
                                <th>Override active</th>
                                <th>Save</th>
                            </tr>
                            </thead>
                            <tbody id="return-tb"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const pendingMs=[
            {name:
                    'Style Hub PK'
                ,type:
                    'Fashion'
                ,city:
                    'Karachi'
                ,plan:
                    'VIP'
                ,joined:
                    '28 May'
                ,status:
                    'pending'
            },
            {name:
                    'Tech Galaxy'
                ,type:
                    'Electronics'
                ,city:
                    'Lahore'
                ,plan:
                    'Basic'
                ,joined:
                    '30 May'
                ,status:
                    'pending'
            },
            {name:
                    'Ghar Ghar'
                ,type:
                    'Grocery'
                ,city:
                    'Islamabad'
                ,plan:
                    'Basic'
                ,joined:
                    '31 May'
                ,status:
                    'pending'
            },
            {name:
                    'Desi Crafts'
                ,type:
                    'Handicrafts'
                ,city:
                    'Multan'
                ,plan:
                    'Gold'
                ,joined:
                    '31 May'
                ,status:
                    'pending'
            },
        ];
        function renderPending(){
            document.getElementById(
                'pending-tb'
            ).innerHTML=pendingMs.map((m,i)=>{
                if(m.status===
                    'pending'
                ) return`<tr><td><b>${m.name}</b></td><td>${m.type}</td><td>${m.city}</td><td><span class="badge bg-w">${m.plan}</span></td><td style="color:var(--color-text-secondary)">${m.joined}</td><td><div style="display:flex;gap:5px"><button class="btn btn-g" onclick="appM(${i})"><i class="ti ti-check" aria-hidden="true"></i>Approve</button><button class="btn btn-r" onclick="rejM(${i})"><i class="ti ti-x" aria-hidden="true"></i>Reject</button></div></td></tr>`;
                if(m.status===
                    'approved'
                ) return`<tr><td><b>${m.name}</b></td><td>${m.type}</td><td>${m.city}</td><td><span class="badge bg-s">${m.plan}</span></td><td style="color:var(--color-text-secondary)">${m.joined}</td><td><span class="badge bg-s"><i class="ti ti-check" aria-hidden="true"></i> Approved</span></td></tr>`;
                return`<tr><td><b>${m.name}</b></td><td>${m.type}</td><td>${m.city}</td><td>${m.plan}</td><td style="color:var(--color-text-secondary)">${m.joined}</td><td><span class="badge bg-d"><i class="ti ti-x" aria-hidden="true"></i> Rejected</span></td></tr>`;
            }).join(
                ''
            );
        }
        function appM(i){pendingMs[i].status=
            'approved'
        ;renderPending();showToast(
            'Merchant approved!'
        );}
        function rejM(i){pendingMs[i].status=
            'rejected'
        ;renderPending();showToast(
            'Merchant rejected.'
        );}

        const activeMs=[
            {name:
                    'ABC Store'
                ,plan:
                    'VIP'
                ,dispatched:842,delivered:785,returned:57,cod:980000,charges:126300,suspended:false,retOverride:false,retOverrideValue:90},
            {name:
                    'XYZ Shop'
                ,plan:
                    'Basic'
                ,dispatched:312,delivered:266,returned:46,cod:420000,charges:68640,suspended:false,retOverride:false,retOverrideValue:120},
            {name:
                    'Fast Deals'
                ,plan:
                    'Gold'
                ,dispatched:1204,delivered:980,returned:224,cod:1850000,charges:264880,suspended:true,retOverride:false,retOverrideValue:110},
            {name:
                    'Tech Zone'
                ,plan:
                    'Basic'
                ,dispatched:198,delivered:160,returned:38,cod:280000,charges:43560,suspended:false,retOverride:false,retOverrideValue:120},
        ];
        function fmt(n){return 
            'Rs '
            +Math.round(n).toLocaleString();}
        function renderActive(){
            document.getElementById(
                'active-tb'
            ).innerHTML=activeMs.map((m,i)=>{
                const tax=Math.round(m.cod*0.04);
                const net=Math.round(m.cod-m.charges-tax);
                const sl=m.suspended?
                    'Suspended'
                    :
                    'Active'
                ;
                return`<tr><td><b>${m.name}</b></td><td><span class="badge bg-w">${m.plan}</span></td><td>${m.dispatched}</td><td style="color:var(--color-text-success)">${m.delivered}</td><td style="color:var(--color-text-danger)">${m.returned}</td><td>${fmt(m.cod)}</td><td>${fmt(m.charges)}</td><td style="color:var(--color-text-warning)">${fmt(tax)}</td><td class="pos">${fmt(net)}</td><td><span class="badge ${m.suspended?
                    'bg-d'
                    :
                    'bg-s'
                }">${sl}</span></td><td><div style="display:flex;gap:4px"><button class="btn">View</button><button class="btn ${m.suspended?
                    'btn-g'
                    :
                    'btn-r'
                }" onclick="suspendM(${i})">${m.suspended?
                    'Reactivate'
                    :
                    'Suspend'
                }</button></div></td></tr>`;
            }).join(
                ''
            );

            document.getElementById(
                'return-tb'
            ).innerHTML=activeMs.map((m,i)=>{
                const std=m.plan===
                    'VIP'
                    ?90:m.plan===
                    'Gold'
                    ?110:120;
                const customVal = m.retOverride ? (m.retOverrideValue ?? std) : std;
                return`<tr><td><b>${m.name}</b></td><td><span class="badge bg-w">${m.plan}</span></td><td>Rs ${std}</td><td><input class="rinput" id="ret-${i}" value="${customVal}" type="number"></td><td><button class="btn ${m.retOverride?
                    'btn-b'
                    :
                    ''
                }" onclick="toggleRet(${i})" id="ret-tgl-${i}">${m.retOverride?
                    'Active'
                    :
                    'Set custom'
                }</button></td></td><td><button class="btn btn-b" onclick="saveRet(${i})"><i class="ti ti-device-floppy" aria-hidden="true"></i>Save</button></td></tr>`;
            }).join(
                ''
            );
        }
        function suspendM(i){activeMs[i].suspended=!activeMs[i].suspended;renderActive();showToast(activeMs[i].suspended?
            'Merchant suspended.'
            :
            'Merchant reactivated!'
        );}
        function toggleRet(i){
            const m=activeMs[i];
            m.retOverride=!m.retOverride;
            if(m.retOverride){
                const v=+document.getElementById(
                    'ret-'
                    +i).value;
                m.retOverrideValue=Number.isFinite(v)?v:m.retOverrideValue;
            }
            renderActive();
        }
        function saveRet(i){
            const m=activeMs[i];
            const v=+document.getElementById(
                'ret-'
                +i).value;
            m.retOverrideValue=Number.isFinite(v)?v:m.retOverrideValue;
            m.retOverride=true;
            renderActive();
            showToast(
                'Return rate saved for '
                +m.name+
                '!'
            );
        }

        renderPending();
        renderActive();
        function showToast(message) {
            console.log(message); // Replace with actual toast display logic
        }
    </script>
</x-filament-panels::page>

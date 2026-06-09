<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <div class="page active" id="page-pricing">
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
                        @forelse($pricingPlans ?? [] as $index => $plan)
                        <div class="plan-card" style="border:{{ $index === 0 ? ''2px solid #10b981'' : ($index === 1 ? ''2px solid #3b82f6'' : ''2px solid #8b5cf6'') }}">
                            <div class="plan-name">
                                {{ $plan["name"] }}
                                <span class="badge {{ $index === 0 ? ''bg-s'' : ($index === 1 ? ''bg-i'' : ''bg-w'') }}">{{ $plan["merchant_count"] }} merchants</span>
                            </div>
                            <table class="pt">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Rate (Rs)</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Different City</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["diff_city_rate"] }}" id="diff-{{ $plan["id"] }}"></td>
                                </tr>
                                <tr>
                                    <td>Same City</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["same_city_rate"] }}" id="same-{{ $plan["id"] }}"></td>
                                </tr>
                                <tr>
                                    <td>Return Charges</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["return_rate"] }}" id="ret-{{ $plan["id"] }}"></td>
                                </tr>

                                <tr>
                                    <td class="font-semibold" colspan="2">Service Rates</td>
                                </tr>

                                <tr>
                                    <td>Overnight Base (≤ 1KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["overnight_base_rate"] ?? 0 }}" id="ob-{{ $plan["id"] }}"></td>
                                </tr>
                                <tr>
                                    <td>Overnight Additional (per extra KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["overnight_additional_rate"] ?? 0 }}" id="oa-{{ $plan["id"] }}"></td>
                                </tr>

                                <tr>
                                    <td>Detain Base (≤ 1KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["detain_base_rate"] ?? 0 }}" id="db-{{ $plan["id"] }}"></td>
                                </tr>
                                <tr>
                                    <td>Detain Additional (per extra KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["detain_additional_rate"] ?? 0 }}" id="da-{{ $plan["id"] }}"></td>
                                </tr>

                                <tr>
                                    <td>Overland Base (≤ 1KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["overland_base_rate"] ?? 0 }}" id="hb-{{ $plan["id"] }}"></td>
                                </tr>
                                <tr>
                                    <td>Overland Additional (per extra KG)</td>
                                    <td><input class="rinput" type="number" value="{{ $plan["overland_additional_rate"] ?? 0 }}" id="ha-{{ $plan["id"] }}"></td>
                                </tr>
                                </tbody>
                            </table>
                            <div style="padding:12px;text-align:center">
                                <button class="btn btn-b" onclick="savePlan({{ $plan["id"] }})"><i class="ti ti-device-floppy"></i> Save {{ $plan["name"] }}</button>
                            </div>
                        </div>
                        @empty
                        <div style="grid-column:span 3;text-align:center;padding:40px;color:var(--color-text-secondary)">No pricing plans found. Run migration to create Basic, Standard, VIP plans.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    const csrf = document.querySelector("meta[name=\"csrf-token\"]")?.content || "";
    async function apiPost(url, payload) {
        const res = await fetch(url, { method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json", "X-CSRF-TOKEN": csrf }, body: JSON.stringify(payload) });
        return await res.json();
    }
    async function savePlan(id) {
        const diff = parseFloat(document.getElementById("diff-"+id)?.value || 0);
        const same = parseFloat(document.getElementById("same-"+id)?.value || 0);
        const ret = parseFloat(document.getElementById("ret-"+id)?.value || 0);

        // Service-type rates
        const overnightBase = parseFloat(document.getElementById("ob-"+id)?.value || 0);
        const overnightAdditional = parseFloat(document.getElementById("oa-"+id)?.value || 0);

        const detainBase = parseFloat(document.getElementById("db-"+id)?.value || 0);
        const detainAdditional = parseFloat(document.getElementById("da-"+id)?.value || 0);

        const overlandBase = parseFloat(document.getElementById("hb-"+id)?.value || 0);
        const overlandAdditional = parseFloat(document.getElementById("ha-"+id)?.value || 0);

        const payload = {
            plan_id: id,
            different_city_delivery: diff,
            same_city_delivery: same,
            return_charge: ret,

            overnight_base_rate: overnightBase,
            overnight_additional_rate: overnightAdditional,

            detain_base_rate: detainBase,
            detain_additional_rate: detainAdditional,

            overland_base_rate: overlandBase,
            overland_additional_rate: overlandAdditional
        };

        const res = await apiPost("/admin/api/admin/pricing/save", payload);
        alert(res.success ? "Plan saved!" : (res.message || "Failed"));
    }
    </script>
</x-filament-panels::page>

<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <div class="page active" id="page-merchants">
                    <!-- Pending Approval -->
                    <div class="sec-title"><i class="ti ti-clock"></i> Pending Approval</div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">New Merchant Requests</div>
                            <span class="badge bg-w">{{ $pendingCount ?? 0 }} pending</span>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Business</th>
                                <th>City</th>
                                <th>Plan</th>
                                <th>Phone</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pendingMerchants ?? [] as $m)
                            <tr>
                                <td><b>{{ $m["name"] }}</b></td>
                                <td>{{ $m["business_type"] }}</td>
                                <td>{{ $m["city"] }}</td>
                                <td><span class="badge bg-w">{{ $m["plan"] }}</span></td>
                                <td>{{ $m["phone"] }}</td>
                                <td>{{ $m["joined"] }}</td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <button class="btn btn-g" onclick="approveMerchant({{ $m["id"] }}, this)">Approve</button>
                                        <button class="btn btn-r" onclick="rejectMerchant({{ $m["id"] }}, this)">Reject</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" style="text-align:center;padding:20px">No pending merchants</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Active Merchants -->
                    <div class="sec-title" style="margin-top:20px"><i class="ti ti-building-store"></i> Active Merchants</div>
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
                                <th>Charges</th>
                                <th>4% Tax</th>
                                <th>Net Payable</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($activeMerchants ?? [] as $m)
                            <tr>
                                <td><b>{{ $m["name"] }}</b></td>
                                <td><span class="badge bg-w">{{ $m["plan"] }}</span></td>
                                <td>{{ $m["dispatched"] }}</td>
                                <td style="color:var(--color-text-success)">{{ $m["delivered"] }}</td>
                                <td style="color:var(--color-text-danger)">{{ $m["returned"] }}</td>
                                <td>Rs {{ number_format($m["total_cod"]) }}</td>
                                <td>Rs {{ number_format($m["delivery_charges"]) }}</td>
                                <td style="color:var(--color-text-warning)">Rs {{ number_format($m["tax_4percent"]) }}</td>
                                <td class="pos">Rs {{ number_format($m["net_payable"]) }}</td>
                                <td><span class="badge {{ ($m["status"] ?? "") === "suspended" ? "bg-d" : "bg-s" }}">{{ ($m["status"] ?? "") === "suspended" ? "Suspended" : "Active" }}</span></td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <button class="btn {{ ($m["status"] ?? "") === "suspended" ? "btn-g" : "btn-r" }}" onclick="toggleMerchantStatus({{ $m["id"] }}, this)">
                                            {{ ($m["status"] ?? "") === "suspended" ? "Reactivate" : "Suspend" }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="11" style="text-align:center;padding:20px">No active merchants</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Custom Return Charges -->
                    <div class="sec-title" style="margin-top:20px"><i class="ti ti-settings"></i> Return Charges</div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">Custom Return Rate per Merchant</div>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Plan</th>
                                <th>Standard Rate</th>
                                <th>Custom Return Charge</th>
                                <th>Save</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($activeMerchants ?? [] as $m)
                            <tr>
                                <td><b>{{ $m["name"] }}</b></td>
                                <td><span class="badge bg-w">{{ $m["plan"] }}</span></td>
                                <td>Rs {{ number_format($m["standard_return_rate"] ?? 200) }}</td>
                                <td><input class="rinput" type="number" value="{{ $m["custom_return_charge"] ?? $m["standard_return_rate"] ?? 200 }}" id="ret-{{ $m["id"] }}"></td>
                                <td><button class="btn btn-b" onclick="saveReturnCharge({{ $m["id"] }})"><i class="ti ti-device-floppy"></i></button></td>
                            </tr>
                            @empty
                            <tr><td colspan="5" style="text-align:center;padding:20px">No merchants</td></tr>
                            @endforelse
                            </tbody>
                        </table>
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
    async function approveMerchant(id, btn) {
        const res = await apiPost("/admin/api/admin/merchant/approve", { id });
        if (res.success) { btn.closest("tr").remove(); alert("Merchant approved!"); }
        else alert(res.message || "Failed");
    }
    async function rejectMerchant(id, btn) {
        const res = await apiPost("/admin/api/admin/merchant/reject", { id });
        if (res.success) { btn.closest("tr").remove(); alert("Merchant rejected!"); }
        else alert(res.message || "Failed");
    }
    async function toggleMerchantStatus(id, btn) {
        const res = await apiPost("/admin/api/admin/merchant/status", { id, status: btn.textContent.trim() === "Suspend" ? "suspended" : "active" });
        if (res.success) { btn.textContent = btn.textContent.trim() === "Suspend" ? "Reactivate" : "Suspend"; btn.className = btn.textContent.trim() === "Suspend" ? "btn btn-r" : "btn btn-g"; }
        else alert(res.message || "Failed");
    }
    async function saveReturnCharge(id) {
        const val = parseFloat(document.getElementById("ret-"+id).value || 0);
        const res = await apiPost("/admin/api/admin/merchant/custom-return-charge", { id, custom_return_charge: val });
        alert(res.success ? "Return charge saved!" : (res.message || "Failed"));
    }
    </script>
</x-filament-panels::page>

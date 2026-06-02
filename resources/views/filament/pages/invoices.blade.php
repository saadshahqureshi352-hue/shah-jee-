<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- INVOICES -->
                <div class="page active" id="page-invoices">
                    <div class="sc" style="grid-template-columns:repeat(4,1fr)">
                        <div class="scard">
                            <div class="scard-lbl">Total invoices</div>
                            <div class="scard-val">48</div>
                        </div>
                        <div class="scard">
                            <div class="scard-lbl">Pending</div>
                            <div class="scard-val" style="color:var(--color-text-warning)">12</div>
                        </div>
                        <div class="scard">
                            <div class="scard-lbl">Paid</div>
                            <div class="scard-val pos">33</div>
                        </div>
                        <div class="scard">
                            <div class="scard-lbl">Overdue</div>
                            <div class="scard-val neg">3</div>
                        </div>
                    </div>

                    <div style="font-size:10px;color:var(--color-text-secondary);background:var(--color-background-info);padding:7px 12px;border-radius:6px;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                        <i class="ti ti-info-circle" style="font-size:12px" aria-hidden="true"></i>
                        Invoice cycle: every 3 days — sirf delivered orders included hote hain. Formula: COD − delivery charges − 4% tax = net payable.
                    </div>

                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">Invoice list — 3-day cycle per merchant</div>
                            <button class="btn btn-b" onclick="showToast(\'Invoice generated!\')"><i class="ti ti-plus" aria-hidden="true"></i> Generate invoice</button>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Merchant</th>
                                <th>Period</th>
                                <th>Delivered</th>
                                <th>COD amount</th>
                                <th>Delivery charges</th>
                                <th>4% Tax</th>
                                <th>Net payable</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><b>INV-00021</b></td>
                                <td>ABC Store</td>
                                <td>1–3 Jun</td>
                                <td>35</td>
                                <td>Rs 85,000</td>
                                <td>Rs 7,200</td>
                                <td>Rs 3,400</td>
                                <td class="pos"><b>Rs 74,400</b></td>
                                <td><span class="badge bg-w">Pending</span></td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <button class="btn"><i class="ti ti-download" aria-hidden="true"></i>PDF</button>
                                        <button class="btn" style="background:var(--color-background-success);color:var(--color-text-success);border:none" onclick="showToast(\'WhatsApp message bheja!\')"><i class="ti ti-brand-whatsapp" aria-hidden="true"></i>WA</button>
                                        <button class="btn btn-b" onclick="showToast(\'Marked as paid!\')">Mark paid</button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><b>INV-00020</b></td>
                                <td>XYZ Shop</td>
                                <td>29 May–31 May</td>
                                <td>22</td>
                                <td>Rs 52,000</td>
                                <td>Rs 5,280</td>
                                <td>Rs 2,080</td>
                                <td class="pos"><b>Rs 44,640</b></td>
                                <td><span class="badge bg-s">Paid</span></td>
                                <td>
                                    <button class="btn"><i class="ti ti-download" aria-hidden="true"></i>PDF</button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>INV-00019</b></td>
                                <td>Fast Deals</td>
                                <td>26–28 May</td>
                                <td>58</td>
                                <td>Rs 1,35,000</td>
                                <td>Rs 12,760</td>
                                <td>Rs 5,400</td>
                                <td class="pos"><b>Rs 1,16,840</b></td>
                                <td><span class="badge bg-d">Overdue</span></td>
                                <td>
                                    <div style="display:flex;gap:4px">
                                        <button class="btn"><i class="ti ti-download" aria-hidden="true"></i>PDF</button>
                                        <button class="btn" style="background:var(--color-background-success);color:var(--color-text-success);border:none" onclick="showToast(\'WhatsApp reminder bheja!\')"><i class="ti ti-brand-whatsapp" aria-hidden="true"></i>WA</button>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- COD & SETTLEMENT -->
                <div class="page active" id="page-cod">
                    <div class="cod-summary">
                        <div class="scard">
                            <div class="scard-lbl">Total COD to pay</div>
                            <div class="scard-val neg">Rs 8,70,000</div>
                            <div class="scard-sub">To merchants</div>
                        </div>
                        <div class="scard">
                            <div class="scard-lbl">Courier receivable</div>
                            <div class="scard-val pos">Rs 3,25,000</div>
                            <div class="scard-sub">COD from couriers</div>
                        </div>
                        <div class="scard">
                            <div class="scard-lbl">Pending settlements</div>
                            <div class="scard-val" style="color:var(--color-text-warning)">12</div>
                            <div class="scard-sub">Merchants</div>
                        </div>
                    </div>
                    <div class="sec-title"><i class="ti ti-cash" aria-hidden="true"></i>Merchant COD settlement</div>
                    <div class="card">
                        <div class="card-hdr">
                            <div class="card-hdr-title">Per merchant — COD payable</div>
                        </div>
                        <table>
                            <thead>
                            <tr>
                                <th>Merchant</th>
                                <th>Delivered orders</th>
                                <th>Total COD</th>
                                <th>Delivery charges</th>
                                <th>4% Tax on COD</th>
                                <th>Net payable to merchant</th>
                                <th>Courier paid us</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><b>ABC Store</b></td>
                                <td>35</td>
                                <td>Rs 85,000</td>
                                <td>Rs 7,200</td>
                                <td>Rs 3,400</td>
                                <td class="pos">Rs 74,400</td>
                                <td>Rs 82,300</td>
                                <td><span class="badge bg-w">Pending</span></td>
                                <td>
                                    <button class="btn btn-b">Pay now</button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>XYZ Shop</b></td>
                                <td>22</td>
                                <td>Rs 52,000</td>
                                <td>Rs 5,280</td>
                                <td>Rs 2,080</td>
                                <td class="pos">Rs 44,640</td>
                                <td>Rs 50,440</td>
                                <td><span class="badge bg-s">Paid</span></td>
                                <td>
                                    <button class="btn">Receipt</button>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Fast Deals</b></td>
                                <td>58</td>
                                <td>Rs 1,35,000</td>
                                <td>Rs 12,760</td>
                                <td>Rs 5,400</td>
                                <td class="pos">Rs 1,16,840</td>
                                <td>Rs 1,30,650</td>
                                <td><span class="badge bg-w">Pending</span></td>
                                <td>
                                    <button class="btn btn-b">Pay now</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="sec-title"><i class="ti ti-truck" aria-hidden="true"></i>Courier COD received</div>
                    <div class="card">
                        <table>
                            <thead>
                            <tr>
                                <th>Courier</th>
                                <th>Delivered orders</th>
                                <th>Total COD collected</th>
                                <th>Courier charges deducted</th>
                                <th>2% Tax deducted</th>
                                <th>Amount remitted to us</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><b>Leopards</b></td>
                                <td>52</td>
                                <td>Rs 1,20,000</td>
                                <td>Rs 8,580</td>
                                <td>Rs 2,400</td>
                                <td class="pos">Rs 1,09,020</td>
                                <td><span class="badge bg-s">Received</span></td>
                            </tr>
                            <tr>
                                <td><b>TCS</b></td>
                                <td>38</td>
                                <td>Rs 92,000</td>
                                <td>Rs 7,600</td>
                                <td>Rs 1,840</td>
                                <td class="pos">Rs 82,560</td>
                                <td><span class="badge bg-w">Pending</span></td>
                            </tr>
                            <tr>
                                <td><b>M&P</b></td>
                                <td>25</td>
                                <td>Rs 68,000</td>
                                <td>Rs 3,750</td>
                                <td>Rs 1,360</td>
                                <td class="pos">Rs 62,890</td>
                                <td><span class="badge bg-s">Received</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

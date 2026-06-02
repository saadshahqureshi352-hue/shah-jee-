<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- ORDERS -->
                <div class="page active" id="page-orders">
                    <div class="fbar">
                        <button class="fb active">All</button><button class="fb">Booked</button><button class="fb">Dispatched</button><button class="fb">Delivered</button><button class="fb">In transit</button><button class="fb">Returned</button><button class="fb">Issue</button>
                    </div>
                    <div class="card">
                        <table>
                            <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Merchant</th>
                                <th>City</th>
                                <th>Courier</th>
                                <th>COD</th>
                                <th>4% Tax</th>
                                <th>Courier 2%</th>
                                <th>Our 2%</th>
                                <th>Delivery charge</th>
                                <th>Net profit</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>#8821</td>
                                <td>ABC Store</td>
                                <td>Karachi</td>
                                <td>Leopards</td>
                                <td>1,000</td>
                                <td>40</td>
                                <td>20</td>
                                <td>20</td>
                                <td>180</td>
                                <td class="pos">55</td>
                                <td><span class="badge bg-s">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>#8820</td>
                                <td>XYZ Shop</td>
                                <td>Lahore</td>
                                <td>TCS</td>
                                <td>2,500</td>
                                <td>100</td>
                                <td>50</td>
                                <td>50</td>
                                <td>240</td>
                                <td class="pos">40</td>
                                <td><span class="badge bg-i">In transit</span></td>
                            </tr>
                            <tr>
                                <td>#8819</td>
                                <td>Fast Deals</td>
                                <td>Karachi</td>
                                <td>M&P</td>
                                <td>3,200</td>
                                <td>128</td>
                                <td>64</td>
                                <td>64</td>
                                <td>220</td>
                                <td class="pos">70</td>
                                <td><span class="badge bg-s">Delivered</span></td>
                            </tr>
                            <tr>
                                <td>#8818</td>
                                <td>Tech Zone</td>
                                <td>Islamabad</td>
                                <td>Trax</td>
                                <td>5,000</td>
                                <td>200</td>
                                <td>100</td>
                                <td>100</td>
                                <td>220</td>
                                <td>—</td>
                                <td><span class="badge bg-d">Returned</span></td>
                            </tr>
                            <tr>
                                <td>#8817</td>
                                <td>Style Hub</td>
                                <td>Karachi</td>
                                <td>BlueEx</td>
                                <td>1,500</td>
                                <td>60</td>
                                <td>30</td>
                                <td>30</td>
                                <td>210</td>
                                <td class="pos">50</td>
                                <td><span class="badge bg-s">Delivered</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>

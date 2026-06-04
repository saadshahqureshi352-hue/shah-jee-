<x-filament-panels::page>
    <div id="shahjeecourier-admin-dashboard">
        <div class="main">
            <div class="content">
                <!-- PRICING PLANS -->
                <div class="page active" id="page-pricing">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:16px">
                        <div class="plan-card">
                            <div class="plan-name">Basic plan <span class="badge bg-s">Default</span></div>
                            <table class="pt">
                                <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Forward</th>
                                    <th>Return</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Karachi</td>
                                    <td><input class="rinput" value="180"></td>
                                    <td><input class="rinput" value="120"></td>
                                </tr>
                                <tr>
                                    <td>Lahore</td>
                                    <td><input class="rinput" value="220"></td>
                                    <td><input class="rinput" value="150"></td>
                                </tr>
                                <tr>
                                    <td>Islamabad</td>
                                    <td><input class="rinput" value="240"></td>
                                    <td><input class="rinput" value="160"></td>
                                </tr>
                                <tr>
                                    <td>Other</td>
                                    <td><input class="rinput" value="260"></td>
                                    <td><input class="rinput" value="170"></td>
                                </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-b" style="margin-top:10px;width:100%;justify-content:center" onclick="showToast(\'Basic plan saved!\')"><i class="ti ti-device-floppy" aria-hidden="true"></i> Save</button>
                        </div>

                        <div class="plan-card" style="border:1.5px solid var(--color-border-info)">
                            <div class="plan-name">VIP plan <span class="badge bg-i">Premium</span></div>
                            <table class="pt">
                                <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Forward</th>
                                    <th>Return</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Karachi</td>
                                    <td><input class="rinput" value="150"></td>
                                    <td><input class="rinput" value="90"></td>
                                </tr>
                                <tr>
                                    <td>Lahore</td>
                                    <td><input class="rinput" value="190"></td>
                                    <td><input class="rinput" value="120"></td>
                                </tr>
                                <tr>
                                    <td>Islamabad</td>
                                    <td><input class="rinput" value="200"></td>
                                    <td><input class="rinput" value="130"></td>
                                </tr>
                                <tr>
                                    <td>Other</td>
                                    <td><input class="rinput" value="220"></td>
                                    <td><input class="rinput" value="140"></td>
                                </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-b" style="margin-top:10px;width:100%;justify-content:center" onclick="showToast(\'VIP plan saved!\')"><i class="ti ti-device-floppy" aria-hidden="true"></i> Save</button>
                        </div>

                        <div class="plan-card">
                            <div class="plan-name">Custom plan <span class="badge bg-w">Per merchant</span></div>
                            <div style="font-size:11px;color:var(--color-text-secondary);margin-bottom:8px">Admin manually sets rates for each merchant in custom plan.</div>
                            <table class="pt">
                                <thead>
                                <tr>
                                    <th>City</th>
                                    <th>Forward</th>
                                    <th>Return</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Karachi</td>
                                    <td><input class="rinput" value="160"></td>
                                    <td><input class="rinput" value="100"></td>
                                </tr>
                                <tr>
                                    <td>Lahore</td>
                                    <td><input class="rinput" value="200"></td>
                                    <td><input class="rinput" value="130"></td>
                                </tr>
                                <tr>
                                    <td>Islamabad</td>
                                    <td><input class="rinput" value="220"></td>
                                    <td><input class="rinput" value="140"></td>
                                </tr>
                                <tr>
                                    <td>Other</td>
                                    <td><input class="rinput" value="240"></td>
                                    <td><input class="rinput" value="150"></td>
                                </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-b" style="margin-top:10px;width:100%;justify-content:center" onclick="showToast(\'Custom plan saved!\')"><i class="ti ti-device-floppy" aria-hidden="true"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function showToast(message) {
            console.log(message); // Replace with actual toast display logic
        }
    </script>
</x-filament-panels::page>

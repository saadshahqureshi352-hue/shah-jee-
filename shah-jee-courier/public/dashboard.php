<?php
require_once __DIR__ . '/../src/header.php';
require_once __DIR__ . '/../src/sidebar.php';

$shipments = get_shipments();

// Calculate Stats dynamically
$total = count($shipments);
$delivered = 0;
$in_transit = 0;
$pending = 0;
$out_for_delivery = 0;

foreach ($shipments as $s) {
    if ($s['status'] === 'delivered') $delivered++;
    elseif ($s['status'] === 'in_transit') $in_transit++;
    elseif ($s['status'] === 'out_for_delivery') $out_for_delivery++;
    elseif ($s['status'] === 'pending') $pending++;
}

// Search and Filter Handling
$search = trim($_GET['search'] ?? '');
$status_filter = trim($_GET['status'] ?? '');

if ($search !== '' || $status_filter !== '') {
    $filtered_shipments = [];
    foreach ($shipments as $s) {
        $matches_search = true;
        $matches_status = true;
        
        if ($search !== '') {
            $matches_search = (
                strpos(strtoupper($s['tracking_no']), strtoupper($search)) !== false ||
                strpos(strtoupper($s['sender']), strtoupper($search)) !== false ||
                strpos(strtoupper($s['receiver']), strtoupper($search)) !== false
            );
        }
        
        if ($status_filter !== '') {
            $matches_status = ($s['status'] === $status_filter);
        }
        
        if ($matches_search && $matches_status) {
            $filtered_shipments[] = $s;
        }
    }
    $shipments_to_display = $filtered_shipments;
} else {
    $shipments_to_display = $shipments;
}
?>

<!-- Main Content Pane -->
<main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    <!-- Navbar / Header Section -->
    <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-20 shadow-sm">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-bold text-slate-800">Dashboard</h2>
        </div>
        <div class="flex items-center space-x-6">
            <!-- Tracking Quick Box -->
            <form action="track_dashboard.php" method="GET" class="relative hidden sm:block">
                <input type="text" name="tracking_no" required
                       class="w-64 bg-slate-100 border border-slate-200 rounded-xl py-2 pl-4 pr-10 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/50 focus:bg-white transition-all duration-200"
                       placeholder="Quick Tracking No...">
                <button type="submit" class="absolute right-3 top-2.5 text-slate-400 hover:text-lionex-orange">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>
            
            <!-- User avatar -->
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="text-xs font-semibold text-slate-700"><?php echo $_SESSION['user']['name']; ?></p>
                    <p class="text-[10px] text-slate-400 font-medium"><?php echo $_SESSION['user']['role']; ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-lionex-orange text-white flex items-center justify-center font-bold shadow-md shadow-lionex-orange/20">
                    <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Content Body -->
    <div class="p-8 space-y-8 flex-1">
        
        <!-- Welcome banner -->
        <div class="relative bg-gradient-to-r from-lionex-darkblue to-slate-900 rounded-3xl p-6 shadow-xl overflow-hidden text-white flex flex-col md:flex-row justify-between items-center">
            <div class="absolute w-96 h-96 rounded-full bg-lionex-orange/10 blur-[60px] -top-20 -right-20 pointer-events-none"></div>
            <div class="space-y-2 text-center md:text-left">
                <h3 class="text-2xl font-bold">Welcome back, <?php echo explode(' ', $_SESSION['user']['name'])[1] ?? $_SESSION['user']['name']; ?>! 👋</h3>
                <p class="text-slate-300 text-xs max-w-lg">Manage bookings, track live shipments, and keep tabs on deliveries across Pakistan with real-time status tracking.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="create_shipment.php" class="bg-lionex-orange hover:bg-lionex-accent text-white px-5 py-3 rounded-xl text-xs font-semibold shadow-lg shadow-lionex-orange/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 block text-center">
                    <i class="fa-solid fa-circle-plus mr-2"></i> Book New Parcel
                </a>
            </div>
        </div>

        <!-- Metric Statistics Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <!-- Total -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Total Shipments</span>
                    <span class="text-2xl font-bold text-slate-800 mt-1 block"><?php echo $total; ?></span>
                </div>
                <div class="bg-blue-50 text-blue-500 p-4 rounded-xl text-xl">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Booked / Pending</span>
                    <span class="text-2xl font-bold text-slate-800 mt-1 block"><?php echo $pending; ?></span>
                </div>
                <div class="bg-amber-50 text-amber-500 p-4 rounded-xl text-xl">
                    <i class="fa-solid fa-box"></i>
                </div>
            </div>

            <!-- In Transit -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">In Transit</span>
                    <span class="text-2xl font-bold text-slate-800 mt-1 block"><?php echo $in_transit; ?></span>
                </div>
                <div class="bg-indigo-50 text-indigo-500 p-4 rounded-xl text-xl">
                    <i class="fa-solid fa-truck"></i>
                </div>
            </div>

            <!-- Out for Delivery -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Out for Delivery</span>
                    <span class="text-2xl font-bold text-slate-800 mt-1 block"><?php echo $out_for_delivery; ?></span>
                </div>
                <div class="bg-purple-50 text-purple-500 p-4 rounded-xl text-xl">
                    <i class="fa-solid fa-motorcycle"></i>
                </div>
            </div>

            <!-- Delivered -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md hover:border-slate-300 transition-all duration-200">
                <div>
                    <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider block">Delivered</span>
                    <span class="text-2xl font-bold text-slate-800 mt-1 block"><?php echo $delivered; ?></span>
                </div>
                <div class="bg-emerald-50 text-emerald-500 p-4 rounded-xl text-xl">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Shipments Data Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <!-- Table Action Filters -->
            <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
                <h4 class="font-bold text-slate-800 text-base">All Shipments Ledger</h4>
                
                <form action="dashboard.php" method="GET" class="flex flex-col sm:flex-row gap-3">
                    <!-- Search input -->
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"
                               class="w-full sm:w-60 bg-slate-50 border border-slate-200 rounded-xl py-2.5 pl-4 pr-10 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200"
                               placeholder="Search tracking, sender, receiver...">
                        <?php if ($search !== ''): ?>
                            <a href="dashboard.php" class="absolute right-10 top-3 text-slate-400 hover:text-slate-600"><i class="fa-solid fa-xmark text-xs"></i></a>
                        <?php endif; ?>
                        <span class="absolute right-3 top-3 text-slate-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                    </div>

                    <!-- Status Filter -->
                    <select name="status" onchange="this.form.submit()"
                            class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200 cursor-pointer text-slate-600 font-medium">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="picked_up" <?php echo $status_filter === 'picked_up' ? 'selected' : ''; ?>>Picked Up</option>
                        <option value="in_transit" <?php echo $status_filter === 'in_transit' ? 'selected' : ''; ?>>In Transit</option>
                        <option value="out_for_delivery" <?php echo $status_filter === 'out_for_delivery' ? 'selected' : ''; ?>>Out For Delivery</option>
                        <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>

                    <noscript>
                        <button type="submit" class="bg-lionex-orange text-white px-4 py-2 rounded-xl text-xs">Filter</button>
                    </noscript>
                </form>
            </div>

            <!-- Responsive Table View -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-400 uppercase text-[10px] font-bold tracking-wider border-b border-slate-100">
                            <th class="py-4 px-6">Tracking Number</th>
                            <th class="py-4 px-6">Sender Details</th>
                            <th class="py-4 px-6">Receiver Details</th>
                            <th class="py-4 px-6">Route</th>
                            <th class="py-4 px-6">Service details</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-xs">
                        <?php if (empty($shipments_to_display)): ?>
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center space-y-2">
                                        <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                                        <span>No shipments found matching your query.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($shipments_to_display as $shipment): ?>
                                <tr class="hover:bg-slate-50/80 transition-colors duration-150">
                                    <!-- Tracking Number -->
                                    <td class="py-4 px-6 font-semibold text-lionex-darkblue select-all font-mono">
                                        <?php echo htmlspecialchars($shipment['tracking_no']); ?>
                                    </td>
                                    
                                    <!-- Sender -->
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['sender']); ?></div>
                                        <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($shipment['origin']); ?></div>
                                    </td>

                                    <!-- Receiver -->
                                    <td class="py-4 px-6">
                                        <div class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['receiver']); ?></div>
                                        <div class="text-[10px] text-slate-400"><?php echo htmlspecialchars($shipment['destination']); ?></div>
                                    </td>

                                    <!-- Route -->
                                    <td class="py-4 px-6 font-medium text-slate-600">
                                        <span class="inline-flex items-center space-x-1">
                                            <span><?php echo htmlspecialchars($shipment['origin']); ?></span>
                                            <i class="fa-solid fa-arrow-right-long text-[10px] text-slate-300 px-1"></i>
                                            <span><?php echo htmlspecialchars($shipment['destination']); ?></span>
                                        </span>
                                    </td>

                                    <!-- Package weight / Pricing -->
                                    <td class="py-4 px-6 text-slate-500">
                                        <div class="font-medium"><?php echo htmlspecialchars($shipment['weight']); ?></div>
                                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Rs. <?php echo number_format($shipment['amount']); ?> (<?php echo $shipment['payment_type']; ?>)</div>
                                    </td>

                                    <!-- Status badge -->
                                    <td class="py-4 px-6">
                                        <?php
                                        $badge_classes = '';
                                        $dot_classes = '';
                                        switch ($shipment['status']) {
                                            case 'delivered':
                                                $badge_classes = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                                $dot_classes = 'bg-emerald-500';
                                                break;
                                            case 'in_transit':
                                                $badge_classes = 'bg-blue-50 text-blue-700 border border-blue-100';
                                                $dot_classes = 'bg-blue-500';
                                                break;
                                            case 'out_for_delivery':
                                                $badge_classes = 'bg-purple-50 text-purple-700 border border-purple-100';
                                                $dot_classes = 'bg-purple-500';
                                                break;
                                            case 'pending':
                                                $badge_classes = 'bg-amber-50 text-amber-700 border border-amber-100';
                                                $dot_classes = 'bg-amber-500';
                                                break;
                                            case 'picked_up':
                                                $badge_classes = 'bg-sky-50 text-sky-700 border border-sky-100';
                                                $dot_classes = 'bg-sky-500';
                                                break;
                                            default:
                                                $badge_classes = 'bg-red-50 text-red-700 border border-red-100';
                                                $dot_classes = 'bg-red-500';
                                                break;
                                        }
                                        ?>
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-wider <?php echo $badge_classes; ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo $dot_classes; ?>"></span>
                                            <span><?php echo str_replace('_', ' ', $shipment['status']); ?></span>
                                        </span>
                                    </td>

                                    <!-- Action -->
                                    <td class="py-4 px-6 text-center">
                                        <a href="track_dashboard.php?tracking_no=<?php echo urlencode($shipment['tracking_no']); ?>" 
                                           class="inline-flex items-center space-x-1 bg-slate-100 hover:bg-lionex-orange hover:text-white px-3.5 py-1.5 rounded-lg text-slate-600 font-semibold transition-all duration-200 text-[11px] shadow-sm">
                                            <i class="fa-solid fa-map-location-dot"></i>
                                            <span>Track</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../src/footer.php'; ?>

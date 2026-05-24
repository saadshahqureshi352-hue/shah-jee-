<?php
require_once __DIR__ . '/../src/header.php';
require_once __DIR__ . '/../src/sidebar.php';

$tracking_no = trim($_GET['tracking_no'] ?? '');
$shipment = null;
$error = '';

if ($tracking_no !== '') {
    $shipment = get_shipment_by_tracking($tracking_no);
    if (!$shipment) {
        $error = "Tracking number not found in our records. Please verify the ID.";
    }
}
?>

<main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    <!-- Header Navbar -->
    <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-20 shadow-sm">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-bold text-slate-800">Track Shipment</h2>
        </div>
        <div class="flex items-center space-x-6">
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

    <!-- Body -->
    <div class="p-8 space-y-8 flex-1 max-w-4xl mx-auto w-full">
        
        <!-- Back Link & Search Form -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="dashboard.php" class="text-xs font-semibold text-slate-500 hover:text-lionex-orange flex items-center space-x-1.5 transition-colors duration-150">
                <i class="fa-solid fa-arrow-left-long"></i>
                <span>Back to Ledger</span>
            </a>
            
            <form action="track_dashboard.php" method="GET" class="flex gap-2">
                <input type="text" name="tracking_no" value="<?php echo htmlspecialchars($tracking_no); ?>" required
                       class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange transition-all duration-200"
                       placeholder="Enter Tracking No...">
                <button type="submit" class="bg-lionex-orange hover:bg-lionex-accent text-white px-5 py-2 rounded-xl text-xs font-semibold shadow-md transition-all duration-150">
                    Track
                </button>
            </form>
        </div>

        <?php if ($tracking_no === ''): ?>
            <!-- Initial State: No Tracking ID Supplied -->
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                <div class="bg-orange-50 text-lionex-orange w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Track a Shipment</h3>
                <p class="text-slate-500 text-xs mt-2 max-w-md mx-auto">Enter a valid tracking number in the search bar above to fetch the current route, shipping logs, and live delivery updates.</p>
            </div>
        <?php elseif (!empty($error)): ?>
            <!-- Error State: ID not found -->
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
                <div class="bg-red-50 text-red-500 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Record Found</h3>
                <p class="text-slate-500 text-xs mt-2 max-w-md mx-auto"><?php echo htmlspecialchars($error); ?></p>
                <a href="dashboard.php" class="inline-block mt-6 text-xs font-semibold text-lionex-orange hover:underline">View All Shipments Ledger</a>
            </div>
        <?php else: ?>
            <!-- Shipment Details View -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left 2 Cols: Details & Timeline -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Basic Stats Info -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <div class="flex justify-between items-start flex-wrap gap-2">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Tracking ID</span>
                                <h3 class="text-lg font-bold text-lionex-darkblue select-all font-mono mt-0.5"><?php echo htmlspecialchars($shipment['tracking_no']); ?></h3>
                            </div>
                            <!-- Status badge -->
                            <div>
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
                            </div>
                        </div>

                        <!-- Route Map visualization -->
                        <div class="border-t border-slate-100 pt-4 grid grid-cols-3 items-center text-center">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($shipment['origin']); ?></h4>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Origin</span>
                            </div>
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-truck-fast text-lionex-orange text-lg animate-pulse"></i>
                                <div class="w-full bg-slate-200 h-1 rounded-full mt-2 relative overflow-hidden">
                                    <div class="bg-lionex-orange h-full rounded-full transition-all duration-500" style="width: <?php 
                                        if ($shipment['status'] === 'pending') echo '10%';
                                        elseif ($shipment['status'] === 'picked_up') echo '35%';
                                        elseif ($shipment['status'] === 'in_transit') echo '65%';
                                        elseif ($shipment['status'] === 'out_for_delivery') echo '85%';
                                        elseif ($shipment['status'] === 'delivered') echo '100%';
                                        else echo '0%';
                                    ?>"></div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm"><?php echo htmlspecialchars($shipment['destination']); ?></h4>
                                <span class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Destination</span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Stepper Tracker -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-4 mb-6">Delivery Progress Status</h4>
                        
                        <?php
                        $statuses = ['pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered'];
                        $status_labels = [
                            'pending' => ['label' => 'Order Booked', 'desc' => 'Parcel booked successfully'],
                            'picked_up' => ['label' => 'Picked Up', 'desc' => 'Shipment collected by courier'],
                            'in_transit' => ['label' => 'In Transit', 'desc' => 'Parcel moving between hubs'],
                            'out_for_delivery' => ['label' => 'Out For Delivery', 'desc' => 'Courier heading to receiver'],
                            'delivered' => ['label' => 'Delivered', 'desc' => 'Parcel handed to recipient'],
                        ];
                        
                        $current_idx = array_search($shipment['status'], $statuses);
                        if ($current_idx === false) $current_idx = -1;
                        ?>
                        
                        <!-- Stepper Grid Vertical -->
                        <div class="relative pl-8 space-y-8 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-200">
                            <?php foreach ($statuses as $idx => $st): ?>
                                <?php
                                $is_completed = $idx <= $current_idx;
                                $is_active = $idx === $current_idx;
                                
                                $circle_class = 'bg-slate-100 text-slate-400 border border-slate-200';
                                if ($is_active) {
                                    $circle_class = 'bg-lionex-orange text-white border-4 border-orange-100 ring-4 ring-orange-500/10 scale-110';
                                } elseif ($is_completed) {
                                    $circle_class = 'bg-green-500 text-white border border-green-400';
                                }
                                ?>
                                <div class="relative flex items-start space-x-4">
                                    <!-- Stepper Marker Circle -->
                                    <div class="absolute -left-[30px] w-6 h-6 rounded-full flex items-center justify-center text-[10px] z-10 transition-all duration-300 <?php echo $circle_class; ?>">
                                        <?php if ($is_completed && !$is_active): ?>
                                            <i class="fa-solid fa-check"></i>
                                        <?php else: ?>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Stepper Info -->
                                    <div>
                                        <h5 class="text-xs font-bold <?php echo $is_completed ? 'text-slate-800' : 'text-slate-400'; ?>">
                                            <?php echo $status_labels[$st]['label']; ?>
                                        </h5>
                                        <p class="text-[10px] <?php echo $is_completed ? 'text-slate-500' : 'text-slate-400'; ?> mt-0.5">
                                            <?php echo $status_labels[$st]['desc']; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Col: Log Details & Parameters -->
                <div class="space-y-8">
                    <!-- General Details Box -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Shipment Details</h4>
                        
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Sender:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['sender']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Receiver:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['receiver']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Weight:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['weight']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Billing Type:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['payment_type']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">COD Value:</span>
                                <span class="font-semibold text-lionex-orange">Rs. <?php echo number_format($shipment['amount']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Booking Date:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['date']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Chronological Logs History -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Activity History Log</h4>
                        
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                            <?php foreach ($shipment['history'] as $log): ?>
                                <div class="border-l-2 border-lionex-orange pl-3 py-0.5 space-y-1">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-lionex-orange uppercase tracking-wide"><?php echo str_replace('_', ' ', $log['status']); ?></span>
                                        <span class="text-[9px] text-slate-400"><?php echo htmlspecialchars($log['time']); ?></span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 font-medium leading-relaxed"><?php echo htmlspecialchars($log['desc']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once __DIR__ . '/../src/footer.php'; ?>

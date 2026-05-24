<?php
require_once __DIR__ . '/../src/db.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Parcel - Shah Jee Courier Portal</title>
    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        lionex: {
                            orange: '#EF5A24',
                            darkblue: '#0B2D5B',
                            accent: '#F47A20',
                            bg: '#F8FAFC'
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col">

    <!-- Public Navigation Bar -->
    <header class="bg-white border-b border-slate-200 h-20 shadow-sm sticky top-0 z-30">
        <div class="max-w-6xl mx-auto h-full px-6 flex items-center justify-between">
            <a href="track.php" class="flex items-center space-x-2">
                <img src="logo.png" class="h-10 w-auto object-contain" alt="Shah Jee Logo">
                <div>
                    <h1 class="font-bold text-slate-900 leading-tight">
                        Shah Jee
                    </h1>
                    <span class="text-[9px] text-slate-400 font-semibold tracking-wider block">COURIER TRACKING</span>
                </div>
            </a>
            
            <a href="login.php" class="bg-lionex-darkblue hover:bg-slate-900 text-white text-xs font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                Operator Login <i class="fa-solid fa-right-to-bracket ml-1.5"></i>
            </a>
        </div>
    </header>

    <!-- Main Container -->
    <main class="flex-grow max-w-4xl mx-auto w-full px-6 py-12 space-y-12">
        
        <!-- Welcome Heading & Search -->
        <div class="text-center space-y-4 max-w-2xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-lionex-darkblue tracking-tight">Track Your Shipment</h2>
            <p class="text-slate-500 text-xs sm:text-sm">Enter your Shah Jee booking tracking number below to view instant, real-time shipment status details.</p>
            
            <!-- Large Search Bar -->
            <form action="track.php" method="GET" class="pt-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-2 shadow-lg flex items-center gap-2 max-w-lg mx-auto focus-within:ring-2 focus-within:ring-lionex-orange/50 transition-all duration-200">
                    <span class="text-slate-400 pl-3"><i class="fa-solid fa-truck-fast text-lg"></i></span>
                    <input type="text" name="tracking_no" value="<?php echo htmlspecialchars($tracking_no); ?>" required
                           class="flex-1 bg-transparent border-0 text-slate-800 text-xs sm:text-sm focus:outline-none placeholder-slate-400 font-semibold uppercase tracking-wider"
                           placeholder="Enter Tracking No (e.g. SJ-8294719-PK)...">
                    <button type="submit" 
                            class="bg-lionex-orange hover:bg-lionex-accent text-white px-6 py-3 rounded-xl font-bold text-xs shadow-md shadow-lionex-orange/15 transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]">
                        Track Parcel
                    </button>
                </div>
            </form>
        </div>

        <?php if ($tracking_no === ''): ?>
            <!-- Initial state: Promotional banner/illustrative section -->
            <div class="bg-gradient-to-r from-lionex-darkblue to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden flex flex-col md:flex-row items-center gap-6">
                <div class="absolute w-[300px] h-[300px] rounded-full bg-lionex-orange/10 blur-[80px] -top-20 -right-20 pointer-events-none"></div>
                
                <div class="space-y-4 flex-1 text-center md:text-left z-10">
                    <span class="bg-lionex-orange/20 text-lionex-orange px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider">Fast & Secure</span>
                    <h3 class="text-xl sm:text-2xl font-bold">Reliable logistics network all over Pakistan</h3>
                    <p class="text-slate-300 text-xs leading-relaxed max-w-md">We offer Cash on Delivery (COD) services, instant bookings, and track-and-trace systems for secure, on-time home delivery.</p>
                </div>

                <div class="shrink-0 flex items-center justify-center bg-slate-800/40 border border-slate-800 p-6 rounded-2xl w-48 text-center">
                    <div class="space-y-2">
                        <span class="text-slate-400 text-[10px] uppercase font-bold tracking-wider">Try Demo Tracker ID</span>
                        <div class="bg-slate-900 border border-slate-700/50 rounded-xl px-3 py-2 font-mono font-bold text-xs text-lionex-orange tracking-widest select-all">
                            SJ-8294719-PK
                        </div>
                        <p class="text-[9px] text-slate-500">Copy & search this number above to see tracking details.</p>
                    </div>
                </div>
            </div>

        <?php elseif (!empty($error)): ?>
            <!-- Error state -->
            <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm max-w-md mx-auto">
                <div class="bg-red-50 text-red-500 w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">No Record Found</h3>
                <p class="text-slate-500 text-xs mt-2 leading-relaxed"><?php echo htmlspecialchars($error); ?></p>
            </div>

        <?php else: ?>
            <!-- Results View -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Left 2 columns: Timeline stepper -->
                <div class="md:col-span-2 space-y-8">
                    <!-- Mini details card -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex justify-between items-center flex-wrap gap-4">
                        <div>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Tracking ID</span>
                            <span class="text-base font-bold text-lionex-darkblue font-mono select-all"><?php echo htmlspecialchars($shipment['tracking_no']); ?></span>
                        </div>
                        
                        <!-- Route badge -->
                        <div class="text-xs font-semibold text-slate-600">
                            <span class="inline-flex items-center space-x-1.5">
                                <span><?php echo htmlspecialchars($shipment['origin']); ?></span>
                                <i class="fa-solid fa-arrow-right-long text-slate-300"></i>
                                <span><?php echo htmlspecialchars($shipment['destination']); ?></span>
                            </span>
                        </div>
                        
                        <!-- Current status -->
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

                    <!-- Stepper -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-4 mb-6">Delivery Progress</h4>
                        
                        <?php
                        $statuses = ['pending', 'picked_up', 'in_transit', 'out_for_delivery', 'delivered'];
                        $status_labels = [
                            'pending' => ['label' => 'Order Booked', 'desc' => 'Parcel booking created'],
                            'picked_up' => ['label' => 'Picked Up', 'desc' => 'Package picked up by courier'],
                            'in_transit' => ['label' => 'In Transit', 'desc' => 'Parcel dispatch to sorting hubs'],
                            'out_for_delivery' => ['label' => 'Out For Delivery', 'desc' => 'Courier enroute to destination address'],
                            'delivered' => ['label' => 'Delivered', 'desc' => 'Package successfully delivered'],
                        ];
                        $current_idx = array_search($shipment['status'], $statuses);
                        if ($current_idx === false) $current_idx = -1;
                        ?>
                        
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
                                    <div class="absolute -left-[30px] w-6 h-6 rounded-full flex items-center justify-center text-[10px] z-10 transition-all duration-300 <?php echo $circle_class; ?>">
                                        <?php if ($is_completed && !$is_active): ?>
                                            <i class="fa-solid fa-check"></i>
                                        <?php else: ?>
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div>
                                        <h5 class="text-xs font-bold <?php echo $is_completed ? 'text-slate-800' : 'text-slate-400'; ?>">
                                            <?php echo $status_labels[$st]['label']; ?>
                                        </h5>
                                        <p class="text-[10px] <?php echo $is_completed ? 'text-slate-500' : 'text-slate-400'; ?> mt-0.5 font-medium">
                                            <?php echo $status_labels[$st]['desc']; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Right 1 Column: Activity Logs -->
                <div class="space-y-8">
                    <!-- Summary info -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Information</h4>
                        <div class="space-y-3 text-xs">
                            <div class="flex justify-between">
                                <span class="text-slate-400">Date Booked:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['date']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Weight:</span>
                                <span class="font-medium text-slate-800"><?php echo htmlspecialchars($shipment['weight']); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Service:</span>
                                <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($shipment['payment_type']); ?> Delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- History logs -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2">Tracking Log History</h4>
                        <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                            <?php foreach ($shipment['history'] as $log): ?>
                                <div class="border-l-2 border-lionex-orange pl-3 py-0.5">
                                    <div class="flex justify-between items-center">
                                        <span class="text-[9px] font-bold text-lionex-orange uppercase"><?php echo str_replace('_', ' ', $log['status']); ?></span>
                                        <span class="text-[9px] text-slate-400"><?php echo htmlspecialchars($log['time']); ?></span>
                                    </div>
                                    <p class="text-[10px] text-slate-600 font-medium leading-relaxed mt-0.5"><?php echo htmlspecialchars($log['desc']); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 border-t border-slate-800 py-8 text-center text-slate-500 text-xs mt-auto">
        <div class="max-w-6xl mx-auto px-6 space-y-2">
            <p>&copy; <?php echo date('Y'); ?> Shah Jee Courier Service. All rights reserved.</p>
            <p class="text-[10px] text-slate-600">Created for visual presentation purposes.</p>
        </div>
    </footer>

</body>
</html>

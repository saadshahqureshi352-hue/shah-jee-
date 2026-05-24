<?php
require_once __DIR__ . '/../src/header.php';
require_once __DIR__ . '/../src/sidebar.php';

$success_msg = '';
$new_tracking_no = '';

// Check form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = trim($_POST['sender'] ?? '');
    $origin = trim($_POST['origin'] ?? '');
    $receiver = trim($_POST['receiver'] ?? '');
    $destination = trim($_POST['destination'] ?? '');
    $weight = trim($_POST['weight'] ?? '');
    $amount = trim($_POST['amount'] ?? 0);
    $payment_type = trim($_POST['payment_type'] ?? 'COD');

    if (!empty($sender) && !empty($origin) && !empty($receiver) && !empty($destination) && !empty($weight)) {
        // Create shipment using helper function
        $new_tracking_no = create_shipment($sender, $receiver, $origin, $destination, $weight, $amount, $payment_type);
        $success_msg = "Shipment created successfully!";
    }
}
?>

<main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
    <!-- Header Navbar -->
    <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-20 shadow-sm">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-bold text-slate-800">New Shipment</h2>
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
        
        <!-- Navigation Back Link -->
        <div class="flex items-center justify-between">
            <a href="dashboard.php" class="text-xs font-semibold text-slate-500 hover:text-lionex-orange flex items-center space-x-1.5 transition-colors duration-150">
                <i class="fa-solid fa-arrow-left-long"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>

        <!-- Success Dialog -->
        <?php if (!empty($success_msg)): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 rounded-3xl p-6 shadow-md flex flex-col sm:flex-row items-center justify-between gap-4 animate-fadeIn">
                <div class="flex items-center space-x-4 text-center sm:text-left">
                    <div class="bg-emerald-500 text-white p-3 rounded-full text-xl shadow-lg shadow-emerald-500/20">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-emerald-950 text-sm">Success! Parcel Booked</h4>
                        <p class="text-xs text-emerald-700 mt-0.5">Tracking number has been generated and booking logged in the system.</p>
                    </div>
                </div>
                <div class="bg-white border border-emerald-200 px-5 py-3 rounded-2xl flex items-center space-x-3 w-full sm:w-auto justify-between">
                    <span class="font-mono font-bold text-slate-800 text-xs tracking-wider select-all" id="tracking-text"><?php echo $new_tracking_no; ?></span>
                    <button onclick="copyTracking()" class="text-slate-400 hover:text-lionex-orange transition-colors duration-150" title="Copy tracking number">
                        <i class="fa-regular fa-copy cursor-pointer"></i>
                    </button>
                </div>
            </div>
            
            <script>
                function copyTracking() {
                    const trackingNo = document.getElementById('tracking-text').innerText;
                    navigator.clipboard.writeText(trackingNo).then(() => {
                        showToast('Tracking number copied to clipboard!', 'success');
                    });
                }
            </script>
        <?php endif; ?>

        <!-- Booking Form Container -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-lionex-darkblue to-slate-900 px-8 py-6 text-white border-b border-slate-200">
                <h3 class="text-lg font-bold">Book a Shipment</h3>
                <p class="text-slate-300 text-xs mt-1">Please enter precise sender, receiver, and parcel specifications below.</p>
            </div>

            <form action="create_shipment.php" method="POST" class="p-8 space-y-8">
                <!-- Section 1: Sender & Receiver Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Sender Details Card -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center">
                            <i class="fa-regular fa-paper-plane text-lionex-orange mr-2"></i> Sender Information
                        </h4>
                        
                        <!-- Sender Name -->
                        <div class="space-y-1">
                            <label for="sender" class="text-slate-500 text-xs font-semibold block">Full Name</label>
                            <input type="text" id="sender" name="sender" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200"
                                   placeholder="e.g. Ali Khan">
                        </div>

                        <!-- Sender Origin City -->
                        <div class="space-y-1">
                            <label for="origin" class="text-slate-500 text-xs font-semibold block">Origin City</label>
                            <select id="origin" name="origin" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200 text-slate-600">
                                <option value="">Select City</option>
                                <option value="Karachi">Karachi</option>
                                <option value="Lahore">Lahore</option>
                                <option value="Islamabad">Islamabad</option>
                                <option value="Rawalpindi">Rawalpindi</option>
                                <option value="Peshawar">Peshawar</option>
                                <option value="Faisalabad">Faisalabad</option>
                                <option value="Multan">Multan</option>
                                <option value="Quetta">Quetta</option>
                            </select>
                        </div>
                    </div>

                    <!-- Receiver Details Card -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center">
                            <i class="fa-solid fa-truck-ramp-box text-lionex-orange mr-2"></i> Receiver Information
                        </h4>
                        
                        <!-- Receiver Name -->
                        <div class="space-y-1">
                            <label for="receiver" class="text-slate-500 text-xs font-semibold block">Receiver Full Name</label>
                            <input type="text" id="receiver" name="receiver" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200"
                                   placeholder="e.g. Zainab Ahmed">
                        </div>

                        <!-- Destination City -->
                        <div class="space-y-1">
                            <label for="destination" class="text-slate-500 text-xs font-semibold block">Destination City</label>
                            <select id="destination" name="destination" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200 text-slate-600">
                                <option value="">Select Destination City</option>
                                <option value="Karachi">Karachi</option>
                                <option value="Lahore">Lahore</option>
                                <option value="Islamabad">Islamabad</option>
                                <option value="Rawalpindi">Rawalpindi</option>
                                <option value="Peshawar">Peshawar</option>
                                <option value="Faisalabad">Faisalabad</option>
                                <option value="Multan">Multan</option>
                                <option value="Quetta">Quetta</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Shipment Specifications -->
                <div class="space-y-4">
                    <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center">
                        <i class="fa-solid fa-scale-balanced text-lionex-orange mr-2"></i> Shipment Parameters
                    </h4>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <!-- Weight -->
                        <div class="space-y-1">
                            <label for="weight" class="text-slate-500 text-xs font-semibold block">Weight (kg/lbs)</label>
                            <input type="text" id="weight" name="weight" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200"
                                   placeholder="e.g. 1.5 kg">
                        </div>

                        <!-- Payment Method -->
                        <div class="space-y-1">
                            <label for="payment_type" class="text-slate-500 text-xs font-semibold block">Payment Method</label>
                            <select id="payment_type" name="payment_type" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200 text-slate-600">
                                <option value="COD">Cash On Delivery (COD)</option>
                                <option value="Prepaid">Prepaid</option>
                            </select>
                        </div>

                        <!-- Amount -->
                        <div class="space-y-1">
                            <label for="amount" class="text-slate-500 text-xs font-semibold block">COD / Declared Amount (Rs.)</label>
                            <input type="number" id="amount" name="amount" min="0" value="0" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs focus:outline-none focus:ring-2 focus:ring-lionex-orange/40 focus:border-lionex-orange focus:bg-white transition-all duration-200"
                                   placeholder="e.g. 2500">
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="pt-6 border-t border-slate-100 flex justify-end space-x-4">
                    <a href="dashboard.php" class="px-6 py-3 border border-slate-200 hover:bg-slate-50 rounded-xl text-xs font-semibold text-slate-600 transition-colors duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-3 bg-gradient-to-r from-lionex-orange to-lionex-accent text-white font-semibold rounded-xl shadow-lg shadow-lionex-orange/20 hover:shadow-lionex-orange/30 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                        Create Booking <i class="fa-solid fa-circle-check ml-1.5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../src/footer.php'; ?>

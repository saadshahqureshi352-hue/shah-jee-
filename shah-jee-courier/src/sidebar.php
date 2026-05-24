<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!-- Sidebar -->
<aside class="w-64 bg-lionex-darkblue text-white flex flex-col min-h-screen shrink-0 border-r border-slate-800">
    <!-- Brand / Logo -->
    <div class="h-20 flex items-center px-6 border-b border-slate-800 space-x-3">
        <div class="flex items-center space-x-2">
            <img src="logo.png" class="h-10 w-auto object-contain" alt="Shah Jee Logo">
            <div>
                <h1 class="font-bold text-sm leading-tight tracking-wide flex items-center text-white">
                    Shah Jee
                </h1>
                <span class="text-[9px] text-slate-400 font-semibold uppercase tracking-widest">Courier Portal</span>
            </div>
        </div>
    </div>

    <!-- User Profile Brief -->
    <div class="p-4 mx-4 my-4 bg-slate-900/50 border border-slate-800 rounded-xl flex items-center space-x-3">
        <div class="w-10 h-10 rounded-lg bg-lionex-orange/10 flex items-center justify-center text-lionex-orange font-semibold">
            <?php echo strtoupper(substr($_SESSION['user']['name'], 0, 1)); ?>
        </div>
        <div class="flex-1 overflow-hidden">
            <h4 class="text-xs font-semibold text-slate-200 truncate"><?php echo $_SESSION['user']['name']; ?></h4>
            <p class="text-[10px] text-slate-400 truncate"><?php echo $_SESSION['user']['role']; ?></p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 space-y-1">
        <a href="dashboard.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 <?php echo $current_page === 'dashboard.php' ? 'bg-lionex-orange text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-chart-pie w-5 text-center"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="create_shipment.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 <?php echo $current_page === 'create_shipment.php' ? 'bg-lionex-orange text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-circle-plus w-5 text-center"></i>
            <span>New Shipment</span>
        </a>
        
        <a href="track_dashboard.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 <?php echo $current_page === 'track_dashboard.php' ? 'bg-lionex-orange text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-magnifying-glass w-5 text-center"></i>
            <span>Track Parcel</span>
        </a>
    </nav>

    <!-- Footer / Logout -->
    <div class="p-4 border-t border-slate-800">
        <a href="logout.php" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-200">
            <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

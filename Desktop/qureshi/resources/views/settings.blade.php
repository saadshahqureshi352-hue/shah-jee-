<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shah Jee Courier - Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 min-h-screen font-sans text-slate-100 flex">

    <aside class="w-64 bg-slate-800 border-r border-slate-700 min-h-screen p-4 hidden md:block">
        <div class="mb-8 px-2">
            <h1 class="text-xl font-black tracking-wider text-white uppercase">
                SHAH JEE <span class="text-orange-500">COURIER</span>
            </h1>
            <p class="text-xs text-slate-400 mt-1">3PL Aggregator Panel</p>
        </div>
        <nav class="space-y-2">
            <a href="/" class="flex items-center space-x-3 text-slate-300 hover:bg-slate-700 p-3 rounded-xl transition-all">
                <span>📊</span> <span>Dashboard</span>
            </a>
            <a href="/bookings" class="flex items-center space-x-3 text-slate-300 hover:bg-slate-700 p-3 rounded-xl transition-all">
                <span>📦</span> <span>Bookings List</span>
            </a>
            <a href="/integrations" class="flex items-center space-x-3 text-slate-300 hover:bg-slate-700 p-3 rounded-xl transition-all">
                <span>🔌</span> <span>Integrations</span>
            </a>
            <a href="/settings" class="flex items-center space-x-3 bg-orange-500 text-white p-3 rounded-xl font-semibold transition-all">
                <span>⚙️</span> <span>Settings</span>
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="bg-slate-800 border-b border-slate-700 p-4 shadow-lg flex justify-between items-center px-6">
            <h2 class="text-lg font-bold text-white">Portal Settings</h2>
            <span class="bg-orange-500/10 text-orange-400 text-xs px-3 py-1 rounded-full font-bold border border-orange-500/20">Saad Bhai (Admin)</span>
        </header>

        <main class="p-6 space-y-6 flex-1 overflow-y-auto">
            
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl text-sm font-semibold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="max-w-2xl bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center space-x-2">
                    <span>👤</span> <span>Admin Profile Configuration</span>
                </h3>
                
                <form action="/update-settings" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Business Name</label>
                            <input type="text" name="business_name" value="Shah Jee Courier" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Admin User Display Name</label>
                            <input type="text" name="admin_name" value="Saad Bhai" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs text-slate-400 block mb-1">Alert Notification Email Address</label>
                        <input type="email" name="admin_email" value="admin@shahjeecourier.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Base COD Fee Default (Rs.)</label>
                            <input type="number" name="default_charges" value="200" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Default Weight Limit (KG)</label>
                            <input type="text" name="default_weight" value="1.0" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-700/50 flex justify-end">
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-lg shadow-orange-500/20">
                            ⚙️ Save System Settings
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
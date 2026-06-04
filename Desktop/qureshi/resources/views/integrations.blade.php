<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shah Jee Courier - Integrations</title>
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
            <a href="/integrations" class="flex items-center space-x-3 bg-orange-500 text-white p-3 rounded-xl font-semibold transition-all">
                <span>🔌</span> <span>Integrations</span>
            </a>
            <a href="/settings" class="flex items-center space-x-3 text-slate-300 hover:bg-slate-700 p-3 rounded-xl transition-all">
                <span>⚙️</span> <span>Settings</span>
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <header class="bg-slate-800 border-b border-slate-700 p-4 shadow-lg flex justify-between items-center px-6">
            <h2 class="text-lg font-bold text-white">Courier API Integrations</h2>
            <span class="bg-orange-500/10 text-orange-400 text-xs px-3 py-1 rounded-full font-bold border border-orange-500/20">Saad Bhai (Admin)</span>
        </header>

        <main class="p-6 space-y-6 flex-1 overflow-y-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md relative">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl">🔴</span>
                            <div>
                                <h3 class="text-lg font-bold text-white">TCS Express API</h3>
                                <p class="text-xs text-slate-400">Status: <span class="text-emerald-400 font-bold">Connected</span></p>
                            </div>
                        </div>
                    </div>
                    <form class="space-y-4" onsubmit="event.preventDefault();">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">API Key (Client ID)</label>
                            <input type="text" value="tcs_live_api_key_xyz78692" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500" readonly>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">API Secret</label>
                            <input type="password" value="tcs_secret_password_secure" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500" readonly>
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Cost Center Code</label>
                            <input type="text" value="KHI-01234" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-slate-300 focus:outline-none focus:border-orange-500" readonly>
                        </div>
                        <button class="w-full bg-slate-700 hover:bg-slate-600 text-white font-bold py-2.5 rounded-xl text-xs transition-all border border-slate-600">
                            ⚙️ Edit TCS Credentials
                        </button>
                    </form>
                </div>

                <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md relative">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <span class="text-3xl">🟡</span>
                            <div>
                                <h3 class="text-lg font-bold text-white">Leopards Courier API</h3>
                                <p class="text-xs text-slate-400">Status: <span class="text-amber-400 font-bold">Setup Pending</span></p>
                            </div>
                        </div>
                    </div>
                    <form class="space-y-4" onsubmit="event.preventDefault();">
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Leopards Api Key</label>
                            <input type="text" placeholder="Enter Leopards API Key" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-orange-500">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 block mb-1">Leopards Api Password</label>
                            <input type="password" placeholder="Enter API Password" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-sm text-white focus:outline-none focus:border-orange-500">
                        </div>
                        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-lg shadow-orange-500/20">
                            🔌 Activate Leopards Courier
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
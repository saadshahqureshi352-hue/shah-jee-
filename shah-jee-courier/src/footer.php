    </div>
    
    <!-- Toast Notification System (Optional Helper) -->
    <div id="toast" class="fixed bottom-5 right-5 transform translate-y-20 opacity-0 transition-all duration-300 ease-out bg-slate-900 text-white px-5 py-3 rounded-lg shadow-xl flex items-center space-x-3 z-50">
        <span id="toast-icon" class="text-lionex-orange"><i class="fa-solid fa-circle-info"></i></span>
        <span id="toast-message" class="text-sm font-medium">Notification message</span>
    </div>

    <script>
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');
            const toastIcon = document.getElementById('toast-icon');
            
            toastMessage.innerText = message;
            
            // Set icons/colors
            if (type === 'success') {
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-check text-green-500 text-lg"></i>';
            } else if (type === 'error') {
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>';
            } else {
                toastIcon.innerHTML = '<i class="fa-solid fa-circle-info text-lionex-orange text-lg"></i>';
            }
            
            // Show toast
            toast.classList.remove('translate-y-20', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
            
            // Hide toast after 4 seconds
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 4000);
        }
    </script>
</body>
</html>

#!/bin/bash
# ====================================================
# INFINITYFREE DEPLOYMENT SCRIPT for Shah Jee Courier
# Domain: shahjeecourier.gt.tc
# ====================================================
# Run this script AFTER uploading files via FTP
# SSH into your InfinityFree account first
# ====================================================

echo "=== Shah Jee Courier - InfinityFree Deployment ==="
echo ""

# Step 1: Navigate to the htdocs directory
# (Change this path to match your InfinityFree account)
cd /home/volX_X/infinityfree.com/if0_XXXXXXX/htdocs

# Step 2: Set proper permissions
echo "[1/5] Setting permissions..."
chmod -R 755 storage bootstrap/cache public
chmod -R 775 storage/logs storage/framework/views storage/framework/cache storage/framework/sessions

# Step 3: Create .env from production template
echo "[2/5] Creating .env file..."
if [ ! -f .env ]; then
    cp .env.production .env
    echo "  -> .env created from .env.production template"
    echo "  -> IMPORTANT: Edit .env with your MySQL credentials!"
else
    echo "  -> .env already exists, skipping."
fi

# Step 4: Generate APP_KEY if needed
echo "[3/5] Checking APP_KEY..."
php artisan key:generate --force
echo "  -> APP_KEY generated."

# Step 5: Run database migrations
echo "[4/5] Running migrations..."
php artisan migrate --force
echo "  -> Migrations complete."

# Step 6: Optimize for production
echo "[5/5] Optimizing Laravel..."
php artisan config:cache
php artisan view:cache
php artisan event:cache
php artisan filament:cache-components
echo "  -> Optimization complete."

echo ""
echo "=== DEPLOYMENT COMPLETE ==="
echo "Visit: https://shahjeecourier.gt.tc"
echo ""
echo "Admin Login: https://shahjeecourier.gt.tc/admin/login"
echo "Email: shahjeecourier@gmail.com"
echo "Password: (set during migration)"
echo ""
echo "Force admin login URL (if needed):"
echo "https://shahjeecourier.gt.tc/force-admin-login"
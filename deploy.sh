#!/bin/bash
# --- CONFIGURE THESE VARIABLES ---
CPANEL_USER="novuiroh"
PROJECT_DIR="/home/$CPANEL_USER/novus"
PUBLIC_HTML="/home/$CPANEL_USER/public_html"
BRANCH="master" # Change if your branch is different
# ---------------------------------

cd "$PROJECT_DIR" || exit 1

echo "[1/7] Pulling latest code from $BRANCH..."
git pull origin $BRANCH || exit 1

echo "[2/7] Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader || exit 1

# echo "[3/7] Installing Node dependencies..."
# npm install || exit 1

# echo "[4/7] Building assets..."
# npm run build || exit 1

echo "[5/7] Copying public folder to public_html..."
rsync -a "$PROJECT_DIR/public/" "$PUBLIC_HTML/" || exit 1

echo "[6/7] Updating index.php paths in public_html..."
# Update index.php to point to the correct vendor and bootstrap paths
sed -i 's|/../vendor|/../novus/vendor|g' "$PUBLIC_HTML/index.php"
sed -i 's|/../bootstrap|/../novus/bootstrap|g' "$PUBLIC_HTML/index.php"

echo "[7/7] Running Laravel optimizations..."
php artisan config:cache || exit 1
php artisan route:cache || exit 1
php artisan view:cache || exit 1

echo "[Done] Deployment complete!" 
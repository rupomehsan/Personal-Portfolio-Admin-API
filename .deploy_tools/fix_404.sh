#!/bin/bash

# Quick fix script to resolve 404 errors after deployment
# Run this on the VPS server

echo "🔧 Laravel 404 Fix Script"
echo "=========================="
echo ""

# Get the current directory (should be project root)
PROJECT_ROOT=$(pwd)
PUBLIC_DIR="$PROJECT_ROOT/public"

echo "📂 Project Root: $PROJECT_ROOT"
echo "📂 Public Directory: $PUBLIC_DIR"
echo ""

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in a Laravel project directory!"
    echo "   Please cd to your project root first."
    exit 1
fi

# Check if public directory exists
if [ ! -d "$PUBLIC_DIR" ]; then
    echo "❌ Error: Public directory not found!"
    exit 1
fi

echo "✅ Laravel project detected"
echo ""

# Fix permissions
echo "🔐 Step 1: Setting correct permissions..."
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"
echo ""

# Clear and cache Laravel
echo "🔄 Step 2: Clearing and caching Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Laravel caches refreshed"
echo ""

# Check if www-data user exists (common for Nginx/Apache)
if id "www-data" &>/dev/null; then
    echo "👤 Step 3: Setting ownership to www-data..."
    echo "   (This may require sudo password)"
    sudo chown -R www-data:www-data "$PROJECT_ROOT"
    echo "✅ Ownership set"
else
    echo "⚠️ www-data user not found, skipping ownership change"
fi
echo ""

# Display Nginx configuration check
echo "📋 Step 4: Nginx Configuration Check"
echo "-----------------------------------"
echo "Your Nginx configuration should have:"
echo ""
echo "    root $PUBLIC_DIR;"
echo ""
echo "NOT:"
echo ""
echo "    root $PROJECT_ROOT;"
echo ""

# Check if nginx is installed and suggest config locations
if command -v nginx &> /dev/null; then
    echo "Nginx is installed. Common config locations:"
    echo "  • /etc/nginx/sites-available/"
    echo "  • /etc/nginx/conf.d/"
    echo "  • /www/server/panel/vhost/nginx/ (BaoTa/1Panel)"
    echo ""
    echo "To edit your site config:"
    echo "  1. Find your site config file"
    echo "  2. Change 'root' directive to: $PUBLIC_DIR"
    echo "  3. Test config: sudo nginx -t"
    echo "  4. Reload Nginx: sudo systemctl reload nginx"
else
    echo "⚠️ Nginx not found in PATH"
fi

echo ""
echo "✅ Fix script completed!"
echo ""
echo "📖 For detailed instructions, see:"
echo "   .deploy_tools/.docs/NGINX_CONFIGURATION.md"
echo ""
echo "🔍 If still not working, check:"
echo "   • Nginx error log: sudo tail -f /var/log/nginx/error.log"
echo "   • Laravel log: tail -f $PROJECT_ROOT/storage/logs/laravel.log"

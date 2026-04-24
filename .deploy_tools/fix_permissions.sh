#!/bin/bash

# Quick fix for Laravel permission issues
# Run this on the VPS server when you get permission denied errors

echo "🔧 Laravel Permission Fix Script"
echo "================================="
echo ""

# Get the current directory (should be project root)
PROJECT_ROOT=$(pwd)

echo "📂 Project Root: $PROJECT_ROOT"
echo ""

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in a Laravel project directory!"
    echo "   Please cd to your project root first."
    exit 1
fi

echo "✅ Laravel project detected"
echo ""

# Step 1: Set directory ownership
echo "👤 Step 1: Setting ownership..."
if chown -R www:www . 2>/dev/null; then
    echo "✅ Ownership set to www:www"
elif chown -R www-data:www-data . 2>/dev/null; then
    echo "✅ Ownership set to www-data:www-data"
else
    echo "⚠️ Could not change ownership. This might need sudo."
    echo "   Try: sudo chown -R www-data:www-data $PROJECT_ROOT"
fi
echo ""

# Step 2: Set base permissions
echo "🔐 Step 2: Setting base permissions..."
chmod -R 755 .
echo "✅ Base permissions set (755)"
echo ""

# Step 3: Set storage permissions (must be writable)
echo "📝 Step 3: Setting storage permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✅ Storage directories are now writable (775)"
echo ""

# Step 4: Ensure storage subdirectories exist
echo "📁 Step 4: Ensuring storage directories exist..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs
mkdir -p storage/app/public
chmod -R 775 storage
echo "✅ Storage directories created and permissions set"
echo ""

# Step 5: Clear Laravel caches
echo "🧹 Step 5: Clearing Laravel caches..."
php artisan config:clear 2>/dev/null || echo "⚠️ Could not clear config cache"
php artisan cache:clear 2>/dev/null || echo "⚠️ Could not clear cache"
php artisan view:clear 2>/dev/null || echo "⚠️ Could not clear view cache"
echo "✅ Caches cleared"
echo ""

# Step 6: Test log writing
echo "🧪 Step 6: Testing log file..."
if [ -f storage/logs/laravel.log ]; then
    chmod 664 storage/logs/laravel.log
    echo "✅ Log file permissions fixed"
else
    touch storage/logs/laravel.log
    chmod 664 storage/logs/laravel.log
    echo "✅ Log file created"
fi

# Try to write to log
if echo "Test write at $(date)" >> storage/logs/laravel.log 2>/dev/null; then
    echo "✅ Log file is writable!"
else
    echo "❌ Log file still not writable. Need sudo access."
fi
echo ""

# Summary
echo "================================="
echo "✅ Permission fix completed!"
echo ""
echo "📋 Summary of changes:"
echo "   • Ownership: www:www or www-data:www-data"
echo "   • Base permissions: 755"
echo "   • Storage/Cache: 775"
echo "   • Log files: 664"
echo ""
echo "🔍 If still having issues, run with sudo:"
echo "   sudo bash .deploy_tools/fix_permissions.sh"
echo ""
echo "📖 For more help, see:"
echo "   .deploy_tools/.docs/NGINX_CONFIGURATION.md"

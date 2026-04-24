#!/bin/bash

# Pre-deployment cleanup script
# This runs on LOCAL machine BEFORE uploading files

echo "🧹 Pre-deployment cleanup..."
echo ""

# Remove local cached files that should NEVER be deployed
echo "🗑️ Removing local cached files..."
rm -f bootstrap/cache/packages.php 2>/dev/null
rm -f bootstrap/cache/services.php 2>/dev/null
rm -f bootstrap/cache/config.php 2>/dev/null
rm -f bootstrap/cache/routes-v7.php 2>/dev/null

# Check if files were removed
if [ ! -f bootstrap/cache/packages.php ] && [ ! -f bootstrap/cache/services.php ]; then
    echo "✅ Local cache files removed"
else
    echo "⚠️ Some cache files may still exist"
fi

echo ""
echo "✅ Pre-deployment cleanup complete!"
echo ""

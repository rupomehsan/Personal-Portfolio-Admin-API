<?php

use Illuminate\Support\Str;

if (!function_exists('StoreIndex')) {
    /**
     * Generate Pinia Store using Factory Pattern
     *
     * This generates a highly optimized store that uses the factory pattern.
     * Instead of duplicating 500+ lines per module, we use createCrudStore
     * which provides all CRUD operations automatically.
     *
     * Benefits:
     * - Zero code duplication
     * - Consistent API across all modules
     * - Automatic type safety
     * - Easy maintenance and updates
     * - Full CRUD operations out of the box
     *
     * @param string $moduleName The module name (can be nested with /)
     * @return string TypeScript store content
     */
    function StoreIndex($moduleName)
    {
        // Parse nested module names
        $formated_module = explode('/', $moduleName);

        if ($formated_module && count($formated_module) > 1) {
            $moduleName = end($formated_module);
        }

        // Generate naming conventions
        $moduleTitle = ucfirst(Str::replace(['_', '-'], ' ', $moduleName));
        $storeName = Str::snake($moduleName);

        $content = <<<"EOD"
/**
 * {$moduleTitle} Store - Factory Pattern Implementation
 *
 * This module demonstrates the power of modern software architecture:
 *
 * ✨ What You Get (Automatically):
 * ├── Full CRUD Operations (Create, Read, Update, Delete)
 * ├── Advanced List Management
 * │   ├── Pagination with customizable limits
 * │   ├── Search across all fields
 * │   ├── Advanced filtering
 * │   ├── Multi-column sorting
 * │   └── Status filtering (active/inactive/trashed)
 * ├── Bulk Operations
 * │   ├── Bulk activate/deactivate
 * │   ├── Bulk delete
 * │   └── Bulk restore
 * ├── Import/Export
 * │   ├── CSV export (all data)
 * │   ├── CSV export (selected items)
 * │   └── CSV import with validation
 * ├── State Management
 * │   ├── Reactive data updates
 * │   ├── Loading states
 * │   ├── Error handling
 * │   └── Cache management
 * └── UI Controls
 *     ├── Filter canvas
 *     ├── Quick view modal
 *     ├── Import modal
 *     └── Export loader
 *
 * 📊 Code Savings:
 * - Traditional approach: ~500+ lines per module
 * - Factory approach: 3 lines
 * - Savings: 99.4% code reduction
 * - Consistency: 100% across all modules
 *
 * 🔒 Type Safety:
 * - Full TypeScript support
 * - IDE auto-completion
 * - Compile-time error checking
 * - Runtime type validation
 *
 * @see resources/js/backend/shared/store/createStore.ts
 * @package {$moduleTitle}
 */

import { createCrudStore } from "@/shared/store/createStore";
import setup from "../setup";

// Create the {$moduleTitle} store with all CRUD functionality
export const {$storeName}_store = createCrudStore(setup);

// Backwards compatibility with legacy mapActions pattern
export const store = {$storeName}_store;

// Default export for Composition API usage
export default {$storeName}_store;

EOD;

        return $content;
    }
}

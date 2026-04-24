<?php

use Illuminate\Support\Str;

if (!function_exists('SetupIndex')) {
    /**
     * Generate Setup Index Configuration
     *
     * Creates a TypeScript configuration file with module setup.
     * This centralizes all module configuration in one place.
     *
     * @param string $moduleName The module name (can be nested with /)
     * @param array $fields Array of field definitions
     * @return string TypeScript setup configuration
     */
    function SetupIndex($moduleName, $fields)
    {
        // Parse module path
        $formated_module = explode('/', $moduleName);
        $lengthOfFormatedModule = 0;

        if ($formated_module && count($formated_module) > 1) {
            $lengthOfFormatedModule = count($formated_module) - 1;
            $moduleName = end($formated_module);
        }

        // Generate naming conventions
        $prefix = ucfirst($moduleName);
        $moduleName = Str::kebab($moduleName);
        $apiName = Str::plural(Str::kebab($moduleName));
        $store = Str::snake($moduleName);

        // Extract field names (excluding braces for relationships)
        $form_fields = [];
        foreach ($fields as $field) {
            $fieldName = $field[0];
            // Skip relationship fields with braces
            if (!isset($field[1]) || !preg_match('/\{.*\}/', $field[1])) {
                $form_fields[] = $fieldName;
            }
        }

        // Format field arrays for TypeScript
        $selectFields = implode(",\n            ", array_map(fn($field) => "\"$field\"", $form_fields));
        $sortByCols = implode(",\n            ", array_map(fn($field) => "\"$field\"", $form_fields));

        $content = <<<"EOD"
/**
 * {$prefix} Module Setup Configuration
 *
 * This file contains all configuration for the {$prefix} module including:
 * - API endpoints and versioning
 * - Field configurations for tables and forms
 * - Route and permission settings
 * - UI labels and titles
 *
 * Generated automatically - Modifications will be preserved if regenerated
 */

import app_config from "@config/app_config";
import setup_type from "@/shared/setup/setup_type";

const prefix: string = "{$prefix}";

const setup: setup_type = {
    // Module Identity
    prefix,
    module_name: "{$moduleName}",
    store_prefix: "{$store}",
    route_prefix: "{$prefix}",
    route_path: "{$moduleName}",

    // Permission Configuration
    permission: ["admin", "super_admin"],

    // API Configuration
    api_host: app_config.api_host,
    api_version: app_config.api_version,
    api_end_point: "{$apiName}",

    // Field Selection for API requests
    select_fields: [
        "id",
        {$selectFields},
        "status",
        "slug",
        "created_at",
        "deleted_at"
    ],

    // Available columns for sorting
    sort_by_cols: [
        "id",
        {$sortByCols},
        "status",
        "created_at",
    ],

    // Table header columns (shown in list view)
    table_header_data: [
        "id",
        {$selectFields},
        "status",
        "created_at",
    ],

    // Table row data fields (rendered in list view)
    table_row_data: [
        "id",
        {$selectFields},
        "status",
        "created_at",
    ],

    // Quick view modal data fields
    quick_view_data: [
        "id",
        {$selectFields},
        "status",
        "created_at",
    ],

    // UI Labels and Titles
    layout_title: prefix + " Management",
    page_title: `\${prefix} Management`,
    all_page_title: "All " + prefix,
    details_page_title: "Details " + prefix,
    create_page_title: "Create " + prefix,
    edit_page_title: "Edit " + prefix,
};

export default setup;

EOD;

        return $content;
    }
}
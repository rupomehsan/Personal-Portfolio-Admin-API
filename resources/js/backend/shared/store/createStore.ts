import { defineStore } from "pinia";

/** async actions */
import all from "./async_actions/all";
import create from "./async_actions/create";
import details from "./async_actions/details";
import update from "./async_actions/update";
import soft_delete from "./async_actions/soft_delete";
import update_status from "./async_actions/update_status";
import restore from "./async_actions/restore";
import destroy from "./async_actions/destroy";
import bulk_action from "./async_actions/bulk_action";
import import_data from "./async_actions/import_data";

/** actions */
import set_filter_criteria from "./actions/set_filter_criteria";
import reset_filter_criteria from "./actions/reset_filter_criteria";
import set_item from "./actions/set_item";
import set_only_latest_data from "./actions/set_only_latest";
import set_page from "./actions/set_page";
import set_paginate from "./actions/set_paginate";
import set_show_details_canvas from "./actions/set_show_details_canvas";
import set_show_filter_canvas from "./actions/set_show_filter_canvas";
import set_import_csv_modal from "./actions/set_import_csv_modal";
import set_status from "./actions/set_status";
import clear_selected from "./actions/clear_selected";

/**
 * Helper to build correct API URL
 * api_host: http://127.0.0.1:8000
 * api_version: api/v1
 * api_end_point: roles
 * Result: http://127.0.0.1:8000/api/v1/roles
 */
export function buildApiUrl(host: string, version: string, endpoint: string, path: string = ''): string {
    // Ensure host doesn't have trailing slash
    const baseHost = (host || 'http://127.0.0.1:8000').replace(/\/$/, '');
    // Ensure version and endpoint don't have leading/trailing slashes, but preserve them
    const cleanVersion = (version || 'api/v1').replace(/^\/|\/$/g, '');
    const cleanEndpoint = (endpoint || '').replace(/^\/|\/$/g, '');
    const cleanPath = path ? '/' + path.replace(/^\/|\/$/g, '') : '';
    
    return `${baseHost}/${cleanVersion}/${cleanEndpoint}${cleanPath}`;
}

/**
 * Factory function to create a CRUD store for any module
 * This eliminates the need to duplicate 500+ lines of store code per module
 *
 * @param setup - Module configuration object
 * @returns Pinia store instance
 *
 * @example
 * // In your module's store/index.ts:
 * import { createCrudStore } from '@/backend/shared/store/createStore';
 * import setup from '../setup';
 *
 * export const store = createCrudStore(setup);
 */
export function createCrudStore(moduleSetup: any) {
    // Ensure setup values exist
    const defaultHost = 'http://127.0.0.1:8000';
    const defaultVersion = 'api/v1';
    
    return defineStore(moduleSetup.store_prefix, {
        state: () => ({
            // Core data storage
            is_loading: false,
            loading_text: 'loading..',
            all: {},
            item: {},
            url: '',

            // API Configuration (from module setup)
            api_host: moduleSetup.api_host || defaultHost,
            api_version: moduleSetup.api_version || defaultVersion,
            api_end_point: moduleSetup.api_end_point || '',

            // Data filters
            select_fields: Array.isArray(moduleSetup.select_fields) ? moduleSetup.select_fields : [],
            sort_by_cols: Array.isArray(moduleSetup.sort_by_cols) ? moduleSetup.sort_by_cols : [],
            sort_by_col: 'id',
            sort_type: 'DESC',
            start_date: '',
            end_date: '',

            filter_criteria: {},
            all_data_count: 0,
            active_data_count: 0,
            inactive_data_count: 0,
            trased_data_count: 0,
            page: 1,
            paginate: 10,
            search_key: '',

            orderByCol: 'id',
            orderByAsc: true,
            status: 'active',

            // Selected data
            selected: [],

            // UI state
            show_filter_canvas: false,
            show_quick_view_canvas: false,
            show_management_modal: false,
            modal_selected_qty: 1,
            show_create_canvas: false,
            show_edit_canvas: false,
            show_details_canvas: false,
            import_csv_modal_show: false,

            // Export/Import progress
            is_exporting: false,
            export_progress: 0,

            // Cache
            cached: 0,
            only_latest_data: false,
        }),
        actions: {
            /* async actions - bind proper context */
            get_all(this: any) { return all.call(this); },
            create(this: any, event: any) { return create.call(this, event); },
            update(this: any, event: any) { return update.call(this, event); },
            details(this: any, id: any) { return details.call(this, id); },
            update_status(this: any) { return update_status.call(this); },
            soft_delete(this: any) { return soft_delete.call(this); },
            restore(this: any) { return restore.call(this); },
            destroy(this: any) { return destroy.call(this); },
            bulk_action(this: any, action: any, ids: any) { return bulk_action.call(this, action, ids); },
            import_data(this: any, event: any) { return import_data.call(this, event); },

            /* sync actions */
            set_page,
            set_paginate,
            set_show_details_canvas,
            set_item,
            set_show_filter_canvas,
            set_import_csv_modal,
            set_filter_criteria,
            set_status,
            set_only_latest_data,
            clear_selected,
            reset_filter_criteria,
        },
    });
}

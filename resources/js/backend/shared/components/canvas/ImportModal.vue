<template>
    <div>
        <!-- Modal backdrop overlay -->
        <div v-if="import_csv_modal_show" class="modal-backdrop fade show"></div>
        
        <!-- Import Modal -->
        <div class="modal fade" :class="`${import_csv_modal_show ? 'show d-block' : 'd-none'}`" id="primarymodal" aria-modal="true">
            <div class="modal-dialog modal-dialog-centered">
                <form @submit.prevent="FileUploadHandler">
                    <div class="modal-content border-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Import {{ setup.prefix }} </h5>
                            <button @click.prevent="closeModal" type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="">
                                <label for="csv_file">Upload file</label>
                                <input ref="fileInput" id="csv_file" type="file" name="file" class="form-control" required accept=".csv">
                            </div>
                            <p class="mt-3">Please check the sample CSV file below to ensure compatibility with the demo data import.</p>
                            <a href="" @click.prevent="export_demo_csv" class="btn btn-sm btn-primary">
                                <i class="fa fa-download"></i> Download Demo CSV
                            </a>
                        </div>
                        <div class="modal-footer">
                            <button @click.prevent="closeModal" type="button" class="btn btn-light" data-dismiss="modal">
                                <i class="fa fa-times"></i> Close
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-upload"></i> Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { inject, computed, ref } from 'vue';
import export_demo_csv from "../../helpers/export_demo_csv";

export default {
    setup() {
        // Inject dependencies from parent
        const moduleSetup = inject('moduleSetup');
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        const fileInput = ref(null);

        const closeModal = () => {
            store.set_import_csv_modal(false);
            if (fileInput.value) {
                fileInput.value.value = ''; // Clear file input
            }
        };

        const handleFileUpload = async (event) => {
            event.preventDefault();
            // Get file from form
            const form = event.target;
            const formData = new FormData(form);
            
            // Call store's import_data action
            try {
                const response = await store.import_data(formData);
                if (response?.data?.status === "success") {
                    window.s_alert(response.data.message);
                    closeModal();
                    // Reload data
                    await store.get_all();
                } else {
                    window.s_warning(response?.data?.message || 'Import failed');
                }
            } catch (error) {
                console.error('Import error:', error);
                window.s_warning('Error importing file');
            }
        };

        return {
            setup: moduleSetup,
            store,
            fileInput,
            import_csv_modal_show: computed({
                get: () => store.import_csv_modal_show,
                set: (val) => store.set_import_csv_modal(val)
            }),
            FileUploadHandler: handleFileUpload,
            export_demo_csv() {
                return export_demo_csv.call({ setup: moduleSetup });
            },
            closeModal
        };
    }
}
</script>

<style></style>

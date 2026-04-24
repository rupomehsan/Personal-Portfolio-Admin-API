<template>
    <div class="loader export_loader" v-if="is_exporting">
        <div class="loader_body">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     :style="{ width: export_progress + '%' }"
                     role="progressbar" 
                     :aria-valuenow="export_progress" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
            <div class="load_amount">
                <h4>{{ export_progress }}</h4>
                <h5>%</h5>
            </div>
        </div>
    </div>
</template>

<script>
import { inject, computed } from 'vue';

export default {
    setup() {
        // Inject dependencies from parent
        const dataStoreConstructor = inject('dataStoreConstructor');
        const store = dataStoreConstructor();
        
        return {
            is_exporting: computed(() => store.is_exporting || false),
            export_progress: computed(() => store.export_progress || 0)
        };
    }
}
</script>

<style scoped>
.export_loader {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.loader_body {
    width: 300px;
    display: flex;
    align-items: center;
    gap: 20px;
}

.progress {
    flex: 1;
    height: 20px;
}

.load_amount {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 60px;
}

.load_amount h4 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.load_amount h5 {
    margin: 0;
    font-size: 12px;
}
</style>


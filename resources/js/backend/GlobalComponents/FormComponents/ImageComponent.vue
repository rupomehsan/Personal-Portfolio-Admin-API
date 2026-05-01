<template>
  <div class="fu-root">

    <!-- ════════════════════════════════════════════════════════════
         SINGLE MODE
    ═════════════════════════════════════════════════════════════ -->
    <div v-if="!multiple">

      <!-- Drop zone (no preview yet) -->
      <div
        v-if="!preview"
        class="fu-zone"
        :class="{ 'fu-zone--drag': isDragging }"
        @click="browse"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="onDrop"
      >
        <div class="fu-zone-body">
          <div class="fu-icon">
            <svg viewBox="0 0 48 48" fill="none">
              <rect width="48" height="48" rx="10" fill="currentColor" fill-opacity=".07"/>
              <path d="M24 14v14M17 21l7-7 7 7" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M12 34h24" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <p class="fu-zone-title">Drop file here or <span class="fu-browse-link">browse</span></p>
          <p class="fu-zone-hint">{{ acceptLabel }}</p>
        </div>
      </div>

      <!-- Preview card -->
      <div v-else class="fu-preview-card">
        <div class="fu-thumb-wrap">
          <img v-if="isImage" :src="preview" class="fu-thumb" alt="preview" />
          <div v-else class="fu-file-icon">
            <svg viewBox="0 0 40 48" fill="none">
              <path d="M6 0h20l14 14v34H6V0z" fill="currentColor" fill-opacity=".1"
                    stroke="currentColor" stroke-width="1.5"/>
              <path d="M26 0v14h14" stroke="currentColor" stroke-width="1.5"/>
              <text x="20" y="34" text-anchor="middle" font-size="9"
                    fill="currentColor" font-family="sans-serif" font-weight="600">
                {{ fileExt }}
              </text>
            </svg>
          </div>
        </div>

        <div class="fu-file-info">
          <span class="fu-file-name" :title="fileName">{{ fileName }}</span>
          <span class="fu-file-size">{{ fileSize }}</span>
        </div>

        <div class="fu-actions">
          <button type="button" class="fu-btn fu-btn--change" @click="browse" title="Replace">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
              <path d="M2 8a6 6 0 1 0 1.5-3.9" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round"/>
              <path d="M2 3.5V8h4.5" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Replace
          </button>
          <button type="button" class="fu-btn fu-btn--remove" @click="removeSingle" title="Remove">
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none">
              <path d="M3 3l10 10M13 3L3 13" stroke="currentColor" stroke-width="1.6"
                    stroke-linecap="round"/>
            </svg>
            Remove
          </button>
        </div>
      </div>

      <!-- Single mode: signal a cleared image to the backend -->
      <input v-if="singleCleared" type="hidden" :name="name + '_clear'" value="1" />

    </div>

    <!-- ════════════════════════════════════════════════════════════
         MULTIPLE MODE
    ═════════════════════════════════════════════════════════════ -->
    <div v-else>
      <div class="fu-multi-grid">

        <!-- Existing server images (existingImages stores the raw DB paths) -->
        <div
          v-for="(path, idx) in existingImages"
          :key="'ex-' + idx"
          class="fu-multi-card"
        >
          <div class="fu-multi-thumb">
            <img :src="toAbsoluteUrl(path)" alt="existing" class="fu-multi-img" />
            <div class="fu-multi-overlay">
              <button type="button" class="fu-multi-remove" @click="removeExisting(idx)" title="Remove">×</button>
            </div>
          </div>
          <span class="fu-multi-label">Saved</span>
        </div>

        <!-- New local files -->
        <div
          v-for="(f, idx) in localFiles"
          :key="'lf-' + idx"
          class="fu-multi-card"
        >
          <div class="fu-multi-thumb">
            <img v-if="f.isImage" :src="f.preview" alt="preview" class="fu-multi-img" />
            <div v-else class="fu-multi-file-icon">
              <span>{{ f.ext }}</span>
            </div>
            <div class="fu-multi-overlay">
              <button type="button" class="fu-multi-remove" @click="removeLocal(idx)" title="Remove">×</button>
            </div>
          </div>
          <span class="fu-multi-label" :title="f.name">{{ f.name }}</span>
          <span class="fu-multi-size">{{ f.size }}</span>
        </div>

        <!-- Add-more tile -->
        <div
          class="fu-multi-add"
          :class="{ 'fu-zone--drag': isDragging }"
          @click="browse"
          @dragover.prevent="isDragging = true"
          @dragleave.prevent="isDragging = false"
          @drop.prevent="onDrop"
        >
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2"
                  stroke-linecap="round"/>
          </svg>
          <span>Add files</span>
        </div>

      </div>
      <p class="fu-zone-hint mt-1">{{ acceptLabel }}</p>
    </div>

    <!-- ── Hidden inputs ──────────────────────────────────────────────── -->

    <!-- Native file picker (new uploads) -->
    <input
      ref="fileInput"
      type="file"
      :name="multiple ? name + '[]' : name"
      :accept="accept"
      :multiple="multiple"
      class="fu-hidden-input"
      @change="onFileChange"
    />

    <!-- Multiple mode: sentinel + surviving paths so backend knows what to keep -->
    <template v-if="multiple">
      <input type="hidden" :name="name + '_present'" value="1" />
      <input
        v-for="(path, idx) in existingImages"
        :key="'kept-' + idx"
        type="hidden"
        :name="name + '_kept[]'"
        :value="path"
      />
    </template>

  </div>
</template>

<script>
export default {
  name: 'ImageComponent',

  props: {
    name:     { type: String,              required: true  },
    multiple: { type: Boolean,             default: false  },
    accept:   { type: String,              default: 'image/*' },
    value:    { type: [String, Array],     default: null   },
    item:     { type: Object,              default: null   },
    api_url:  { type: String,              default: null   },
  },

  data() {
    return {
      // single mode
      preview:       null,
      fileName:      '',
      fileSize:      '',
      fileExt:       '',
      isImage:       false,
      singleCleared: false,   // true when user removes a server image in single mode
      // multiple mode
      existingImages: [],     // raw DB paths (e.g. "uploads/Blog/file.jpg")
      localFiles:     [],     // newly picked files with preview data URLs
      // ui state
      isDragging: false,
    };
  },

  computed: {
    acceptLabel() {
      if (!this.accept || this.accept === '*') return 'All file types accepted';
      return 'Accepted: ' + this.accept
        .split(',')
        .map(s => s.trim().replace('image/', '.').replace('application/', '.').toUpperCase())
        .join(', ');
    },
  },

  watch: {
    value: { immediate: true, handler(v) { this.syncValue(v); } },
  },

  methods: {
    // ── Helpers ───────────────────────────────────────────────────
    toAbsoluteUrl(path) {
      if (!path) return '';
      if (path.startsWith('http') || path.startsWith('data:') || path.startsWith('/')) return path;
      return '/' + path;
    },

    normaliseValue(v) {
      // Guard against double-encoded JSON strings from the backend
      if (typeof v === 'string' && v.trim().startsWith('[')) {
        try { return JSON.parse(v); } catch { /* fall through */ }
      }
      return v;
    },

    // ── Sync from prop ────────────────────────────────────────────
    syncValue(raw) {
      const v = this.normaliseValue(raw);
      if (!v || (Array.isArray(v) && v.length === 0)) return;

      if (this.multiple) {
        const arr = Array.isArray(v) ? v : String(v).split(',');
        // Store raw DB paths — toAbsoluteUrl() is applied in the template for display
        this.existingImages = arr.map(p => String(p).trim()).filter(Boolean);
      } else {
        // Single mode: store URL for preview, keep raw path for form submission
        this.preview       = this.toAbsoluteUrl(String(v));
        this.fileName      = String(v).split('/').pop();
        this.isImage       = /\.(jpe?g|png|gif|webp|svg|bmp)$/i.test(String(v));
        this.fileExt       = String(v).split('.').pop().toUpperCase();
        this.fileSize      = '';
        this.singleCleared = false;
      }
    },

    // ── Browse / drop ─────────────────────────────────────────────
    browse() {
      this.$refs.fileInput.value = '';
      this.$refs.fileInput.click();
    },

    onDrop(e) {
      this.isDragging = false;
      const files = Array.from(e.dataTransfer.files);
      if (files.length) this.processFiles(files);
    },

    onFileChange(e) {
      const files = Array.from(e.target.files);
      if (files.length) this.processFiles(files);
    },

    // ── File processing ───────────────────────────────────────────
    isImageFile(file) {
      // Primary: MIME type. Fallback: extension (covers WebP when OS mis-reports type).
      if (file.type.startsWith('image/')) return true;
      return /\.(jpe?g|jpg|png|gif|webp|bmp|svg|ico|avif|tiff?)$/i.test(file.name);
    },

    async processFiles(files) {
      if (this.multiple) {
        // Read all files in parallel — avoids sequential stalls and
        // eliminates mid-loop renders with stale null previews.
        const entries = await Promise.all(
          Array.from(files).map(async (file) => {
            const isImg = this.isImageFile(file);
            return {
              file,
              name:    file.name,
              size:    this.humanSize(file.size),
              ext:     file.name.split('.').pop().toUpperCase(),
              isImage: isImg,
              preview: isImg ? await this.readAsDataURL(file).catch(() => null) : null,
            };
          })
        );
        // Single atomic write — Vue only re-renders once, after all previews are ready
        this.localFiles = [...this.localFiles, ...entries];
      } else {
        const file         = files[0];
        this.fileName      = file.name;
        this.fileSize      = this.humanSize(file.size);
        this.fileExt       = file.name.split('.').pop().toUpperCase();
        this.isImage       = this.isImageFile(file);
        this.singleCleared = false;
        this.preview       = this.isImage
          ? await this.readAsDataURL(file).catch(() => 'file')
          : 'file';
      }
    },

    readAsDataURL(file) {
      return new Promise((resolve, reject) => {
        const reader    = new FileReader();
        reader.onload   = e => resolve(e.target.result);
        reader.onerror  = () => reject(new Error('FileReader error'));
        reader.readAsDataURL(file);
      });
    },

    // ── Remove ────────────────────────────────────────────────────
    removeSingle() {
      this.preview       = null;
      this.fileName      = '';
      this.fileSize      = '';
      this.singleCleared = true;
      this.$refs.fileInput.value = '';
    },

    removeExisting(idx) {
      // Remove from the local list; the reduced list is sent via images_kept[] hidden inputs
      this.existingImages = this.existingImages.filter((_, i) => i !== idx);
    },

    removeLocal(idx) {
      this.localFiles = this.localFiles.filter((_, i) => i !== idx);
    },

    // ── Util ──────────────────────────────────────────────────────
    humanSize(bytes) {
      if (bytes < 1024)        return bytes + ' B';
      if (bytes < 1048576)     return (bytes / 1024).toFixed(1) + ' KB';
      return (bytes / 1048576).toFixed(1) + ' MB';
    },
  },
};
</script>

<style scoped>
.fu-hidden-input { display: none; }

/* ── Drop zone ─────────────────────────────────────────────────────── */
.fu-zone {
  border: 2px dashed var(--border-color);
  border-radius: 10px;
  background: var(--bg-input);
  cursor: pointer;
  transition: border-color .2s, background .2s;
  min-height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.fu-zone:hover,
.fu-zone--drag {
  border-color: var(--primary-color);
  background: color-mix(in srgb, var(--primary-color) 5%, var(--bg-input));
}
.fu-zone-body {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  padding: 24px 16px;
  text-align: center;
  pointer-events: none;
  color: var(--text-secondary);
}
.fu-icon svg { width: 48px; height: 48px; color: var(--text-light); }
.fu-zone-title { margin: 0; font-size: .88rem; color: var(--text-secondary); }
.fu-browse-link { color: var(--primary-color); font-weight: 600; text-decoration: underline; }
.fu-zone-hint { font-size: .75rem; color: var(--text-light); margin: 0; }

/* ── Single preview card ───────────────────────────────────────────── */
.fu-preview-card {
  border: 1px solid var(--border-color);
  border-radius: 10px;
  overflow: hidden;
  background: var(--bg-input);
  display: flex;
  flex-direction: column;
}
.fu-thumb-wrap {
  width: 100%; height: 180px;
  overflow: hidden;
  background: var(--bg-hover);
  display: flex; align-items: center; justify-content: center;
}
.fu-thumb { width: 100%; height: 100%; object-fit: cover; display: block; }
.fu-file-icon { display: flex; align-items: center; justify-content: center; color: var(--text-light); }
.fu-file-icon svg { width: 56px; height: 64px; }
.fu-file-info {
  display: flex; align-items: center; justify-content: space-between;
  padding: 8px 12px 4px; gap: 8px;
}
.fu-file-name {
  font-size: .8rem; color: var(--text-primary); font-weight: 500;
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1;
}
.fu-file-size { font-size: .75rem; color: var(--text-light); white-space: nowrap; }
.fu-actions { display: flex; gap: 6px; padding: 8px 12px 12px; }
.fu-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 10px; border-radius: 6px; font-size: .78rem; font-weight: 500;
  border: 1px solid var(--border-color); cursor: pointer; transition: all .15s;
}
.fu-btn--change { background: var(--bg-hover); color: var(--text-secondary); }
.fu-btn--change:hover { background: var(--bg-disabled); border-color: var(--primary-color); color: var(--primary-color); }
.fu-btn--remove {
  background: transparent; color: var(--danger-color);
  border-color: color-mix(in srgb, var(--danger-color) 30%, transparent);
  margin-left: auto;
}
.fu-btn--remove:hover {
  background: color-mix(in srgb, var(--danger-color) 10%, transparent);
  border-color: var(--danger-color);
}

/* ── Multiple grid ─────────────────────────────────────────────────── */
.fu-multi-grid { display: flex; flex-wrap: wrap; gap: 10px; }
.fu-multi-card { width: 150px; display: flex; flex-direction: column; gap: 3px; }
.fu-multi-thumb {
  position: relative; width: 150px; height: 150px;
  border-radius: 8px; overflow: hidden;
  background: var(--bg-hover); border: 1px solid var(--border-color);
  display: flex; align-items: center; justify-content: center;
}
.fu-multi-img { width: 100%; height: 100%; object-fit: cover; display: block; }
.fu-multi-file-icon {
  font-size: .7rem; font-weight: 700; color: var(--text-light);
  background: var(--bg-card); padding: 2px 6px;
  border-radius: 4px; border: 1px solid var(--border-color);
}
.fu-multi-overlay {
  position: absolute; inset: 0;
  background: rgba(0,0,0,.45);
  display: flex; align-items: flex-start; justify-content: flex-end;
  padding: 4px; opacity: 0; transition: opacity .15s;
}
.fu-multi-thumb:hover .fu-multi-overlay { opacity: 1; }
.fu-multi-remove {
  width: 22px; height: 22px; border-radius: 50%;
  border: none; background: var(--danger-color); color: #fff;
  font-size: 1rem; line-height: 1; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  padding: 0; transition: transform .15s;
}
.fu-multi-remove:hover { transform: scale(1.15); }
.fu-multi-label {
  font-size: .72rem; color: var(--text-secondary);
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 110px;
}
.fu-multi-size { font-size: .68rem; color: var(--text-light); }

/* ── Add-more tile ─────────────────────────────────────────────────── */
.fu-multi-add {
  width: 150px; height: 150px; border-radius: 8px;
  border: 2px dashed var(--border-color); background: var(--bg-input);
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 4px; cursor: pointer; color: var(--text-light); font-size: .72rem;
  transition: border-color .15s, color .15s, background .15s;
}
.fu-multi-add:hover,
.fu-multi-add.fu-zone--drag {
  border-color: var(--primary-color);
  color: var(--primary-color);
  background: color-mix(in srgb, var(--primary-color) 5%, var(--bg-input));
}
</style>

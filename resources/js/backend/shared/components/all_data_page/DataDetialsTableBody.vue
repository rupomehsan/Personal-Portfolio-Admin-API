<template>
  <template v-for="(key, index) in moduleSetup.table_row_data" :key="index">
    <tr>
      <th class="dd-key">{{ formatLabel(key) }}</th>
      <th class="text-center">:</th>
      <th class="dd-val text-wrap">

        <!-- ① Single image -->
        <template v-if="isSingleImg(key, item[key])">
          <img
            :src="toUrl(item[key])"
            class="dd-img-single"
            alt="image"
            @click="openLightbox([toUrl(item[key])], 0, formatLabel(key))"
          />
        </template>

        <!-- ② Image gallery -->
        <template v-else-if="isImgArray(key, item[key])">
          <div class="dd-img-grid" v-if="asArray(item[key]).length">
            <img
              v-for="(path, i) in asArray(item[key])"
              :key="i"
              :src="toUrl(path)"
              class="dd-img-thumb"
              :alt="formatLabel(key) + ' ' + (i + 1)"
              @click="openLightbox(asArray(item[key]).map(toUrl), i, formatLabel(key))"
            />
          </div>
          <span v-else class="dd-none">—</span>
        </template>

        <!-- ③ Rich-text / HTML -->
        <template v-else-if="isHtml(item[key])">
          <div class="dd-html-wrap" :class="{ 'dd-collapsed': !expanded[key] }">
            <div class="dd-html-body" v-html="item[key]"></div>
          </div>
          <button type="button" class="dd-toggle" @click="toggle(key)">
            {{ expanded[key] ? '▲ Show less' : '▼ Show more' }}
          </button>
        </template>

        <!-- ④ URL -->
        <template v-else-if="isUrl(item[key])">
          <a :href="item[key]" target="_blank" rel="noopener noreferrer" class="dd-link">
            {{ item[key] }}
          </a>
        </template>

        <!-- ⑤ Plain array (tags, roles …) -->
        <template v-else-if="Array.isArray(item[key])">
          <template v-if="item[key].length">
            <span v-for="(v, i) in item[key]" :key="i" class="dd-badge">{{ v }}</span>
          </template>
          <span v-else class="dd-none">—</span>
        </template>

        <!-- ⑥ Default -->
        <template v-else>
          <span :class="{ 'dd-none': !item[key] && item[key] !== 0 }">
            {{ formatValue(item[key], key) }}
          </span>
        </template>

      </th>
    </tr>
  </template>

  <!-- ══ Lightbox ══════════════════════════════════════════════════════ -->
  <Teleport to="body">
    <Transition name="lb-fade">
      <div
        v-if="lb.open"
        class="lb-backdrop"
        @click.self="closeLb"
      >
        <!-- Close -->
        <button class="lb-close" @click="closeLb">&#x2715;</button>

        <!-- Prev -->
        <button v-if="lb.images.length > 1" class="lb-arrow lb-prev" @click="lbStep(-1)">&#x2039;</button>

        <!-- Image -->
        <div class="lb-img-wrap">
          <img
            :src="lb.images[lb.idx]"
            class="lb-img"
            :alt="lb.title"
            @click.stop
          />
        </div>

        <!-- Next -->
        <button v-if="lb.images.length > 1" class="lb-arrow lb-next" @click="lbStep(1)">&#x203a;</button>

        <!-- Caption -->
        <div class="lb-caption">
          <span>{{ lb.title }}</span>
          <span v-if="lb.images.length > 1" class="lb-count">
            {{ lb.idx + 1 }} / {{ lb.images.length }}
          </span>
        </div>

        <!-- Thumbnails strip -->
        <div v-if="lb.images.length > 1" class="lb-strip">
          <img
            v-for="(src, i) in lb.images"
            :key="i"
            :src="src"
            class="lb-strip-thumb"
            :class="{ 'lb-strip-active': i === lb.idx }"
            @click.stop="lb.idx = i"
            :alt="'thumb ' + (i + 1)"
          />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { inject, reactive } from 'vue';

const moduleSetup = inject('moduleSetup');
defineProps(['item']);

// ── Expand / collapse rich-text ───────────────────────────────────────
const expanded = reactive({});
const toggle   = (key) => { expanded[key] = !expanded[key]; };

// ── Lightbox state ────────────────────────────────────────────────────
const lb = reactive({ open: false, images: [], idx: 0, title: '' });

const lbStep = (dir) => {
  lb.idx = (lb.idx + dir + lb.images.length) % lb.images.length;
};

const lbKeydown = (e) => {
  if (e.key === 'Escape')      closeLb();
  if (e.key === 'ArrowRight')  lbStep(1);
  if (e.key === 'ArrowLeft')   lbStep(-1);
};

const openLightbox = (images, idx = 0, title = '') => {
  lb.images = images.filter(Boolean);
  lb.idx    = idx;
  lb.title  = title;
  lb.open   = true;
  document.body.style.overflow = 'hidden';
  document.addEventListener('keydown', lbKeydown);
};
const closeLb = () => {
  lb.open = false;
  document.body.style.overflow = '';
  document.removeEventListener('keydown', lbKeydown);
};

// ── Image URL helpers ─────────────────────────────────────────────────
const IMG_EXT = /\.(jpe?g|jpg|png|gif|webp|bmp|svg|ico|avif)(\?.*)?$/i;

const toUrl = (path) => {
  if (!path) return '';
  const s = String(path).trim();
  if (s.startsWith('http') || s.startsWith('data:') || s.startsWith('/')) return s;
  return '/' + s;
};

const asArray = (v) => {
  if (Array.isArray(v)) return v.map(String).filter(Boolean);
  if (typeof v === 'string') {
    const t = v.trim();
    if (t.startsWith('[')) {
      try { return JSON.parse(t).map(String).filter(Boolean); } catch { /**/ }
    }
    return t.split(',').map(s => s.trim()).filter(Boolean);
  }
  return [];
};

const looksLikeImg = (v) => typeof v === 'string' && IMG_EXT.test(v.trim());

// ── Type guards ───────────────────────────────────────────────────────
const SINGLE_IMG_RE = /thumbnail|_image$|^image$|photo$/i;
const MULTI_IMG_RE  = /^images$|^gallery$|^photos$|_images$/i;

const isSingleImg = (key, val) => SINGLE_IMG_RE.test(key) && looksLikeImg(val);

const isImgArray  = (key, val) => {
  if (!val) return false;
  if (MULTI_IMG_RE.test(key)) return true;
  if (Array.isArray(val) && val.length > 0) return val.every(v => looksLikeImg(String(v)));
  return false;
};

const isHtml = (val) => typeof val === 'string' && /<[a-z][\s\S]*?>/i.test(val);
const isUrl  = (val) => typeof val === 'string' && /^https?:\/\//i.test(val.trim());

// ── Formatting ────────────────────────────────────────────────────────
const formatLabel = (key) =>
  String(key).replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());

const DATE_KEYS = new Set(['created_at', 'updated_at', 'deleted_at', 'publish_date']);

const formatValue = (val, key) => {
  if (val === null || val === undefined || val === '') return '—';
  if (DATE_KEYS.has(key)) {
    const d = new Date(val);
    return isNaN(d) ? val : d.toLocaleString();
  }
  if (typeof val === 'object') {
    for (const k of ['title', 'name', 'label']) { if (val[k]) return val[k]; }
    return JSON.stringify(val);
  }
  return String(val);
};
</script>

<style scoped>
/* ── Layout ──────────────────────────────────────────────────────────── */
.dd-key { white-space: nowrap; font-weight: 600; width: 160px; vertical-align: top; padding-top: 8px; }
.dd-val { vertical-align: top; padding: 6px 8px; word-break: break-word; max-width: 0; width: 100%; }

/* ── Single image ────────────────────────────────────────────────────── */
.dd-img-single {
  width: 140px; height: 90px;
  object-fit: cover; border-radius: 6px;
  border: 1px solid var(--border-color, #dee2e6);
  cursor: zoom-in; transition: opacity .15s, transform .15s;
}
.dd-img-single:hover { opacity: .85; transform: scale(1.03); }

/* ── Image gallery ───────────────────────────────────────────────────── */
.dd-img-grid { display: flex; flex-wrap: wrap; gap: 6px; }
.dd-img-thumb {
  width: 80px; height: 60px;
  object-fit: cover; border-radius: 5px;
  border: 1px solid var(--border-color, #dee2e6);
  cursor: zoom-in; transition: opacity .15s, transform .15s;
}
.dd-img-thumb:hover { opacity: .8; transform: scale(1.05); }

/* ── HTML / rich-text ────────────────────────────────────────────────── */
.dd-html-wrap {
  overflow: hidden;
  transition: max-height .3s ease;
}
.dd-html-body {
  font-size: .875rem;
  line-height: 1.6;
  overflow-x: auto;
}
/* Neutralise inline floats from editors (Summernote etc.) */
.dd-html-body :deep(*) { float: none !important; width: auto !important; max-width: 100% !important; }
.dd-html-body :deep(img) { max-width: 100%; height: auto; }

.dd-collapsed {
  max-height: 120px;
  mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
  -webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
}
.dd-toggle {
  background: none; border: none;
  color: var(--primary-color, #0d6efd);
  font-size: .78rem; cursor: pointer; padding: 4px 0; margin-top: 2px;
}
.dd-toggle:hover { text-decoration: underline; }

/* ── URL ─────────────────────────────────────────────────────────────── */
.dd-link { color: var(--primary-color, #0d6efd); word-break: break-all; font-size: .875rem; }
.dd-link:hover { text-decoration: underline; }

/* ── Badges ──────────────────────────────────────────────────────────── */
.dd-badge {
  display: inline-block; padding: 2px 9px;
  background: var(--bg-hover, #f1f3f5);
  border: 1px solid var(--border-color, #dee2e6);
  border-radius: 20px; font-size: .75rem; margin: 2px 2px 2px 0;
  color: var(--text-secondary, #495057);
}

/* ── Empty ───────────────────────────────────────────────────────────── */
.dd-none { color: var(--text-light, #adb5bd); font-size: .85rem; }

/* ══ Lightbox ════════════════════════════════════════════════════════ */
.lb-backdrop {
  position: fixed; inset: 0; z-index: 99999;
  background: rgba(0, 0, 0, .88);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 20px;
  outline: none;
}

/* Image area */
.lb-img-wrap {
  flex: 1; display: flex; align-items: center; justify-content: center;
  max-height: calc(100vh - 160px); width: 100%;
}
.lb-img {
  max-width: 100%; max-height: 100%;
  object-fit: contain; border-radius: 6px;
  box-shadow: 0 8px 40px rgba(0,0,0,.6);
  user-select: none;
}

/* Close button */
.lb-close {
  position: absolute; top: 16px; right: 20px;
  background: rgba(255,255,255,.15); border: none;
  color: #fff; font-size: 1.4rem; width: 38px; height: 38px;
  border-radius: 50%; cursor: pointer; display: flex;
  align-items: center; justify-content: center;
  transition: background .15s;
}
.lb-close:hover { background: rgba(255,255,255,.3); }

/* Prev / Next arrows */
.lb-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(255,255,255,.12); border: none;
  color: #fff; font-size: 2.4rem; width: 48px; height: 72px;
  border-radius: 6px; cursor: pointer; display: flex;
  align-items: center; justify-content: center;
  transition: background .15s; line-height: 1; padding-bottom: 4px;
  user-select: none;
}
.lb-arrow:hover { background: rgba(255,255,255,.25); }
.lb-prev { left: 16px; }
.lb-next { right: 16px; }

/* Caption */
.lb-caption {
  display: flex; align-items: center; gap: 12px;
  color: rgba(255,255,255,.75); font-size: .85rem; margin-top: 10px;
}
.lb-count {
  background: rgba(255,255,255,.15);
  padding: 2px 10px; border-radius: 20px; font-size: .78rem;
}

/* Thumbnail strip */
.lb-strip {
  display: flex; gap: 6px; margin-top: 10px;
  overflow-x: auto; max-width: 100%; padding: 4px 0;
  scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.3) transparent;
}
.lb-strip-thumb {
  width: 56px; height: 42px; object-fit: cover;
  border-radius: 4px; cursor: pointer; flex-shrink: 0;
  opacity: .5; border: 2px solid transparent;
  transition: opacity .15s, border-color .15s;
}
.lb-strip-thumb:hover { opacity: .8; }
.lb-strip-active { opacity: 1; border-color: #fff; }

/* Fade transition */
.lb-fade-enter-active, .lb-fade-leave-active { transition: opacity .2s ease; }
.lb-fade-enter-from, .lb-fade-leave-to { opacity: 0; }
</style>

<template>
  <tr
    v-for="(item, dataindex) in data"
    :key="item.id"
    :class="`table_rows table_row_${item.id}`"
  >
    <td><table-row-action :item="item" /></td>
    <td><select-single :data="item" /></td>

    <template v-for="(key, index) in moduleSetup.table_row_data" :key="index">

      <!-- Serial # -->
      <td v-if="key === 'id'">{{ dataindex + 1 }}</td>

      <!-- Single image (thumbnail_image, user_image, photo …) -->
      <td v-else-if="isSingleImg(key, item[key])">
        <img
          :src="toUrl(item[key])"
          class="tb-img"
          alt="image"
          @click="openLb([toUrl(item[key])], 0)"
        />
      </td>

      <!-- Image array (images, gallery …) — first thumb + count badge -->
      <td v-else-if="isImgArray(key, item[key])">
        <div v-if="asArray(item[key]).length" class="tb-img-group">
          <img
            :src="toUrl(asArray(item[key])[0])"
            class="tb-img"
            alt="image"
            @click="openLb(asArray(item[key]).map(toUrl), 0)"
          />
          <span v-if="asArray(item[key]).length > 1" class="tb-img-count">
            +{{ asArray(item[key]).length - 1 }}
          </span>
        </div>
        <span v-else class="tb-none">—</span>
      </td>

      <!-- URL -->
      <td v-else-if="isUrl(item[key])" class="max-w-120">
        <a :href="item[key]" target="_blank" rel="noopener noreferrer" class="tb-link">
          {{ truncate(item[key], 30) }}
        </a>
      </td>

      <!-- Default: smart-truncated cell value -->
      <td v-else class="text-wrap max-w-120">
        {{ cellValue(item[key], key) }}
      </td>

    </template>
  </tr>

  <!-- ══ Lightbox ════════════════════════════════════════════════════ -->
  <Teleport to="body">
    <Transition name="lb-fade">
      <div v-if="lb.open" class="tb-lb-backdrop" @click.self="closeLb">
        <button class="tb-lb-close" @click="closeLb">&#x2715;</button>

        <button v-if="lb.images.length > 1" class="tb-lb-arrow tb-lb-prev" @click.stop="lbStep(-1)">&#x2039;</button>

        <img :src="lb.images[lb.idx]" class="tb-lb-img" alt="preview" @click.stop />

        <button v-if="lb.images.length > 1" class="tb-lb-arrow tb-lb-next" @click.stop="lbStep(1)">&#x203a;</button>

        <div class="tb-lb-caption" v-if="lb.images.length > 1">
          {{ lb.idx + 1 }} / {{ lb.images.length }}
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { inject, reactive } from 'vue';
import TableRowAction from './TableRowAction.vue';
import SelectSingle   from './select_data/SelectSingle.vue';

const moduleSetup = inject('moduleSetup');
defineProps(['data']);

// ── Lightbox ──────────────────────────────────────────────────────────
const lb = reactive({ open: false, images: [], idx: 0 });

const lbKey = (e) => {
  if (e.key === 'Escape')     closeLb();
  if (e.key === 'ArrowRight') lbStep(1);
  if (e.key === 'ArrowLeft')  lbStep(-1);
};
const openLb = (images, idx = 0) => {
  lb.images = images.filter(Boolean);
  lb.idx    = idx;
  lb.open   = true;
  document.body.style.overflow = 'hidden';
  document.addEventListener('keydown', lbKey);
};
const closeLb = () => {
  lb.open = false;
  document.body.style.overflow = '';
  document.removeEventListener('keydown', lbKey);
};
const lbStep = (dir) => { lb.idx = (lb.idx + dir + lb.images.length) % lb.images.length; };

// ── Helpers ───────────────────────────────────────────────────────────
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
    if (t.startsWith('[')) { try { return JSON.parse(t).map(String).filter(Boolean); } catch { /**/ } }
    return t.split(',').map(s => s.trim()).filter(Boolean);
  }
  return [];
};

const looksLikeImg  = (v) => typeof v === 'string' && IMG_EXT.test(v.trim());
const SINGLE_IMG_RE = /thumbnail|_image$|^image$|photo$/i;
const MULTI_IMG_RE  = /^images$|^gallery$|^photos$|_images$/i;

const isSingleImg = (key, val) => SINGLE_IMG_RE.test(key) && looksLikeImg(val);
const isImgArray  = (key, val) => {
  if (!val) return false;
  if (MULTI_IMG_RE.test(key)) return true;
  if (Array.isArray(val) && val.length > 0) return val.every(v => looksLikeImg(String(v)));
  return false;
};
const isUrl = (val) => typeof val === 'string' && /^https?:\/\//i.test(val.trim());

const truncate = (s, len = 30) => {
  const str = String(s ?? '');
  return str.length > len ? str.slice(0, len) + '…' : str;
};

const stripHtml = (html) => html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();

const DATE_KEYS = new Set(['created_at', 'updated_at', 'deleted_at', 'publish_date']);

const cellValue = (val, key) => {
  if (val === null || val === undefined || val === '') return '—';

  if (DATE_KEYS.has(key)) {
    const d = new Date(val);
    return isNaN(d) ? val : d.toLocaleString();
  }
  if (typeof val === 'string') {
    const clean = val.includes('<') ? stripHtml(val) : val;
    return truncate(clean, 30);
  }
  if (Array.isArray(val)) {
    if (!val.length) return '—';
    const preview = val.slice(0, 2).join(', ');
    return val.length > 2 ? `${preview} (+${val.length - 2})` : preview;
  }
  if (typeof val === 'object') {
    for (const k of ['title', 'name', 'label']) { if (val[k]) return truncate(val[k], 30); }
    return '…';
  }
  return String(val);
};
</script>

<style scoped>
/* ── Table image thumbnail ───────────────────────────────────────────── */
.tb-img {
  width: 60px; height: 40px;
  object-fit: cover; border-radius: 4px;
  border: 1px solid var(--border-color, #dee2e6);
  cursor: zoom-in; transition: opacity .15s, transform .15s;
  display: block;
}
.tb-img:hover { opacity: .82; transform: scale(1.06); }

/* image array: first thumb + count badge */
.tb-img-group { position: relative; display: inline-block; }
.tb-img-count {
  position: absolute; bottom: 3px; right: 3px;
  background: rgba(0,0,0,.6); color: #fff;
  font-size: .65rem; font-weight: 600;
  padding: 1px 5px; border-radius: 10px;
  pointer-events: none; line-height: 1.4;
}

/* ── URL link ────────────────────────────────────────────────────────── */
.tb-link {
  color: var(--primary-color, #0d6efd);
  font-size: .8rem; word-break: break-all;
}
.tb-link:hover { text-decoration: underline; }

/* ── Empty placeholder ───────────────────────────────────────────────── */
.tb-none { color: var(--text-light, #adb5bd); font-size: .8rem; }

/* ══ Lightbox ════════════════════════════════════════════════════════ */
.tb-lb-backdrop {
  position: fixed; inset: 0; z-index: 99999;
  background: rgba(0,0,0,.88);
  display: flex; align-items: center; justify-content: center;
}
.tb-lb-img {
  max-width: 90vw; max-height: 88vh;
  object-fit: contain; border-radius: 6px;
  box-shadow: 0 8px 40px rgba(0,0,0,.6);
  user-select: none;
}
.tb-lb-close {
  position: absolute; top: 16px; right: 20px;
  background: rgba(255,255,255,.15); border: none;
  color: #fff; font-size: 1.3rem; width: 36px; height: 36px;
  border-radius: 50%; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
.tb-lb-close:hover { background: rgba(255,255,255,.3); }

.tb-lb-arrow {
  position: absolute; top: 50%; transform: translateY(-50%);
  background: rgba(255,255,255,.12); border: none;
  color: #fff; font-size: 2.2rem; width: 46px; height: 68px;
  border-radius: 6px; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s; user-select: none;
}
.tb-lb-arrow:hover { background: rgba(255,255,255,.25); }
.tb-lb-prev { left: 16px; }
.tb-lb-next { right: 16px; }

.tb-lb-caption {
  position: absolute; bottom: 16px;
  color: rgba(255,255,255,.7); font-size: .82rem;
  background: rgba(0,0,0,.4); padding: 3px 14px; border-radius: 20px;
}

/* Fade transition */
.lb-fade-enter-active, .lb-fade-leave-active { transition: opacity .2s ease; }
.lb-fade-enter-from,  .lb-fade-leave-to      { opacity: 0; }
</style>

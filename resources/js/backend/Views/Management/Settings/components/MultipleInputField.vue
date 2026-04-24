<template>
  <div class="form-group row">
    <label class="col-lg-3 col-form-label form-control-label">{{ label }}</label>
    <div class="col-lg-9">
      <div v-for="(item, index) in items" :key="index" class="mb-2">
        <div v-if="type === 'social'" class="row">
          <div class="col-5">
            <select v-model="item.media_name" class="form-control" @change="updateValue">
              <option value="">Select Platform</option>
                <option value="facebook">Facebook</option>
                <option value="twitter">Twitter</option>
                <option value="instagram">Instagram</option>
                <option value="linkedin">LinkedIn</option>
                <option value="youtube">YouTube</option>
                <option value="tiktok">TikTok</option>
                <option value="pinterest">Pinterest</option>
                <option value="snapchat">Snapchat</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="telegram">Telegram</option>
                <option value="reddit">Reddit</option>
                <option value="tumblr">Tumblr</option>
                <option value="discord">Discord</option>
                <option value="slack">Slack</option>
                <option value="other">Other</option>
            </select>
          </div>
          <div class="col-6">
            <input v-model="item.media_link" type="url" class="form-control" placeholder="Enter URL" @input="updateValue" />
          </div>
          <div class="col-1">
            <button type="button" class="btn btn-sm btn-danger" @click="removeItem(index)">×</button>
          </div>
        </div>
        <div v-else class="d-flex">
          <input v-model="items[index]" type="text" class="form-control" :placeholder="placeholder" @input="updateValue" />
          <button type="button" class="btn btn-sm btn-danger ml-2" @click="removeItem(index)">×</button>
        </div>
      </div>
      <button type="button" class="btn btn-sm btn-primary" @click="addItem">+ Add</button>
      <input type="hidden" :name="name" :value="getSerializedValue()" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'MultipleInputField',
  props: {
    modelValue: {
      type: Array,
      default: () => []
    },
    label: String,
    name: String,
    type: {
      type: String,
      default: 'text'
    },
    placeholder: String
  },
  emits: ['update:modelValue'],
  data() {
    return {
      items: [],
      isUpdating: false
    }
  },
  watch: {
    modelValue: {
      handler(newValue) {
        // Don't update if we're currently updating from user input
        if (this.isUpdating) {
          this.isUpdating = false
          return
        }
        
        if (this.type === 'social') {
          this.items = Array.isArray(newValue) && newValue.length > 0 
            ? newValue.map(item => ({
                media_name: item.media_name || '',
                media_link: item.media_link || ''
              }))
            : [{ media_name: '', media_link: '' }]
        } else {
          this.items = Array.isArray(newValue) && newValue.length > 0 
            ? [...newValue] 
            : ['']
        }
      },
      immediate: true
    }
  },
  methods: {
    addItem() {
      if (this.type === 'social') {
        this.items.push({ media_name: '', media_link: '' })
      } else {
        this.items.push('')
      }
      this.updateValue()
    },
    removeItem(index) {
      if (this.items.length > 1) {
        this.items.splice(index, 1)
        this.updateValue()
      }
    },
    updateValue() {
      // Set flag to prevent watcher from overwriting changes
      this.isUpdating = true
      
      // Always emit all items to parent, let parent handle the data
      if (this.type === 'social') {
        this.$emit('update:modelValue', this.items)
      } else {
        this.$emit('update:modelValue', this.items.filter(item => item !== null && item !== undefined))
      }
    },
    getSerializedValue() {
      let filtered
      if (this.type === 'social') {
        filtered = this.items.filter(item => item.media_name && item.media_link)
      } else if (this.type === 'phone') {
        filtered = this.items.filter(item => {
          if (!item || !item.trim()) return false
          const phone = item.trim()
          const phoneRegex = /^[\+]?[0-9]{10,15}$/
          return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''))
        })
      } else {
        filtered = this.items.filter(item => item && item.trim() !== '')
      }
      return JSON.stringify(filtered)
    }
  }
}
</script>

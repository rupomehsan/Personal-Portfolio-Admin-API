# MultipleInputField Component Usage Guide

The `MultipleInputField.vue` component is a reusable Vue 3 component designed to handle multiple input values with validation, formatting, and different serialization options.

## Basic Usage

```vue
<template>
  <MultipleInputField
    label="Phone Numbers"
    name="phone_numbers"
    type="phone"
    placeholder="Enter phone number and press Enter"
    v-model="phoneNumbers"
  />
</template>

<script>
import MultipleInputField from './components/MultipleInputField.vue';

export default {
  components: {
    MultipleInputField
  },
  data() {
    return {
      phoneNumbers: []
    }
  }
}
</script>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | String | required | Display label for the field |
| `name` | String | required | Form field name for submission |
| `modelValue` | Array | `[]` | Array of current values (v-model) |
| `placeholder` | String | `''` | Input placeholder text |
| `hint` | String | `''` | Help text shown below the field |
| `type` | String | `'text'` | Validation type: 'text', 'phone', 'social', 'email', 'url' |
| `validation` | Function | `null` | Custom validation function |
| `allowDuplicates` | Boolean | `false` | Whether to allow duplicate values |
| `serialization` | String | `'json'` | How to serialize for form submission: 'json', 'comma', 'array' |

## Examples by Type

### Phone Numbers
```vue
<MultipleInputField
  label="Phone Numbers"
  name="phone_numbers"
  type="phone"
  placeholder="Enter phone number and press Enter"
  hint="Supports formats like +1234567890 or 01234567890"
  v-model="phoneNumbers"
  serialization="json"
/>
```

### Social Media Links
```vue
<MultipleInputField
  label="Social Media"
  name="social_media"
  type="social"
  placeholder="Enter social media URL or handle and press Enter"
  hint="Enter URLs (https://facebook.com/username) or handles (@username)"
  v-model="socialMediaLinks"
  serialization="json"
/>
```

### Email Addresses
```vue
<MultipleInputField
  label="Email Addresses"
  name="emails"
  type="email"
  placeholder="Enter email address and press Enter"
  v-model="emailAddresses"
  allowDuplicates="false"
/>
```

### Tags/Keywords
```vue
<MultipleInputField
  label="Tags"
  name="tags"
  type="text"
  placeholder="Enter tag and press Enter"
  v-model="tags"
  serialization="comma"
  allowDuplicates="true"
/>
```

### Custom Validation
```vue
<MultipleInputField
  label="Product Codes"
  name="product_codes"
  placeholder="Enter product code and press Enter"
  :validation="validateProductCode"
  v-model="productCodes"
/>

<script>
export default {
  methods: {
    validateProductCode(code) {
      const regex = /^[A-Z]{2}\d{4}$/;
      if (!regex.test(code)) {
        return {
          valid: false,
          message: 'Product code must be 2 uppercase letters followed by 4 digits (e.g., AB1234)'
        };
      }
      return { valid: true };
    }
  }
}
</script>
```

## Serialization Options

### JSON (default)
Form field value: `'["item1","item2","item3"]'`
```vue
<MultipleInputField serialization="json" />
```

### Comma-separated
Form field value: `'item1,item2,item3'`
```vue
<MultipleInputField serialization="comma" />
```

### Array
Form field value: `['item1','item2','item3']`
```vue
<MultipleInputField serialization="array" />
```

## Events

The component emits `update:modelValue` when items are added or removed, making it compatible with v-model.

## Styling

The component includes built-in styling that supports both light and dark themes. You can customize the appearance by overriding the CSS classes:

- `.bootstrap-tagsinput` - Main container
- `.tag` - Individual tag/chip styling
- `.multiple-input-field` - Input field styling

## Integration Notes

- The component automatically handles form submission by creating a hidden input field with the serialized value
- Built-in validation provides user feedback through `window.s_alert()`
- The component is fully reactive and supports v-model binding
- Press Enter to add items, click the × to remove items
- Validation is performed on each item addition

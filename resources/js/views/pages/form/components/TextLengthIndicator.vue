<template>
  <div class="flex justify-end mt-1x text-grey text-small">{{ $props.field ? $props.field.length : '0' }}/{{ $props.maxChars }} Zeichen</div>
</template>
<script>
export default {
  props: {
    field: {
      type: String,
      default: ''
    },

    maxChars: {
      type: Number,
      default: 500,
    }
  },
}
</script>
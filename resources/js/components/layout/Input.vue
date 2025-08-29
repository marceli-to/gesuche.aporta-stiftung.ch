<template>
  <div :class="[$props.cls ? $props.cls : '']">
    <span :class="[$props.innerCls ? $props.innerCls : '']">
      <span v-if="$props.prefix">
        {{ $props.prefix }}
      </span>
      <slot />
    </span>
  </div>
</template>
<script>
export default {
  props: {
    cls: {
      type: String,
      default: ''
    },
    innerCls: {
      type: String,
      default: ''
    },
    prefix: {
      type: String,
      default: ''
    },
  },
}
</script>

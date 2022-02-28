<template>
<div>
  <icon-mail v-if="$props.id == 1"></icon-mail>
  <icon-cross v-if="$props.id == 2"></icon-cross>
  <icon-magnifier v-if="$props.id == 3"></icon-magnifier>

</div>
</template>

<script>
import IconMail from "@/components/ui/icons/Mail.vue";
import IconCross from "@/components/ui/icons/Cross.vue";
import IconMagnifier from "@/components/ui/icons/Magnifier.vue";

export default {

  components: {
    IconMail,
    IconCross,
    IconMagnifier,
  },

  props: {
    id: Number
  },

  mounted() {
    console.log(this.$props.id);
  }
}
</script>

<template>
<div>
  <icon-mail v-if="$props.id == 1"></icon-mail>
  <icon-cross v-if="$props.id == 2"></icon-cross>
  <icon-zueri v-if="$props.id == 3"></icon-zueri>
  <icon-checkmark v-if="$props.id == 4"></icon-checkmark>
  <icon-cross-hair v-if="$props.id == 5"></icon-cross-hair>
</div>
</template>

<script>
import IconMail from "@/components/ui/icons/Mail.vue";
import IconCross from "@/components/ui/icons/Cross-sm.vue";
import IconMagnifier from "@/components/ui/icons/Magnifier.vue";
import IconCrossHair from "@/components/ui/icons/CrossHair.vue";
import IconZueri from "@/components/ui/icons/Zueri.vue";
import IconCheckmark from "@/components/ui/icons/Checkmark.vue";

export default {

  components: {
    IconMail,
    IconCross,
    IconMagnifier,
    IconZueri,
    IconCheckmark,
    IconCrossHair,
  },

  props: {
    id: Number
  },

}
</script>

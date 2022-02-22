<template>
  <div v-if="isFetched" class="is-loaded">
    <h1>Willkommen <strong>{{user.firstname}} {{user.name}}</strong></h1>
  </div>
</template>
<script>

// Mixins
import Helpers from "@/mixins/Helpers";

export default {

  components: {
  },

  mixins: [Helpers],

  data() {
    return {
      isFetched: false,
      user: {},
    };
  },

  created() {
    this.fetch();
  },

  methods: {
    fetch() {
      this.axios.get(`/api/user`).then(response => {
        this.user = response.data;
        this.isFetched = true;
      });
    }
  }
}
</script>
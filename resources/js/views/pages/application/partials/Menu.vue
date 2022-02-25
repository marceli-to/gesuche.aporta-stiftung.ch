<template>
  <nav class="page-menu mb-15x">
    <ul>
      <li v-if="type == 'archive'">
        <router-link :to="{name: 'applications-archive', params: { type: $props.type }}">
          <span>Zurück</span>
        </router-link>
      </li>
      <li v-else>
        <router-link :to="{name: 'applications-current', params: { type: $props.type }}">
          <span>Zurück</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'application-edit', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Bearbeiten</span>
        </router-link>
      </li>
      <li>
        <router-link :to="{name: 'application-comments', params: { type: $props.type, uuid: $props.uuid }}" :active-class="'is-active'">
          <span>Kommentar</span>
        </router-link>
      </li>
      <li>
        <a href="">
          <span>Protokoll</span>
        </a>
      </li>
      <li>
        <a href="javascript:;" @click.prevent="archive()">
          <span>Archivieren</span>
        </a>
      </li>
      <li>
        <a href="" @click.prevent="destroy()">
          <span>Löschen</span>
        </a>
      </li>
    </ul>
    <slot />
  </nav>
</template>
<script>
export default {
  props: {
    type: String,
    uuid: String
  },

  data() {
    return {

      // States
      isLoading: false,
      
      // Routes
      routes: {
        destroy: '/api/application',
        put: '/api/application',
      },
    }
  },

  methods: {
    destroy(uuid, event) {
      if (confirm('Sicher?')) {
        this.isLoading = true;
        this.axios.delete(`${this.routes.destroy}/${this.$props.uuid}`).then(response => {
          this.isLoading = false;
          this.$router.push({ name: 'applications-current', params: {type: 'archiv', uuid: this.$props.uuid} });
        });
      }
    },

    archive() {
      this.isLoading = true;
      if (confirm('Sicher?')) {
        this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, {archive: 1}).then(response => {
          this.isLoading = false;
          this.$router.push({ name: 'applications-archive', params: {type: 'archiv', uuid: this.$props.uuid} });
        });
      }
    },
  }
}
</script>
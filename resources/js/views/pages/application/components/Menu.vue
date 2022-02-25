<template>
<div>
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
        <a href="javascript:;" @click.prevent="$refs.dialogArchive.show()">
          <span>Archivieren</span>
        </a>
      </li>
      <li>
        <a href="" @click.prevent="$refs.dialogDestroy.show()">
          <span>Löschen</span>
        </a>
      </li>
    </ul>
    <slot />
  </nav>
  <dialog-wrapper ref="dialogArchive">
    <template #message>
      <div><strong>Möchten Sie das Gesuch wirklich archivieren?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="archive()">Ja, archivieren</a>
    </template>
  </dialog-wrapper>
  
  <dialog-wrapper ref="dialogDestroy">
    <template #message>
      <div><strong>Möchten Sie das Gesuch wirklich löschen?</strong></div>
    </template>
    <template #actions>
      <a href="javascript:;" class="btn-primary mb-3x" @click.stop="destroy()">Ja, löschen</a>
    </template>
  </dialog-wrapper>
</div>
</template>
<script>
import DialogWrapper from "@/components/ui/misc/Dialog.vue";

export default {

  components: {
    DialogWrapper,
  },

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
    destroy() {
      this.isLoading = true;
      this.axios.delete(`${this.routes.destroy}/${this.$props.uuid}`).then(response => {
        this.isLoading = false;
        this.$router.push({ name: 'applications-current', params: {type: 'archiv', uuid: this.$props.uuid} });
      });
    },

    archive() {
      this.isLoading = true;
      this.axios.put(`${this.routes.put}/${this.$route.params.uuid}`, {archive: 1}).then(response => {
        this.isLoading = false;
        this.$refs.dialogArchive.hide();
        this.$router.push({ name: 'applications-archive', params: {type: 'archiv', uuid: this.$props.uuid} });
      });
    },
  }
}
</script>